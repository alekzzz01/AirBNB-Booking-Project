<?php
include ('db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

 

    $sql = "INSERT INTO user_table (name, email, phone_number, password) VALUES ('$name', '$email', '$phone_number', '$password')";

    if ($conn->query($sql) === TRUE) {
           echo '<script>alert("Registered Succesfully");</script>';
        echo '<script>window.location.href = "host-signin.php";</script>';
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
