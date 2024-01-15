<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or another page as needed
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Retrieve booking details from the URL
$bookingId = $_GET['booking_id'];
$propertyId = $_GET['property_id'];
$checkInDate = $_GET['checkin'];
$checkOutDate = $_GET['checkout'];
$numberOfGuests = $_GET['guests'];
$totalPrice = $_GET['total_price'];

// Format the dates using DateTime
$checkInDateTime = new DateTime($checkInDate);
$checkOutDateTime = new DateTime($checkOutDate);

// Format dates to a more user-friendly format
$formattedCheckInDate = $checkInDateTime->format('F j, Y, \a\t g:ia');
$formattedCheckOutDate = $checkOutDateTime->format('F j, Y, \a\t g:ia');

// Initialize variables
$amountToBePaid = $totalPrice;
$amountPaid = 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amountPaid = $_POST['amount'];

    // Validate and sanitize the input (you may want to add more validation)
    $amountPaid = mysqli_real_escape_string($conn, $amountPaid);
    $amountPaid = floatval($amountPaid); // Convert to float

    // Check if the amounts match
    if ($amountToBePaid != $amountPaid) {
        echo '<script>alert("The amount entered does not match the total amount to be paid.");</script>';
    } else {
        // Update payment information in the paymententity table
        $updatePaymentSql = "UPDATE payment_entity SET payment_date = NOW(), amount = '$amountPaid', payment_status = 'Completed' WHERE booking_id = '$bookingId'";

        // Execute the update query
        if ($conn->query($updatePaymentSql) === TRUE) {
            echo '<script>alert("Payment successful.");</script>';
            // You can add additional logic here, such as updating the booking status or sending a confirmation email.
        } else {
            // If the query execution fails, you may want to display an error message
            echo '<script>alert("Error in payment. Please try again later.");</script>';
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
    <title>Payments | Group 3</title>
</head>
<body>

<nav>
      
      <ul class="nav__links">
        <li class="link"><a href="homepage.php">Home</a></li>
        <li class="link"><a href="booking.php">Booking</a></li>
        <li class="link"><a href="message.php">Message</a></li>
        <li class="link"><a href="notification.php">Notification</a></li>
        <li class="link"><a href="logout.php">Log Out</a></li>
      </ul>
      
    </nav>
    <form action="" method="post">
        <h1>PAYMENT FORM</h1>
        <div class="elem-group">
            <label for="booking-num">Booking number: <?php echo $bookingId ?></label>
        </div>
       
        <div class="elem-group">
            <label for="check-in">Check in: <?php echo $formattedCheckInDate  ?> </label>
        </div>
        <div class="elem-group">
            <label for="check-out">Check out: <?php echo $formattedCheckOutDate ?> </label>
        </div>
        <div class="elem-group">
            <label for="adult">Guests: </label>
        </div>
        <hr>
        <div class="elem-group">
            <label for="amount">Amount to be paid: <?php echo $totalPrice ?></label>
          </div>
        <div class="elem-group">
            <label for="payment">Enter the amount to be paid:</label>
            <input type="number" id="payment" name="amount" required>
        </div>
       
    
        
        <button type="submit">Pay Now</button>
      </form>

</body>

</html>
