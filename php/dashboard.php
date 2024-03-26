<?php
session_start();
require_once "config.php"; 

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Logout 
if(isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../index.php");
    exit();
}


$stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
if ($stmt->execute()) {
    $stmt->bind_result($username, $profile_picture);
    if (!$stmt->fetch()) {
        echo "Error: Failed to fetch user data";
    }
    $stmt->close();
} else {
    echo "Error: Failed to execute database query";
}

$_SESSION['username'] = $username;

    $userId = $_SESSION['user_id'];

    $sql = "SELECT first_name, last_name FROM users WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $userId);

    $stmt->execute();

    $stmt->bind_result($firstName, $lastName);

    $stmt->fetch();

    $stmt->close();

    $currentUserFirstName = $firstName;
    $currentUserLastName = $lastName;



// Display profile picture path
$profile_picture_path = "../assets/profile_pictures/" . $profile_picture;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username']; 
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile_number = $_POST['mobile_number'];
    $address_line1 = $_POST['address_line1'];
    $postcode = $_POST['postcode'];
    $state = $_POST['state'];
    $email = $_POST['email'];
    $education = $_POST['education'];
    $country = $_POST['country'];
    $state_region = $_POST['state_region'];

    // Handle profile picture upload
    if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $temp_file = $_FILES['profile_picture']['tmp_name'];
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION); // Get the file extension
        $file_name = $username . "." . $file_extension; // Change the file name to username.extension
        $upload_dir = "../assets/profile_pictures/";
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($temp_file, $target_file)) {
            // Update profile picture path in the database
            $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
            $stmt->bind_param("ss", $file_name, $username);
            $stmt->execute();
            $stmt->close();

            // Update profile picture path variable
            $profile_picture_path = $target_file;
        } else {
            echo "Profile picture not uploaded";
        }
    }

    // Update other user details in the database
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, mobile_number = ?, address_line1 = ?, postcode = ?, state = ?, email = ?, education = ?, country = ?, state_region = ? WHERE username = ?");
    $stmt->bind_param("sssssssssss", $first_name, $last_name, $mobile_number, $address_line1, $postcode, $state, $email, $education, $country, $state_region, $username);

    // Execute the statement
    if ($stmt->execute()) {
        // Data updated successfully
        echo '<script>alert("Profile settings updated successfully")</script>';
    } else {
        // Error occurred
        echo '<script>alert("Error occurred while updating profile settings")</script>';
    }

    // Close statement
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="nav-container">
        <div>
            <h1>Hi,
                <?php echo $firstName; ?>
            </h1>
        </div>
        <div>
        <img src="<?php echo $profile_picture_path; ?>" alt="Profile Picture">
        </div>
        <div>
        <form action="dashboard.php" method="GET">
        <input type="hidden" name="logout" value="true">
        <button type="submit" class="logout-btn"><span class="text">Logout</span><span>Thanks!</span></button>
        </form>
        </div>
    </div>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                        <div class="row mt-2">
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label class="labels">Profile Picture</label>
                                    <input type="file" name="profile_picture" class="form-control-file">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">First Name</label>
                                <input name="first_name" type="text" class="form-control" placeholder="First Name">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Last Name</label>
                                <input name="last_name" type="text" class="form-control" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Mobile Number</label>
                                <input name="mobile_number" type="text" class="form-control"
                                    placeholder="Mobile Number">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Address Line 1</label>
                                <input name="address_line1" type="text" class="form-control"
                                    placeholder="Address Line 1">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Postcode</label>
                                <input name="postcode" type="text" class="form-control" placeholder="Postcode">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">State</label>
                                <input name="state" type="text" class="form-control" placeholder="State">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Email</label>
                                <input name="email" type="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Education</label>
                                <input name="education" type="text" class="form-control" placeholder="Education">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Country</label>
                                <input id="Country" name="country" type="text" class="form-control"
                                    placeholder="Country">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">State/Region</label>
                                <input name="state_region" type="text" class="form-control" placeholder="State/Region">
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <button class="button-82-pushable" role="button"> <span class="button-82-shadow"></span>
                                <span class="button-82-edge"></span> <span class="button-82-front text">
                                    Save Profile </span>
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://bossanova.uk/jsuites/v3/jsuites.js"></script>

</body>

</html>