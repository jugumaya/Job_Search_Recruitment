<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch applied jobs with status
$query = "SELECT jobs.id, jobs.title, jobs.company, jobs.location, applications.applied_at, applications.status
          FROM applications 
          JOIN jobs ON applications.job_id = jobs.id 
          WHERE applications.user_id = ? 
          ORDER BY applications.applied_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Bind result variables
$stmt->bind_result($job_id, $job_title, $job_company, $job_location, $applied_at, $status);

// Fetch all rows into an array
$appliedJobs = [];
while ($stmt->fetch()) {
    $appliedJobs[] = array(
        'id' => $job_id,
        'title' => $job_title,
        'company' => $job_company,
        'location' => $job_location,
        'applied_at' => $applied_at,
        'status' => $status
    );
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applied Jobs</title>
    <link rel="stylesheet" href="applied-jobs.css">
    <script defer src="applied-jobs.js"></script>
</head>
<body>

<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="job_seeker.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h1>Applied Jobs</h1>
    <div class="applied-jobs-list" id="applied-jobs-list">
        <?php if (count($appliedJobs) > 0): ?>
            <?php foreach ($appliedJobs as $job): ?>
                <div class="job-card">
                    <h3><?= htmlspecialchars($job['title']) ?></h3>
                    <p>Company: <?= htmlspecialchars($job['company']) ?></p>
                    <p>Location: <?= htmlspecialchars($job['location']) ?></p>
                    <p>Applied On: <?= htmlspecialchars($job['applied_at']) ?></p>

                    <p>Status: 
                        <span class="status <?= $job['status'] ?>">
                            <?= ucfirst($job['status']) ?>
                        </span>
                    </p>

                    <?php if ($job['status'] == 'waiting'): ?>
                        <button class="withdraw-btn" data-job-id="<?= $job['id'] ?>">Withdraw Application</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have not applied for any jobs yet.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>© 2025 JobFinder. All rights reserved.</p>
</footer>

<script>
    const appliedJobs = <?php echo json_encode($appliedJobs); ?>;
</script>

</body>
</html>