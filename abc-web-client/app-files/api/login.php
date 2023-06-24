<?php
//CONNECT TO DATABASE
require_once("../reqfiles-secure/db-config.php");
?>


<?php
//API VALUES
$data = json_decode(file_get_contents('php://input'), true);

$xbbt_username = $data["username"];
$xbbt_password = $data["password"];


if(empty($xbbt_username) && empty($xbbt_password))
{
$xbbt_username = $_POST["username"];
$xbbt_password = $_POST["password"];
}
?>







<?php
//LOGIN****************************************************************

$resp_xa = array();
header("Content-Type: application/json");

//HANDLE ERRORS
if(empty($xbbt_username))
{
$api_input_1_err = "yes";	
}



if(empty($xbbt_password))
{
$api_input_2_err = "yes";
}
//HANDLE ERRORS


if(empty($api_input_1_err) && empty($api_input_2_err))
{
//PROCEED WITH THE OPERATION

//USER EXISTS VERIFICATION START
$sqla = "SELECT created, full_name, user_type FROM users WHERE username = ? AND password = ?";

if($stmta = mysqli_prepare($link_to_db, $sqla))
{
mysqli_stmt_bind_param($stmta, "ss", $value_00_username, $value_00_password);

$value_00_username = $xbbt_username;
$value_00_password = $xbbt_password;

if(mysqli_stmt_execute($stmta))
{
mysqli_stmt_store_result($stmta);
mysqli_stmt_bind_result($stmta, $pull_created, $pull_full_name, $pull_user_type); //this is to obtain the user's username
mysqli_stmt_fetch($stmta);

if(mysqli_stmt_num_rows($stmta) == 1)
{
//SUCCESS ----


$xas_status = "success";
$xas_code = "01";
$xas_message = "User authenticated successfully";
$xas_objects = array(
'username'=>$xbbt_username,
'full_name'=>$pull_full_name,
'user_type'=>$pull_user_type,
'account_created'=>date("d F Y", strtotime($pull_created))
);

response($xas_status, $xas_code, $xas_message, $xas_objects);

//SUCCESS ----
} 

else
{
//ERROR ----

$xas_status = "fail";
$xas_code = "00";
$xas_message = "User was not found, please try again";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);

//ERROR ----
}
} 

else
{
//ERROR ----

$xas_status = "fail";
$xas_code = "00";
$xas_message = "Can't reach the database to authenticate";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);

//ERROR ----
}
}

mysqli_stmt_close($stmta);
//USER EXISTS VERIFICATION START




//PROCEED WITH THE OPERATION
}




else
{
//STOP THE OPERATION
$xas_status = "fail";
$xas_code = "00";
$xas_message = "
Your form inputs don't meet the criteria <br>
Username: $xbbt_username <br>
Password: $xbbt_password
";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);
//STOP THE OPERATION
}


//DEFINE THE CALL FUNCTION***************************************
function response($xas_status, $xas_code, $xas_message, $xas_objects)
{
$resp_xa["status"]= $xas_status;
$resp_xa["code"]= $xas_code;
$resp_xa["message"]= $xas_message;
$resp_xa["objects"]= $xas_objects;

echo json_encode($resp_xa); 
}


//LOGIN****************************************************************

?>
