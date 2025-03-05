<?php
ini_set('session.cookie_httponly', 1);  // Makes the session cookie accessible only through HTTP (not JavaScript)
ini_set('session.cookie_secure', 1);    // Ensures cookies are sent over secure HTTPS connections
session_start();
include 'db.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form data
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login-signup page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/sign_up.css">
</head>

<body>
    <div class="wrapper">
        <div class="home">
            <a id="homebtn" href="home.php">
                <i class="fa fa-home"></i>
            </a>
        </div>
        <span class="bganimate1"></span>
        <span class="bganimate2"></span>

        <div class="signupbox">
            <h1 class="animation">Sign Up</h1>
            <form method="POST" action="signup.php">
                <div class="input-box animation">
                    <input type="text" name="username" required>
                    <label>FullName</label>
                    <i class='fas fa-user-alt'></i>
                </div>
                <div class="input-box animation">
                    <input type="email" name="email" required>
                    <label>Email</label>
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="input-box animation">
                    <input type="password" name="password" required>
                    <label>Password</label>
                    <i class='fas fa-lock'></i>
                </div>
                <button class="animation" type="submit" id="signupbtn" onclick="alertt()">Sign Up</button>
                <div class="text animation">
                    <p>Already have an account?<a id="loglink" href="#">Login</a></p>
                </div>
            </form>
        </div>
        <div class="signup_heading animation">
            <h2 id="h2">Welcome Back!!</h2>
        </div>

        <div class="loginbox">
            <h1 class="animation">Login</h1>
            <form method="POST" action="login.php">
                <div class="input-box animation">
                    <input type="text" name="email" required>
                    <label>Email</label>
                    <i class='fas fa-user-alt'></i>
                </div>
                <div class="input-box animation">
                    <input type="password" name="password" required>
                    <label>Password</label>
                    <i class='fas fa-lock'></i>
                </div>
                <button class="animation" type="submit" id="loginbtn" onclick="alertt()">Login</button>
                <div class="text animation">
                    <p>Don't have an account?<a id="signlink" href="#">Sign up</a></p>
                </div>
            </form>
        </div>
        <div class="login_heading animation">
            <h2 id="h2">Welcome Back!!</h2>
        </div>

        
    </div>
  

    <script src="js/sign_up.js">

    </script>
</body>

</html>