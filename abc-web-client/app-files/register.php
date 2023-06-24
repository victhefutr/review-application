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
$xx_fullname = $xx_username = $xx_password = "";
$xx_fullname_err = $xx_username_err = $xx_password_err = "";
$form_succ = $form_err = "";
?>


<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
//SERVER POST DATA


if($_POST['submit'] == "login") 
{
//LOGIN FORM


//CHECK FOR ERRORS
if(empty(trim($_POST["xx_fullname"])))
{
$xx_fullname_err = "Please enter your Full name";
} 
elseif(strlen(trim($_POST["xx_fullname"])) > 40)
{
$xx_fullname_err = "Your name is longer than 40 characters, please shorten it";
} 
else
{
$xx_fullname = trim($_POST["xx_fullname"]);
}


if(empty(trim($_POST["xx_username"])))
{
$xx_username_err = "Please enter your Username";
}
elseif(strlen(trim($_POST["xx_username"])) > 10)
{
$xx_username_err = "Your username is longer than 10 characters, please shorten it";
} 
else
{
$xx_username = trim($_POST["xx_username"]);
}


if(empty(trim($_POST["xx_password"])))
{
$xx_password_err = "Please enter your Password";
} 
elseif(strlen(trim($_POST["xx_password"])) < 6)
{
$xx_fullname_err = "Your password is shorter than 6 characters, please make it longer";
} 
elseif(strlen(trim($_POST["xx_password"])) > 10)
{
$xx_fullname_err = "Your password is longer than 10 characters, please shorten it";
} 
else
{
$xx_password = trim($_POST["xx_password"]);
}
//CHECK FOR ERRORS



if(empty($xx_fullname_err) && empty($xx_username_err) && empty($xx_password_err))
{
//INPUT ERROR FREE

//USER EXISTS VERIFICATION START
$sqla = "SELECT full_name FROM users WHERE username = ?";

if($stmta = mysqli_prepare($link_to_db, $sqla))
{
mysqli_stmt_bind_param($stmta, "s", $value_00_username);

$value_00_username = $xx_username;

if(mysqli_stmt_execute($stmta))
{
mysqli_stmt_store_result($stmta);

if(mysqli_stmt_num_rows($stmta) != 1)
{
//SUCCESS ----

$sql_succ = "INSERT INTO users (username, password, full_name, user_type) VALUES (?, ?, ?, ?)";

if(($stmt_succ = mysqli_prepare($link_to_db, $sql_succ)))
{
mysqli_stmt_bind_param($stmt_succ, "ssss", $value_0X_username, $value_0X_password, $value_0X_full_name, $value_0X_user_type);

$value_0X_username = $xx_username; 
$value_0X_password = $xx_password; 
$value_0X_full_name = $xx_fullname;
$value_0X_user_type = "user";

if(mysqli_stmt_execute($stmt_succ))
{
//SUCCESS -------------------------------------------

#1 - display success message
$form_succ = "Your account was created succesfully, you will be redirected to the login page in 5 seconds";

#2 - redirect to login page after 5 seconds
header("refresh:5; url=login");

//SUCCESS -------------------------------------------
} 

else
{
//ERROR ------
$form_err = "Account was not created, please try again!";
//ERROR ------
}
}

mysqli_stmt_close($stmt_succ); 


//SUCCESS ----
} 

else
{
//ERROR ----
$form_err = "We're sorry but this username is taken, try another one";
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
<title>Register - ABC Travels</title>
<?php include_once "reqfiles-app/style-content.php"; ?>
</head>

<body>

<?php
$header_caption = "Use the form below to register";
$header_sub = "Already have an account, <a href='login'>login</a> here";
include_once "reqfiles-app/element-header.php";
?>




<div class="form-content">
<!--CONTENT-->
<div class="form-header">Register</div>

<form method="post" action="register">
<!--FORM INPUTS-->

<!--###General Request Error-->
<div class="form-general-error"><?php echo $form_err; ?></div>
<div class="form-general-success"><?php echo $form_succ; ?></div>


<!--###Input Boxes-->
<div class="form-group">
<div class="form-label">Full Name</div>
<input class="form-control" type="text" name="xx_fullname" placeholder="John Doe" value="<?php echo $xx_fullname; ?>" maxlength="40">
<div class="form-error"><?php echo $xx_fullname_err; ?></div>	
</div>


<div class="form-group">
<div class="form-label">Username</div>
<input class="form-control" type="text" name="xx_username" placeholder="Username" value="<?php echo $xx_username; ?>" maxlength="10">
<div class="form-error"><?php echo $xx_username_err; ?></div>	
</div>


<div class="form-group">
<div class="form-label">Password</div>
<input class="form-control" type="password" name="xx_password" placeholder="Password" value="<?php echo $xx_password; ?>" maxlength="10">
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