<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, email, profile_pic, education, experience FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Bind result variables
$stmt->bind_result($name, $email, $profile_pic, $education, $experience);

// Fetch the data
$user = null;
if ($stmt->fetch()) {
    $user = array(
        'name' => $name,
        'email' => $email,
        'profile_pic' => $profile_pic,
        'education' => $education,
        'experience' => $experience
    );
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="profile.css">
    <script defer src="profile.js"></script>
</head>
<body>

<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="job_seeker.php">Home</a>
        <a href="applied-jobs.php">Applied Jobs</a>
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
            
            <h3>Education</h3>
            <p><?= nl2br(htmlspecialchars($user['education'])) ?></p>

            <h3>Experience</h3>
            <p><?= nl2br(htmlspecialchars($user['experience'])) ?></p>
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

                <label>Education:</label>
                <textarea name="education"><?= htmlspecialchars($user['education']) ?></textarea>

                <label>Experience:</label>
                <textarea name="experience"><?= htmlspecialchars($user['experience']) ?></textarea>

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



<script>
    const userData = <?php echo json_encode($user); ?>;
</script>

</body>
</html>