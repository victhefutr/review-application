<?php
//CONNECT TO DATABASE
require_once("../reqfiles-secure/db-config.php");
?>


<?php
//API VALUES
$data = json_decode(file_get_contents('php://input'), true);

$xbbt_review = $data["review"];
$xbbt_rating = $data["rating"];
$xbbt_review_id = $data["review_id"];
$xbbt_username = $data["username"];


if(empty($xbbt_review) && empty($xbbt_rating) && empty($xbbt_review_id) && empty($xbbt_username))
{
$xbbt_review = $_POST["review"];
$xbbt_rating = $_POST["rating"];
$xbbt_review_id = $_POST["review_id"];
$xbbt_username = $_POST["username"];
}
?>







<?php
//REVIEW UPDATE****************************************************************

$resp_xa = array();
header("Content-Type: application/json");

//HANDLE ERRORS
if(empty($xbbt_review))
{
$api_input_1_err = "yes";	
}
elseif(strlen($xbbt_review) > 128)
{
$api_input_1_err = "yes";	
}


if(empty($xbbt_rating))
{
$api_input_2_err = "yes";
}
elseif($xbbt_rating != "1" && $xbbt_rating != "2" && $xbbt_rating != "3" && $xbbt_rating != "4" && $xbbt_rating != "5")
{
$api_input_2_err = "yes";	
}


if(empty($xbbt_review_id))
{
$api_input_3_err = "yes";	
}


if(empty($xbbt_username))
{
$api_input_4_err = "yes";	
}
//HANDLE ERRORS


if(empty($api_input_1_err) && empty($api_input_2_err) && empty($api_input_3_err) && empty($api_input_4_err))
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
//review exists
$xas_status = "fail";
$xas_code = "00";
$xas_message = "The review you are trying to update doesn't exist";

response($xas_status, $xas_code, $xas_message);
//review exists
}










else
{
//GOOD TO GO
$sql_succ = "UPDATE reviews SET review = ?, rating = ? WHERE review_id = ? AND username = ?";

if(($stmt_succ = mysqli_prepare($link_to_db, $sql_succ)))
{
mysqli_stmt_bind_param($stmt_succ, "ssss", $value_0X_review, $value_0X_rating, $value_0X_review_id, $value_0X_username);

$value_0X_review = $xbbt_review; 
$value_0X_rating = $xbbt_rating; 
$value_0X_review_id = $xbbt_review_id;
$value_0X_username = $xbbt_username;

if(mysqli_stmt_execute($stmt_succ))
{
//SUCCESS -------------------------------------------
$xas_status = "success";
$xas_code = "01";
$xas_message = "Review was updated successfully";

response($xas_status, $xas_code, $xas_message);

//SUCCESS -------------------------------------------
} 

else
{
//ERROR ------
$xas_status = "fail";
$xas_code = "00";
$xas_message = "Review was not updated, please try again";

response($xas_status, $xas_code, $xas_message);
//ERROR ------
}
}

mysqli_stmt_close($stmt_succ); 




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
Review: $xbbt_review <br>
Rating: $xbbt_username <br>
Review ID: $xbbt_review_id <br>
Username: $xbbt_username
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


//REVIEW UPDATE****************************************************************

?>
