<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
$id = sanitize($_POST['id']);
$id = (int)$id;
$sql = "SELECT * FROM products WHERE product_id = '$id'";
$result = $conn->query($sql);
$product = mysqli_fetch_assoc($result);
$shop_id = $product['shop_id'];

$brand_id = $product['brand_id'];
$sql = "SELECT brand FROM brand WHERE brand_id = '$brand_id'";

$brand_query = $conn->query($sql);
$brand = mysqli_fetch_assoc($brand_query);

$colorstring = $product['color'];
$colorstring = rtrim($colorstring, ',');
$color_array = explode(',', $colorstring);
?>

<!--Details Modal -->
<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" onclick="closeModal()" aria-label="close">
				<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center"><?= $product['title'];?></h4>
			</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<span id="modal_errors" class="bg-danger"></span>
					<div class="col-sm-6 fotorama">
						<?php $photos = explode(',',$product['image']);
						foreach($photos as $photo) : ?>
							<img src="<?= $photo; ?>" alt="<?= $product['title']; ?>" class="img-details img-responsive">
						<?php endforeach; ?>
					</div>
				<div class="col-sm-6">
					<h4>Description</h4>
					<p><?= nl2br($product['description']); ?></p>
					<hr>
					<p class="text-success">Price: <?=money($product['price']);?></p>
					<p>Brand: <?= $brand['brand']; ?></p>
					<form action="add_cart.php" method="post" id="add_product_form">
						<input type="hidden" name="product_id" value="<?=$id;?>">
						<input type="hidden" name="shop_id" value="<?=$shop_id;?>">
						<input type="hidden" name="available" id="available" value="">
						<div class="form-group">
							<div class="col-xs-6 col-md-3">
								<label for="quantity">Quantity:</label>
								<input type="number" class="form-control" id="quantity" min="1" max="100" value="1" name="quantity">
							</div>
						</div>
						<div class="form-group">
						<div class="col-xs-6 col-md-6">						
							<label for="color">Color:</label>
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
					</form>
				</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		<button class="btn btn-default" onclick="closeModal()">Close</button>
		<button class="btn btn-warning" onclick="add_to_cart();return false">
		<span class="glyphicon glyphicon-shopping-cart"></span>Add to Cart</button>
		</div>
		</div>
	</div>
</div>
<script>
jQuery('#color').change(function(){
	var available = jQuery('#color option:selected').data("available");
	jQuery('#available').val(available);
})

$(function (){
	$('.fotorama').fotorama({'loop':true,'autoplay':true});
});

function closeModal(){
	jQuery('#details-modal').modal('hide');
	setTimeout(function() {
		jQuery('#details-modal').remove();
		//jQuery('.modal-backdrop').remove();
	},300);
}
</script>
<?php echo ob_get_clean(); ?>
