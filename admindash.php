<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch total jobs
$total_jobs_query = "SELECT COUNT(*) FROM jobs";
$total_jobs = $conn->query($total_jobs_query)->fetch_row()[0];

// Fetch total employers
$total_employers_query = "SELECT COUNT(*) FROM users WHERE user_type = 'job_giver'";
$total_employers = $conn->query($total_employers_query)->fetch_row()[0];

// Fetch total job seekers
$total_seekers_query = "SELECT COUNT(*) FROM users WHERE user_type = 'job_seeker'";
$total_seekers = $conn->query($total_seekers_query)->fetch_row()[0];

// Fetch new job applications (last 30 days)
$new_applications_query = "SELECT COUNT(*) FROM applications WHERE applied_at >= NOW() - INTERVAL 30 DAY";
$new_applications = $conn->query($new_applications_query)->fetch_row()[0];



// Fetch user growth data for Chart.js
$user_growth_query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM users GROUP BY DATE(created_at) ORDER BY created_at DESC LIMIT 10";
$user_growth_result = $conn->query($user_growth_query);
$user_growth_data = [];
while ($row = $user_growth_result->fetch_assoc()) {
    $user_growth_data[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Job Portal</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for Graphs -->
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
            <h1>Dashboard Overview</h1>
        </header>

        <!-- Dashboard Cards -->
        <section class="dashboard">
            <div class="card">
                <h3>Total Jobs</h3>
                <p><?php echo $total_jobs; ?></p>
            </div>
            <div class="card">
                <h3>Total Employers</h3>
                <p><?php echo $total_employers; ?></p>
            </div>
            <div class="card">
                <h3>Total Job Seekers</h3>
                <p><?php echo $total_seekers; ?></p>
            </div>
            <div class="card">
                <h3>New Applications (30 Days)</h3>
                <p><?php echo $new_applications; ?></p>
            </div>
            
        </section>

        <!-- Graphs & Reports -->
        <section class="reports">
            <h2>User Growth Statistics</h2>
            <canvas id="userChart"></canvas>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const userGrowthData = <?php echo json_encode($user_growth_data); ?>;
            const dates = userGrowthData.map(data => data.date);
            const counts = userGrowthData.map(data => data.count);

            const ctx = document.getElementById('userChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates.reverse(),
                    datasets: [{
                        label: 'New Users Per Day',
                        data: counts.reverse(),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>
