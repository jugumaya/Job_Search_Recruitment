<?php
$servername = "localhost";
$username = "afraimr1_job_finder";
$password = "SG8W8Q9tVMwwCma6SP9R";
$database = "afraimr1_job_finder";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
