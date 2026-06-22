<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_giver') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, email, profile_pic, company_name, position FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Bind result variables
$stmt->bind_result($name, $email, $profile_pic, $company_name, $position);

// Fetch the data
$user = null;
if ($stmt->fetch()) {
    $user = array(
        'name' => $name,
        'email' => $email,
        'profile_pic' => $profile_pic,
        'company_name' => $company_name,
        'position' => $position
    );
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Giver Profile</title>
    <link rel="stylesheet" href="giverprofile.css">
</head>
<body>

<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="home.php">Home</a>
        <a href="post-job.php">Post a Job</a>
        <a href="manage-jobs.php">Manage Jobs</a>
        <a href="view-applicants.php">View Applicants</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <div class="profile-container">
        <h1>My Profile</h1>
        
        <div class="profile-card">
            <img src="uploads/profile_pictures/<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="profile-pic">
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>

            <h3>Company Name</h3>
            <p><?= nl2br(htmlspecialchars($user['company_name'])) ?></p>

            <h3>Position</h3>
            <p><?= nl2br(htmlspecialchars($user['position'])) ?></p>
        </div>

        <button id="edit-profile-btn">Edit Profile</button>
        <button id="reset-password-btn">Reset Password</button>
    </div>

    <!-- Edit Profile Modal -->
    <div id="edit-profile-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Profile</h2>
            <form id="edit-profile-form" enctype="multipart/form-data">
                <label>Profile Picture:</label>
                <input type="file" name="profile_pic">
                
                <label>Full Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">

                <label>Company Name:</label>
                <input type="text" name="company_name" value="<?= htmlspecialchars($user['company_name']) ?>">

                <label>Position:</label>
                <input type="text" name="position" value="<?= htmlspecialchars($user['position']) ?>">

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="reset-password-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Reset Password</h2>
            <form id="reset-password-form">
                <label>Current Password:</label>
                <input type="password" name="current_password" required>

                <label>New Password:</label>
                <input type="password" name="new_password" required>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>

</main>

<footer>
    <p>© 2025 JobFinder. All rights reserved.</p>
</footer>

<script src="giverprofile.js"></script>

</body>
</html>
