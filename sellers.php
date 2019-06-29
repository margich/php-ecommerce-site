<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

//archive or restore shop
if(isset($_GET['archived']) && isset($_GET['shop'])){
	$archived = sanitize((int)$_GET['archived']);
	$shop = sanitize((int)$_GET['shop']);
	$conn->query("UPDATE shop SET archived = '$archived' WHERE shop_id = '$shop'");
	$conn->query("UPDATE products SET archived = '$archived', featured = '0' WHERE shop_id = '$shop'");
	//echo("Error description: " . mysqli_error($conn));
	if($archived == 1){
		$_SESSION['success_flash'] = 'Shop successfully archived';
	}
	if($archived == 0){
		$_SESSION['success_flash'] = 'Shop successfully restored';
	}
	header('Location: sellers.php');
}

$shopQuery = $conn->query("SELECT shop.shop_id,shop.join_date,shop.archived,name,COUNT(products.product_id) as no_pro, 
							SUM(products.sold) as pro_sold FROM shop INNER JOIN products ON shop.shop_id = products.shop_id 
							GROUP BY shop.shop_id ORDER BY name ASC");
?>

<h2 class="text-center">Shops</h2>
<a href="add_shop.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Shop</a><div class="clearfix"></div>
<hr>

<div class="col-md-12">
	<table class="table table-bordered table-condensed table-stripped">
  <thead>
		<th></th><th>Shop</th> <th>No of Products</th> <th>Sold</th> <th>Date Joined</th> <th>Sellers</th> <th>Archived</th> 
  </thead>
  <tbody>
    <?php while($shop = mysqli_fetch_assoc($shopQuery)):
		$shop_id = $shop['shop_id'];
		$shop_name = $shop['name'];
		$no_pro = $shop['no_pro'];
		$pro_sold = $shop['pro_sold'];
		$date = $shop['join_date'];
		//$archived = (($shop['archived'] == '1')?'Yes':'No');
		$archived = $shop['archived'];
		$no_sellersQ = $conn->query("SELECT COUNT(sellers.seller_id) as sellers
									FROM sellers
									Where shop_id = '$shop_id'");
		while($no_sellers = mysqli_fetch_assoc($no_sellersQ)){
			$sellers = $no_sellers['sellers'];
		}
		
      ?>
      <tr<?=(($archived == '1')?' class="danger"':' class="success"');?>>
        <td>
			<a href="/retlug/admin/shop.php?shop=<?=$shop_id;?>" class="btn btn-xs btn-info">View</a>
			<a href="/retlug/admin/edit_shop.php?shop=<?=$shop_id;?>" class="btn btn-xs btn-default">
				<span class="glyphicon glyphicon-pencil"></span>
			</a>
        </td>
		<td><?=$shop_name;?></td>
		<td><?=$no_pro;?></td>
		<td><?=$pro_sold;?></td>
		<td><?=$date;?></td>
		<td><?=$sellers;?></td>
		<td>
			<a href="/retlug/admin/sellers.php?archived=<?=(($archived == 0)?'1':'0');?>&shop=<?=$shop_id;?>" class="btn btn-xs 	btn-default">
				<span class="glyphicon glyphicon-<?=(($archived == 0)?'plus':'minus');?>"></span>
			</a>
			&nbsp <?=(($archived == 0)?'Archive':'Restore');?>
		</td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>
<div class="col-md-4">

</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
