<?php
require_once("../reqfiles-secure/db-config.php");

/*
This will delete everything in the database
It will also insert the record for the admin
*/

$del_1 = "DELETE FROM interactions AUTO_INCREMENT = 1";
mysqli_query($link_to_db, $del_1);

$del_2 = "DELETE FROM messages AUTO_INCREMENT = 1";
mysqli_query($link_to_db, $del_2);

$del_3 = "DELETE FROM reviews AUTO_INCREMENT = 1";
mysqli_query($link_to_db, $del_3);

$del_4 = "DELETE FROM users";
mysqli_query($link_to_db, $del_4);

$del_5 = "INSERT INTO USERS (username, password, full_name, user_type) VALUES ('admin', 'password', 'James Muller', 'admin')";
mysqli_query($link_to_db, $del_5);

?>