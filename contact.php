<?php require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
$subject = ((isset($_POST['subject']))?sanitize($_POST['subject']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$comment = ((isset($_POST['comment']))?sanitize($_POST['comment']):'');
$errors = array();
	$required = array(
		'full_name' => 'Full name',
		'subject' 	=> 'Subject',
		'email' 	=> 'Email',
		'comment' 	=> 'Comment',
	);

	//check all required fields are filled
	foreach($required as $f => $d) {
		if(empty($_POST[$f]) || $_POST[$f] == '') {
			$errors[] = $d.' is required.';
		}
	}

	if(!empty($email)){
	  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$errors[] = 'You must enter a valid email address.';
	  }
	}

	if(!empty($errors)) {
		echo display_errors($errors);
	}
	else{
	
	$sql = "INSERT INTO comments (`full_name`,`subject`,`email`,`comment`) 
			VALUES ('$full_name','$subject','$email','$comment')";
	$conn->query($sql);
	$_SESSION['success_flash'] = 'Message successfully sent.';
	}
?>