<?php


include ('db.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or another page as needed
    header("Location: index.php");
    exit();
}



  
  $host_id = $_SESSION['user_id'];
  $name = $_SESSION['name'];

  

  $sql = "SELECT b.*, p.payment_status, p.amount 
  FROM booking_entity b
  LEFT JOIN payment_entity p ON b.booking_id = p.booking_id
  WHERE b.user_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $host_id);
$stmt->execute();

$result = $stmt->get_result();
  

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="css/booking.css">
</head>
<body>
    <h1>Booking</h1>

<table id="customers">
  <tr>
    <th>Booking ID</th>
    <th>Property ID</th>
    <th>Check in date</th>
    <th>Check out date</th>
    <th>Number of Guest</th>
    <th>Booking Status</th>
    <th>Payment</th>
    <th>Payment Status</th>
    <th>Action</th>
  </tr>


  <tbody>


<?php
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['booking_id'] . "</td>";
    echo "<td>" . $row['property_id'] . "</td>";
    echo "<td>" . $row['check_in_date'] . "</td>";
    echo "<td>" . $row['check_out_date'] . "</td>";
    echo "<td>" . $row['number_of_guests'] . "</td>";
  
    echo "<td>" . $row['booking_status'] . ' | ' .'total price: '  . $row['total_price'] . "</td>";
    echo "<td>" . $row['amount'] . "</td>";
    echo "<td>" . $row['payment_status'] . "</td>";

    echo "<td class='button-action'>";
    if (empty($row['payment_status']) || strpos($row['payment_status'], 'Pending') !== false) {
        echo "<a href='propertyview.php?id={$row['property_id']}' class='view-button'>View Property <i class='bx bxs-show'></i></a>";
        echo "<a href='payment.php?booking_id={$row['booking_id']}&property_id={$row['property_id']}&checkin={$row['check_in_date']}&checkout={$row['check_out_date']}&guests={$row['number_of_guest']}&total_price={$row['total_price']}' class='edit-button'>Pay now <i class='bx bxs-message-square-edit'></i></a>";
        echo "<button class='cancel-button' data-booking-id='{$row['booking_id']}' type='button'>Cancel <i class='bx bxs-message-square-x'></i></button>";
    } else {
        echo "<a href='propertyview.php?id={$row['property_id']}' class='view-button'>View Property <i class='bx bxs-show'></i></a>";
    }
    echo "</td>";
    
    
    echo "</tr>";
}

$stmt->close();
$conn->close();
?>
                                                                    


</tbody>
</table>

</body>
</html>
