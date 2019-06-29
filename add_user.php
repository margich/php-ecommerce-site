<?php require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';

$g_password = ((isset($_POST['g_password']))?sanitize($_POST['g_password']):'');
$g_confirm = ((isset($_POST['g_confirm']))?sanitize($_POST['g_confirm']):'');
$errors = array();
	$required = array(
		'g_password' 	=> 'Password',
		'g_confirm' 	=> 'Confirm password',
	);

	//check all required fields are filled
	foreach($required as $f => $d) {
		if(empty($_POST[$f]) || $_POST[$f] == '') {
			$errors[] = $d.' is required.';
		}
	}
	
	//check if password is more than 6 characters
	if(strlen($g_password) < 6){
		$errors[] = 'Password must be at least 6 characters.';
	}

	//check if new password matches confirm
	if($g_password != $g_confirm){
		$errors[] = 'Your password and confirm password do not match';
	}
	
	if(!empty($errors)) {
		echo display_errors($errors);
	}
	else{
		echo 'passed';
	}
?>