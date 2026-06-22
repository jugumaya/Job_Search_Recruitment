<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_giver') {
    die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $company_name = $_POST['company_name'];
    $position = $_POST['position'];

    if ($_FILES['profile_pic']['size'] > 0) {
        $profile_pic = $_FILES['profile_pic']['name'];
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/profile_pictures/" . $profile_pic);
    } else {
        $profile_pic = $_SESSION['profile_pic'];
    }

    $stmt = $conn->prepare("UPDATE users SET name=?, company_name=?, position=?, profile_pic=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $company_name, $position, $profile_pic, $user_id);
    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile.";
    }
}
?>
