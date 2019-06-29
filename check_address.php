<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
	
	$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
	$phone = ((isset($_POST['phone']))?sanitize($_POST['phone']):'');
	$street = ((isset($_POST['street']))?sanitize($_POST['street']):'');
	$street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
	$region = ((isset($_POST['region']))?sanitize($_POST['region']):'');
	$city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
	$errors = array();
	$required = array(
		'full_name' 	=> 'Full Name',
		'phone' 		=> 'Phone No',
		'street'		=> 'Street',
		'region' 		=> 'Region',
		'city' 			=> 'City',
	);
	
	//check all required fields are filled
	foreach($required as $f => $d) {
		if(empty($_POST[$f]) || $_POST[$f] == '') {
			$errors[] = $d.' is required.';
		}
	}
	
	if(!empty($errors)) {
		echo display_errors($errors);
	}
	else{
		echo 'passed';
	}
?>