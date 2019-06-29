<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$subscriber = sanitize($_POST['subscriber']);


//form validation
if(empty($_POST['subscriber']) || $subscriber == ''){
  $errors[] = 'Email required.';
}

//validate email
if(!filter_var($subscriber,FILTER_VALIDATE_EMAIL)){
  $errors[] = 'You must enter a valid email.';
}

//check if user exists in database
$query = $conn->query("SELECT * FROM subscribers WHERE email = '$subscriber'");
$result = mysqli_fetch_assoc($query);
$subCount = mysqli_num_rows($query);
if($subCount > 0){
	$errors[] = 'You have already subscribed.';
}

//check for errors
if(!empty($errors)){
	display_errors($errors);
}else {
  //input into database
	
	$conn->query("INSERT INTO subscribers (email) VALUES ('$subscriber')");
	$_SESSION['success_flash'] = 'You have successfully subscribed.';
	echo 'passed';
}

?>
