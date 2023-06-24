<?php
require_once("reqfiles-secure/db-config.php");
?>

<?php
//check for current session
require_once "xH-logged-status.php";
?>


<?php
/*
Set all variables to empty so that they don't declare an error
*/
$xx_username = $xx_password = "";
$xx_username_err = $xx_password_err = "";
$form_err = $form_succ = "";
?>



<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
//SERVER POST DATA


if($_POST['submit'] == "login") 
{
//LOGIN FORM


//CHECK FOR ERRORS
if(empty(trim($_POST["xx_username"])))
{
$xx_username_err = "Please enter your Username";
} 
else
{
$xx_username = trim($_POST["xx_username"]);
}


if(empty(trim($_POST["xx_password"])))
{
$xx_password_err = "Please enter your password";
} 
else
{
$xx_password = trim($_POST["xx_password"]);
}
//CHECK FOR ERRORS



if(empty($xx_username_err) && empty($xx_password_err))
{
//INPUT ERROR FREE

//USER EXISTS VERIFICATION START
$sqla = "SELECT full_name FROM users WHERE username = ? AND password = ?";

if($stmta = mysqli_prepare($link_to_db, $sqla))
{
mysqli_stmt_bind_param($stmta, "ss", $value_00_username, $value_00_password);

$value_00_username = $xx_username;
$value_00_password = $xx_password;

if(mysqli_stmt_execute($stmta))
{
mysqli_stmt_store_result($stmta);

if(mysqli_stmt_num_rows($stmta) == 1)
{
//SUCCESS ----

#1 - Log the session to remember the user
$_SESSION["XX-LOGGED"] = true;
$_SESSION["XX-USERNAME"] = $xx_username;

header("location: ./");
exit;

//SUCCESS ----
} 

else
{
//ERROR ----
$form_err = "We're sorry but such user doesn't exist";
//ERROR ----
}
} 

else
{
$form_err = "User data was not retrieved";
}
}

mysqli_stmt_close($stmta);
//USER EXISTS VERIFICATION START


//INPUT ERROR FREE
}



//LOGIN FORM
}


//SERVER POST DATA
}

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - ABC Travels</title>
<?php include_once "reqfiles-app/style-content.php"; ?>
</head>

<body>

<?php
$header_caption = "Use the form below to login";
$header_sub = "Don't have an account, <a href='register'>register</a> for one";
include_once "reqfiles-app/element-header.php";
?>




<div class="form-content">
<!--CONTENT-->
<div class="form-header">Login</div>

<form method="post" action="login">
<!--FORM INPUTS-->

<!--###General Request Error-->
<div class="form-general-error"><?php echo $form_err; ?></div>


<!--###Input Boxes-->
<div class="form-group">
<div class="form-label">Username</div>
<input class="form-control" type="text" name="xx_username" placeholder="Username" value="<?php echo $xx_username; ?>">
<div class="form-error"><?php echo $xx_username_err; ?></div>	
</div>


<div class="form-group">
<div class="form-label">Password</div>
<input class="form-control" type="password" name="xx_password" placeholder="Password" value="<?php echo $xx_password; ?>">
<div class="form-error"><?php echo $xx_password_err; ?></div>	
</div>



<div class="form-group">
<button class="button-true" name="submit" type="submit" value="login">Login</button>
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