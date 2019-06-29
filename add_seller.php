<?php require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

	$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
	$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
	$phoneNo = ((isset($_POST['phone']))?sanitize($_POST['phone']):'');
	$phoneNo = ((isset($_POST['phoneNo']))?sanitize($_POST['phoneNo']):'');
	$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
	$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
	$permissions = 'admin,editor';
	$errors = array();
	$required = array(
		'full_name' 	=> 'Full Name',
		'email' 		=> 'Email',
		'phoneNo' 		=> 'Phone No',
		'password' 		=> 'Password',
		'confirm' 		=> 'Confirm',
	);

	//check all required fields are filled
	foreach($required as $f => $d) {
		if(empty($_POST[$f]) || $_POST[$f] == '') {
			$errors[] = $d.' is required.';
		}
	}
	
	$emailQuery = $conn->query("SELECT * FROM sellers WHERE email = '$email'");
	$emailCount = mysqli_num_rows($emailQuery);
	if($emailCount != 0){
		$errors[] = 'That email already exists in our database.';
	}
	
	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$errors[]= 'You must enter a valid email address.';
	}
	
	//check if password is more than 6 characters
	if(strlen($password) < 6){
		$errors[] = 'Password must be at least 6 characters.';
	}

	//check if new password matches confirm
	if($password != $confirm){
		$errors[] = 'Your password and confirm password do not match';
	}
	
	
	if(!empty($errors)) {
		echo display_errors($errors);
	}
	/*else{
	//add seller to database
	$hashed = password_hash($password,PASSWORD_DEFAULT);
	$ssql = "INSERT INTO sellers (`shop_id,`full_name`,`email`,`password`,`phoneNo`,`permissions`) 
						VALUES ('$shop_id','$full_name','$email','$hashed','$phoneNo','$permissions')";
	$conn->query($ssql);
	//$_SESSION['success_flash'] = 'Shop account has been created';
	//header('Location: /retlug/admin/sellers.php');
	}*/
?>