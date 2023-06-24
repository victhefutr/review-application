<?php
$rem_stars = 5 - $set_uni_rating;

echo '
<h1>';

for($i = 0; $i < $set_uni_rating; $i++)
{
echo '
<i class="icon ion-ios-star"></i>
';
}

for($ii = 0; $ii < $rem_stars; $ii++)
{
echo '
<i class="icon ion-ios-star-outline"></i>
';
}


echo'
</h1>';
?>