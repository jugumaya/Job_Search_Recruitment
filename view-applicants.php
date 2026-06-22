<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_giver') {
    header("Location: login.php");
    exit();
}

$job_giver_id = $_SESSION['user_id'];

// Fetch jobs posted by the job giver
$query = "SELECT id, title FROM jobs WHERE posted_by = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_giver_id);
$stmt->execute();

// Bind result variables
$stmt->bind_result($job_id, $job_title);

// Fetch all rows into an array
$jobs = array();
while ($stmt->fetch()) {
    $jobs[] = array(
        'id' => $job_id,
        'title' => $job_title
    );
}
$stmt->close();

// Fetch applicants
$applicants = [];
if (!empty($jobs)) {
    $job_ids = implode(',', array_column($jobs, 'id'));
    
    // Using prepared statement for security (preventing SQL injection)
    $placeholders = implode(',', array_fill(0, count($jobs), '?'));
    $query = "SELECT applications.id, users.name, users.email, users.experience, users.education, applications.job_id, applications.status, applications.applied_at
              FROM applications
              JOIN users ON applications.user_id = users.id
              WHERE applications.job_id IN ($placeholders)";
    
    $stmt = $conn->prepare($query);
    
    // Bind parameters dynamically
    $types = str_repeat('i', count($jobs));
    $job_ids_array = array_column($jobs, 'id');
    $stmt->bind_param($types, ...$job_ids_array);
    
    $stmt->execute();
    
    // Bind result variables
    $stmt->bind_result($app_id, $user_name, $user_email, $user_experience, $user_education, $app_job_id, $app_status, $applied_at);
    
    // Fetch all applicants
    while ($stmt->fetch()) {
        $applicants[] = array(
            'id' => $app_id,
            'name' => $user_name,
            'email' => $user_email,
            'experience' => $user_experience,
            'education' => $user_education,
            'job_id' => $app_job_id,
            'status' => $app_status,
            'applied_at' => $applied_at
        );
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <link rel="stylesheet" href="view-applicants.css">
    <script defer src="view-applicants.js"></script>
</head>
<body>

<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="job_giver.php">Home</a>
        <a href="manage-jobs.php">Manage Jobs</a>
        <a href="view-applicants.php" class="active">View Applicants</a>
        <a href="giver_profile.php">View Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <div class="container">
        <h1>Job Applicants</h1>

        <?php if (empty($applicants)): ?>
            <p>No applicants yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Experience</th>
                        <th>Education</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applicants as $applicant): ?>
                        <tr>
                            <td><?= htmlspecialchars($jobs[array_search($applicant['job_id'], array_column($jobs, 'id'))]['title']) ?></td>
                            <td><?= htmlspecialchars($applicant['name']) ?></td>
                            <td><?= htmlspecialchars($applicant['email']) ?></td>
                            <td><?= htmlspecialchars($applicant['experience']) ?></td>
                            <td><?= htmlspecialchars($applicant['education']) ?></td>
                            <td class="status"><?= htmlspecialchars($applicant['status']) ?></td>
                            <td>
                                <button class="accept-btn" data-id="<?= $applicant['id'] ?>">Accept</button>
                                <button class="reject-btn" data-id="<?= $applicant['id'] ?>">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>© 2025 JobFinder. All rights reserved.</p>
</footer>

</body>
</html>
