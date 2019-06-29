<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/title/title-category.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head-min.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/mobile.php';
?>


<div class="row" style="padding:30px">
<h1 class="text-center h1-list"><?=$cat;?></h1><hr>
<ul class="breadcrumb">
	<li><a href="/">Home</a></li>
	<li><?=$cat;?></li>
</ul>
	<div class="row" style="padding:20px">
    <div class="col-md-12 col-sm-12">
		<h2>Most Popular</h2><hr>
		<div class="product">
		<?php $popularQ = $conn->query("SELECT * FROM products pro
										INNER JOIN categories c ON c.category_id = pro.category_id
										INNER JOIN categories p ON p.category_id = c.parent 
										WHERE p.category_id = '$cat_id' AND archived = 0 
										ORDER BY sold DESC, title ASC LIMIT 8");
				while($popular = mysqli_fetch_assoc($popularQ)): 
				$price = $popular['price'];
				?>
			<div class="col-md-3 col-sm-4 col-xs-6 text-center pro-hover" style="cursor:pointer;">
				<button class="btn btn-sm btn-default btn-primary col-md-2 col-sm-2 col-xs-2 pull-right" onclick="detailsmodal(<?=$popular['product_id'];?>)">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
				<div onclick="location.href='product.php?item=<?=$popular['product_id'];?>'" class="">
					<?php $photos = explode(',',$popular['image']);?>
					<img src="<?=$photos[0]; ?>" name="<?php echo $popular['title']; ?>" alt="<?php echo $popular['title']; ?>" class="img-show"/>
					<h2 class="h3-list"><?php echo $popular['title']; ?></h2>
					<h3 class="h3-list text-success"><?=money($price);?></h3>
				</div>
			</div>
		<?php endwhile; ?>
		</div>
    </div>
  </div>
  
  <div class="row" style="padding:20px">
		<div class="col-md-12 col-sm-12">
			<h2>Most Recent</h2><hr>
			<div class="product">
				<?php 	$transQ = $conn->query("SELECT * FROM products pro
										INNER JOIN categories c ON c.category_id = pro.category_id
										INNER JOIN categories p ON p.category_id = c.parent 
										WHERE p.category_id = '$cat_id' AND archived = 0  
										ORDER BY date DESC, title ASC LIMIT 8");
						while($recent = mysqli_fetch_assoc($transQ)):
						$price = $recent['price'];
				?>
				<div class="col-md-3 col-sm-4 col-xs-6 text-center pro-hover" style="cursor:pointer;">
					<button class="btn btn-sm btn-default btn-primary col-md-2 col-sm-2 col-xs-2 pull-right" onclick="detailsmodal(<?=$recent['product_id'];?>)">
						<span class="glyphicon glyphicon-plus"></span>
					</button>
					<div onclick="location.href='product.php?item=<?=$recent['product_id'];?>'" class="text-center">
						<?php $photos = explode(',',$recent['image']);?>
						<img src="<?=$photos[0]; ?>"  name="<?php echo $recent['title']; ?>" alt="<?php echo $recent['title']; ?>" 
						class="img-show"/>
						<h2 class="h3-list"><?php echo $recent['title']; ?></h2>
						<h3 class="h3-list text-success"><?=money($price);?></h3>
					</div>
				</div>
				<?php  endwhile; ?>
			</div>
		</div>
	</div>
	
	<div class="col-md-12 col-sm-12">
			<?php 	
				$sql = "SELECT * FROM categories WHERE parent = '$cat_id' ORDER BY category";
				$cquery = $conn->query($sql);
				
				while($child = mysqli_fetch_assoc($cquery)):

				$c_id = $child['category_id'];
				//var_dump($child);
			?>
				<h1 class="h1-list"><?=$child['category'];?> <a href="/retlug/subcategory.php?subcategory=<?=$child['category'];?>"><p class="p-list">View all</p></a></h1><hr>
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
					<img src="<?=$photos[0]; ?>" name="<?php echo $product['title']; ?>"alt="<?php echo $product['title']; ?>" 
					class="img-show img-responsive"/>
					<h2 class="h3-list"><?php echo $product['title']; ?></h2>
					<h3 class="h3-list text-success"><?=money($price);?></h3>
				</div>
			</div>
			<?php  endwhile; ?>
			
			</div>
			
			<div class="clearfix"></div>
			<?php  endwhile; ?>
		</div>
	</div>
  
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/pro.php'; ?>