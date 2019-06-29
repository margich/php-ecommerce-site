<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';
?>

<!--Order to fill-->
<?php

$txnQuery = "SELECT *
			FROM shipped s
			LEFT JOIN products p ON s.item = p.product_id
			LEFT JOIN shop c ON s.shop_id = c.shop_id
			LEFT JOIN transactions2 t ON s.cart_id = t.cart_id
			LEFT JOIN users u ON t.user_id = u.user_id
			WHERE s.shipped = 1
			ORDER BY s.date DESC";
$txnResult = $conn->query($txnQuery);
//echo("Error description: " . mysqli_error($conn));
$sub_total = 0;
$total = 0;
$grand_total = 0;
$price = 0;
$tax = 0;	
$i = 0;
?>
<div class="col-md-12">
	<h2 class="text-center">Orders Shipped</h2>
	<hr>
	<table class="table table-bordered table-condensed table-stripped"> 
		<thead><th></th> <th>Name</th> <th>Seller</th> <th>Total</th> <th>Date</th></thead>
		<tbody>
		<?php while($product = mysqli_fetch_assoc($txnResult)): //var_dump($product);
				
				/*$quantity = (int)$product['quantity'];
				$price = (int)$product['price'];//var_dump($price);
				$sub_total = $price * $quantity;//var_dump($sub_total);
				$tax = $sub_total * TAXRATE;
				$total = $sub_total + $tax;//var_dump($total);*/
				$total = $product['total'];
				$date = date("M, Y", strtotime($product['date']));
				$i++;
				//$grand_total = $grand_total + $total;
		?>
		  <tr<?=(date("M, Y") == $date)?' class="success"':'';?>>
			<td><a href="shippedorders.php?shipped_id=<?=$product['shipped_id'];?>" class="btn btn-xs btn-info">Details</a></td>
			<td><?=$product['full_name'];?></td>
			<td><?=$product['name'];?></td>
			<td><?=money($total);?></td>
			<td><?=pretty_date($product['date']);?></td>
		  </tr>
		<?php endwhile; ?>
	  </tbody>
	</table>
</div>


<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
