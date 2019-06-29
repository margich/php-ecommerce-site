<?php
function display_errors($errors) {
  $display = '<ul class="text-danger" style="text-decoration:none";>';
  foreach ($errors as $error) {
    $display .= '<li class="text-danger" style="text-decoration-style:solid;list-style:none;text-align:left;">'.$error.'</li>';
    //break;
  }
  $display .= '</ul>';
  return $display;
}

function sanitize($dirty) {
	$dirty = trim($dirty);
	$dirty = strip_tags($dirty);
	return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}

function money($number) {
    return 'Ksh '.number_format($number,0);
}

function loginAdmin($admin_id) {
  $_SESSION['ADUser'] = $admin_id;
  global $conn;
  $date = date("Y-m-d H:i:s");
  $conn->query("UPDATE admin SET last_login = '$date' WHERE admin_id = '$admin_id'");
  $_SESSION['success_flash'] = 'You are now logged in!';
  header('Location: index.php');
}

function loginUser($user_id) {
  $_SESSION['SPUser'] = $user_id;
  global $conn;
  $date = date("Y-m-d H:i:s");
  $conn->query("UPDATE users SET last_login = '$date' WHERE user_id = '$user_id'");
  $_SESSION['success_flash'] = 'You are now logged in!';
  //header('Location: /retlug/user/user/login.php');
}

function loginValid($user_id) {
  $_SESSION['SPUser'] = $user_id;
  global $conn;
  $date = date("Y-m-d H:i:s");
  $conn->query("UPDATE users SET last_login = '$date' WHERE user_id = '$user_id'");
}

function loginSeller($seller_id) {
  $_SESSION['SLUser'] = $seller_id;
  global $conn;
  $date = date("Y-m-d H:i:s");
  $conn->query("UPDATE sellers SET last_login = '$date' WHERE seller_id = '$seller_id'");
  $_SESSION['success_flash'] = 'You are now logged in!';
  header('Location: /retlug/user/seller/index.php');
}

function is_logged_in_admin(){
  if(isset($_SESSION['ADUser']) && $_SESSION['ADUser'] > 0){
    return true;
  }
  return false;
}

function is_logged_in_user(){
  if(isset($_SESSION['SPUser']) && $_SESSION['SPUser'] > 0){
    return true;
  }
  return false;
}

function is_logged_in_seller(){
  if(isset($_SESSION['SLUser']) && $_SESSION['SLUser'] > 0){
    return true;
  }
  return false;
}

function login_error_redirect($url = 'login.php'){
  $_SESSON['error_flash'] = 'You must be logged in to access that page';
  header('Location: '.$url);
}

function permission_error_redirect($url = 'login.php'){
  $_SESSON['error_flash'] = 'You do not have permission to access that page';
  header('Location: '.$url);
}

function has_permission($permission){
  global $admin_data;
  $permissions = explode(',', $admin_data['permissions']); 
  if(in_array($permission, $permissions, true)){
    return true;
  }
  return false;
}

function seller_has_permission($permission){
  global $seller_data;
  $permissions = explode(',', $seller_data['permissions']); 
  if(in_array($permission, $permissions, true)){
    return true;
  }
  return false;
}

function pretty_date($date){
  return date("M d, Y h:i A", strtotime($date));
}

function get_category($child_id){
  global $conn;
  $id = sanitize($child_id);
  $sql = "SELECT p.category_id AS 'pid', p.category AS 'parent', c.category_id AS 'cid', c.category AS 'child'
          FROM categories c
          INNER JOIN categories p
          ON c.parent = p.category_id
          WHERE c.category_id = '$child_id'";
  $query = $conn->query($sql);
  $category = mysqli_fetch_assoc($query);
  return $category;
}

function get_shop($shop_id){
  global $conn;
  $id = sanitize($shop_id);
  $sql = "SELECT p.shop_id AS 'shop_id', p.name AS 'shop_name', c.seller_id AS 'seller_id', c.full_name AS 'seller_name'
          FROM sellers c
          INNER JOIN shop p
          ON c.shop_id = p.shop_id
          WHERE c.shop_id = '$shop_id'";
  $query = $conn->query($sql);
  $shop = mysqli_fetch_assoc($query);
  return $shop;
}

function get_seller($seller_id){
  global $conn;
  $id = sanitize($seller_id);
  $sql = "SELECT p.shop_id AS 'shop_id', p.name AS 'shop_name', c.seller_id AS 'seller_id', c.full_name AS 'seller_name'
          FROM sellers c
          INNER JOIN shop p
          ON c.shop_id = p.shop_id
          WHERE c.seller_id = '$seller_id'";
  $query = $conn->query($sql);
  $shop = mysqli_fetch_assoc($query);
  return $shop;
}
function colorsToArray($string){
	$colorsArray = explode(',', $string);
	$returnArray = array();
	foreach($colorsArray as $color){
		$c = explode(':', $color);
		$returnArray[] = array('color' => $c[0], 'quantity' => $c[1], 'threshold' => $c[2]);
	}
	return $returnArray;
}

function colorsToString($colors){
	$colorString = '';
	foreach($colors as $color){
		$colorString .= $color['color'].':'.$color['quantity'].':'.$color['threshold'].',';
	}
	$trimmed = rtrim($colorString, ',');
	return $trimmed;
}

function getRandomString($length){
	$validCharacters = "ABCDEFGHIJKLMNPQRSTUXYVWZabsdefghijklmnpqrstuxyvwz123456789";
	$validCharNumber = strlen($validCharacters);
	$result = "";

	for ($i = 0; $i < $length; $i++) {
		$index = mt_rand(0, $validCharNumber - 1);
		$result .= $validCharacters[$index];
	}
	return $result;
}

function mail($to, $subject, $message, $headers, $parameters){
	$to = sanitize($to);
	$subject = sanitize($subject);
	$message = sanitize($message);
	$headers = sanitize($headers); 
	$parameters = sanitize($parameters); 
	 
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	 
	// Create email headers
	$headers .= 'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
	 
	// Compose a simple HTML email message
	$message = '<html><body>';
	$message .= '<h1 style="color:#f40;"></h1>';
	$message .= '<p style="color:#080;font-size:18px;">'.$message.'</p>';
	$message .= '</body></html>';
	 
	// Sending email
	if(mail($to, $subject, $message, $headers)){
		echo 'Your mail has been sent successfully.';
	} else{
		echo 'Unable to send email. Please try again.';
	}
}
