<?php
session_start();


// Check if the user is already logged in, if yes then redirect to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: homepage.html");
    exit;
}

// Include config file
require_once "config.php";


    // if the username exists
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
        //$_SESSION['password'] = $_POST["password"];
        $password = md5($_POST["password"]);
        $exist = false;

        // run through all results of the query
        foreach ($dbh->query("SELECT username FROM users
                WHERE username = '$username' and password = '$password'") as $row) {
            // if there is a result in the query, then that student gets logged in successfully
            if ($row[0]) {
                $exist = TRUE;
                $_SESSION['username'] = $_POST["username"];
                header("Location: https://classdb.it.mtu.edu/group_projects/cs3141/classdb/TeamFreedomFlow/homepage.html");     // redirect to homepage
                exit;
            }
        }
        // if there is no result from the query, then username/password is incorrect
        if (!$exist) {
            echo "incorrect username and password";
        }
    }

?>
<!-- text boxes for username and password, and submit-type login button -->
<form  method=post>
username: <input type="text" name ="userid">
<br>
password: <input type="password" name="password">
<br>
<input type="submit" name="login" value="login">

</form>
