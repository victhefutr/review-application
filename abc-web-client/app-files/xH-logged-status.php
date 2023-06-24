<?php
/*
Check for a User ID session, if present, it means user is logged in
---Redirect to the home page
*/

if(isset($_SESSION["XX-LOGGED"]) && $_SESSION["XX-LOGGED"] === true)
{
header("location: ./");
exit;
}

else
{
$logged_status = "no";
}
?>