<?php  

include ('db.php');
session_start();


if (!isset($_SESSION['user_id'])) {
  // Redirect the user to the login page or another page as needed
  header("Location: index.php");
  exit();
}



$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$propertyId = null; // Add this line to initialize $propertyId

if (isset($_GET['property_id'])) {
  $propertyId = $_GET['property_id'];

  // Fetch property details from the database based on the property ID
  $sql = "SELECT * FROM property_entity WHERE PROPERTY_ID = $propertyId";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $property = $result->fetch_assoc();
  }
} else {
  // Redirect to an error page or handle the case when property_id is not set
  echo '<script>alert("Error.");</script>';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $checkInDate = $_POST['checkin'];
  $checkOutDate = $_POST['checkout'];
  $numberOfGuests = $_POST['guestsnumber'];

      // Validate and sanitize the input (you may want to add more validation)
      $checkInDate = mysqli_real_escape_string($conn, $checkInDate);
      $checkOutDate = mysqli_real_escape_string($conn, $checkOutDate);
      $numberOfGuests = intval($numberOfGuests); // Convert to integer

      $checkInDateTime = new DateTime($checkInDate);
      $checkOutDateTime = new DateTime($checkOutDate);

      if ($checkOutDateTime <= $checkInDateTime) {
          echo '<script>alert("Invalid date selection. Check-out date should be after the check-in date.");</script>';
          // Add any additional handling or redirect as needed
          exit();
      }
      // Calculate the interval between check-in and check-out
      $interval = $checkInDateTime->diff($checkOutDateTime);

      // Get the number of nights
      $numberOfNights = $interval->format('%a');

      $totalPrice = $numberOfNights * $property['PRICE_PER_NIGHT'];




      $availabilityCheckSql = "SELECT * FROM booking_entity 
      WHERE property_id = '$propertyId'
      AND (
          (check_in_date BETWEEN '$checkInDate' AND '$checkOutDate')
          OR
          (check_out_date BETWEEN '$checkInDate' AND '$checkOutDate')
      )
      AND booking_status = 'confirmed'";
  

  $availabilityResult = $conn->query($availabilityCheckSql);

  if ($availabilityResult->num_rows > 0) {
      // Dates are not available, handle accordingly (e.g., show an error message)
      echo '<script>alert("Selected dates are not available. Please choose different dates.");</script>';
  } else {
      // Dates are available, proceed with booking
      $insertSql = "INSERT INTO booking_entity (user_id, property_id, check_in_date, check_out_date, number_of_guests, total_price, booking_status) VALUES ('$user_id', '$propertyId', '$checkInDate', '$checkOutDate', '$numberOfGuests', '$totalPrice', 'Pending')";
  
      if ($conn->query($insertSql) === TRUE) {
          // Booking successful, insert into notificationtable
          $bookingId = $conn->insert_id; // Get the last inserted booking ID
  
          $notificationContent = "New booking: Property ID $propertyId, Booking ID $bookingId";
          $insertNotificationSql = "INSERT INTO notification_entity (user_id, booking_id, content, timestamp, status) VALUES ('$user_id', '$bookingId', '$notificationContent', NOW(), 'Unread')";
  
          // Execute the notification insert query
          if ($conn->query($insertNotificationSql) === TRUE) {
              // Notification insertion successful

              $insertPaymentSql = "INSERT INTO payment_entity (booking_id, payment_status) VALUES ('$bookingId', 'Pending')";

              if ($conn->query($insertPaymentSql) === TRUE) {
                  // Payment entity insertion successful
      
                  // Redirect to payment.php with booking details
                  header("Location: payment.php?booking_id=$bookingId&property_id=$propertyId&checkin=$checkInDate&checkout=$checkOutDate&guests=$numberOfGuests&total_price=$totalPrice");
                  exit();
              } else {
                  // If the query execution fails, you may want to display an error message
                  echo '<script>alert("Error in adding payment record. Please try again later.");</script>';
              }
          } else {
              // If the query execution fails, you may want to display an error message
              echo '<script>alert("Error in adding notification. Please try again later.");</script>';
          }
      }
      
      else {
          // If the query execution fails, you may want to display an error message
          echo '<script>alert("Error in booking. Please try again later.");</script>';
      }
  }

}
  

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reservation.css">
    <title>Reservation | Group 3</title>
</head>
<body>



<div class="main-section-booking">

<img src="<?php echo isset($property['image_url']) ? $property['image_url'] : ''; ?>" alt="" width="100%" height="350px">
<p><?php echo isset($property['TITLE']) ? $property['TITLE'] : ''; ?></p>



</div>

    <form action="" method="post">
        <h1>RESERVATION FORM</h1>


        <hr>
        <div class="elem-group">
            <label for="max-peeps">Maximum number of Guest(s):</label>
            <select name="guestsnumber" id="guestsnumber">
              <?php
              // Use a loop to generate options based on the property details
              for ($i = 1; $i <= $property['maximum_number_of_guests']; $i++) {
                  echo "<option value='$i'>$i</option>";
              }
              ?>
             </select>
          </div>
      
        <div class="elem-group inlined">
          <label for="checkin-date">Check-in Date</label>
          <input type="datetime-local" id="check_in_date" name="checkin" >
        </div>
        <div class="elem-group inlined">
          <label for="checkout-date">Check-out Date</label>
          <input type="datetime-local" id="check_out_date" name="checkout">
        </div>
     
        <div class="elem-group">
            <label for="price">Price per night: <?php echo $property['PRICE_PER_NIGHT'] ?></label>
          </div>
         
        <hr>
        <div class="elem-group">
          <label for="message">Anything Else?</label>
          <textarea id="message" name="visitor_message" placeholder="Tell us anything else that might be important." required></textarea>
        </div>
        <button type="submit">Book Now</button>
      </form>

</body>





<?php

$conn->close();

?>


</html>

