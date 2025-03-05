<?php
ini_set('session.cookie_httponly', 1);  // Makes the session cookie accessible only through HTTP (not JavaScript)
ini_set('session.cookie_secure', 1);    // Ensures cookies are sent over secure HTTPS connections
session_start();
include 'db.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form data
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    // Check if the user has too many failed login attempts
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // If the user has been locked out due to too many failed attempts
        if ($user['failed_attempts'] >= 5 && strtotime($user['last_failed_attempt']) > time() - 900) {
            echo "Your account is temporarily locked. Please try again after 15 minutes.";
            exit();
        }

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Reset failed attempts after a successful login
            $stmt = $conn->prepare("UPDATE users SET failed_attempts = 0 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Start a session and store user information
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to a protected page (e.g., dashboard)
            header("Location: home.php");
            exit();
        } else {
            // Increment failed attempts
            $stmt = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            echo "Invalid email or password!";
        }
    } else {
        echo "No user found with that email!";
    }

    $stmt->close();
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
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="bg-image"></div>
    
    <div class="wrapper">
        <div class="home">
            <a id="homebtn" href="home.php">
                <i class="fa fa-home"></i>
            </a>
        </div>
        <span class="bganimate1"></span>
        <span class="bganimate2"></span>

        <div class="loginbox">
            <h1 class="animation">Login</h1>
            <form method="POST" action="login.php">
                <div class="input-box animation">
                    <input type="text" name="email" required>
                    <label>Email</label>
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="input-box animation">
                    <input type="password" name="password" required>
                    <label>Password</label>
                    <i class='fas fa-lock'></i>
                </div>
                <button class="animation" type="submit" id="loginbtn">Login</button>
                <div class="text animation">
                    <p>Don't have an account?<a id="signlink" href="#">Sign up</a></p>
                </div>
            </form>
        </div>
        <div class="login_heading animation">
            <h2 id="h2">Welcome Back!!</h2>
        </div>

        <div class="signupbox">
            <h1 class="animation">Sign Up</h1>
            <method="POST" action="signup.php">
                <div class="input-box animation">
                    <input type="text" name="username" required>
                    <label>FullName</label>
                    <i class='fas fa-user-alt'></i>
                </div>
                <div class="input-box animation">
                    <input type="email" name="eamil" required>
                    <label>Email</label>
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="input-box animation">
                    <input type="password" name="password" required>
                    <label>Password</label>
                    <i class='fas fa-lock'></i>
                </div>
                <button class="animation" type="submit" id="signupbtn">Sign Up</button>
                <div class="text animation">
                    <p>Already have an account?<a id="loglink" href="#">Login</a></p>
                </div>
            </form>
        </div>
        <div class="signup_heading animation">
            <h2 id="h2">Welcome Back!!</h2>
        </div>
    </div>

    <script src="js/login.js">

    </script>
</body>

</html>