<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: homepage.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm = "";
$username_err = $password_err = $confirm_err = "";
// temp variable for if statments below because of error
$usercheck = isset($_POST["username"]);
$passcheck = isset($_POST["password"]);
$confirmcheck = isset($_POST["confirm"]);
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if username is empty
    if(!$usercheck){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(!$passcheck){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Check if password is empty
    if(!$confirmcheck){
        $confirm_err = "Please check the password.";
    } else{
        $confirm = trim($_POST["confirm"]);
    }
    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($confirm_err)){
        // Prepare a select statement
        $sql = "SELECT username FROM users WHERE username = '$username'";

		$stmt = mysqli_prepare($link, $sql);

        if($stmt){

            if(mysqli_stmt_execute($stmt)){

                // Store result
                mysqli_stmt_store_result($stmt);

                //Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 0){

                    if(($password == $confirm)){

                        // Password is correct, so start a new session
                        session_start();
                        $sql = "Insert into users values('$username','$password')";
                        $stmt = mysqli_prepare($link, $sql);
                        mysqli_stmt_execute($stmt);

                        // Redirect user to login.php
                        header("location: login.php");

                    } else {
                        // Display an error message if password is not valid
                        $confirm_err = "The passwords are not the same";
                    }
                } else {
                  $username_err = "Someone else already has this username.";
                }
            } else {
                    // Display an error message if username doesn't exist
                    echo "Oops! Something went wrong. Please try again later.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Close connection
        mysqli_close($link);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Account Information</h2>
        <p>Please provide a username and a password for your account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm" class="form-control">
                <span class="help-block"><?php echo $confirm_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create">
            </div>
            <p>Have an account? <a href="login.php">Sign in here</a>.</p>
        </form>
    </div>
</body>
</html>
