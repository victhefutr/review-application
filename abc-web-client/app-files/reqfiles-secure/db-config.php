<?php
/*
Sets the database timezone, can edit or comment out later
*/
date_default_timezone_set("Europe/London");
?>

<?php
//Start the session on all pages
session_start();
?>


<?php
#Review Server Connection Protocols

define('DB_SERVERNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'abc');
 
//connects to the database through the defined constants
$link_to_db = mysqli_connect(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
?>


<?php
if($link_to_db === false)
{
die("No database connection");
}
?>