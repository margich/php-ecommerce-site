<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

$hashed = $admin_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$admin_id = $admin_data['admin_id'];
$errors = array();

//if submit form
  if($_POST){
	//form validation
	if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
	  $errors[] = 'You must fill out all fields.';
	}
	//check if password is more than 6 characters
	if(strlen($password) < 6){
	  $errors[] = 'Password must be at least 6 characters.';
	}

	//check if new password matches confirm
	if($password != $confirm){
	  $errors[] = 'The new password and confirm new password do not match';
	}

	if(!(password_verify($old_password, $hashed))){
	  $errors[] = 'Your old password does not match our records';
	}

	//check for errors
	if(!empty($errors)){
	  echo display_errors($errors);
	}else {
	  //change password
	  $conn->query("UPDATE admin SET password = '$new_hashed' WHERE admin_id = '$admin_id'");
	  $_SESSION['success_flash'] = 'Your password has been successfuly updated';
	  header('Location: /retlug/admin/index.php');
	}
  }

    ?>

	<h2 class="text-center">Change password</h2><hr>
	<form action="" method="post">
		<div style="width:50%; margin:0 auto;">
			<div class="row">
				<div class="form-group col-sm-12 col-xs-12">
					<label for="old_password">Old Password*:</label>
					<input type="password" name="old_password" placeholder="enter old password" id="old_password" class="form-control" value="<?=$old_password;?>">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-12 col-xs-12">
					<label for="password">New Password*:</label>
					<input type="password" name="password" placeholder="enter new password" id="password" class="form-control" value="<?=$password;?>">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-12 col-xs-12">
					<label for="confirm">Confirm New Password*:</label>
					<input type="password" name="confirm" placeholder="confirm new password" id="confirm" class="form-control" value="<?=$confirm;?>">
				</div>
			</div>
			<div class="form-group col-md-6 text-right pull-right" style="margin-top:25px;">
				<a href="/retlug/admin/index.php" class="btn btn-default">Cancel</a>
				<input type="submit" class="btn btn-primary" value="Update">
			</div>
		</div>
	</form>

<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>

