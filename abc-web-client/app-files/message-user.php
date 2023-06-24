<?php
require_once("reqfiles-secure/db-config.php");
?>

<?php
//check for current session
require_once "xH-user-data.php";
?>

<?php
//this page is an action page, redirect away if no user login
if($logged_status == "no")
{
header("location: ./");
exit;
}
?>

<?php
$review_id = $_GET["id"];
?>

<?php
/*
Set all variables to empty so that they don't declare an error
*/
$xx_message = "";
$xx_message_err = "";
$form_err = $form_succ = "";
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
//SERVER POST DATA


if($_POST['submit'] == "send") 
{
//REVIEW FORM


//CHECK FOR ERRORS
if(empty(trim($_POST["xx_message"])))
{
$xx_message_err = "Please type in your message";
} 
elseif(strlen(trim($_POST["xx_message"])) > 128)
{
$xx_message_err = "Your message is longer than 128 characters, please shorten it";
} 
else
{
$xx_message = trim($_POST["xx_message"]);
}

//CHECK FOR ERRORS



if(empty($xx_message_err))
{
//INPUT ERROR FREE

//USER REVIEW EXISTS VERIFICATION START
$sqla = "SELECT username FROM reviews WHERE review_id = ?";

if($stmta = mysqli_prepare($link_to_db, $sqla))
{
mysqli_stmt_bind_param($stmta, "s", $value_00_review_id);

$value_00_review_id = $review_id;

if(mysqli_stmt_execute($stmta))
{
mysqli_stmt_store_result($stmta);
mysqli_stmt_bind_result($stmta, $pull_username_user); //this is to obtain the user's username
mysqli_stmt_fetch($stmta);

if(mysqli_stmt_num_rows($stmta) == 1)
{
//MESSAGE EXISTS VERIFICATION START
$sqlb = "SELECT message_id FROM messages WHERE review_id = ? AND username_user = ?";

if($stmtb = mysqli_prepare($link_to_db, $sqlb))
{
mysqli_stmt_bind_param($stmtb, "ss", $value_01_review_id, $value_01_username_user);

$value_01_review_id = $review_id;
$value_01_username_user = $pull_username_user;

if(mysqli_stmt_execute($stmtb))
{
mysqli_stmt_store_result($stmtb);

if(mysqli_stmt_num_rows($stmtb) == 1)
{
//ERROR ----
$form_err = "The company has already sent this user a message for this review";
//ERROR ----
} 

else
{
//SUCCESS ----
$sql_succ = "INSERT INTO messages (message, username_user, username_admin, review_id) VALUES (?, ?, ?, ?)";

if(($stmt_succ = mysqli_prepare($link_to_db, $sql_succ)))
{
mysqli_stmt_bind_param($stmt_succ, "ssss", $value_0X_message, $value_0X_username_user, $value_0X_username_admin, $value_0X_review_id);

$value_0X_message = $xx_message; 
$value_0X_username_user = $pull_username_user; 
$value_0X_username_admin = $get_username;
$value_0X_review_id = $review_id;

if(mysqli_stmt_execute($stmt_succ))
{
//SUCCESS -------------------------------------------

#1 - display success message
$form_succ = "Thank you for your message, you will be redirected to the home page in 3 seconds";

#2 - redirect to login page after 5 seconds
header("refresh:3; url=./");

//SUCCESS -------------------------------------------
} 

else
{
//ERROR ------
$form_err = "Review was not created, please try again";
//ERROR ------
}
}

mysqli_stmt_close($stmt_succ); 


//SUCCESS ----
}
} 

else
{
$form_err = "User data was not retrieved";
}
}

mysqli_stmt_close($stmtb);
//MESSAGE EXISTS VERIFICATION END
} 

else
{
//ERROR ----
$form_err = "The review doesn't exist anymore";
//ERROR ----
}
} 

else
{
$form_err = "User data was not retrieved";
}
}

mysqli_stmt_close($stmta);
//USER REVIEW EXISTS VERIFICATION END


//INPUT ERROR FREE
}



//REVIEW FORM
}


//SERVER POST DATA
}

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Message user - ABC Travels</title>
<?php include_once "reqfiles-app/style-content.php"; ?>
</head>

<body>

<?php
$header_caption = "Use the form below to message this user";
$header_sub = "See other reviews, <a href='./'>click here</a>";
include_once "reqfiles-app/element-header.php";
?>


<?php
//Since this is an update, pull review data

$review_data_sql= "SELECT review, rating, username FROM reviews WHERE review_id='$review_id'";
$review_data_que = mysqli_query($link_to_db, $review_data_sql);
$review_data_row = mysqli_fetch_array($review_data_que);

$stored_review = $review_data_row["review"];
$stored_rating = $review_data_row["rating"];
$stored_username = $review_data_row["username"];
?>




<div class="form-content">
<!--CONTENT-->
<div class="form-header">Message User</div>

<form method="post" action="message-user?id=<?php echo $review_id; ?>">
<!--FORM INPUTS-->

<!--###General Request Error-->
<div class="form-general-error"><?php echo $form_err; ?></div>
<div class="form-general-success"><?php echo $form_succ; ?></div>


<!--###Input Boxes-->
<div class="form-group">
<div class="form-label">
<?php 
echo $stored_review . "<br>"; 
$set_uni_rating = $stored_rating;
include "xH-star-system.php";
?>

</div>
<textarea class="form-control" type="text" name="xx_message" placeholder="Send <?php echo $stored_username; ?> a message concerning this review" maxlength="128" rows="4"><?php echo $xx_message; ?></textarea>
<div class="form-error"><?php echo $xx_message_err; ?></div>	
</div>



<div class="form-group">
<button class="button-true" name="submit" type="submit" value="send">Send</button>
</div>


<!--FORM INPUTS-->
</form>

<!--CONTENT-->
</div>



<?php
include_once "reqfiles-app/element-footer.php";
?>




</body>
</html>