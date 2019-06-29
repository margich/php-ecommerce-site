<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

//complete order
$sub_total = 0;
$total = 0;
$grand_total = 0;
$price = 0;
$shipped_id = sanitize((int)$_GET['shipped_id']);
$shopQ = $conn->query("SELECT shop_id FROM shipped WHERE shipped_id = '$shipped_id'");
$shop = mysqli_fetch_assoc($shopQ);
$shop_id = $shop['shop_id'];
$txnQuery = $conn->query("SELECT * FROM transactions2 t 
						INNER JOIN shipped s ON s.cart_id = t.cart_id 
						INNER JOIN users u ON t.user_id = u.user_id
						WHERE s.shipped_id = '{$shipped_id}'");
//$txnQuery = $conn->query("SELECT * FROM transactions WHERE transaction_id = '{$txn_id}'");
$txn = mysqli_fetch_assoc($txnQuery);
$cart_id = $txn['cart_id'];
$cartQ = $conn->query("SELECT * FROM cart WHERE cart_id = '{$cart_id}'");
$cart = mysqli_fetch_assoc($cartQ);


$productQ = $conn->query("
			SELECT s.shipped_id, s.cart_id, s.shop_id, s.item, s.color, s.quantity, s.date, s.shipped, p.product_id, p.title, p.price,
			c.category as child, v.category as parent
			FROM shipped s
			LEFT JOIN products p ON s.item = p.product_id
			LEFT JOIN transactions2 t ON s.cart_id = t.cart_id
			LEFT JOIN categories c ON c.category_id = p.category_id
			LEFT JOIN categories v ON v.category_id = c.parent
			WHERE s.shipped = 0  AND s.shipped_id = '{$shipped_id}'
			ORDER BY s.date");
			//echo("Error description: " . mysqli_error($conn));
			
			
if(isset($_GET['complete']) && $_GET['complete'] == 1){
	$soldQ = $conn->query("SELECT item, quantity FROM shipped WHERE shipped_id = '{$shipped_id}'");
	$sold = mysqli_fetch_assoc($soldQ);
	$soldItem = $sold['item'];
	$soldQuantity = $sold['quantity'];
	$oldQ = $conn->query("SELECT product_id, sold FROM products WHERE product_id = '{$soldItem}'");
	$old = mysqli_fetch_assoc($oldQ);
	$oldSold = $old['sold'];
	$newSold = $oldSold + $soldQuantity;
	$conn->query("UPDATE shipped SET shipped = 1 WHERE shipped_id = '{$shipped_id}'");
	$conn->query("UPDATE products SET sold = '$newSold' WHERE product_id = '{$soldItem}'");
	//echo("Error description: " . mysqli_error($conn));
	$_SESSION['success_flash'] = "The order has been completed";
	header('Location: index.php'); 
}	
	
?>

	<h2 class="text-center">Items Ordered</h2>
	<hr>
	<table class="table table-bordered table-condensed table-stripped">
		<thead><th>Quantity</th> <th>Title</th> <th>Category</th> <th>Color</th></thead>
		<tbody>
		<?php while ($product = mysqli_fetch_assoc($productQ)):?>
		  <tr>
			<td><?=$product['quantity'];?></td>
			<td><?=$product['title'];?></td>
			<td><?=$product['parent'].'~'.$product['child'];?></td>
			<td><?=$product['color'];?></td>
		  </tr>
			<?php 
				$quantity = (int)$product['quantity'];
				$price = (int)$product['price'];
				$sub_total = $price * $quantity;
				$tax = $sub_total * TAXRATE;
				$grand_total = $sub_total + $tax;
			?>
		<?php endwhile; ?>
	  </tbody>
	</table>
	
	<div class="row">
		<div class="col-md-6">
			<h3 class="text-center">Order Details</h3>
			<table class="table table-bordered table-condensed table-stripped">
				<tbody>
				  <tr>
					<td>Sub Total</td>
					<td><?=money($sub_total);?></td>
				  </tr>
				    <tr>
					<td>Tax</td>
					<td><?=money($tax);?></td>
				  </tr>
				    <tr>
					<td>Grand Total</td>
					<td><?=money($grand_total);?></td>
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
				<?=$txn['full_name'];?><br>
				<?=$txn['street'];?><br>
				<?=(($txn['street2'] != '')?$txn['street2'].'<br>':'')?>
				<?=$txn['city'].', '.$txn['region'];?><br>
			</address>
		</div>
	</div>
	<div class="pull-right">
		<a href="index.php" class="btn btn-default btn-large">Cancel</a>
		<a href="orders.php?complete=1&shipped_id=<?=$shipped_id;?>" class="btn btn-default btn-primary btn-large">Complete Order</a>
	</div>
	
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
