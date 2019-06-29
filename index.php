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
			WHERE s.shipped = 0 
			ORDER BY s.date DESC";
$txnResult = $conn->query($txnQuery);
//echo("Error description: " . mysqli_error($conn));
$sub_total = 0;
$total = 0;
$grand_total = 0;
$price = 0;
$tax = 0;	

?>
<div class="col-md-12">
	<h2 class="text-center">Orders to Ship</h2>
	<hr>
	<table class="table table-bordered table-condensed table-stripped"> 
		<thead><th></th> <th>Name</th> <th>Seller</th> <th>Total</th> <th>Date</th></thead>
		<tbody>
		<?php while($product = mysqli_fetch_assoc($txnResult)): //var_dump($product);
				$total = $product['total'];
				$date = date("M d, Y", strtotime($product['date']));
		?>
		  <tr<?=(date("M d, Y") == $date)?' class="success"':'';?>>
			<td><a href="orders.php?shipped_id=<?=$product['shipped_id'];?>" class="btn btn-xs btn-info">Details</a></td>
			<td><?=$product['full_name'];?></td>
			<td><?=$product['name'];?></td>
			<td><?=money($total);?></td>
			<td><?=pretty_date($product['date']);?></td>
		  </tr>
		<?php endwhile; ?>
	  </tbody>
	</table>
</div>

<div class="row">
	<!--sales by month-->
	<?php 
		$thisYr = date("Y");
		$lastYr = $thisYr - 1;
		$thisYrQ = $conn->query("SELECT SUM(total) as grand_total, date 
								FROM shipped s
								WHERE YEAR(date) = '{$thisYr}' AND s.shipped = 1
								GROUP BY s.date");
		//echo("Error description: " . mysqli_error($conn));
		$lastYrQ = $conn->query("SELECT SUM(total) as grand_total, date 
								FROM shipped s
								WHERE YEAR(date) = '{$lastYr}' AND s.shipped = 1
								GROUP BY s.date");
		//echo("Error description: " . mysqli_error($conn));
		$current = array();
		$last = array();
		$currentTotal = 0;
		$lastTotal = 0;
		while($x = mysqli_fetch_assoc($thisYrQ)){
			$month = date("m",strtotime($x['date']));
			if(!array_key_exists($month,$current)){
				$current[(int)$month] = $x['grand_total'];
			}else{
				$current[(int)$month] += $x['grand_total'];
			}
			$currentTotal = $x['grand_total'];
		}
		
		while($y = mysqli_fetch_assoc($lastYrQ)){
			$month = date("m",strtotime($y['date']));
			if(!array_key_exists($month,$last)){
				$last[(int)$month] = $y['grand_total'];
			}else{
				$last[(int)$month] += $y['grand_total'];
			}
			$lastTotal = $y['grand_total'];
		}
	;?>
	<div class="col-md-4">
		<h3 class="text-center">Sales by Month</h3>
		<table class="table table-bordered table-condensed table-stripped">
			<thead><th></th> <th><?=$lastYr;?></th> <th><?=$thisYr;?></th></thead>
			<tbody>
				<?php for($i = 1; $i <= 12; $i++): 
					$dt = DateTime::createFromFormat('!m',$i);
				?>
					<tr<?=(date("m") == $i)?' class="info"':'';?>>
						<td><?=$dt->format("F");?></td>
						<td><?=(array_key_exists($i,$last))?money($last[$i]):money(0);?></td>
						<td><?=(array_key_exists($i,$current))?money($current[$i]):money(0);?></td>
					</tr>
				<?php endfor; ?>
				<tr>
					<td>Total</td>
					<td><?=money($lastTotal);?></td>
					<td><?=money($currentTotal);?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<!--inventory-->
	<?php
		$iQuery = $conn->query("SELECT * FROM products WHERE archived = 0");
		$lowItems = array();
		while($product = mysqli_fetch_assoc($iQuery)){
			$item = array();
			
			$colors = colorsToArray($product['color']);
			foreach($colors as $color){
				if($color['quantity'] <= $color['threshold']){
					$cat = get_category($product['category_id']);
					$item = array(
						'title' => $product['title'],
						'color' => $color['color'],
						'quantity' => $color['quantity'],
						'threshold' => $color['threshold'],
						'category' => $cat['parent'].' ~ '.$cat['child']
					);
					$lowItems[] = $item;
				}
			}
		}
	
	;?>
	<div class="col-md-8">
		<h3 class="text-center">Low Inventory</h3>
		<table class="table table-bordered table-condensed table-stripped">
			<thead><th>Product</th> <th>Category</th> <th>Color</th> <th>Quantity</th> <th>Threshold</th></thead>
			<tbody>
			<?php foreach($lowItems as $item): ?>
				<tr<?=($item['quantity'] == 0)?' class="danger"':'';?>>
					<td><?=$item['title'];?></td>
					<td><?=$item['category'];?></td>
					<td><?=$item['color'];?></td>
					<td><?=$item['quantity'];?></td>
					<td><?=$item['threshold'];?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>


<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
