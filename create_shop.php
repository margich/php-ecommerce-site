<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
	
	$shop_name = ((isset($_POST['shop_name']))?sanitize($_POST['shop_name']):'');
	$street = ((isset($_POST['street']))?sanitize($_POST['street']):'');
	$street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
	$region = ((isset($_POST['region']))?sanitize($_POST['region']):'');
	$city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
	$errors = array();
	$required = array(
		'shop_name' 	=> 'Shop name',
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
	
	$shopQ = $conn->query("SELECT COUNT(shop_id) AS shop FROM shop WHERE name = '$shop_name'");
	$shopCount = mysqli_fetch_assoc($shopQ);
	if($shopCount['shop'] > 0){
		$errors[] = 'That shop name already exists in our database.';
	}
	
	if(!empty($errors)) {
		echo display_errors($errors);
	}
	else{
		echo 'passed';
	}
?>