<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $_SESSION['message'] = "Password do not match.";
        header("Location: dashboard.php");
    } else {
        $userID = $_SESSION['user_id'];
        $updateSql = "UPDATE users SET lname='$name', email='$email', lpassword='$password' WHERE user_id='$userID'";
        if ($conn->query($updateSql) === TRUE) {
            $_SESSION['message'] = "Profile updated successfully.";
            header("Location: dashboard.php");
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
            header("Location: dashboard.php");
        }
    }
}

$conn->close();
?>