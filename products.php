<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
$dbPath = '';
$photoCount = '';
//archive product
if(isset($_GET['delete'])){
  $delete_id = sanitize((int)$_GET['delete']);
  $conn->query("UPDATE products SET archived = 1, featured = 0  WHERE product_id = '$delete_id'");
  header('Location: products.php');
}
if (isset($_GET['add']) || isset($_GET['edit'])) {
$brandQuery = $conn->query("SELECT * FROM brand ORDER BY brand");
$storeQuery = $conn->query("SELECT * FROM shop ORDER BY name");
$parentQuery = $conn->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
$store = ((isset($_POST['store']) && !empty($_POST['store']))?sanitize($_POST['store']):'');
$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):'');
$specs = ((isset($_POST['specs']) && !empty($_POST['specs']))?sanitize($_POST['specs']):'');
$colors = ((isset($_POST['colors']) && $_POST['colors'] != '')?sanitize($_POST['colors']):'');
$colors = rtrim($colors, ',');
$saved_image = '';
	if(isset($_GET['edit'])){
	$edit_id = (int)$_GET['edit'];
	$productresults = $conn->query("SELECT * FROM products WHERE product_id = '$edit_id'");
	$product = mysqli_fetch_assoc($productresults);
	$category = ((isset($_POST['category']) && $_POST['category'] != '')?sanitize($_POST['category']):$product['category_id']);
	$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
	$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand_id']);
	$store = ((isset($_POST['store']) && $_POST['store'] != '')?sanitize($_POST['store']):$product['shop_id']);
	$parentQ = $conn->query("SELECT * FROM categories WHERE category_id = '$category'");
	$parentResult = mysqli_fetch_assoc($parentQ);
	$parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
	$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
	$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
	$specs = ((isset($_POST['specs']) && $_POST['specs'] != '')?sanitize($_POST['specs']):$product['specs']);
	$colors = ((isset($_POST['colors']) && $_POST['colors'] != '')?sanitize($_POST['colors']):$product['color']);
	$colors = rtrim($colors, ',');
	//$saved_image = (($product['image'] != '')?$product['image']:'');
	$saved_image = $product['image'];
	$dbpath = $saved_image;
		if(isset($_GET['delete_image'])){
			$imgi = sanitize((int)$_GET['imgi']) - 1;
			$images = explode(',',$product['image']);
			$image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
			unlink($image_url);
			unset($images[$imgi]);
			$imageString = implode(',',$images);
			$conn->query("UPDATE products SET image = '{$imageString}' WHERE product_id = '$edit_id'");
			header('Location: products.php?edit='.$edit_id);
		} //end of if delete image
	}//end of edit
