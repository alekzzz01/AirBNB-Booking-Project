<?php
include ('db.php');

session_start(); // Start session for storing user data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

  

    $sql = "SELECT * FROM user_entity WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Save user data in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone_number'] = $user['phone_number'];
        $_SESSION['profile_picture'] = $user['profile_picture'];

        header("Location: homepage.php"); // Redirect to homepage after successful login
        exit();
    } else {
        echo '<script>alert("Invalid email or password");</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    }

    $conn->close();
}
?>
