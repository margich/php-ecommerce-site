<?php  //database connection details
$hn = "localhost";
$db = "retlugco_retlug";
$un = "marvin";
$pw = "marvin";

 $conn = mysqli_connect($hn, $un, $pw, $db);
 if (mysqli_connect_errno())
 {
	echo 'Database connection failed with following error: '. mysqli_connect_error();
	die();
 }
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/config.php';
require_once BASEURL.'helpers/helpers.php';



$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
  $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}


if(isset($_SESSION['ADUser'])){
  $admin_id = $_SESSION['ADUser'];
  $query = $conn->query("SELECT * FROM admin WHERE admin_id = '$admin_id'");
  $admin_data = mysqli_fetch_assoc($query);
  $fn = explode(' ', $admin_data['full_name']);
  $admin_data['first'] = $fn[0];
  $admin_data['last'] = ((empty($admin_data['last']))?'':$fn[1]);
}

if(isset($_SESSION['SPUser'])){
  $user_id = $_SESSION['SPUser'];
  $query = $conn->query("SELECT * FROM users WHERE user_id = '$user_id'");
  $user_data = mysqli_fetch_assoc($query);
  $fn = explode(' ', $user_data['full_name']);
  $user_data['first'] = $fn[0];
  $user_data['last'] = ((empty($user_data['last']))?'':$fn[1]);
}

if(isset($_SESSION['SLUser'])){
  $seller_id = $_SESSION['SLUser'];
  $query = $conn->query("SELECT * FROM sellers WHERE seller_id = '$seller_id'");
  $seller_data = mysqli_fetch_assoc($query);
  $fn = explode(' ', $seller_data['full_name']);
  $seller_data['first'] = $fn[0];
  $seller_data['last'] = ((empty($seller_data['last']))?'':$fn[1]);
}

/* if(isset($_SESSION['success_flash'])){
  echo '<div class="alert alert-success fade in" style="margin-top:37px; margin-bottom:-50px;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Success! </strong>'.$_SESSION['success_flash'].'
		</div>';
  unset($_SESSION['success_flash']);
} */

/* if(isset($_SESSION['error_flash'])){
  echo '<div class="alert alert-danger fade in" style="margin-top:78px; margin-bottom:-64px;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Error! </strong>'.$_SESSION['error_flash'].'
		</div>';
  unset($_SESSION['error_flash']);
}

if(isset($_SESSION['info_flash'])){
  echo '<div class="alert alert-info fade in" style="margin-top:78px; margin-bottom:-64px;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Info! </strong>'.$_SESSION['info_flash'].'
		</div>';
  unset($_SESSION['info_flash']);
}

if(isset($_SESSION['warning_flash'])){
  echo '<div class="alert alert-warning fade in" style="margin-top:78px; margin-bottom:-64px;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Info! </strong>'.$_SESSION['warning_flash'].'
		</div>';
  unset($_SESSION['warning_flash']);
} */

if(isset($_SESSION['success_flash'])){echo '<div class="alert alert-success fade in">
<a href="#" class="close" data-dismiss="alert">&times;</a>
<strong>Success! </strong>'.$_SESSION['success_flash'].'
</div>';unset($_SESSION['success_flash']);}if(isset($_SESSION['error_flash'])){echo '<div class="alert alert-danger fade in">
<a href="#" class="close" data-dismiss="alert">&times;</a>
<strong>Error! </strong>'.$_SESSION['error_flash'].'
</div>';unset($_SESSION['error_flash']);}if(isset($_SESSION['info_flash'])){echo '<div class="alert alert-info fade in">
<a href="#" class="close" data-dismiss="alert">&times;</a>
<strong>Info! </strong>'.$_SESSION['info_flash'].'
</div>';unset($_SESSION['info_flash']);}if(isset($_SESSION['warning_flash'])){echo '<div class="alert alert-warning fade in">
<a href="#" class="close" data-dismiss="alert">&times;</a>
<strong>Info! </strong>'.$_SESSION['warning_flash'].'
</div>';unset($_SESSION['warning_flash']);}