if(!empty($colors)){
	$colorString = sanitize($colors);
	$colorString = rtrim($colorString, ',');
	$colorsArray = explode(',', $colorString);
	$cArray = array();
	$qArray = array();
	$tArray = array();
	foreach($colorsArray as $cc){
		$c = explode(':', $cc);
		$cArray[] = $c[0];
		$qArray[] = $c[1];
		$tArray[] = $c[2];
	}
}//end of if !empty colors	
else{$colorsArray = array();}
if($_POST){
	$errors = array();
	$required = array('title','brand','store','price','parent','child','colors');
	$allowed = array('png','jpg','jpeg');
	$tmpLoc = array();
	$uploadPath = array();
	foreach($required as $field){
		if($_POST[$field] == ''){
			$errors[] = 'All fields with an * are required';
			break;
		}
	}//end of foreach
	
	$photoCount = count($_FILES['photo']['name']);
	//var_dump($_FILES);
	//var_dump($photoCount);
	if($photoCount > 6 && $_FILES['photo']['name'] == ''){
		$errors[] = 'Maximum of six photos.';
	}
	
	if($_FILES['photo']['size'] != 0 && $_FILES['photo']['error'] != 0 && $photoCount <= 6){
		for($i = 0; $i < $photoCount; $i++){
			$name = $_FILES['photo']['name'][$i];
			$nameArray = explode('.',$name);
			$fileName = $nameArray[0];
			$fileExt = $nameArray[1];
			$mime = explode('/',$_FILES['photo']['type'][$i]);
			$mimeType = $mime[0];
			$mimeExt = $mime[1];
			$tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
			$fileSize = $_FILES['photo']['size'][$i];
			
			$uploadName = md5(microtime()).'.'.$fileExt;
			$uploadPath[] = BASEURL.'images/product/'.$uploadName;
			if($i != 0){
				$dbPath .= ',';
			}
			$dbPath .= '/retlug/images/product/'.$uploadName;
			if($mimeType != 'image'){
				$errors[] = 'The File must be an image.';
			}
			if (!in_array($fileExt, $allowed)) {
				$errors[] = 'The file extension must be a png, jpg or jpeg.';
			}
			if($fileSize > 2000000) {
				$errors[] = 'The file size must be under 2MB.';
			}
			if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
				$errors[] = 'The file extension does not match the file.';
			}
		}
    } //end fo !empty $_FILE images[]
	
	if(!empty($errors)) {
      echo display_errors($errors);
    }
	else{
		if($_FILES['photo']['size'] != 0 && $_FILES['photo']['error'] != 0 && $photoCount <= 6){
			for($i = 0; $i < $photoCount; $i++){
				move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
			}
		} 

		if(isset($_GET['add'])){
			$insertSql = "INSERT INTO products (`title`,`price`,`brand_id`,`shop_id`,`category_id`,`color`,`description`,`specs`,`image`)
			VALUES ('$title','$price','$brand','$store','$category','$colors','$description','$specs','$dbPath')";
		}
		
		if(isset($_GET['edit'])){
			
			//$insertSql = "UPDATE products SET title = '$title', price = '$price',brand_id = '$brand',shop_id = '$store'
			//category_id = '$category', color = '$colors', description = '$description', specs = '$specs', image = '$dbPath' WHERE product_id = '$edit_id'";
			$insertSql = "UPDATE products SET title = '$title', price = '$price',brand_id = '$brand', 
			category_id = '$category', color = '$colors', description = '$description', specs = '$specs', image = '$dbPath' WHERE product_id = '$edit_id'";
		}
		$conn->query($insertSql);
		//echo("Error description: " . mysqli_error($conn));
		header('Location: products.php');
	} //end of else
}//end of if POST
?>

<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a New');?> Product</h2>
<hr>
<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
  <div class="form-group col-md-3">
    <label for="title">Title*:</label>
    <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
  </div>
	<div class="form-group col-md-3">
		<label for="store">Store*:</label>
		<select  class="form-control" id="store" name="store">
			<option value=""<?=(($store == '')?' selected':'');?>></option>
			<?php while($s = mysqli_fetch_assoc($storeQuery)): ?>
			<option value="<?=$s['shop_id'];?>"<?=(($store == $s['shop_id'])?' selected':'')?>><?=$s['name'];?></option>
			<?php endwhile; ?>
		</select>
	</div>
  <div class="form-group col-md-3">
    <label for="brand">Brand*:</label>
    <select  class="form-control" id="brand" name="brand">
		<option value=""<?=(($brand == '')?' selected':'');?>></option>
		<?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
			<option value="<?=$b['brand_id'];?>"<?=(($brand == $b['brand_id'])?' selected':'')?>><?=$b['brand'];?></option>
		<?php endwhile; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label for="parent">Parent Category*:</label>
    <select  class="form-control" id="parent" name="parent">
		<option value=""<?=(($parent == '')?' selected':'');?>></option>
		<?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
			<option value="<?=$p['category_id'];?>"<?=(($parent == $p['category_id'] )?' selected':'')?>><?=$p['category'];?></option>
		<?php endwhile; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
	<label for="child">Child Category*:</label>
	<select  class="form-control" id="child" name="child"></select>
  </div>
  <div class="form-group col-md-3">
    <label for="price">Price*:</label>
    <input type="number" id="price" min="0" name="price" class="form-control" value="<?=$price;?>"></input>
  </div>
  <div class="form-group col-md-3">
    <label for="color">Colors & Quantity*:</label>
    <button class="btn btn-default form-control" onclick="jQuery('#colorsModal').modal('toggle');return false;">Colors</button>
  </div>
  <div class="form-group col-md-3">
    <label for="quantity">Colors & Qty Preview:</label>
    <input type="text" name="colors" id="colors" class="form-control" value="<?=$colors;?>" readonly>
  </div>
  
  <div class="form-group col-md-6">
  <h5>Images*:</h5><hr>
	<?php if($saved_image != ''): ?>
		<?php 
			$imgi = 1;
			$images = explode(',',$saved_image);?>
		<?php foreach($images as $image): ?>
	  <div class="saved-image col-md-4">
		<img src="<?=$image?>" alt="saved image"/><br>
		<a href="products.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete Image</a>
	  </div>
	  <?php 
		$imgi++;
		endforeach; 
		?>
	<?php endif ?>
	<?php if($saved_image == ''): ?>
		<div class="clearfix"></div>
		<label for="photo"></label>
		<input type="file" id="photo" name="photo[]" class="form-control" multiple></input>
	<?php endif ?>
  </div>
  
  <div class="clearfix"></div>
  <div class="row">
	  <div class="form-group col-md-6">
		 <h5>Description*:<h5><hr>
		<textarea id="description" name="description" class="form-control" rows="12"><?=$description?></textarea>
	  </div>
	  
	  <div class="form-group col-md-6">
		 <h5>Specifications*:<h5><hr>
		<textarea id="specs" name="specs" class="form-control" rows="12"><?=$specs?></textarea>
	  </div>
  </div>
  <div class="form-group pull-right">
    <a href="products.php" class="btn btn-default">Cancel</a>
    <input type="submit" name="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class="btn btn-success" >
  </div><div class="clearfix"></div>
