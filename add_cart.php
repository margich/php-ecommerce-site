<?php require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$product_id = sanitize($_POST['product_id']);
$shop_id = sanitize($_POST['shop_id']);
$color = sanitize($_POST['color']);
$available = sanitize($_POST['available']);
$quantity = sanitize($_POST['quantity']);
$item = array();
$item[] = array(
  'id'        =>  $product_id,
  'color'     =>  $color,
  'quantity'  =>  $quantity,
  'shop_id'   =>  $shop_id,
);

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$query = $conn->query("SELECT * FROM products WHERE product_id = '{$product_id}'");
$product = mysqli_fetch_assoc($query);
$_SESSION['success_flash'] = $product['title']. ' added to your cart.';
//chech if cart cookie exists
if($cart_id != ''){
	$cartQ = $conn->query("SELECT * FROM cart WHERE cart_id = '{$cart_id}'");
	$cart = mysqli_fetch_assoc($cartQ);
	//$shippedQ = $conn->query("SELECT * FROM transactions WHERE cart_id = '{$cart_id}'");
	//$shipped = mysqli_fetch_assoc($shippedQ);
	
	//$orderQ = $conn->query("SELECT * FROM shipped2 WHERE cart_id = '{$cart_id}' AND shop_id = '$shop_id'");
	//$order = mysqli_fetch_assoc($orderQ);
	//$shipped_id = $order['id'];
	
	$previous_items = json_decode($cart['items'],true);
	//$pre_shipped_items = json_decode($cart['items'],true);
	$item_match = 0;
	//$shipped_match = 0;
	$new_items = array();
	//$shipped_items = array();
	
	/*foreach($pre_shipped_items as $sitem){
	if(($item[0]['id'] == $sitem['id']) && ($item[0]['color'] == $sitem['color']) && ($item[0]['shop_id'] == $sitem['shop_id'])){
		$sitem['quantity'] = $sitem['quantity'] + $item[0]['quantity'];
		if($sitem['quantity'] > $available){
			$sitem['quantity'] = $available;
		}
		$shipped_match = 1;
	}
	$shipped_items[] = $sitem;
	}
	if($shipped_match != 1){
		$shipped_items = array_merge($item,$shipped_items);
	}*/
	
	
	
	foreach($previous_items as $pitem){
	if(($item[0]['id'] == $pitem['id']) && ($item[0]['color'] == $pitem['color'])){
	  $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
	  if($pitem['quantity'] > $available){
		$pitem['quantity'] = $available;
	  }
	  $item_match = 1;
	}
	$new_items[] = $pitem;
	}// end of foreach
	
	if($item_match != 1){
		$new_items = array_merge($item,$new_items);
	}
	
	$items_json = json_encode($new_items);
	//$shipped_json = json_encode($shipped_items);
	$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
	//$date = date("Y-m-d H:i:s");
	$conn->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE cart_id = '{$cart_id}'");
	//$conn->query("UPDATE shipped2 SET items = '{$shipped_json}', date = '{$date}' WHERE cart_id = '{$cart_id}' AND id = '$shipped_id'");
	//$conn->query("UPDATE shipped SET items = '{$items_json}' WHERE id = '{$cart_id}'");
	echo("Update Error description: " . mysqli_error($conn));
	setcookie(CART_COOKIE,'',1,"/",$domain,false);
	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);

}else{
  //add cart to the database and set CART_COOKIE
  $items_json = json_encode($item);
  //$shipped_json = json_encode($shipped_items);var_dump($shipped_json);
  $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
  //$conn->query("INSERT INTO cart (items,expire_date) VALUES ('{$shipped_json}','{$cart_expire}')");
  $conn->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}')");
  //$conn->query("INSERT INTO shipped (transaction_id,cart_id,shop_id,items,date) VALUES ('$transaction_id,{$cart_id},$shop_id,{$shipped_json}','{$date}')");
  //$conn->query("INSERT INTO shipped2 (cart_id,shop_id,items) VALUES ('{$cart_id},$shop_id,{$shipped_json})");
  echo("Insert Error description: " . mysqli_error($conn));
  $cart_id = $conn->insert_id;
  setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
}
?>