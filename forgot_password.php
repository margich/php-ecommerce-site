<?php require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$errors = array();

	if(empty($email) || $email == '') {
		$errors[] = $email.' is required.';
	}

	if(!empty($email)){
	  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$errors[] = 'You must enter a valid email address.';
	  }
	}
	
	$emailQ = $conn->query("SELECT * FROM users WHERE email = '$email'");
	$emailR = mysqli_num_rows($emailQ);
	if($emailR == 1){
		//send email with reset link
		echo 'passed';
	}
	else{
		$errors[] = 'Email does not exist in our database.';
	}

	if(!empty($errors)) {
		echo display_errors($errors);
	}
?>