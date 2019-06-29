<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

$edit_id = sanitize($admin_data['admin_id']);
$query = $conn->query("SELECT * FROM admin WHERE admin_id = '$edit_id'");
$result = mysqli_fetch_assoc($query);
$full_name =  sanitize($result['full_name']);
$email =  sanitize($result['email']);
$phoneNo =  sanitize($result['phoneNo']);



$errors = array();

if($_POST){
	$new_full_name =  sanitize($_POST['full_name']);
	$new_email =  sanitize($_POST['email']);
	$new_phoneNo =  sanitize($_POST['phoneNo']);
	$emailQuery = $conn->query("SELECT * FROM admin WHERE email = '$email' AND admin_id != '$edit_id'");
	$emailCount = mysqli_num_rows($emailQuery);
	if($emailCount != 0){
		$errors[] = 'That email already exists in our database.';
	}

	$required = array('full_name','email','phoneNo');
	foreach ($required as $f) {
		if(empty($_POST[$f])){
			$errors[] = 'All fields with an * are required.';
			break;
		}
	}

	if(!empty($email)){
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$errors[]= 'You must enter a valid email address.';
		}
	}



	if(!empty($errors)){
		echo display_errors($errors);
	}else{
		//update user account
		$sql = "UPDATE admin SET full_name = '$new_full_name', email = '$new_email', phoneNo = '$new_phoneNo' WHERE user_id = '$edit_id'";	
		$conn->query($sql);
		echo("Error description: " . mysqli_error($conn));
		$_SESSION['success_flash'] = 'Account has been updated';
		header('Location: /retlug/user/user/edit_account.php');
		
	}
}


?>

	<h2 class="text-center">Edit account</h2>
	<hr>
	<form action="edit_account.php?edit=<?=$edit_id;?>" method="post">
		<div style="width:50%; margin:0 auto;">
			<div class="row">
				<div class="form-group col-sm-12 col-xs-12">
					<label for="full_name">Full Name*:</label>
					<input type="text" id="full_name" name="full_name" class="form-control" value="<?=$full_name;?>"></input>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-12 col-xs-12">
					<label for="email">Email*:</label>
					<input type="email" id="email" name="email" class="form-control" value="<?=$email;?>"></input>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-12 col-xs-12">
					<label for="phoneNo">Phone No*:</label>
					<input type="text" id="phoneNo" name="phoneNo" class="form-control" value="<?=$phoneNo;?>"></input>
				</div>
			</div>
			<div class="form-group col-md-6 text-right pull-right" style="margin-top:25px;">
				<a href="/retlug/admin/index.php" class="btn btn-default">Cancel</a>
				<input type="submit" value="Update" class="btn btn-primary">
			</div>
		</div>
	</form>




<?php
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php';
?>