<?php
// Start session
session_start();

// Include database configuration
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, last_name, dob, address, gender, username, email FROM users WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Update profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    $update_query = "UPDATE users SET first_name=?, last_name=?, dob=?, address=?, gender=? WHERE id=?";
    if ($stmt = $conn->prepare($update_query)) {
        $stmt->bind_param("sssssi", $first_name, $last_name, $dob, $address, $gender, $user_id);
        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $user = ['first_name' => $first_name, 'last_name' => $last_name, 'dob' => $dob, 'address' => $address, 'gender' => $gender];
        } else {
            $error_message = "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Educator LEARN</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>My Profile</h2>

    <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <form method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>

        <label>Date of Birth:</label>
        <input type="date" name="dob" value="<?= $user['dob']; ?>" required>

        <label>Address:</label>
        <textarea name="address" required><?= htmlspecialchars($user['address']); ?></textarea>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
        </select>

        <label>Username:</label>
        <input type="text" value="<?= htmlspecialchars($user['username']); ?>" disabled>

        <label>Email:</label>
        <input type="email" value="<?= htmlspecialchars($user['email']); ?>" disabled>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
