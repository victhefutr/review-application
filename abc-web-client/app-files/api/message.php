<?php
//CONNECT TO DATABASE
require_once("../reqfiles-secure/db-config.php");
?>


<?php
//API VALUES
$data = json_decode(file_get_contents('php://input'), true);


$xbbt_message = $data["message"];
$xbbt_review_id = $data["review_id"];
$xbbt_username = $data["username"];

if(empty($xbbt_message) && empty($xbbt_review_id) && empty($xbbt_username))
{
$xbbt_message = $_POST["message"];
$xbbt_review_id = $_POST["review_id"];
$xbbt_username = $_POST["username"];
}
?>







<?php
//SEND MESSAGE****************************************************************

$resp_xa = array();
header("Content-Type: application/json");

//HANDLE ERRORS
if(empty($xbbt_message))
{
$api_input_1_err = "yes";	
}
elseif(strlen($xbbt_message) > 128)
{
$api_input_1_err = "yes";	
}


if(empty($xbbt_review_id))
{
$api_input_2_err = "yes";	
}


if(empty($xbbt_username))
{
$api_input_3_err = "yes";	
}
//HANDLE ERRORS


if(empty($api_input_1_err) && empty($api_input_2_err) && empty($api_input_3_err))
{
//PROCEED WITH THE OPERATION

//CHECK IF REVIEW OR USER EXISTS AND IS ADMIN --
$sql_x0 = "SELECT full_name FROM users WHERE username='$xbbt_username' AND user_type='admin'";
$result_x0 = mysqli_query($link_to_db, $sql_x0);
$row_x0 = mysqli_fetch_array($result_x0);


$sql_x1 = "SELECT username, created FROM reviews WHERE review_id='$xbbt_review_id'";
$result_x1 = mysqli_query($link_to_db, $sql_x1);
$row_x1 = mysqli_fetch_array($result_x1);

$stored_user_username = $row_x1["username"]; // this is the users own username, it was pulled from the table that verified the review



$sql_x3 = "SELECT message_id FROM messages WHERE review_id='$xbbt_review_id' AND username_user='$stored_user_username'";
$result_x3 = mysqli_query($link_to_db, $sql_x3);



if(empty($row_x0["full_name"]))
{
//user exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "This user doesn't exist OR they might not be an admin";

response($xas_status, $xas_code, $xas_message);
//user exists
}






elseif(empty($row_x1["created"]))
{
//user exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "The review you are trying to send this message for doesn't exist";

response($xas_status, $xas_code, $xas_message);
//user exists
}









elseif(mysqli_num_rows($result_x3) == 1)
{
//user exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "The company has already sent a message to this user";

response($xas_status, $xas_code, $xas_message);
//user exists
}











else
{
//GOOD TO GO

$sql_xa = "INSERT INTO messages (message, username_user, username_admin, review_id) VALUES ('$xbbt_message', '$stored_user_username', '$xbbt_username', '$xbbt_review_id')";
$result_xa = mysqli_query($link_to_db, $sql_xa);

if($result_xa)
{
//SUCCESS	
$xas_status = "success";
$xas_code = "01";
$xas_message = "Message sent successfully";

response($xas_status, $xas_code, $xas_message);
//SUCCESS	
}


else
{
//SUCCESS	
$xas_status = "fail";
$xas_code = "00";
$xas_message = "Message was not sent, please try again";

response($xas_status, $xas_code, $xas_message);
//SUCCESS
}
//GOOD TO GO
}
//CHECK IF REVIEW EXISTS AND IS ADMIN--

//PROCEED WITH THE OPERATION
}




else
{
//STOP THE OPERATION
$xas_status = "fail";
$xas_code = "00";
$xas_message = "
Your form inputs don't meet the criteria <br>
Message: $xbbt_message <br>
Review ID: $xbbt_review_id <br>
Admin Username: $xbbt_username 
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


///SEND MESSAGE****************************************************************

?>
