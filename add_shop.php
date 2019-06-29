<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}

if(!has_permission('admin')){
  permission_error_redirect('/retlug/admin/index.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

$shop_name = ((isset($_POST['shop_name']))?sanitize($_POST['shop_name']):''); 
$street = ((isset($_POST['street']))?sanitize($_POST['street']):'');
$street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
$city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
$region = ((isset($_POST['region']))?sanitize($_POST['region']):'');
$logo = ((isset($_POST['logo']))?sanitize($_POST['logo']):'');

$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$phoneNo = ((isset($_POST['phoneNo']))?sanitize($_POST['phoneNo']):'');
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$permissions= 'admin,editor';
$errors = array();
/*
if($_POST){
	$required = array('shop_name','street','city','region');
	
	foreach ($required as $f) {
		if(empty($_POST[$f])){
			$errors[] = 'All fields with an * are required.';
			break;
		}
	}
	
	$shopQ = $conn->query("SELECT shop_id FROM shop WHERE name = '$shop_name'");
	$shopCount = mysqli_num_rows($shopQ);
	if($shopCount > 0){
		$errors[] = 'That shop name already exists in our database.';
	}
	
	$shopQ = $conn->query("SELECT COUNT(shop_id) AS shop FROM shop WHERE name = '$shop_name'");
	$shopCount = mysqli_fetch_assoc($shopQ);
	if($shopCount['shop'] > 0){
		$errors[] = 'That shop name already exists in our database.';
	}
	
	
	if(!empty($errors)) {
		echo display_errors($errors);
	}else{
		$sql = "INSERT INTO shop (`name`,`street`,`street2`,`city`,`region`) 
		VALUES ('$shop_name','$street','$street2','$city','$region')";
		$conn->query($sql);
		echo("Error description: " . mysqli_error($conn));
		$_SESSION['success_flash'] = 'Shop profile has been created';
		header('Location: /retlug/admin/sellers.php');
	}
}*/
?>

<form action="/retlug/admin/account.php" method="post">
	<div id="step1" style="display:block;">
		<h2 class="text-center">Create shop profile</h2>
		<span id="sh_errors"></span>
		<hr>
		<div class="form-group col-md-6 col-xs-12">
			<label for="shop_name">Shop Name*:</label>
			<input type="text" id="shop_name" name="shop_name" class="form-control" value="<?=$shop_name;?>"></input>
		</div>
		<div class="form-group col-md-6 col-xs-12">
			<label for="city">City*:</label>
			<input type="text" id="city" name="city" class="form-control" value="<?=$city;?>"></input>
		</div>
		<div class="form-group col-md-6 col-xs-12">
			<label for="region">Region*:</label>
			<input type="text" id="region" name="region" class="form-control" value="<?=$region;?>"></input>
		</div>
		<div class="form-group col-md-6 col-xs-12">
			<label for="street">Street*:</label>
			<input type="text" id="street" name="street" class="form-control" value="<?=$street;?>"></input>
		</div>
		<div class="form-group col-md-6 col-xs-12">
			<label for="street2">Street 2:</label>
			<input type="text" id="street2" name="street2" class="form-control" value="<?=$street2;?>"></input>
		</div>
		<div class="form-group col-md-6 col-xs-12">
			<div class="clearfix"></div>
			<label for="logo">Logo:</label>
			<input type="file" id="logo" name="logo" class="form-control"></input>
		</div>
		<div class="form-group col-md-6 col-xs-12 text-right pull-right" style="margin-top:25px;">
			<input type="button" id="next" name="next" onclick="next_step();" value="Next>>" class="btn btn-success">
			<a href="/retlug/admin/sellers.php" id="cancel" class="btn btn-default">Cancel</a>
		</div>
	</div>
	<div id="step2" style="display:none">
		<h2 class="text-center">Create admin account</h2>
		<span id="s_errors"></span>
		<hr>
		<div class="form-group col-md-6">
			<label for="full_name">Full Name*:</label>
			<input type="text" id="full_name" name="full_name" class="form-control" value="<?=$full_name;?>"></input>
		</div>
		<div class="form-group col-md-6">
			<label for="email">Email*:</label>
			<input type="email" id="email" name="email" class="form-control" value="<?=$email;?>"></input>
		</div>
		<div class="form-group col-md-6">
			<label for="phoneNo">Phone No*:</label>
			<input type="text" id="phoneNo" name="phoneNo" class="form-control" value="<?=$phoneNo;?>"></input>
		</div>
		<div class="form-group col-md-6">
			<label for="password">Password*:</label>
			<input type="password" id="password" name="password" class="form-control" value="<?=$password;?>"></input>
		</div>
		<div class="form-group col-md-6">
			<label for="confirm">Confirm password*:</label>
			<input type="password" id="confirm" name="confirm" class="form-control" value="<?=$confirm;?>"></input>
		</div>
		<div class="form-group col-md-6">
			<label for="permissions">Permissions*:</label>
			<input type="text" id="permissions" name="permissions" class="form-control" value="<?=$permissions;?>" readonly></input>
		</div>
		<div class="form-group col-md-6 col-xs-12 text-right pull-right" style="margin-top:25px;">
			<input type="button" id="back" name="back" onclick="back_step();" value="<<Back" class="btn btn-primary">
			<input type="submit" id="submit" name="submit" onclick="submit_sh();" value="Submit" class="btn btn-success" >
			<a href="/retlug/admin/sellers.php" id="cancel" class="btn btn-default">Cancel</a>
		</div>
	</div>
</form>

<script>
function next_step(){
	var error = "";
	jQuery('#sh_errors').html("");
	var shop_name = jQuery('#shop_name').val();
	var street = jQuery('#street').val();
	var street2 = jQuery('#street2').val();
	var region = jQuery('#region').val();
	var sh_city = jQuery('#sh_city').val();
	if(shop_name == ''){
		error += '<p class="text-danger text-left">Shop name required</p>';
		jQuery('#sh_errors').html(error);
	}
	if(street == ''){
		error += '<p class="text-danger text-left">Street required</p>';
		jQuery('#sh_errors').html(error);
	}
	if(region == ''){
		error += '<p class="text-danger text-left">Region required</p>';
		jQuery('#sh_errors').html(error);
	}
	if(city == ''){
		error += '<p class="text-danger text-left">City required</p>';
		jQuery('#sh_errors').html(error);
	}
	
	var data = 	{
		'shop_name' : jQuery('#shop_name').val(),
		'street' : jQuery('#street').val(),
		'street2' : jQuery('#street2').val(),
		'region' : jQuery('#region').val(),
		'city' : jQuery('#city').val(),
		'logo' : jQuery('#logo').val(),
	};
	jQuery.ajax({
		url: '/retlug/admin/parsers/create_shop.php',
		method: "post",
		data: data,
		success : function(data){
			if(data != 'passed') {
				jQuery('#sh_errors').html(data);
			}
			if(data == 'passed'){
				jQuery('#sh_errors').html("");
				jQuery('#step1').css("display","none");
				jQuery('#step2').css("display","block");
			}
		},
		error : function(){alert("Something went wrong");},
	});
}

function back_step(){
	jQuery('#s_errors').html("");
	jQuery('#step1').css("display","block");
	jQuery('#step2').css("display","none");
}

function submit_sh(){
	var error = "";
	jQuery('#s_errors').html("");
	var full_name = jQuery('#full_name').val();
	var email = jQuery('#email').val();
	var phoneNo = jQuery('#phoneNo').val();
	var password = jQuery('#password').val();
	var password2 = jQuery('#confirm').val();
	if(full_name == ''){
		error += '<p class="text-danger text-left">Full name required</p>';
		jQuery('#s_errors').html(error);
	}
	if(email == ''){
		error += '<p class="text-danger text-left">Email required</p>';
		jQuery('#s_errors').html(error);
	}
	if(phoneNo == ''){
		error += '<p class="text-danger text-left">Phone no required</p>';
		jQuery('#s_errors').html(error);
	}
	if(password == ''){
		error += '<p class="text-danger text-left">Password required</p>';
		jQuery('#s_errors').html(error);
	} 
	if(password2 == ''){
		error += '<p class="text-danger text-left">Confirm password required</p>';
		jQuery('#s_errors').html(error);
	} 
	
	var data = 	{
		'email' : jQuery('#email').val(),
		'full_name' : jQuery('#full_name').val(),
		'phoneNo' : jQuery('#phoneNo').val(),
		'password' : jQuery('#password').val(),
		'confirm' : jQuery('#confirm').val(),
	};
	jQuery.ajax({
		url: '/retlug/admin/parsers/add_seller.php',
		method: "post",
		data: data,
		success : function(data){
			if(data != 'passed') {
				jQuery('#s_errors').html(data);
			}
		},
		error : function(){alert("Something went wrong");},
	});
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>