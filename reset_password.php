<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Fetch current password from database
$query = "SELECT password FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verify current password
if (!password_verify($current_password, $user['password'])) {
    echo "Current password is incorrect!";
    exit();
}

// Check if new passwords match
if ($new_password !== $confirm_password) {
    echo "New passwords do not match!";
    exit();
}

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in the database
$update_query = "UPDATE users SET password=? WHERE id=?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("si", $hashed_password, $user_id);

if ($stmt->execute()) {
    echo "Password changed successfully!";
} else {
    echo "Failed to update password!";
}
?>