</form>

<!--modal form-->
<div class="modal fade" id="colorsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Colors & Quantity</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
		<?php for($i=1; $i <= 12; $i++): ?>
          <div class="form-group col-md-2">
            <label for="color<?=$i;?>">Color:</label>
            <input type="text" id="color<?=$i;?>" name="color<?=$i;?>" class="form-control" value="<?=((!empty($cArray[$i-1]))?$cArray[$i-1]:'');?>"></input>
          </div>
		  <div class="form-group col-md-2">
            <label for="qty<?=$i;?>">Quantity:</label>
            <input type="number" id="qty<?=$i;?>" min="0" name="qty<?=$i;?>" class="form-control" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>"></input>
          </div>
		   <div class="form-group col-md-2">
            <label for="threshold<?=$i;?>">Threshold:</label>
            <input type="number" id="threshold<?=$i;?>" min="0" name="threshold<?=$i;?>" class="form-control" value="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:'');?>"></input>
          </div>
		  <?php endfor; ?> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateColors();jQuery('#colorsModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
}else{ //end of add and delete
$sql = "SELECT * FROM products WHERE archived = 0 ORDER BY title";
$presults = $conn->query($sql);
if (isset($_GET['featured'])) {
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];
  $featuredsql = "UPDATE products SET featured = '$featured' WHERE product_id = '$id'";
  $conn->query($featuredsql);
  header('Location: products.php');
}
?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-stripped">
  <thead>
		<th></th> <th>Products</th> <th>Store</th> <th>Price</th> <th>Categories</th> <th>Featured</th> <th>Sold</th>
  </thead>
  <tbody>
    <?php while($product = mysqli_fetch_assoc($presults)):
          $childID = $product['category_id'];
		  $shop_id = $product['shop_id'];
          $catSql = "SELECT * FROM categories WHERE category_id = '$childID'";
          $result = $conn->query($catSql);
          $child = mysqli_fetch_assoc($result);
          $parentID = $child['parent'];
          $pSql = "SELECT * FROM categories WHERE category_id = '$parentID'";
          $presult = $conn->query($pSql);
          $parent = mysqli_fetch_assoc($presult);
          $category = $parent['category'].'~'.$child['category'];
		  $storeQ = $conn->query("SELECT * FROM shop WHERE shop_id = '$shop_id'");
		  $store = mysqli_fetch_assoc($storeQ);
		  $store_name = $store['name'];
      ?>
      <tr>
        <td>
          <a href="products.php?edit=<?=$product['product_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$product['product_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
        <td><?=$product['title'];?></td>
		<td><?=$store_name;?></td>
        <td><?=money($product['price']);?></td>
        <td><?=$category;?></td>
        <td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['product_id'];?>" class="btn btn-xs btn-default">
            <span class="glyphicon glyphicon-<?=(($product['featured'] == 0)?'plus':'minus');?>"></span>
        </a>&nbsp <?=(($product['featured'] == 1)?'Featured Product':'');?></td>
        <td><?=$product['sold'];?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>


<?php } include 'includes/footer.php'; ?>
<script>
jQuery('document').ready(function(){
  get_child_options('<?=$category;?>')
});
</script>
