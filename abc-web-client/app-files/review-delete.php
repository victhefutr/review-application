<?php
require_once("reqfiles-secure/db-config.php");
?>

<?php
//check for current session
require_once "xH-user-data.php";
?>



<?php
/*
If there is no set session, redirect away from this page
*/

if($logged_status == "no")
{
header("location: ./");
exit;
}



elseif($logged_status == "yes")
{
//get review id to perform the delete operation
$review_id = $_GET["id"];

//delete the review, only if you are the one who posted it

#1 - delete all assoicates
$sql_01 = "DELETE FROM interactions WHERE review_id='$review_id' AND username='$get_username'";
mysqli_query($link_to_db, $sql_01);

$sql_02 = "DELETE FROM messages WHERE review_id='$review_id' AND username_user='$get_username'";
mysqli_query($link_to_db, $sql_02);

#2 - finally delete the review itself
$sql_03 = "DELETE FROM reviews WHERE review_id='$review_id' AND username='$get_username'";
mysqli_query($link_to_db, $sql_03);
}



header("location: ./");
exit;
?>
