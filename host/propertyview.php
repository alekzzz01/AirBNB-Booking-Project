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

if(isset($_GET['property_id'])) {
    $property_id = $_GET['property_id'];

    // Fetch property details from the database based on the property ID
    $sql = "SELECT * FROM property_entity WHERE property_id = $property_id";
    $result = $conn->query($sql);

    $sqlreview = "SELECT * FROM review_entity WHERE property_id = $property_id";
    $resultReview = $conn->query($sqlreview);

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Check if the form is submitted
  if (isset($_POST['rating']) && isset($_POST['comment'])) {
      // Retrieve the submitted data
      $rating = $_POST['rating'];
      $comment = $_POST['comment'];

      // Prepare and execute the SQL query to insert the review
      $insertReviewSql = "INSERT INTO review_entity (user_id, property_id, rating, comment) 
                          VALUES ('$user_id', '$property_id', '$rating', '$comment')";

                          
      if ($conn->query($insertReviewSql) === TRUE) {
        echo '<script>alert("Review submitted successfully!");</script>';
    } else {
        echo "Error: " . $insertReviewSql . "<br>" . $conn->error;
    }

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
    <link rel="stylesheet" href="css/hotel-1.css" />
    <title>Group 3 AIRBNB </title>
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
    <br>


  <div class="hotel">


  
  <?php 

if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
    $review =  $resultReview->fetch_assoc();

}

?>


    <div class="hotel-location">
      <h2>Seattle, WA</h2>
    </div>




    <div class="hotel-info">
    <img src="<?php echo $property['image_url'] ?>" alt="">
                                            <p>Property ID: <?php  echo $property ['PROPERTY_ID']?> </p>
                                            <p>Rating: <?php echo $review ['rating'] ?> / 10</p>
                                            <p>Type: <?php echo  $property['TYPE'] ?> </p>
                                            <p>Price per night: <?php echo $property['PRICE_PER_NIGHT']  ?> </p>
                                            <p>No. of Bedrooms: <?php echo  $property['number_of_bedrooms'] ?> </p>
                                            <p>No. of Bathrooms: <?php echo  $property['number_of_bathrooms'] ?> </p>
                                            <p>Amenities: <?php echo  $property['AMENITIES'] ?> </p>
                                            <p>Maximum No. of Guest: <?php echo  $property['maximum_number_of_guests'] ?> </p>
                            
    </div>
    
    



    <br>
    <div class="bookbtn">
      <center>
      <a href="booking.php?property_id=<?php echo $property_id; ?>" class="book_btn">Book now</a>
    </center>
    </div>






    
  </div>


  <form action="" method="post" class="reviewform">

          <div class="star-container">

          <p>Your Rating: </p>

          <div class="star-rating">

          <input type="radio" id="5-stars" name="rating" value="5" />
          <label for="5-stars" class="star">&#9733;</label>
          <input type="radio" id="4-stars" name="rating" value="4" />
          <label for="4-stars" class="star">&#9733;</label>
          <input type="radio" id="3-stars" name="rating" value="3" />
          <label for="3-stars" class="star">&#9733;</label>
          <input type="radio" id="2-stars" name="rating" value="2" />
          <label for="2-stars" class="star">&#9733;</label>
          <input type="radio" id="1-star" name="rating" value="1" />
          <label for="1-star" class="star">&#9733;</label>
          </div>
          </div>




          <div class="comment">

          <p>Comments:</p>

          <textarea cols="100" rows="15" placeholder="Please describe your experience for your stay in the property" name="comment"></textarea>






          </div>

          <button type="submit">Submit</button>





</form>









  </body>
</html>