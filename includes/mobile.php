<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
$sql = "SELECT * FROM categories WHERE parent = 0 ORDER BY category";
$pquery = $conn->query($sql);

$subscriber = '';
$subscriber = trim($subscriber);

?>
<?php ob_start(); ?>
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12" style="background-color:black; color:white; text-align:center">
				<h5>Free delivery within Nairobi CBD Area.</h5>
			</div>
		</div>
		<div class="navbar-header">
			<div class="row">
				<div class="col-xs-4 pull-right">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".myMegaMenu" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-4" >
					<a class="navbar-brand" href="/retlug/index.php"><?=SITE_NAME;?></a>
				</div>
			</div>
		</div>
		<div class="nav-mobile pull-right" style="margin:6px;">
			<form class="navbar-form" action="http://localhost/retlug/search.php" method="get" autocomplete="off">
				<div class="input-group">
					<input type="text" class="center-block form-control" name="search" placeholder="Search for products">
					<span class="input-group-btn"><button class="btn btn-primary" type="submit">Search</button></span>
				</div>
			</form>
		</div>
		<div class="collapse navbar-collapse myMegaMenu">
			<ul class="nav navbar-nav navbar-left myMegaMenu">
				<li class="dropdown mega-dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">Menu <span class="caret"></span></a>
					<ul class="dropdown-menu mega-dropdown-menu row">
						<li class="col-sm-3">
							<ul>
								<li class="dropdown-header"><?=SITE_NAME;?></li>
								<li><a href="/retlug/footer/contact_us.php">Contact Us</a></li>
								<li class="divider"></li>
								<li class="dropdown-header">Subscribe</li> <span id="menu_errors"></span>
								<li>
									<form class="form" action="" method="post" role="form">
										<div class="form-group">
											<label class="sr-only" for="email">Email address</label>
											<?php ((isset($_POST['subscriber']))?sanitize($_POST['subscriber']):'');?>
											<input type="email" name="subscriber" class="form-control" id="subscriber" value="<?=$subscriber;?>" placeholder="Your Email address" autocomplete="off"> 
										</div>
										<button type="button" onclick="subscribe()" class="btn btn-primary btn-block">Sign up</button>
									</form>
								</li>
							</ul>
						</li>
						<?php while($parent = mysqli_fetch_assoc($pquery)):
						  $parent_id = (int)$parent['category_id'];
						  $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
						  $cresult = $conn->query($sql2);
						  ?>
						<li class="col-sm-3">
							<ul>
								<span id="menu_errors"></span>
								<li class="dropdown-header"><?=$parent['category'];?></li>
								<?php while($child = mysqli_fetch_assoc($cresult)): ?>
								<li><a href="/retlug/subcategory.php?subcategory=<?=$child['category'];?>"><?=$child['category'];?></a></li>
								<?php endwhile; ?>
							</ul>
						</li>
						<?php endwhile; ?>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-left">
				<li class="hover">
					<a href="/retlug/cart.php" ><span class="glyphicon glyphicon-shopping-cart" style="position:relative;"> Cart</span></a>
				</li>
				<li class="dropdown">
				<?php if(is_logged_in_user()): ?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" role="button" aria-haspopup="true" aria-expanded="false"><?=$user_data['first'];?><span class="caret"></span></a>
				<?php endif; ?>
				<?php if(!is_logged_in_user()): ?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span><span class="caret"></span></a>
				<?php endif; ?>
				  <ul class="dropdown-menu" style="margin-top:0px;">
					<?php if(!is_logged_in_user()): ?>
						<li><a href="/retlug/user/user/login.php">Log In</a></li>
					<?php endif; ?>
					<?php if(is_logged_in_user()): ?>
						<li><a href="/retlug/user/user/index.php">Dashboard</a></li>
						<li><a href="/retlug/user/user/edit_account.php">Edit Account</a></li>
						<li><a href="/retlug/user/user/change_password.php">Change Password</a></li>
						<li><a href="/retlug/user/user/logout.php">Log Out</a></li>
					<?php endif; ?>
				  </ul>
				</li>
			</ul>
		</div>	
	</div>
</nav>
<div class="container" style="padding-top:100px;">
<?php
if(isset($_SESSION['success_flash'])){
  echo '<div class="alert alert-success fade in">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Success! </strong>'.$_SESSION['success_flash'].'
		</div>';
  unset($_SESSION['success_flash']);
}
  if(isset($_SESSION['error_flash'])){
  echo '<div class="alert alert-danger fade in">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Error! </strong>'.$_SESSION['error_flash'].'
		</div>';
  unset($_SESSION['error_flash']);
}

if(isset($_SESSION['info_flash'])){
  echo '<div class="alert alert-info fade in">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Info! </strong>'.$_SESSION['info_flash'].'
		</div>';
  unset($_SESSION['info_flash']);
}

if(isset($_SESSION['warning_flash'])){
  echo '<div class="alert alert-warning fade in">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Info! </strong>'.$_SESSION['warning_flash'].'
		</div>';
  unset($_SESSION['warning_flash']);
}
?>