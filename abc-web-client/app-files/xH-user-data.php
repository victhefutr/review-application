<?php
/*
Checks for a User ID session, if present, it means user is logged in
---Pull their data
*/

if(isset($_SESSION["XX-LOGGED"]) && $_SESSION["XX-LOGGED"] === true)
{
//USER IS LOGGED IN

$logged_status = "yes";
$get_username = $_SESSION["XX-USERNAME"];




$user_data_sql= "SELECT full_name, user_type FROM users WHERE username = '$get_username'";
$user_data_que = mysqli_query($link_to_db, $user_data_sql);
$user_data_row = mysqli_fetch_array($user_data_que);

$disp_username = $get_username;
$disp_full_name = $user_data_row["full_name"];
$disp_user_type = $user_data_row["user_type"];

//USER IS LOGGED IN
}




else
{
$get_username = "";
$logged_status = "no";
}
?>