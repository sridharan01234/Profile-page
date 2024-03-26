<?php
require_once "php/config.php"; // Include your database connection configuration

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
      echo '<script>alert("User Already Exists!");</script>';
        } else {
        // Insert the new user into the database
        $insert_stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $fname, $lname, $email, $password);

        if ($insert_stmt->execute()) {
            // Registration successful, redirect to the login page
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


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style/style.css">
</head>

<body>
  <section>
    <div class="container">
      <div class="user signinBx">
        <div class="imgBx"><img
            src="https://raw.githubusercontent.com/WoojinFive/CSS_Playground/master/Responsive%20Login%20and%20Registration%20Form/img2.jpg"
            alt="" /></div>
        <div class="formBx">
        <form action="php/login.php" method="post" class="signin-form">
            <h2>Sign In</h2>
            <input type="text" name="email" placeholder="E-mail" />
            <input type="password" name="password" placeholder="Password" />
            <input type="submit" name="" value="Login" />
            <p class="signup">
              Don't have an account ?
              <a href="#" onclick="toggleForm();">Sign Up.</a>
            </p>
          </form>
        </div>
      </div>
      <div class="user signupBx">
        <div class="formBx">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Create an account</h2>
            <input type="text" id="fname" name="fname" placeholder="First Name" required/>
            <input type="text" id="lname" name="lname" placeholder="Last Name" required/>
            <input type="email" id="Email" name="email" placeholder="Email Address" />
            <p id="Valid-Email" class="glyphicon glyphicon-remove">Invalid Email</p>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
            <div class="form-group has-feedback">
            <input class="form-control" id="NewPassword" placeholder="New Password" type="password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="Password-Validation">
            <div><p>Must Have</p></div>
            <p id="Length" class="glyphicon glyphicon-remove" >7 letter</p>
            <P id="UpperCase" class="glyphicon glyphicon-remove">1 upper case </P>
            <P id="LowerCase" class="glyphicon glyphicon-remove">1 lower case </P>
            <P id="Numbers" class="glyphicon glyphicon-remove">1 numeric </P>
            <P id="Symbols" class="glyphicon glyphicon-remove">1 special </P>
            </div>
            <input type="password" id="ConfirmPassword" name="password" placeholder="Confirm Password" />
            <p id="Match" class="glyphicon glyphicon-remove">Confirm Your Password</p>
            <input type="submit"  id="SignUp-btn" name="" value="Sign Up" />
            <p class="signup">
              Already have an account ?
              <a href="#" onclick="toggleForm();">Sign in.</a>
            </p>
          </form>
        </div>
        <div class="imgBx"><img
            src="https://raw.githubusercontent.com/WoojinFive/CSS_Playground/master/Responsive%20Login%20and%20Registration%20Form/img2.jpg"
            alt="" /></div>
      </div>
    </div>
  </section>
  <script src="js/script.js"></script>
</body>

</html>