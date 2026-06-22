<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    echo "Unauthorized access!";
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'] ?? '';

if (!$job_id) {
    echo "Invalid Job ID";
    exit();
}

// Ensure withdrawal is only possible if status is 'waiting'
$query = "DELETE FROM applications WHERE user_id = ? AND job_id = ? AND status = 'waiting'";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $job_id);

if ($stmt->execute()) {
    echo "Application withdrawn successfully!";
} else {
    echo "Failed to withdraw application. Try again!";
}
?>
