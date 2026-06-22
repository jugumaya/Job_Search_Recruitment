<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_giver') {
    header("Location: login.php");
    exit();
}

$job_giver_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    if (!empty($title) && !empty($company) && !empty($location) && !empty($description)) {
        $query = "INSERT INTO jobs (title, company, location, description, posted_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $title, $company, $location, $description, $job_giver_id);

        if ($stmt->execute()) {
            echo "<script>alert('Job posted successfully!'); window.location.href = 'manage-jobs.php';</script>";
        } else {
            echo "<script>alert('Error posting job. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link rel="stylesheet" href="post-job.css">
    <script defer src="post-job.js"></script>
</head>
<body>

<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="job_giver.php">Home</a>
        <a href="manage-jobs.php">Manage Jobs</a>
        <a href="view-applicants.php">View Applicants</a>
        <a href="giverprofile.php">View Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <div class="form-container">
        <h1>Post a New Job</h1>
        <form id="post-job-form" method="POST">
            <label>Job Title:</label>
            <input type="text" name="title" required>

            <label>Company Name:</label>
            <input type="text" name="company" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Job Description:</label>
            <textarea name="description" required></textarea>

            <button type="submit">Post Job</button>
        </form>
    </div>
</main>

<footer>
    <p>© 2025 JobFinder. All rights reserved.</p>
</footer>

</body>
</html>
