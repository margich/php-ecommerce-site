<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/title/title-product.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head-min.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/mobile.php';

$colorstring = $product['color'];
$colorstring = rtrim($colorstring, ',');
$color_array = explode(',', $colorstring);

?>


<!-- main side bar -->
<div class="container" style="padding:20px;">
	<div class="row" itemscope itemtype="http://schema.org/Product">
		<span id="product_errors"></span>
		<div class="col-md-12">
			<h1 itemprop="name"><?=$title;?></h1>
            <ul class="breadcrumb" itemprop="category">
                <li><a href="/">Home</a></li>
                <li>
                    <a href="/retlug/category.php?category=<?=$category['parent'];?>">
                        <?=$category['parent'];?>
                    </a>
                </li>
                <li>
                    <?=$category['child'];?>
                </li>
            </ul>
			<div class="col-md-6 fotorama" data-nav="thumbs"  data-allowfullscreen="native" data-width="80%">
				<?php $photos = explode(',',$product['image']);
				foreach($photos as $photo) : ?>
				<img itemprop="image" src="<?=$photo;?>" name="<?=$title;?>"alt="<?=$title;?>" class="img-details img-responsive">
				<?php endforeach; ?>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-4">
				<div class="row">
					<form class="col-md-12" action="/retlug/admin/parsers/add_cart.php" method="post" id="add_product_form">
						<input type="hidden" name="product_id" id="item_id" value="<?=$item_id;?>">
						<input type="hidden" name="product_name" id="item_name" value="<?=$title;?>">
						<input type="hidden" name="available" id="available" value="">
						<div class="col-xs-12" itemscope itemtype="http://schema.org/Offer">
						<div class="form-group col-xs-12 col-sm">
							<h3 class="text-success h2-list" style="display:inline;" itemprop="priceCurrency" content="KES">Ksh</h3>
							<h3 class="text-success h2-list" itemprop="price" style="display:inline;"><?=number_format($price, 2);?></h3>
							<link itemprop="availability" href="http://schema.org/InStock" /><span>In stock
							<h1 class="h2-list text-left">Brand: <?=$brand;?></h1>
						</div>
						<div class="form-group col-xs-12 col-sm">
							<label for="quantity">Quantity: <span id="quantity_errors" class="bg-danger"></span></label>
							<input type="number" name="quantity" id="quantity" min="1" max="100" class="form-control" value="1">
						</div>
						<div class="form-group col-xs-12 col-sm">
							<label for="colors">Colors:</label><span id="color_errors" class="bg-danger"></span>
							<select name="color" id="color" class="form-control">
								<option value=""></option>
								<?php 
									foreach($color_array as $string){
									$string_array = explode(':', $string);
									$color = $string_array[0];
									$available = $string_array[1];
									echo '<option value="'.$color.'" data-available="'.$available.'">'.$color.' ('.$available.' Available)</option>';}
								?>
							</select>
						</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-12">
							<div class="form-group form-inline col" style="padding:4px;">
								<button class="btn btn-lg btn-warning col-12 col-xs-12 col-sm col-md" onclick="add_to_cart();return false"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</button>
							</div><div class="clearfix"></div>
							<div class="form-group form-inline col" style="padding:4px;">
								<?php if(is_logged_in_user()): ?>	
								<button class="btn btn-lg btn-primary col-12 col-xs-12 col-sm col-md" onclick="add_to_wishlist();return false"><span class="glyphicon glyphicon-star"></span> Wishlist</button>
								<?php endif; ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row" style="margin-top:15px;">
			<div <?=($description == '')?' class="col-md-12"':'class="col-md-6"';?>>
				<h3>Specifications</h3><hr>
				<h3 class="h3-list" itemprop="description"><?=nl2br($specs);?></h3>
			</div>
			<div <?=($description == '')?' style="display:none"':'class="col-md-6"';?>>
				<h3>Description</h3><hr>
				<h3 class="h3-list"><?=nl2br($description);?></h3>
			</div>
		</div>
		
	</div>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/pro.php'; ?>