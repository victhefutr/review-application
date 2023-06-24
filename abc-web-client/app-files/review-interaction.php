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
//---3453677 -dont get lost in braces

//get review id and like type to perform the interaction operation
$review_id = $_GET["id"];
$like_type = $_GET["content"];


/*
STEPS....
1. We will check if interaction data exists, if it doesn't we will
   * Simply insert the interaction and type
   
2. If it doesn't, we will
   * Check if interaction is the same type (i.e User clicked like on a LIKED post), then we will delete the interation data
   * ELSE, if the interaction is not the same type, we will update
*/

#1 
$imp_data_sql= "SELECT content FROM interactions WHERE username = '$get_username' AND review_id='$review_id'";
$imp_data_que = mysqli_query($link_to_db, $imp_data_sql);
$imp_data_row = mysqli_fetch_array($imp_data_que);

$stored_like_type = $imp_data_row["content"];

# - data exists
if(mysqli_num_rows($imp_data_que) == 1)
{
//---7349949 -dont get lost in braces

# - if same type, delete interaction
if($stored_like_type == $like_type)
{
$imp_del = "DELETE FROM interactions WHERE content='$like_type' AND  username='$get_username' AND review_id='$review_id'";
mysqli_query($link_to_db, $imp_del);
}

else
{
$upd_del = "UPDATE interactions SET content='$like_type' WHERE username='$get_username' AND review_id='$review_id'";
mysqli_query($link_to_db, $upd_del);
}


//---7349949 -dont get lost in braces
}





# - data doesn't exist
else
{
$imp_ins = "INSERT INTO interactions (content, username, review_id) VALUES ('$like_type', '$get_username', '$review_id')";
mysqli_query($link_to_db, $imp_ins);
}


//---3453677 -dont get lost in braces
}



header("location: ./");
exit;
?>
