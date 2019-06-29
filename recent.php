<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/title/title-recent.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head-min.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/off-nav.php';
?>

    <div class="row">
		<div class="container"> 
			<h1 class="h1-list">Most Recent</h1><hr>
			<ul class="breadcrumb">
				<li><a href="/">Home</a></li>
				<li>Most Recent</li>
			</ul>
			<div class="product">
				<?php while($product = mysqli_fetch_assoc($recentQ)) :
						$price = $product['price'];
				?>
				<div class="col-md-3 col-sm-4 col-xs-12 text-center pro-hover" style="cursor:pointer;">
					<button class="btn btn-sm btn-default btn-primary col-md-2 col-sm-2 col-xs-2 pull-right" onclick="detailsmodal(<?=$product['product_id'];?>)">
						<span class="glyphicon glyphicon-plus"></span>
					</button>
					<div onclick="location.href='product.php?item=<?=$product['product_id'];?>'">
						<?php $photos = explode(',',$product['image']);?>
						<img src="<?=$photos[0]; ?>" name="<?php echo $product['title']; ?>" alt="<?php echo $product['title']; ?>"
						class="img-show"/>
						<h2 class="h3-list"><?php echo $product['title']; ?></h2>
						<h3 class="h3-list text-success"><?=money($price);?></h3>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
  
  <div style="text-align:center">
	  <ul class="pagination">
		<li class="<?php if($total_pages == 1 || $pageno == 1){ echo 'disabled'; } ?>"><a href="?pageno=1">First</a></li>
		<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
			<a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
		</li>
		
		<!--Previous Pages-->
		<li class="<?php if($pageno -3 <= 0) { echo 'disabled hide'; } ?>">
			<a href="<?php if($pageno <= 0){ echo '#'; } else { echo "?pageno=".($pageno - 3); } ?>"><?=$pageno - 3;?></a>
		</li>
		<li class="<?php if($pageno -2 <= 0) { echo 'disabled hide'; } ?>">
			<a href="<?php if($pageno <= 0){ echo '#'; } else { echo "?pageno=".($pageno - 2); } ?>"><?=$pageno - 2;?></a>
		</li>
		<li class="<?php if($pageno -1 <= 0) { echo 'disabled hide'; } ?>">
			<a href="<?php if($pageno <= 0){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>"><?=$pageno - 1;?></a>
		</li>
		
		
		<!--Current Page-->
		<li class="active">
			<a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno); } ?>"><?=$pageno;?></a>
		</li>
		
		
		<!--Next Pages-->
		<li class="<?php if($pageno +1 > $total_pages){ echo 'disabled hide'; } ?>">
			<a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>"><?=$pageno +1;?></a>
		</li>
		<li class="<?php if($pageno +2 >= $total_pages){ echo 'disabled hide'; } ?>">
			<a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 2); } ?>"><?=$pageno +2;?></a>
		</li>
		<li class="<?php if($pageno +3 >= $total_pages){ echo 'disabled hide'; } ?>">
			<a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 3); } ?>"><?=$pageno +3;?></a>
		</li>
		
		<li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
			<a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
		</li>
		<li class="<?php if($total_pages == 1 || $pageno == $total_pages){ echo 'disabled'; } ?>"><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
	</ul>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/pro.php'; ?>