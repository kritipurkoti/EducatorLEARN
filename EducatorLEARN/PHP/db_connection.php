<?php
$servername = "localhost";
$username = "root"; // Default username in XAMPP/WAMP
$password = ""; // Default password is empty
$dbname = "e_learning"; // Your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
