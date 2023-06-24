<?php
//CONNECT TO DATABASE
require_once("../reqfiles-secure/db-config.php");
?>


<?php
//API VALUES
$data = json_decode(file_get_contents('php://input'), true);

$xbbt_review_id = $data["review_id"];
$xbbt_username = $data["username"];

if(empty($xbbt_review_id) && empty($xbbt_username))
{
$xbbt_review_id = $_POST["review_id"];
$xbbt_username = $_POST["username"];
}
?>







<?php
//REVIEW DELETE****************************************************************

$resp_xa = array();
header("Content-Type: application/json");

//HANDLE ERRORS
if(empty($xbbt_review_id))
{
$api_input_1_err = "yes";	
}


if(empty($xbbt_username))
{
$api_input_2_err = "yes";	
}
//HANDLE ERRORS


if(empty($api_input_1_err) && empty($api_input_2_err))
{
//PROCEED WITH THE OPERATION

//CHECK IF REVIEW OR USER EXISTS --
$sql_x0 = "SELECT full_name FROM users WHERE username='$xbbt_username'";
$result_x0 = mysqli_query($link_to_db, $sql_x0);
$row_x0 = mysqli_fetch_array($result_x0);


$sql_x1 = "SELECT created FROM reviews WHERE review_id='$xbbt_review_id'";
$result_x1 = mysqli_query($link_to_db, $sql_x1);
$row_x1 = mysqli_fetch_array($result_x1);



if(empty($row_x0["full_name"]))
{
//user exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "This user doesn't exist";

response($xas_status, $xas_code, $xas_message);
//user exists
}








elseif(empty($row_x1["created"]))
{
//user exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "The review you are trying to remove doesn't exist";

response($xas_status, $xas_code, $xas_message);
//user exists
}










else
{
//GOOD TO GO
$sql_xa = "DELETE FROM interactions WHERE review_id='$xbbt_review_id' AND username='$xbbt_username'";
$result_xa = mysqli_query($link_to_db, $sql_xa);

$sql_xb = "DELETE FROM messages WHERE review_id='$xbbt_review_id' AND username_user='$xbbt_username'";
$result_xb = mysqli_query($link_to_db, $sql_xb);

$sql_xc = "DELETE FROM reviews WHERE review_id='$xbbt_review_id' AND username='$xbbt_username'";
$result_xc = mysqli_query($link_to_db, $sql_xc);


if($result_xa && $result_xb && $result_xc)
{
//SUCCESS	
$xas_status = "success";
$xas_code = "01";
$xas_message = "Review was removed successfully";

response($xas_status, $xas_code, $xas_message);
//SUCCESS	
}


else
{
//SUCCESS	
$xas_status = "fail";
$xas_code = "00";
$xas_message = "Review was not removed, please try again";

response($xas_status, $xas_code, $xas_message);
//SUCCESS
}
//GOOD TO GO
}
//CHECK IF REVIEW EXISTS --

//PROCEED WITH THE OPERATION
}




else
{
//STOP THE OPERATION
$xas_status = "fail";
$xas_code = "00";
$xas_message = "
Your form inputs don't meet the criteria <br>
Review: $xbbt_review_id <br>
Rating: $xbbt_username
";

response($xas_status, $xas_code, $xas_message);
//STOP THE OPERATION
}


//DEFINE THE CALL FUNCTION***************************************
function response($xas_status, $xas_code, $xas_message)
{
$resp_xa["status"]= $xas_status;
$resp_xa["code"]= $xas_code;
$resp_xa["message"]= $xas_message;

echo json_encode($resp_xa); 
}


//REVIEW DELETE****************************************************************

?>
