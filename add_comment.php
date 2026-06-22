<?php
session_start();
include 'config.php'; // Make sure this path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $job_id = intval($_POST['job_id']);
    $user_name = $_SESSION['user_name'] ?? 'Anonymous';
    $comment_text = trim($_POST['comment_text']);
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : null;

    // Simple validation
    if (!empty($user_name) && !empty($comment_text) && $job_id > 0) {
        // Prepare and insert the comment or reply
        $stmt = $conn->prepare("INSERT INTO comments (job_id, user_name, comment_text, parent_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $job_id, $user_name, $comment_text, $parent_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect back to the browse page
header("Location: browsejob.php");
exit();
