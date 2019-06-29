<?php require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$item_id = ((isset($_POST['item_id']))?sanitize($_POST['item_id']):'');
$item_name = ((isset($_POST['item_name']))?sanitize($_POST['item_name']):'');
$date = date("Y-m-d H:i:s");
$user = $user_data['user_id'];


//$conn->query("INSERT INTO wishlist (`user`,`item`,`date`) VALUES ('$user','$item_id','$date')");
$countQ = $conn->query("SELECT * FROM wishlist WHERE item = '$item_id'  AND user = '$user'");
$wishlist = mysqli_fetch_assoc($countQ); 
$wuser = $wishlist['user'];
$item = $wishlist['item'];

if($item_id ==  $item && $wuser ==  $user){
	$conn->query("UPDATE wishlist SET date = '$date' WHERE item = '$item_id' AND user = '$user'");
}

else{
	$conn->query("INSERT INTO wishlist (`user`,`item`,`date`) VALUES ('$user','$item_id','$date')");
} 

$_SESSION['success_flash'] = $item_name. ' added to your wishlist.';
?>