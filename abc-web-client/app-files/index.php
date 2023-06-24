<?php
require_once("reqfiles-secure/db-config.php");
?>

<?php
//check for current session
require_once "xH-user-data.php";
?>

<?php
/*
Set all variables to empty so that they don't declare an error
*/
$xx_review = $xx_rating = "";
$xx_review_err = $xx_rating_err = "";
$form_err = $form_succ = "";
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Reviews - ABC Travels</title>
<?php include_once "reqfiles-app/style-content.php"; ?>
</head>

<body>

<?php
$header_caption = "See all of our user reviews";

if($logged_status == "yes")
{
$header_sub = "Welcome $disp_full_name, <a href='review-add'>post a review</a> or <a href='logout'>logout</a>.";
}
elseif($logged_status == "no")
{
$header_sub = "<a href='login'>Log in</a> to post a review or see messages sent to you";
}


include_once "reqfiles-app/element-header.php";
?>







<div class="review">
<!--REVIEW LIST-->


<?php
$reviews_01_sql = "SELECT * FROM reviews ORDER BY created DESC";

if($reviews_01_res = mysqli_query($link_to_db, $reviews_01_sql))
{
	
if(mysqli_num_rows($reviews_01_res) > 0)
{
	
while($reviews_01_row = mysqli_fetch_array($reviews_01_res))
{	
//CONTENT GOES HERE	

#1 - PULL LIKE DATA
$imp_like_data_sql= "SELECT interaction_id FROM interactions WHERE review_id='".$reviews_01_row['review_id']."' AND content='like'";
$imp_like_data_que = mysqli_query($link_to_db, $imp_like_data_sql);

#2 - PULL DISLIKE DATA
$imp_dislike_data_sql= "SELECT interaction_id FROM interactions WHERE review_id='".$reviews_01_row['review_id']."' AND content='dislike'";
$imp_dislike_data_que = mysqli_query($link_to_db, $imp_dislike_data_sql);

#2 - PULL MESSAGE DATA, IF ANY
$imp_message_data_sql= "SELECT * FROM messages WHERE review_id='".$reviews_01_row['review_id']."'";
$imp_message_data_que = mysqli_query($link_to_db, $imp_message_data_sql);
$imp_message_data_row = mysqli_fetch_array($imp_message_data_que);


$imp_like_count = mysqli_num_rows($imp_like_data_que);
$imp_dislike_count = mysqli_num_rows($imp_dislike_data_que);

//------------------------------------------

echo '
<div class="review-item">
<!--REVIEW ITEM-->
<h1>'.$reviews_01_row["rating"].' Star</h1>';

$set_uni_rating = $reviews_01_row["rating"];
include "xH-star-system.php";

echo ' 
<h2>'.$reviews_01_row["review"].'</h2>
<h3>Posted by '.$reviews_01_row["username"].' on '.date("d F Y", strtotime($reviews_01_row["created"])).' at '.date("h:i A", strtotime($reviews_01_row["created"])).'</h3>';



if(mysqli_num_rows($imp_message_data_que) > 0)
{
//show message only if you are the user
echo ' 
<div class="message">
<h1>Message</h1>
<h2>'.$imp_message_data_row["message"].'</h2>
<h3>Posted by '.$imp_message_data_row["username_admin"].' on '.date("d F Y", strtotime($imp_message_data_row["created"])).' at '.date("h:i A", strtotime($imp_message_data_row["created"])).'</h3>
</div>';
}


echo '
<div class="after-info">
<span>'.$imp_like_count.' Like(s)</span>
<span>'.$imp_dislike_count.' Dislike(s)</span>
</div>';


echo '
<div class="after-links">';

if($logged_status == "yes")
{
//INTERACTION DATA---LIKES AND DISLIKES
echo '
<span class="likes"><a href="review-interaction?id='.$reviews_01_row["review_id"].'&content=like">Like <i class="icon ion-thumbsup"></i></a></span>
<span class="likes"><a href="review-interaction?id='.$reviews_01_row["review_id"].'&content=dislike">Dislike <i class="icon ion-thumbsdown"></i></a></span>';
}



if($logged_status == "yes" && $reviews_01_row["username"] == $get_username)
{
//UPDATE AND DELETE FUNCTIONS
echo '
<span><a href="review-update?id='.$reviews_01_row["review_id"].'">Edit your review <i class="icon ion-compose"></i></a></span>
<span><a href="review-delete?id='.$reviews_01_row["review_id"].'">Delete <i class="icon ion-android-delete"></i></a></span>
';
}

echo '
</div>';


if($logged_status == "yes" && $disp_user_type == "admin" && mysqli_num_rows($imp_message_data_que) == 0)
{
//SEND USER MESSAGE
echo '
<a href="message-user?id='.$reviews_01_row["review_id"].'"><button>Message User</button></a>
';
}


echo '
<!--REVIEW ITEM-->
</div>
';

//CONTENT GOES HERE
}

mysqli_free_result($reviews_01_res);
}

else
{
//NO RESULT
echo '
<div class="review-item-empty">
<!--REVIEW ITEM-->
<i class="icon ion-ios-compose-outline"></i>
No reviews have been left for our platform, tell us how we\'re doing please.
<!--REVIEW ITEM-->
</div>
';
//NO RESULT
}
}
?>



<!--REVIEW LIST-->
</div>




<?php
include_once "reqfiles-app/element-footer.php";
?>




</body>
</html>