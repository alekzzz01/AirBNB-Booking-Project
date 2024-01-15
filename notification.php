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

// Fetch user notifications from the database
$sql = "SELECT * FROM notification_entity WHERE user_id = '$host_id' ORDER BY timestamp DESC";
$result = $conn->query($sql);




// Store the notifications in an array
$notifications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}

// Handle marking a notification as read
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_as_read'])) {
    $notificationId = $_POST['notification_id'];

    // Update the status to 'Read' in the database
    $updateSql = "UPDATE notificationentity  SET status = 'Read' WHERE notif_id = $notificationId AND user_id = '$host_id'";
    $conn->query($updateSql);


    // Redirect to the same page to refresh the notifications
    header("Location: notification.php");
    exit();
}


?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/homepage.css" />
    <title>Group 3 AIRBNB</title>
  </head>
  <body>
    <nav>
      <div class="nav__logo">AIRBNB RESERVATION SYSTEM</div>
      <ul class="nav__links">
        <li class="link"><a href="homepage.php">Home</a></li>
        <li class="link"><a href="booking.php">Booking</a></li>
        <li class="link"><a href="message.php">Message</a></li>
        <li class="link"><a href="notification.php">Notification</a></li>
        <li class="link"><a href="logout.php">Log Out</a></li>
      </ul>
      
    </nav>
 
    <section class="section__container popular__container">

    
    <div class="main-section-notif">
                <p><i class='bx bxs-bell'></i>Notification</p>
           
        </div>

        
        <?php foreach ($notifications as $notification): ?>
    <div class="notif-container">
        <form action="" method="post">
            <div class="notif-slide">
                <img src="" alt="">
                <div class="info-notif">
                    <p>Booking no. <?php echo $notification['booking_id']; ?></p>
                    <p><?php echo $notification['content']; ?></p>
                    <p><?php echo timeAgo($notification['timestamp']); ?></p>


                </div>
                <?php if ($notification['status'] == 'Unread'): ?>
                    <button type="submit" name="mark_as_read">Mark as Read</button>
                    <input type="hidden" name="notification_id" value="<?php echo $notification['notif_id']; ?>">
                <?php endif; ?>
            </div>
        </form>
        <div class="dot-read">
            <p><?php echo ($notification['status'] == 'Unread') ? 'Unread' : 'Read'; ?></p>
        </div>
    </div>
<?php endforeach; ?>


<?php
function timeAgo($timestamp) {
    $currentTime = time();
    $timestamp = strtotime($timestamp);
    $timeDifference = $currentTime - $timestamp;

    if ($timeDifference < 60) {
        return "Just Now";
    } elseif ($timeDifference < 3600) {
        $minutes = round($timeDifference / 60);
        return $minutes == 1 ? "1 minute ago" : "$minutes minutes ago";
    } elseif ($timeDifference < 86400) {
        $hours = round($timeDifference / 3600);
        return $hours == 1 ? "an hour ago" : "$hours hours ago";
    } elseif ($timeDifference < 604800) {
        $days = round($timeDifference / 86400);
        return $days == 1 ? "yesterday" : "$days days ago";
    } else {
        return date("M j, Y", $timestamp); // Fallback to a specific date format for older timestamps
    }
}

?>
   






    </section>

  </body>
</html>