<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_giver') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Latest Job Listings
$jobs_query = "SELECT id, title, company, location FROM jobs ORDER BY created_at DESC LIMIT 10";
$jobs_result = $conn->query($jobs_query);
$jobs = [];
while ($job = $jobs_result->fetch_assoc()) {
    $jobs[] = $job;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Home</title>
    <link rel="stylesheet" href="home.css">
    <script defer src="home.js"></script>
</head>
<body>

<!-- Header -->
<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="job_giver.php">Home</a>
        <a href="manage-jobs.php">Manage Jobs</a>
        <a href="post-job.php">Post a Job</a>
        <a href="view-applicants.php">View Applicants</a>
        <a href="giverprofile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>


<!-- Main Body -->
<main>
    <section class="hero">
        <h1>Find Your Dream Job Today!</h1>
        <p>Thousands of job listings from top companies.</p>
        <div class="search-bar">
            <input type="text" id="job-search" placeholder="Search job title, company...">
            <button id="search-btn">Search</button>
        </div>
    </section>

    <h2>Latest Job Listings</h2>
    <div class="job-listings" id="job-listings">
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <h3><?= htmlspecialchars($job['title']) ?></h3>
                <p>Company: <?= htmlspecialchars($job['company']) ?></p>
                <p>Location: <?= htmlspecialchars($job['location']) ?></p>
                <button class="apply-btn" data-job-id="<?= $job['id'] ?>">Apply Now</button>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Footer -->
<footer>
    <p>© 2025 JobFinder. All rights reserved.</p>
    <p><a href="#">Privacy Policy</a> | <a href="#">Contact Us</a></p>
    <div class="social-links">
        <a href="#">LinkedIn</a> | <a href="#">Twitter</a> | <a href="#">Facebook</a>
    </div>
</footer>

<script>
    const jobData = <?php echo json_encode($jobs); ?>;
</script>
</body>
</html>
