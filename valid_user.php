<?php
session_start();
include 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle validation status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $status = ($action === 'validate') ? 'valid' : 'cancelled';
    
    $update_query = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "User #$user_id status updated to $status successfully!";
    } else {
        $error_message = "Error updating user status: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all users
$users_query = "SELECT id, name, email, user_type, created_at, status FROM users ORDER BY created_at DESC";
$users_result = $conn->query($users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Validation | Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="validate.css">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admindash.php">Dashboard</a></li>
            <li><a href="jobs.php">Manage Jobs</a></li>
            <li><a href="employers.php">Employers</a></li>
            <li><a href="seekers.php">Job Seekers</a></li>
            <li><a href="applications.php">Applications</a></li>
            <li><a href="valid_user.php" class="active">User Validation</a></li>
            
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header>
            <h1>User Validation</h1>
        </header>

        <?php if (isset($success_message)): ?>
            <div class="alert success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <section class="users-validation">
            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Registration Date</th>
                            <th>Current Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users_result->num_rows > 0): ?>
                            <?php while ($user = $users_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <?php 
                                        $user_type = $user['user_type'];
                                        if ($user_type === 'job_seeker') {
                                            echo 'Job Seeker';
                                        } elseif ($user_type === 'job_giver') {
                                            echo 'Employer';
                                        } elseif ($user_type === 'admin') {
                                            echo 'Admin';
                                        } else {
                                            echo htmlspecialchars($user_type);
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $user['status'] ?? 'pending'; ?>">
                                            <?php echo ucfirst($user['status'] ?? 'Pending'); ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <form method="POST" action="" class="inline-form">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="validate">
                                            <button type="submit" class="btn validate-btn">Valid</button>
                                        </form>
                                        <form method="POST" action="" class="inline-form">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="cancel">
                                            <button type="submit" class="btn cancel-btn">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-data">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });
    </script>

</body>
</html>

