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

// Check if already applied
$check_query = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You have already applied for this job!";
    exit();
}

// Insert into applications table
$apply_query = "INSERT INTO applications (user_id, job_id, applied_at) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($apply_query);
$stmt->bind_param("ii", $user_id, $job_id);
if ($stmt->execute()) {
    echo "Application submitted successfully!";
} else {
    echo "Failed to apply. Try again!";
}
?>
