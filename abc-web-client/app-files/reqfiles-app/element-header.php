<div class="header">
<!--HEADER-->
<img src="media/logo.png">

<h1><?php echo $header_caption; ?></h1>
<h2><?php echo $header_sub; ?></h2>

<?php
if($logged_status == "yes")
{
if($disp_user_type == "admin")
{
echo '
<h2>This user is an administrator</h2>
';
}
}
?>

<!--HEADER-->
</div>