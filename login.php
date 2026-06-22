<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>





<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, name, password, user_type FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // Use bind_result instead of get_result
    $stmt->bind_result($user_id, $user_name, $hashed_password, $user_type);
    
    // Fetch the result
    if ($stmt->fetch()) {
        // Close the statement before using the connection again
        $stmt->close();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_type'] = $user_type;

            if ($user_type == "job_seeker") {
                header("Location: job_seeker.php");
            } elseif ($user_type == "job_giver") {
                header("Location: job_giver.php");
            } elseif ($user_type == "admin") {
                header("Location: admindash.php");
            }
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script src="login.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="" onsubmit="return validateLoginForm()">
            <label>Email:</label>
            <input type="email" name="email" id="login_email" required>

            <label>Password:</label>
            <input type="password" name="password" id="login_password" required>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
