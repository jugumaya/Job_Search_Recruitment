<?php
session_start();
include 'config.php';

// Check if user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

// Only allow POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['job_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $job_id = intval($_POST['job_id']);

    // 1. Fetch Job Description
    $stmt = $conn->prepare("SELECT title FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job_result = $stmt->get_result();
    $job = $job_result->fetch_assoc();

    if (!$job) {
        echo json_encode(["success" => false, "message" => "Job not found!"]);
        exit();
    }

    // 2. Fetch User Experience
    $stmt = $conn->prepare("SELECT experience FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found!"]);
        exit();
    }

    // 3. Normalize (lowercase + remove symbols)
    $job_description_clean = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $job['description']));
    $user_experience_clean = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $user['experience']));

    if (empty(trim($user_experience_clean))) {
        echo json_encode(["success" => false, "message" => "Please update your experience before applying!"]);
        exit();
    }

    // ✅ SPLIT section: convert sentences to array of words
    $experience_words = array_filter(explode(' ', $user_experience_clean));
    $description_words = array_filter(explode(' ', $job_description_clean));

    $matched = false;

    // 4. Matching logic: partial match (experience word inside description word)
    foreach ($experience_words as $exp_word) {
        if (strlen($exp_word) >= 3) { // Ignore very short words
            foreach ($description_words as $desc_word) {
                if (stripos($desc_word, $exp_word) !== false || stripos($exp_word, $desc_word) !== false) {
                    $matched = true;
                    break 2; // Stop both loops if one match found
                }
            }
        }
    }

    if (!$matched) {
        echo json_encode(["success" => false, "message" => "Your experience does not match the job requirements!"]);
        exit();
    }

    // 5. Check if already applied
    $stmt = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "You have already applied for this job!"]);
        exit();
    }

    // 6. Insert into applications table
    $stmt = $conn->prepare("INSERT INTO applications (job_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $job_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Application submitted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Application failed. Please try again!"]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Invalid request!"]);
}
?>
