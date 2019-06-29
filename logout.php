<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
unset($_SESSION['SBUser']);
$_SESSION['success_flash'] = 'You are now logged out!';
header('Location: index.php');
?>
