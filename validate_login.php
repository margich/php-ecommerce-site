<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

	$email = sanitize($_POST['email']);
	$email = trim($email);
	$password = sanitize($_POST['password']);
	$password = trim($password);
	$errors = array();
	$required = array(
		'email' 	=> 'Email',
		'password' 	=> 'Password',
	);
	
	//check all required fields are filled
	foreach($required as $f => $d) {
		if(empty($_POST[$f]) || $_POST[$f] == '') {
			$errors[] = $d.' is required.';
		}
	}
	
	//check if valid email address
	if(!empty($_POST['email']) && $_POST['email'] != ''){
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$errors[] = 'Please enter a valid email address.';
		}
	}
	
	//check if user exists in database
	$query = $conn->query("SELECT * FROM users WHERE email = '$email' AND live = '1'");
	$user = mysqli_fetch_assoc($query);
	$userCount = mysqli_num_rows($query);
	if($userCount < 1){
	  $errors[] = 'That email does not exist in our database.';
	}

	if(!(password_verify($password,$user['password']))){
	  $errors[] = 'The password does not match that user name.';
	}

	//check for errors
	if(!empty($errors)){
		echo display_errors($errors);
		return false;
	}else {
		if(empty($errors)){
		$user_id = $user['user_id'];
		echo 'passed';
		loginValid($user_id);
		
		//loginValid($user_id);
		}
	}
	
?>