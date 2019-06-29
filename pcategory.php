<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
//include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/title-category.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/mobile.php';


if(isset($_GET['id'])){
  $cat_id = sanitize($_GET['id']);
  $cat = sanitize($_GET['cat']);
}else{
  $cat_id = '';
}
$csql = "SELECT * FROM `categories` 
		WHERE category_id = '$cat_id'";
$pcatQ =  $conn->query($csql);
while($pcatR = mysqli_fetch_assoc($pcatQ)){
	$pcat = $pcatR['category'];
}

		
$sql = "SELECT products.product_id, products.title, products.price, products.list_price, products.image, categories.category, categories.parent FROM products 
		INNER JOIN categories
		ON products.category_id = category.category_id
		WHERE categories.parent = '$cat_id' AND archived = 0
		ORDER BY categories.category";
$productQ = $conn->query($sql);
$category = get_category($cat_id);
?>


<div class="row" style="padding:30px">
<h1 class="text-Left"><?=$pcat;?></h1>
<ul class="breadcrumb">
	<li><a href="/retlug/index.php">Home</a></li>
	<li><?=$pcat;?></li>
</ul>
<hr>
		<div class="col-md-12 col-sm-12">
			<?php 	
				$sql = "SELECT * FROM categories WHERE parent = '$cat_id' ORDER BY category";
				$cquery = $conn->query($sql);
				while($child = mysqli_fetch_assoc($cquery)):
				$c_id = $child['category_id'];
				//var_dump($child);
			?>
				<h2><?=$child['category'];?></h2><hr class="col-md-3">
				<div class="clearfix"></div>
				<div class="product">
			<?php
				$sql2 = "SELECT * FROM categories INNER JOIN products ON categories.category_id = products.category_id WHERE categories.category_id = $c_id AND archived = 0 ORDER BY category LIMIT 4";
				$iquery = $conn->query($sql2);
				while($product = mysqli_fetch_assoc($iquery)):
				$price = $product['price'];
			?>
			<div class="col-md-3 col-sm-4 col-xs-12 text-center pro-hover" style="cursor:pointer;">
				<button class="btn btn-sm btn-default btn-primary col-md-2 col-sm-2 col-xs-2 pull-right" onclick="detailsmodal(<?=$product['product_id'];?>)">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
				<div onclick="location.href='product.php?item=<?=$product['product_id'];?>'">
					<?php $photos = explode(',',$product['image']);?>
					<img src="<?=$photos[0]; ?>" alt="<?php echo $product['title']; ?>" class="img-show img-responsive"/>
					<h5><?php echo $product['title']; ?></h5>
					<p class="price text-success">Price: <?=money($price);?></p>
				</div>
			</div>
			<?php  endwhile; ?>
			</div>
			<a href="/retlug/category.php?cat=<?=$child['category_id'];?>"><h5 class="pull-right">View all</h5></a>
			<div class="clearfix"></div>
			<?php  endwhile; ?>
		</div>
	</div>
  
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/pro.php'; ?>