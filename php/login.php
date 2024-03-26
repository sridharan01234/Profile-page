<?php
session_start();

require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $username, $hashed_password, $role);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $id;
        
        if ($role == "admin") {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        echo '<script>alert("Invalid username or password");</script>';
        echo '<script>window.location = "../index.php";</script>';
        exit;
           }

    $stmt->close();
}

$conn->close();
?>