<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$cart_id = sanitize((int)$_GET['cart_id']);
$itemQ = $conn->query("SELECT * FROM cart WHERE cart_id = '{$cart_id}'");
$result = mysqli_fetch_assoc($itemQ);
$items = json_decode($result['items'],true);

foreach($items as $item){
	$idArray[] = $item['id'];
}
$ids = implode(',',$idArray);
$productQ = $conn->query("
			SELECT i.product_id, i.title, i.shop_id, i.price
			FROM products i
			WHERE i.product_id IN ({$ids})
			ORDER BY i.title");
			
while($p = mysqli_fetch_assoc($productQ)){//var_dump($p);
	
	foreach($items as $item){
		if($item['id'] == $p['product_id']){
		$x = $item;
		continue;
		}
	}
	$products[] = array_merge($x,$p);//var_dump($products);
}


//insert into shipped
	foreach($products as $product){
		$s_id = $product['shop_id'];
		$i_id = $product['id'];
		$color = $product['color'];
		$quantity = $product['quantity'];
		$price = $product['price'];
		$sub_total = $price * $quantity;
		$tax =  $sub_total * TAXRATE;
		$total = $sub_total + $tax;
		$conn->query("INSERT INTO shipped (`cart_id`, `shop_id`,  `item`, `color`, `quantity`, `sub_total`, `tax`, `total`) 
						VALUES ('{$cart_id}','$s_id','$i_id','$color','$quantity','$sub_total','$tax','$total')");
	}



foreach($items as $item){
	$newColors = array();
	$item_id = $item['id'];
	$productQ = $conn->query("SELECT color FROM products WHERE product_id = '{$item_id}'");
	$product = mysqli_fetch_assoc($productQ);
	$colors = colorsToArray($product['color']);
	foreach($colors as $color){
		if($color['color'] == $item['color']){
			$q = $color['quantity'] - $item['quantity'];
			$newColors[] = array('color' => $color['color'], 'quantity' => $q, 'threshold' => $color['threshold']);
		}else{
			$newColors[] = array('color' => $color['color'], 'quantity' => $color['quantity'], 'threshold' => $color['threshold']);
		}
	}
	$colorString = colorsToString($newColors);
	$conn->query("UPDATE products SET color = '{$colorString}' WHERE product_id = '{$item_id}'");
}

		
	
$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
setcookie(CART_COOKIE,'',1,"/",$domain,false);
$_SESSION['success_flash'] = ' Order Confirmed.';
header('Location: /retlug/index.php');

?>