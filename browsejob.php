<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle Search Query
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$search_query = "SELECT id, title, company, location, description FROM jobs WHERE title LIKE ? OR company LIKE ? ORDER BY created_at DESC";
$stmt = $conn->prepare($search_query);
$search_param = "%" . $search . "%";
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $title, $company, $location, $description);

// Fetch all rows into an array
$jobs = array();
while ($stmt->fetch()) {
    $jobs[] = array(
        'id' => $id,
        'title' => $title,
        'company' => $company,
        'location' => $location,
        'description' => $description
    );
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs</title>
    <link rel="stylesheet" href="browsejob.css">
    <script defer src="browsejob.js"></script>
</head>
<body>

<header>
    <div class="logo">JobFinder</div>
    <nav>
        <a href="job_seeker.php">Home</a>
        <a href="browsejob.php">Browse Jobs</a>
        <a href="applied-jobs.php">Applied Jobs</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h1>Browse Jobs</h1>

    <!-- Search Bar -->
    <form method="GET" action="browsejob.php">
        <input type="text" name="search" placeholder="Search by job title or company" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="job-listings">
        <?php if (empty($jobs)): ?>
            <p>No jobs found.</p>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
    <div class="job-card">
        <h2><?= htmlspecialchars($job['title']) ?></h2>
        <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
        <button class="apply-btn" data-job-id="<?= $job['id'] ?>">Apply Now</button>

        <!-- Comment Form -->
        <form method="POST" action="add_comment.php" class="comment-form">
            <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
            <p><strong>Commenting as:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
            <textarea name="comment_text" placeholder="Write a comment..." required></textarea><br>
            <button type="submit">Post Comment</button>
        </form>

        <!-- Display Existing Comments -->
        <?php
        $job_id = $job['id'];
        $comment_query = "SELECT id, user_name, comment_text, created_at FROM comments WHERE job_id = ? AND parent_id IS NULL ORDER BY created_at DESC";
        $comment_stmt = $conn->prepare($comment_query);
        $comment_stmt->bind_param("i", $job_id);
        $comment_stmt->execute();
        
        // Bind comment result variables
        $comment_stmt->bind_result($comment_id, $comment_user_name, $comment_text, $comment_created_at);
        
        while ($comment_stmt->fetch()):
            // Store current comment data before nested query
            $current_comment_id = $comment_id;
            $current_comment_user_name = $comment_user_name;
            $current_comment_text = $comment_text;
            $current_comment_created_at = $comment_created_at;
        ?>
            <div class="comment">
                <strong><?= htmlspecialchars($current_comment_user_name) ?></strong>: 
                <?= htmlspecialchars($current_comment_text) ?> 
                <small>(<?= $current_comment_created_at ?>)</small>
            </div>

            <?php
            // Need to store comment_stmt to resume later
            $comment_stmt->store_result();
            
            // Replies
            $reply_query = "SELECT id, user_name, comment_text, created_at FROM comments WHERE parent_id = ? ORDER BY created_at ASC";
            $reply_stmt = $conn->prepare($reply_query);
            $reply_stmt->bind_param("i", $current_comment_id);
            $reply_stmt->execute();
            
            // Bind reply result variables
            $reply_stmt->bind_result($reply_id, $reply_user_name, $reply_text, $reply_created_at);
            
            while ($reply_stmt->fetch()):
            ?>
                <div class="reply" style="margin-left: 20px;">
                    ↳ <strong><?= htmlspecialchars($reply_user_name) ?></strong>: 
                    <?= htmlspecialchars($reply_text) ?> 
                    <small>(<?= $reply_created_at ?>)</small>
                </div>
            <?php endwhile; 
            $reply_stmt->close();
            ?>

            <!-- Reply Form -->
            <form method="POST" action="add_comment.php" style="margin-left:20px;" class="reply-form">
                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                <input type="hidden" name="parent_id" value="<?= $current_comment_id ?>">
                <p><strong>Replying as:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
                <textarea name="comment_text" placeholder="Write a reply..." required></textarea>
                <button type="submit">Reply</button>
            </form>
        <?php 
        endwhile; 
        $comment_stmt->close();
        ?>
    </div>
<?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>© 2025 JobFinder. All rights reserved.</p>
</footer>

<script>
    const jobData = <?php echo json_encode($jobs); ?>;
</script>

</body>
</html>