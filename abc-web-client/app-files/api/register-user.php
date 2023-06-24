<?php
//CONNECT TO DATABASE
require_once("../reqfiles-secure/db-config.php");
?>


<?php
//API VALUES
$data = json_decode(file_get_contents('php://input'), true);

$xbbt_full_name = $data["full_name"];
$xbbt_username = $data["username"];
$xbbt_password = $data["password"];

if(empty($xbbt_full_name) && empty($xbbt_username) && empty($xbbt_password))
{
$xbbt_full_name = $_POST["full_name"];
$xbbt_username = $_POST["username"];
$xbbt_password = $_POST["password"];
}
?>







<?php
//REGISTER USERS****************************************************************

$resp_xa = array();
header("Content-Type: application/json");

//HANDLE ERRORS
if(empty($xbbt_username))
{
$api_input_1_err = "yes";	
}
elseif(strlen($xbbt_username) > 10)
{
$api_input_1_err = "yes";	
}


if(empty($xbbt_full_name))
{
$api_input_2_err = "yes";
}
elseif(strlen($xbbt_full_name) > 40)
{
$api_input_2_err = "yes";	
}


if(empty($xbbt_password))
{
$api_input_3_err = "yes";
}
elseif(strlen($xbbt_password) < 6)
{
$api_input_3_err = "yes";
}
elseif(strlen($xbbt_password) > 10)
{
$api_input_3_err = "yes";	
}
//HANDLE ERRORS


if(empty($api_input_1_err) && empty($api_input_2_err) && empty($api_input_3_err))
{
//PROCEED WITH THE OPERATION
//CHECK IF USER EXISTS --
$sql_x0 = "SELECT created FROM users WHERE username='$xbbt_username'";
$result_x0 = mysqli_query($link_to_db, $sql_x0);

if(mysqli_num_rows($result_x0) == 1)
{
//user exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "This username is taken already";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);
//user exists
}




else
{
//GOOD TO GO
$sql_xa = "INSERT INTO users (username, password, full_name, user_type) VALUES ('$xbbt_username', '$xbbt_password', '$xbbt_full_name', 'user')";
$result_xa = mysqli_query($link_to_db, $sql_xa);

if($result_xa)
{
//SUCCESS	
$xas_status = "success";
$xas_code = "01";
$xas_message = "User was registered successfully";
$xas_objects = array(
'username'=>$xbbt_username,
'full_name'=>$xbbt_full_name,
'user_type'=>'user',
'account_created'=>date("d F Y")
);

response($xas_status, $xas_code, $xas_message, $xas_objects);
//SUCCESS	
}


else
{
//SUCCESS	
$xas_status = "fail";
$xas_code = "00";
$xas_message = "User was not registered, please try again";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);
//SUCCESS
}
//GOOD TO GO
}
//CHECK IF USER EXISTS --
//PROCEED WITH THE OPERATION
}




else
{
//STOP THE OPERATION
$xas_status = "fail";
$xas_code = "00";
$xas_message = "
Your form inputs don't meet the criteria <br>
Full Name: $xbbt_full_name <br>
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


//REGISTER USERS****************************************************************

?>
