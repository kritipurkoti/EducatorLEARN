<?php
// Database configuration
$host = "localhost"; // Change if using a remote database
$db_name = "db_connection"; // Database name
$username = "root"; // Database username
$password = ""; // Database password (leave empty if using XAMPP)

// Establish database connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8");

// Start the session
session_start();

// Create users table if it does not exist
$table_query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    address TEXT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($table_query)) {
    die("Error creating table: " . $conn->error);
}

// Check if test user exists
$email = 'john@example.com';
$check_query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($check_query);

if ($result->num_rows == 0) {
    // Insert test user if not exists
    $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO users (first_name, last_name, dob, address, gender, username, email, password) 
                     VALUES ('John', 'Doe', '2000-05-15', '123 Street, City', 'Male', 'johndoe', '$email', '$hashed_password')";

    if (!$conn->query($insert_query)) {
        die("Error inserting test user: " . $conn->error);
    }
}

?>
