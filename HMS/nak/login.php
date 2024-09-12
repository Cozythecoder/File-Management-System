<?php
// Establish database connection
$servername = "localhost:3306";
$username = "cozy";
$password = "cozy@1234";
$database = "mysqldb";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Query to check if the email and password exist in the database
  $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Successful login, redirect to dashboard or any other page
    header("Location: index.html");
    exit();
  } else {
    // Invalid credentials, redirect back to login page
    header("Location: login.html");
    exit();
  }
}

$conn->close();
?>
