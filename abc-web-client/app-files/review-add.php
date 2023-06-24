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
/*
Set all variables to empty so that they don't declare an error
*/
$xx_review = $xx_rating = "";
$xx_review_err = $xx_rating_err = "";
$form_err = $form_succ = "";
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
//SERVER POST DATA


if($_POST['submit'] == "add") 
{
//REVIEW FORM


//CHECK FOR ERRORS
if(empty(trim($_POST["xx_review"])))
{
$xx_review_err = "Please leave your review";
} 
elseif(strlen(trim($_POST["xx_review"])) > 128)
{
$xx_review_err = "Your review is longer than 128 characters, please shorten it";
} 
else
{
$xx_review = trim($_POST["xx_review"]);
}


if(empty(trim($_POST["xx_rating"])))
{
$xx_rating_err = "Please select a rating";
}
else
{
$xx_rating = trim($_POST["xx_rating"]);
}
//CHECK FOR ERRORS



if(empty($xx_review_err) && empty($xx_rating_err))
{
//INPUT ERROR FREE

//REVIEW EXISTS VERIFICATION START
$sqla = "SELECT review FROM reviews WHERE username = ?";

if($stmta = mysqli_prepare($link_to_db, $sqla))
{
mysqli_stmt_bind_param($stmta, "s", $value_00_username);

$value_00_username = $get_username;

if(mysqli_stmt_execute($stmta))
{
mysqli_stmt_store_result($stmta);

if(mysqli_stmt_num_rows($stmta) != 1)
{
//SUCCESS ----

$sql_succ = "INSERT INTO reviews (review, rating, username) VALUES (?, ?, ?)";

if(($stmt_succ = mysqli_prepare($link_to_db, $sql_succ)))
{
mysqli_stmt_bind_param($stmt_succ, "sss", $value_0X_review, $value_0X_rating, $value_0X_username);

$value_0X_review = $xx_review; 
$value_0X_rating = $xx_rating; 
$value_0X_username = $get_username;

if(mysqli_stmt_execute($stmt_succ))
{
//SUCCESS -------------------------------------------

#1 - display success message
$form_succ = "Thank you for your review, you will be redirected to the home page in 3 seconds";

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

else
{
//ERROR ----
$form_err = "You've already left a review for our platform, you can edit your review from the review page";
//ERROR ----
}
} 

else
{
$form_err = "User data was not retrieved";
}
}

mysqli_stmt_close($stmta);
//REVIEW EXISTS VERIFICATION START


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
<title>Add Reviews - ABC Travels</title>
<?php include_once "reqfiles-app/style-content.php"; ?>
</head>

<body>

<?php
$header_caption = "Use the form below to add a review";
$header_sub = "See other reviews, <a href='./'>click here</a>";
include_once "reqfiles-app/element-header.php";
?>




<div class="form-content">
<!--CONTENT-->
<div class="form-header">Add Review</div>

<form method="post" action="review-add">
<!--FORM INPUTS-->

<!--###General Request Error-->
<div class="form-general-error"><?php echo $form_err; ?></div>
<div class="form-general-success"><?php echo $form_succ; ?></div>


<!--###Input Boxes-->
<div class="form-group">
<div class="form-label">Review</div>
<textarea class="form-control" type="text" name="xx_review" placeholder="What do you think about ABC Travels <?php echo $disp_full_name; ?>" maxlength="128" rows="4"><?php echo $xx_review; ?></textarea>
<div class="form-error"><?php echo $xx_review_err; ?></div>	
</div>


<div class="form-group">
<div class="form-label">Rating</div>
<select class="form-control" name="xx_rating">
<option value="5" <?php if($xx_rating == "5") { echo "selected"; } ?>>5 Stars</option>
<option value="4" <?php if($xx_rating == "4") { echo "selected"; } ?>>4 Stars</option>
<option value="3" <?php if($xx_rating == "3") { echo "selected"; } ?>>3 Stars</option>
<option value="2" <?php if($xx_rating == "2") { echo "selected"; } ?>>2 Stars</option>
<option value="1" <?php if($xx_rating == "1") { echo "selected"; } ?>>1 Star</option>
</select>
<div class="form-error"><?php echo $xx_rating_err; ?></div>	
</div>



<div class="form-group">
<button class="button-true" name="submit" type="submit" value="add">Submit</button>
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