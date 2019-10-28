<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'classdb.it.mtu.edu');
define('DB_USERNAME', 'teamfreedomfl_rw');
define('DB_PASSWORD', 'libertystream');
define('DB_NAME', 'teamfreedomflow');
define('PORT', '3307');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, PORT);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>