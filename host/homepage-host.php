<?php 

include ('db.php');

session_start();

if (!isset($_SESSION['host_id'])) {
    // Redirect the user to the login page or another page as needed
    header("Location: host-signin.php");
    exit();
}


$host_id = $_SESSION['host_id'];
$name = $_SESSION['name'];

$sqlReviews = "SELECT * FROM review_entity";
  $resultReviews = $conn->query($sqlReviews);

  $reviews = [];

  if ($resultReviews->num_rows > 0) {
    // Store reviews in an array
    while($rowReview = $resultReviews->fetch_assoc()) {
      $reviews[] = $rowReview['comment'];
    }
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
        <li class="link"><a href="homepage-host.php">Home</a></li>
        <li class="link"><a href="booking.php">Booking</a></li>
 
        <li class="link"><a href="logout.php">Log Out</a></li>
      </ul>
      
    </nav>
    <header class="section__container header__container">
      <div class="header__image__container">
        <div class="header__content">
          <h1>Enjoy Your Dream Vacation</h1>
          <p>Book Hotels, Flights and stay packages at lowest price.</p>
        </div>
       
    </header>

    <section class="section__container popular__container">
      <h2 class="section__header">Your Listings</h2>
      <div class="popular__grid">

      <?php
        // Assuming you have a database connection
    
        // Fetch properties for the logged-in host
$sql = "SELECT * FROM property_entity WHERE host_id = $host_id";
$result = $conn->query($sql);


        if ($result->num_rows > 0) {
          // Output data of each row
          while($row = $result->fetch_assoc()) {
            echo '<div class="popular__card">';
            echo '<img src="' . $row["image_url"] . '" alt="' . $row["TITLE"] . '" />';
            echo '<div class="popular__content">';
            echo '<div class="popular__card__header">';
            echo '<h4>' . $row["TITLE"] . '</h4>';
            echo '<h4>' . $row["PRICE_PER_NIGHT"] . '$</h4>';
            echo '</div>';
            echo '<p>' . $row["LOCATION"] . '</p>';
            echo '<br>';
            echo '<a href="propertyview.php?property_id=' . $row["PROPERTY_ID"] . '" class="info_btn">More Info</a>';
            echo '</div>';
            echo '</div>';
          }
        } else {
          echo "0 results";
        }

        $conn->close();
        ?>









        </div>

  
      </div>
    </section>

    <section class="client">
      <div class="section__container client__container">
        <h2 class="section__header">Reviews</h2>
        <div class="client__grid" id="reviewsContainer">
          <div class="client__card">
           
            <p>
             
            </p>
          </div>
        
         
        </div>
      </div>
    </section>

    <section class="section__container">
      <div class="reward__container">
        <p>Lots of places available</p>
        <h4>Book with us and discover amazing places and sceneries on your booking</h4>
        <br>
        <a href="reservation.html" class="reward__btn">Book now</a>
      </div>
    </section>

  </body>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    var reviews = <?php echo json_encode($reviews); ?>;
    var currentIndex = 0;

    function displayReviews() {
      var reviewsContainer = document.getElementById("reviewsContainer");
      reviewsContainer.innerHTML = '';

      // Display three reviews at a time
      for (var i = 0; i < 3; i++) {
        var index = (currentIndex + i) % reviews.length;
        reviewsContainer.innerHTML += '<div class="client__card"><p>' + reviews[index] + '</p></div>';
      }

      currentIndex = (currentIndex + 3) % reviews.length;
    }

    // Display reviews every 10 seconds
    setInterval(displayReviews, 10000);

    // Initial display
    displayReviews();
  });
</script>

</html>