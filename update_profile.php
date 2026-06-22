<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$education = $_POST['education'];
$experience = $_POST['experience'];

// Handle Profile Picture Upload
if (!empty($_FILES['profile_pic']['name'])) {
    $target_dir = "uploads/profile_pictures/";
    $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $update_query = "UPDATE users SET name=?, education=?, experience=?, profile_pic=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $name, $education, $experience, $file_name, $user_id);
    } else {
        echo "Error uploading file!";
        exit();
    }
} else {
    $update_query = "UPDATE users SET name=?, education=?, experience=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $name, $education, $experience, $user_id);
}

if ($stmt->execute()) {
    echo "Profile updated successfully!";
} else {
    echo "Failed to update profile.";
}
?>
