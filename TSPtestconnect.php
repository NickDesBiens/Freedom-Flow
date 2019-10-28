<?php
session_start();


try {
    //get into database
    $config = parse_ini_file("TSP.ini");
        $dbh = new PDO($config['dsn'], $config['username'],$config['password'],$config['port']);

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST["logout"])) {
        session_destroy();
        echo "user logged out";
        //return;
    }


    // if the username exists
    if (isset($_POST["userid"])) {
        $username = $_POST["userid"];
        $password = md5($_POST["password"]);
        $exist = false;

        // run through all results of the query
      //  foreach ($dbh->query("SELECT id FROM students
        //        WHERE id = '$username' and password = '$password'") as $row) {
            // if there is a result in the query, then that student gets logged in successfully
          //  if ($row[0]) {
            //    $exist = TRUE;
              //  $_SESSION['userid'] = $_POST["userid"];
                //header('TSPlogin.php');     // redirect to student homepage
                //exit;
            }
        }
        // if there is no result from the query, then username/password is incorrect
        if (!$exist) {
            echo "incorrect username and password";
        }
    }
} catch (PDOException $e) {
    print "Error!" . $e->getMessage()."<br/>";
    die();
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

