<?php
session_start();
include 'config.php';

// Check if user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['job_id'])) {
    $user_id = $_SESSION['user_id'];
    $job_id = intval($_POST['job_id']);

    // Fetch Job Title
    $stmt = $conn->prepare("SELECT title FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    
    // Bind result variable
    $stmt->bind_result($job_title_db);
    
    $job = null;
    if ($stmt->fetch()) {
        $job = array('title' => $job_title_db);
    }
    $stmt->close();

    if (!$job) {
        echo json_encode(["success" => false, "message" => "Job not found!"]);
        exit();
    }

    // Fetch User Experience
    $stmt = $conn->prepare("SELECT experience FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // Bind result variable
    $stmt->bind_result($user_experience_db);
    
    $user = null;
    if ($stmt->fetch()) {
        $user = array('experience' => $user_experience_db);
    }
    $stmt->close();

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found!"]);
        exit();
    }

    // Normalize and Match
    $job_title = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $job['title']));
    $user_experience = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $user['experience']));

    if (empty($user_experience)) {
        echo json_encode(["success" => false, "message" => "Please update your experience to apply!"]);
        exit();
    }

    $experience_words = explode(" ", $user_experience);
    $matched = false;

    foreach ($experience_words as $word) {
        if (strlen($word) >= 3 && strpos($job_title, $word) !== false) {
            $matched = true;
            break;
        }
    }

    if (!$matched) {
        echo json_encode(["success" => false, "message" => "Your experience does not match the job title!"]);
        exit();
    }

    // Check if already applied
    $stmt = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "You have already applied for this job!"]);
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Insert application
    $stmt = $conn->prepare("INSERT INTO applications (job_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $job_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Application submitted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Application failed. Please try again!"]);
    }
    $stmt->close();

} else {
    echo json_encode(["success" => false, "message" => "Invalid request!"]);
}
?>