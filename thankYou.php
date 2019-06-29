<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

if(!is_logged_in_user()== false){
$create = ((isset($_POST['create']))?sanitize($_POST['create']):'');
$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
$guest_email = ((isset($_POST['guest_email']))?sanitize($_POST['guest_email']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$phoneNo = ((isset($_POST['phoneNo']))?sanitize($_POST['phoneNo']):'');
$street = ((isset($_POST['street']))?sanitize($_POST['street']):'');
$street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
$city = ((isset($_POST['city ']))?sanitize($_POST['city ']):'');
$region = ((isset($_POST['region']))?sanitize($_POST['region']):'');
$create = ((isset($_POST['create']))?sanitize($_POST['create']):'');
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$sub_total = ((isset($_POST['sub_total']))?sanitize($_POST['sub_total']):'');
$tax = ((isset($_POST['tax']))?sanitize($_POST['tax']):'');
$grand_total = ((isset($_POST['grand_total']))?sanitize($_POST['grand_total']):'');
$user_id = ((isset($_POST['user_id']))?sanitize($_POST['user_id']):'');
$cart_id = ((isset($_POST['cart_id']))?sanitize($_POST['cart_id']):'');
}

$date = date("Y-m-d H:i:s");
$z = array();
$b = array();
$idArray = array();


if(is_logged_in_user()){
	$user_id = $user_data['user_id'];
	$full_name = $user_data['full_name']; 
	$email = $user_data['email']; 
	$phoneNo = $user_data['phoneNo']; 
	$street = $user_data['street']; 
	$street2 = $user_data['street2']; 
	$city = $user_data['city']; 
	$region = $user_data['region']; 
}

//create account
if($create == '' && $user_id == ''){
	$live = 1;
	$guest = 0;
	//$password = md5();
	$hashed = password_hash($password,PASSWORD_DEFAULT);
	$sql = "INSERT INTO users (`full_name`,`email`,`phoneNo`,`password`,`street`,`street2`,`city`,`region`,`live`,`guest`) 
		VALUES ('$full_name','$guest_email','$phoneNo','$hashed','$street','$street2','$city','$region','$live','$guest')";
	$conn->query($sql);
	$insert = mysqli_insert_id($conn);	
	$email = sanitize($_POST['guest_email']);
	$user_id = $insert;
}

//create guest account
if($create == '' && $user_id == ''){
	$live = 0;
	$guest = 1;
	//$password = md5();
	$password = 'password';
	$hashed = password_hash($password,PASSWORD_DEFAULT);
	$sql = "INSERT INTO users (`full_name`,`email`,`phoneNo`,`password`,`street`,`street2`,`city`,`region`,`live`,`guest`) 
		VALUES ('$full_name','$guest_email','$phoneNo','$hashed','$street','$street2','$city','$region','$live','$guest')";
	$conn->query($sql);
	$insert = mysqli_insert_id($conn);	
	$email = sanitize($_POST['guest_email']);
	$user_id = $insert;
}
	
//adjust inventory
$itemQ = $conn->query("SELECT * FROM cart WHERE cart_id = '{$cart_id}'");
$result = mysqli_fetch_assoc($itemQ);
$items = json_decode($result['items'],true);

foreach($items as $item){
	$idArray[] = $item['id'];
}
$ids = implode(',',$idArray);
$productQ = $conn->query("
			SELECT i.product_id as 'id', i.title as 'title', s.name as 'shop', c.category_id as 'cid', c.category as 'child', p.category as 'parent'
			FROM products i
			LEFT JOIN shop s ON i.shop_id = s.shop_id
			LEFT JOIN categories c ON i.category_id = c.category_id
			LEFT JOIN categories p ON c.parent = p.category_id
			WHERE i.product_id IN ({$ids})
			ORDER BY i.title");
			
while($p = mysqli_fetch_assoc($productQ)){
	
	foreach($items as $item){
		if($item['id'] == $p['id']){
		$x = $item;
		continue;
		}
	}
	$products[] = array_merge($x,$p);
}


//input into transactions
$conn->query("INSERT INTO transactions2 (`user_id`, `cart_id`, `sub_total`, `tax`, `grand_total`, `date`) 
			VALUES ('{$user_id}','{$cart_id}','$sub_total','$tax','$grand_total','$date')");
/*
$conn->query("INSERT INTO transactions (`user_id`, `cart_id`, `full_name`, `email`, `phoneNo`, `street`, `street2`, `city`, 
										`region`,`sub_total`, `tax`, `grand_total`, `date`) 
								VALUES ('$user_id', '$cart_id', '$full_name', '$email', '$phoneNo', '$street', '$street2', 
										'$city', '$region','$sub_total','$tax','$grand_total','$date')");*/
		
		
$txnQuery = $conn->query("SELECT * FROM transactions2 WHERE cart_id = '{$cart_id}'");
$txn = mysqli_fetch_assoc($txnQuery);
$txn_id = sanitize($txn['id']);
$user_id = sanitize($txn['user_id']);
$item_match = 0;
$new_items = array();
	
$userQuery = $conn->query("SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($userQuery);

include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/off-nav.php';
?>
<?php
if(isset($_POST['create'])){
  echo '<div class="alert alert-success fade in" style="margin-top:50px; margin-bottom:-48px;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Success! </strong>Account successfully created.
		</div>';
}
?>
	<h2 class="text-center" style="padding:30px;">Items Ordered</h2>
	<hr>
	<table class="table table-bordered table-condensed table-stripped">
		<thead><th>Quantity</th> <th>Title</th> <th>Sold By</th> <th>Category</th> <th>Color</th></thead>
		<tbody>
		<?php foreach($products as $product): ?>
		<tr>
			<td><?=$product['quantity'];?></td>
			<td><?=$product['title'];?></td>
			<td><?=$product['shop'];?></td>
			<td><?=$product['parent'].'~'.$product['child'];?></td>
			<td><?=$product['color'];?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	
	<div class="row">
		<div class="col-md-6">
			<h3 class="text-center">Order Details</h3>
			<table class="table table-bordered table-condensed table-stripped">
				<tbody>
				  <tr>
					<td>Sub Total</td>
					<td><?=money($txn['sub_total']);?></td>
				  </tr>
				    <tr>
					<td>Tax</td>
					<td><?=money($txn['tax']);?></td>
				  </tr>
				    <tr>
					<td>Grand Total</td>
					<td><?=money($txn['grand_total']);?></td>
				  </tr>
				    <tr>
					<td>Date</td>
					<td><?=pretty_date($txn['date']);?></td>
				  </tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<h3 class="text-center">Shipping Address</h3>
			<address>
				<?=$user['full_name'];?><br>
				<?=$user['street'];?><br>
				<?=(($user['street2'] != '')?$user['street2'].'<br>':'')?>
				<?=$user['city'].', '.$user['region'];?><br>
			</address>
		</div>
	</div>
	<div class="pull-right">
		<a href="/retlug/cart.php" class="btn btn-default btn-large">Cancel</a>
		<a href="confirm.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-default btn-primary btn-large">Confirm Order</a>
	</div>