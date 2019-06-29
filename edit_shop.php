<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

$shop_id = sanitize((int)$_GET['shop']);
$query = $conn->query("SELECT * FROM shop WHERE shop_id = '$shop_id'");
$shop = mysqli_fetch_assoc($query);
$name = sanitize($shop['name']);
$street = sanitize($shop['street']);
$street2 = sanitize($shop['street2']);
$city = sanitize($shop['city']);
$region = sanitize($shop['region']);
$archived = sanitize($shop['archived']);
$errors = array();

$squery = $conn->query("SELECT * FROM sellers WHERE shop_id = '$shop_id'");

if($_POST){
	$new_name = ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']):$name);
	$new_street = ((isset($_POST['street']) && $_POST['street'] != '')?sanitize($_POST['street']):$street);
	$new_street2 = ((isset($_POST['street2']) && $_POST['street2'] != '')?sanitize($_POST['street2']):'');
	$new_city = ((isset($_POST['city']) && $_POST['city'] != '')?sanitize($_POST['city']):$city);
	$new_region = ((isset($_POST['region']) && $_POST['region'] != '')?sanitize($_POST['region']):$region);
	
	$required = array(
		'name' 			=> 'Shop name',
		'city' 			=> 'City',
		'region' 		=> 'Region',
		'street'		=> 'Street',
		'city' 			=> 'City',
	);
	
	//check all required fields are filled
	foreach($required as $f => $d) {
		if(empty($_POST[$f]) || $_POST[$f] == '') {
			$errors[] = $d.' is required.';
		}
	}
	
	$shopQuery = $conn->query("SELECT name FROM shop WHERE name = '$new_name' AND shop_id != '$shop_id'");
	$shopCount = mysqli_num_rows($shopQuery);
	if($shopCount != 0){
		$errors[] = 'That shop name already exists in our database.';
	}
		
	if(!empty($errors)) {
		echo display_errors($errors);
	}else{
		//edit account
		$shopEQ = "UPDATE shop SET name = '$new_name', street = '$new_street', street2 = '$new_street2', city = '$new_city', 
		region = '$new_region' WHERE shop_id = '$shop_id'";
		$conn->query($shopEQ);
		$_SESSION['success_flash'] = 'Account successfully updated';
		header('Location: sellers.php');
	}
}
?>

	<h2 class="text-center">Edit <?=$name;?></h2><div class="clearfix"></div>
	<div class="clearfix"></div>
	<hr>
	<form action="" method="post">
		<div class="form-group col-sm-6 col-xs-12">
			<label for="name">Name*:</label>
			<input type="text" id="name" name="name" class="form-control" value="<?=$name;?>"></input>
		</div>
		<div class="form-group col-sm-6 col-xs-12">
			<label for="city">City*:</label>
			<input type="text" id="city" name="city" class="form-control" value="<?=$city;?>"></input>
		</div>
		<div class="form-group col-sm-6 col-xs-12">
			<label for="region">Region*:</label>
			<input type="text" id="region" name="region" class="form-control" value="<?=$region;?>"></input>
		</div>
		<div class="form-group col-sm-6 col-xs-12">
			<label for="street">Street*:</label>
			<input type="text" id="street" name="street" class="form-control" value="<?=$street;?>"></input>
		</div>
		<div class="form-group col-sm-6 col-xs-12">
			<label for="street2">Street2*:</label>
			<input type="text" id="street2" name="street2" class="form-control" value="<?=$street2;?>"></input>
		</div>
		<div class="form-group col-sm-6 col-xs-12 text-right" style="margin-top:25px;">
			<a href="/retlug/admin/sellers.php" class="btn btn-default">Cancel</a>
			<input type="submit" name="submit" value="Update" class="btn btn-primary">
		</div>
	</form>
	
	<h2 class="text-center" style="padding-down:10px;">Shop Accounts</h2><div class="clearfix"></div>
	<hr>
	<div class="col-md-12">
		<table class="table table-bordered table-condensed table-stripped"> 
			<thead><th></th> <th>Name</th> <th>Email</th> <th>Phone No</th> <th>Permissions</th> <th>Last Login</th></thead>
			<tbody>
			<?php while($sellers = mysqli_fetch_assoc($squery)): //var_dump($sellers);
					$name = $sellers['full_name'];
					$email = $sellers['email'];
					$phone = $sellers['phoneNo'];
					$permissions = $sellers['permissions'];
					$last = $sellers['last_login'];
			?>
			  <tr>
				<td>
					<a href="/retlug/admin/edit_shop.php?shop=<?=$shop_id;?>" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				</td>
				<td><?=$name;?></td>
				<td><?=$email;?></td>
				<td><?=$phone;?></td>
				<td><?=$permissions;?></td>
				<td><?=$last;?></td>
			  </tr>
			<?php endwhile; ?>
		  </tbody>
		</table>
	</div>
	

<?php
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php';
?>