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
$permissions = 'admin,editor';
$date = date("Y-m-d H:i:s");

if(isset($_POST['submit'])){
	$emailQuery = $conn->query("SELECT * FROM users WHERE email = '$email' AND live = '1' AND guest = '0'");
	$emailCount = mysqli_num_rows($emailQuery);
	if($emailCount != 0){
		$errors[] = 'That email already exists in our database.';
	}

	$required = array('full_name','email','phoneNo','password','confirm','street','city','region',);
	foreach ($required as $f) {
	if(empty($_POST[$f])){
		$errors[] = 'All fields with an * are required.';
		break;
		}
	}

	if(strlen($password) < 6 && $password != ''){
		$errors[]= 'Password must be at least 6 characters.';
	}

	if($password != $confirm){
		$errors[]= 'Your passwords do not match.';
	}

	if(strlen($phoneNo) != 10 && $phoneNo != ''){
		$errors[]= 'Invalid phone number.';
	}

	if((!filter_var($email,FILTER_VALIDATE_EMAIL)) && $email != ''){
		$errors[]= 'You must enter a valid email address.';
	}

	if(!empty($errors)){
		echo display_errors($errors);
	}else{
		$sql = "INSERT INTO shop (`name`,`join_date`,`street`,`street2`,`city`,`region`) 
				VALUES ('$shop_name','$date','$street','$street2','$city','$region')";
		$conn->query($sql);
		//echo("Error description: " . mysqli_error($conn));
		$insert = mysqli_insert_id($conn);
		$shop_id = $insert;
		$hashed = password_hash($password,PASSWORD_DEFAULT);
		$sql2 = "INSERT INTO sellers (`shop_id`,`full_name`,`email`,`phoneNo`,`password`,`permissions`,`join_date`) 
				VALUES ('$shop_id','$full_name','$email','$phoneNo','$hashed','$permissions','$date')";
		$conn->query($sql2);	
		//echo("Error description: " . mysqli_error($conn));
		$_SESSION['success_flash'] = 'Account has been created';
		header('Location: /retlug/admin/sellers.php');
	}
}

?>
<h3 class="text-center"><?=$shop_name;?> Store</h3>
<hr>
<table class="table table-bordered table-condensed table-stripped">
	<thead><th>Shop Name</th> <th>City</th> <th>Street</th> <th>Street 2</th></thead>
	<tbody>
		<tr>
			<td><?=$shop_name;?></td>
			<td><?=$city.', '.$region;?></td>
			<td><?=$street;?></td>
			<td><?=$street2;?></td>
		</tr>
	</tbody>
</table>
<br>
<h3 class="text-left">Admin Account</h3><hr>
<table class="table table-bordered table-condensed table-stripped">
	<thead><th>Full Name</th> <th>Email</th> <th>Phone No</th></thead>
	<tbody>
		<tr>
			<td><?=$full_name;?></td>
			<td><?=$email;?></td>
			<td><?=$phoneNo;?></td>
		</tr>
	</tbody>
</table>
<div class="pull-right">
	<a href="/retlug/admin/add_shop.php" class="btn btn-default btn-large">Back</a>
	<button type="submit" name="submit" id="submit" class="btn btn-primary btn-large">Create</button>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php';?>