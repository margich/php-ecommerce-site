<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$mode = sanitize($_POST['mode']);
$edit_color = sanitize($_POST['edit_color']);
$edit_id = sanitize($_POST['edit_id']);
$cartQ = $conn->query("SELECT * FROM cart WHERE cart_id = '{$cart_id}'");
$result = mysqli_fetch_assoc($cartQ);

$items = json_decode($result['items'],true);
$updated_items = array();
$domain = (($_SERVER['HTTP_POST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

if($mode == 'removeone'){
	foreach($items as $item){
		if($item['id'] == $edit_id && $item['color'] == $edit_color){
			$item['quantity'] = $item['quantity'] - 1;
		}
		if($item['quantity'] > 0){
			$updated_items[] = $item;
		}
	}
}

if($mode == 'addone'){
	foreach($items as $item){
		if($item['id'] == $edit_id && $item['color'] == $edit_color){
			$item['quantity'] = $item['quantity'] + 1;
		}
		$updated_items[] = $item;
	}
}

if(!empty($updated_items)){
	$json_updated = json_encode($updated_items);
	$conn->query("UPDATE cart SET items = '{$json_updated}' WHERE cart_id = '{$cart_id}'");
	$_SESSION['success_flash'] = 'Your shopping cart has been updated';
}

//may be useful for buying analytics
if(empty($updated_items)){
	$conn->query("DELETE FROM cart WHERE cart_id = '{$cart_id}'");
	setcookie(CART_COOKIE,'',1,"/",$domain,false);
}

?>





