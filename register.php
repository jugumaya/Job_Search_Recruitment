<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];
    $experience = $education = $company_name = $position = NULL;

    if ($user_type == "job_seeker") {
        $experience = $_POST['experience'];
        $education = $_POST['education'];
    } elseif ($user_type == "job_giver") {
        $company_name = $_POST['company_name'];
        $position = $_POST['position'];
    }

    $profile_pic = "default.png";
    if (!empty($_FILES['profile_pic']['name'])) {
        $profile_pic = time() . "_" . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/" . $profile_pic);
    }

    $sql = "INSERT INTO users (name, email, password, user_type, experience, education, company_name, position, profile_pic)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $email, $password, $user_type, $experience, $education, $company_name, $position, $profile_pic);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="registerstyle.css">
    <script src="register.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label>Name:</label>
            <input type="text" name="name" id="name" required>

            <label>Email:</label>
            <input type="email" name="email" id="email" required>

            <label>Password:</label>
            <input type="password" name="password" id="password" required>

            <label>User Type:</label>
            <select name="user_type" id="user_type" onchange="toggleFields()" required>
                <option value="job_seeker">Job Seeker</option>
                <option value="job_giver">Job Giver</option>
            </select>

            <div id="seeker_fields">
                <label>Experience:</label>
                <input type="text" name="experience">

                <label>Education:</label>
                <input type="text" name="education">
            </div>

            <div id="giver_fields" style="display:none;">
                <label>Company Name:</label>
                <input type="text" name="company_name">

                <label>Position:</label>
                <input type="text" name="position">
            </div>

            <label>Profile Picture:</label>
            <input type="file" name="profile_pic">

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
