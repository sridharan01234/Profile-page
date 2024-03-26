<?php
require_once "config.php"; // Include your database connection configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if the email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "exists";
    } else {
        // Insert the new user into the database
        $insert_stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $fname, $lname, $email, $password);

        if ($insert_stmt->execute()) {
            echo '<script>alert("Registration Success");</script>';
            echo '<script>window.location = "index.php";</script>';
        } else {
            echo "failure";
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>
