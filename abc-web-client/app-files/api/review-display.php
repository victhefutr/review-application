<?php
//CONNECT TO DATABASE
require_once("../reqfiles-secure/db-config.php");
?>






<?php
//RETRIEVE REVIEWS****************************************************************

$resp_xa = array();
header("Content-Type: application/json");

$sql_xa = "SELECT * FROM reviews ORDER BY created DESC";

if($result_xa = mysqli_query($link_to_db, $sql_xa))
{

if(mysqli_num_rows($result_xa) > 0)
{	
$xas_status = "success";
$xas_code = "01";
$xas_message = "Review records exist";
$records = array();


while($row_xa = mysqli_fetch_assoc($result_xa))
{
//Gather results-----------------

#1 - PULL LIKE DATA
$imp_like_data_sql= "SELECT interaction_id FROM interactions WHERE review_id='".$row_xa['review_id']."' AND content='like'";
$imp_like_data_que = mysqli_query($link_to_db, $imp_like_data_sql);

#2 - PULL DISLIKE DATA
$imp_dislike_data_sql= "SELECT interaction_id FROM interactions WHERE review_id='".$row_xa['review_id']."' AND content='dislike'";
$imp_dislike_data_que = mysqli_query($link_to_db, $imp_dislike_data_sql);

#2 - PULL MESSAGE DATA, IF ANY
$imp_message_data_sql= "SELECT * FROM messages WHERE review_id='".$row_xa['review_id']."'";
$imp_message_data_que = mysqli_query($link_to_db, $imp_message_data_sql);
$imp_message_data_row = mysqli_fetch_array($imp_message_data_que);


$imp_like_count = mysqli_num_rows($imp_like_data_que);
$imp_dislike_count = mysqli_num_rows($imp_dislike_data_que);


#3 - RESOLVE MESSAGE DATE -- this is so the message date can return a null if empty
if(empty($imp_message_data_row["created"]))
{
$stored_message_date = null;
}

else
{
$stored_message_date = date("d F Y", strtotime($imp_message_data_row["created"])).' at '.date("h:i A", strtotime($imp_message_data_row["created"]));
}

//------------------------------------------



$records[] = array(
"review_id" => $row_xa["review_id"],
"review" => $row_xa["review"],
"rating" => $row_xa["rating"],
"username" => $row_xa["username"],
"created" => date("d F Y", strtotime($row_xa["created"])).' at '.date("h:i A", strtotime($row_xa["created"])),

"likes" => $imp_like_count,
"dislikes" => $imp_dislike_count,

"message" => $imp_message_data_row["message"],
"user_admin" => $imp_message_data_row["username_admin"],
"message_date" => $stored_message_date
);


//Gather results-----------------
}


#input all the stored row arrays into the objects
$xas_objects = $records;



response($xas_status, $xas_code, $xas_message, $xas_objects);

mysqli_free_result($result_xa);
}


else
{
//NO DATA TO DISPLAY	
$xas_status = "fail";
$xas_code = "00";
$xas_message = "There are no records to display";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);
//NO DATA TO DISPLAY
}
} 

else
{
//SERVER IS UNAVAILABLE	
$xas_status = "fail";
$xas_code = "00";
$xas_message = "Can't reach the database to authenticate";
$xas_objects = null;

response($xas_status, $xas_code, $xas_message, $xas_objects);
//SERVER IS UNAVAILABLE	
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


//RETRIEVE REVIEWS****************************************************************
?>
