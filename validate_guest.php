<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
unset($_SESSION['SPUser']);
$guest_email = ((isset($_POST['guest_email']))?sanitize($_POST['guest_email']):'');
$guest_email = trim($guest_email);
$errors = array();
$required = array(
	'guest_email' 	=> 'Email',
);

//check all required fields are filled
foreach($required as $f => $d) {
	if(empty($_POST[$f]) || $_POST[$f] == '') {
		$errors[] = $d.' is required.';
	}
}

//check if valid email address
if(!empty($_POST['guest_email']) && $_POST['guest_email'] != ''){
	if(!filter_var($guest_email,FILTER_VALIDATE_EMAIL)){
		$errors[] = 'Please enter a valid email address.';
	}
}

//check if user exists in database
$query = $conn->query("SELECT * FROM users WHERE email = '$guest_email' AND live = '1' AND guest = '0'");
$user = mysqli_fetch_assoc($query);
$userCount = mysqli_num_rows($query);
if($userCount == 1){
  $errors[] = 'Account already exists. Please log in';
}

//check for errors
if(!empty($errors)){
	echo display_errors($errors);
}
else{
	echo 'passed';
}
?>
