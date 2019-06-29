<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/title/title-cart.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head-min.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/mobile.php';

if($cart_id != ''){
	$cartQ = $conn->query("SELECT * FROM cart WHERE cart_id = '{$cart_id}'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'],true);
	$i = 1;
	$sub_total = 0;
	$item_count = 0;
}

$countQ = $conn->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
$user = mysqli_fetch_assoc($countQ); 

if(is_logged_in_user()){
	$user_id = $user_data['user_id'];
	$full_name = $user_data['full_name']; 
	$email = $user_data['email']; 
	$phoneNo = $user_data['phoneNo']; 
	$street = $user_data['street']; 
	$street2 = $user_data['street2']; 
	$city = $user_data['city']; 
	$region = $user_data['region']; 
}

if(!is_logged_in_user()){
	$user_id = ((isset($_POST['user_id']))?sanitize($_POST['user_id']):'');
	$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
	$email = trim($email);
	$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
	$phoneNo = ((isset($_POST['phoneNo']))?sanitize($_POST['phoneNo']):'');
	$street  = ((isset($_POST['street']))?sanitize($_POST['street']):'');
	$street2 = ((isset($_POST['street2']))?sanitize($_POST['street2']):'');
	$city = ((isset($_POST['city']))?sanitize($_POST['city']):'');
	$region = ((isset($_POST['region']))?sanitize($_POST['region']):'');
	$g_password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
	$g_confirm = ((isset($_POST['password']))?sanitize($_POST['password']):'');
}
$guest_email = ((isset($_POST['$guest_email']))?sanitize($_POST['$guest_email']):'');
$guest_email = trim($guest_email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
	
$errors = array();
?>

<div class="container" style="padding-top:30px;">
<div class="col-md-12">
	<div class="row">
		<h1 class="text-center">My Shopping Cart</h1><hr>
		<?php if($cart_id == ''): ?>
			<div class="bg-danger">
				<p class="text-danger text-center">Your shopping cart is empty!</p><!--place link to start shopping-->
			</div>
			<h2><a href="/retlug/index.php" class="text-success">Start Shopping</a></h2>
		<?php endif; ?>
		<?php if ($cart_id != ''): ?>
			<table class="table table-bordered table-condensed table-striped">
				<thead><th>#</th><th>Item</th><th>Price</th><th>Quantity</th><th>Color</th><th>Subtotal</th></thead>
				<tbody>
					<?php
						foreach($items as $item){
							$product_id = $item['id'];
							$productQ = $conn->query("SELECT * FROM products WHERE product_id = '{$product_id}'");
							$product = mysqli_fetch_assoc($productQ);
							$cArray = explode(',', $product['color']);
							foreach($cArray as $colorString){
								$c = explode(':', $colorString);
								if($c[0] == $item['color']){
									$available = $c[1];
								}
							}
							?>
							<tr>
							<td><?=$i;?></td>
							<td><?=$product['title'];?></td>
							<td><?=money($product['price']);?></td>
							<td>
								<button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['product_id'];?>','<?=$item['color'];?>')">-</button>
								<?=$item['quantity'];?>
								<?php if($item['quantity'] < $available): ?>
									<button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['product_id'];?>','<?=$item['color'];?>')">+</button>
								<?php else: ?>
									<span class="text-danger">Max Available</span>
								<?php endif; ?>
							</td>
							<td><?=$item['color'];?></td>
							<td><?=money($item['quantity'] * $product['price']);?></td>
							</tr>
							<?php 
							$i++;
							$item_count += $item['quantity'];
							$sub_total += ($product['price'] * $item['quantity']);
							}
							$tax = TAXRATE * $sub_total;
							//$tax = number_format($tax, 2);
							$grand_total = $tax + $sub_total;
							?>
				</tbody>
			</table>
			<table class="table table-bordered table-condensed text-right">
				<legend>Totals</legend>
				<thead class="totals-table-header"><th>Total Items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th></thead>
				<tbody>
				<tr>
					<td><?=$item_count;?></td>
					<td><?=money($sub_total);?></td>
					<td><?=money($tax);?></td>
					<td class="bg-success"><?=money($grand_total);?></td>
				</tr>
				</tbody>
			</table>
			
			<button type="button" class="btn btn-default btn-primary btn-success pull-right" data-toggle="modal" data-target="#checkout-modal">
				CheckOut>>
			</button>
			
			<!-- Modal -->
			<div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog" aria-labelledby="checkout-modal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="checkoutModalLabel">Login</h4>
						</div>
						<div class="modal-body">
						<form action="thankYou.php"  method="post" id="checkout-form"> 
							<span id="checkout_errors"></span>
							<!--login -->
							<div id="step1" class="row" style="display:block">
								<div class="col-md-6" style="border-right: 1px solid #D3D2E9;">
									<p><h3 class="text-align:left">Use my account</h3></p>
									<input type="hidden" name="cart_id" value="<?=$cart_id;?>">
									<input type="hidden" name="user_id" value="<?=$user_id;?>">
									<input type="hidden" name="full_name" value="<?=$full_name;?>">
									<input type="hidden" name="phoneNo" value="<?=$phoneNo;?>">
									<input type="hidden" name="street" value="<?=$street;?>">
									<input type="hidden" name="street2" value="<?=$street2;?>">
									<input type="hidden" name="city" value="<?=$city;?>">
									<input type="hidden" name="region" value="<?=$region;?>">
									<input type="hidden" name="tax" value="<?=$tax;?>">
									<input type="hidden" name="sub_total" value="<?=$sub_total;?>">
									<input type="hidden" name="grand_total" value="<?=$grand_total;?>">
									  <?php if(is_logged_in_user()): ?>
										  <div class="form-group">
											<input type="email" name="email" placeholder="enter username" id="email" class="form-control" value="<?=$email;?>" readonly>
										  </div>
										<input type="button" class="btn btn-primary" id="btn-login" onclick="logged();" value="Proceed">
									  <?php endif; ?>
									  <?php if(!is_logged_in_user()): ?>
									   <div class="form-group">
										<input type="email" name="email" placeholder="enter username" id="email2" onkeydown="validate_email();" class="form-control" value="<?=$email;?>">
										<span id="email_errors"></span>
									  </div>
									  <div class="form-group">
										<input type="password" name="password" placeholder="enter password" id="password" onkeydown="validate_password();" class="form-control" value="<?=$password;?>">
										<span id="password_errors"></span>
									  </div>
									  <div class="form-group" >
										<input type="button" class="btn btn-primary"  onclick="validate_login();" name="btn-login" id="btn-login" value="Login">
									  </div>
									  <div class="form-group">
										<p class="text-center"><a href="/retlug/user/user/create_account.php">Create Account</a></p>
									  </div>
									  <?php endif; ?>
								</div>
								<div class="col-md-6">
									<p><h3 class="text-align:left">Guest Checkout</h3></p>
										<input type="hidden" name="tax" value="<?=$tax;?>">
										<input type="hidden" name="sub_total" value="<?=$sub_total;?>">
										<input type="hidden" name="grand_total" value="<?=$grand_total;?>">
										<input type="hidden" name="cart_id" value="<?=$cart_id;?>">
										<div class="form-group">
											<input type="email" name="guest_email" placeholder="enter email" onkeydown="validate_guest_email();" onkeyup="validate_guest_email();" id="guest_email" class="form-control" value="<?=$guest_email;?>" >
										</div>
										<div class="form-group" >
											<input type="button" onclick="validate_guest();" class="btn btn-primary pull-right" value="Proceed">
										</div>
								</div>
							</div>
							<!-- guest checkout step2-->
							<div id="step2" style="display:none">
								<div class="row">
										<div class="form-group col-md-6">
											<label for="full_name">Full Name*:</label>
											<input type="text" id="full_name" name="full_name" class="form-control" value="<?=$full_name;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="phoneNo">Phone No*:</label>
											<input type="text" id="phoneNo" name="phoneNo" class="form-control" value="<?=$phoneNo;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="street">Street*:</label>
											<input type="text" id="street" name="street" class="form-control" value="<?=$street;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="street2">Street 2:</label>
											<input type="text" id="street2" name="street2" class="form-control" value="<?=$street2;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="city">City*:</label>
											<input type="text" id="city" name="city" class="form-control" value="<?=$city;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="region">Region*:</label>
											<input type="text" id="region" name="region" class="form-control" value="<?=$region;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="create"></label>
											<input type="checkbox" id="create" name="create" value="create"> Create Account
										</div>
								</div>
							</div>
							

							<!-- guest password-->
								<div id="step3" style="display:none">
									<div class="row">
										<div class="form-group col-md-6">
											<label for="password">Password*:</label>
											<input type="password" id="g_password" name="password" class="form-control" value="<?=$g_password;?>"></input>
										</div>
										<div class="form-group col-md-6">
											<label for="confirm">Confirm Password*:</label>
											<input type="password" id="g_confirm" name="confirm" class="form-control" value="<?=$g_confirm;?>"></input>
										</div>
									</div>
								</div>
							
							<!-- payment-->
								<div id="step4" style="display:none">
									<div class="row">
										<div class="col-md-6 form-group" style="border-right: 1px solid #E3E2E2;text-align:left;">
											<p><h3 class="text-align:left">Online payments</h3></p>
											<button type="submit" class="btn btn-default btn-primary form-control" disabled>Coming Soon</button>
										</div>
										<div class="col-md-6 form-group" style="text-align: left;">
											<p><h3 class="text-align:left">Pay on delivery</h3></p>
											<button type="submit" class="btn btn-default btn-primary form-control">Pay on Delivery</button>
										</div>
									</div>
								</div>
						</div>
						</form>
						<div class="modal-footer">
							<!-- step2-->
							<input type="button" id="btn_back1" onclick="back_to_step1();" value="<<Back" class="btn btn-primary" style="display:none">
							<input type="button" id="submit_guest" onclick="check_address();" name="submit_guest" value="Next>>" class="btn btn-success" style="display:none">
							<!-- step3-->
							<input type="button" id="btn_back2" onclick="back_to_step2();" value="<<Back" class="btn btn-primary" style="display:none">
							<input type="button" id="submit_password" onclick="insert_password();" name="insert_password" value="Next>>" class="btn btn-success" style="display:none">
							<!-- step4-->
							<input type="button" id="btn_back3" onclick="back_to_step3();" value="<<Back" class="btn btn-primary" style="display:none">
							<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-success" style="display:none">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
	<?php endif; ?>
	</div>
</div>
</div>

<script>

function update_cart(mode,edit_id,edit_color){
	var data = {"mode": mode, "edit_id": edit_id, "edit_color":edit_color};
	jQuery.ajax({
		url: '/retlug/admin/parsers/update_cart',
		method: "post",
		data: data,
		success: function(){
			location.reload();
		},
		error: function(){alert("Error updating cart!");},
	});	
}

function validate_email(){
	jQuery('#checkout_errors').html("");
	jQuery('#guest_email').html("");
}

function validate_password(){
	jQuery('#checkout_errors').html("");
	jQuery('#guest_email').html("");
}

function validate_guest_email(){
	jQuery('#checkout_errors').html("");
	jQuery('#email2').html("");
	jQuery('#password').html("");
}

function validate_login(){
	var error = "";
	jQuery('#checkout_errors').html("");
	var email = jQuery('#email2').val();
	var password = jQuery('#password').val();
	var data = 	{
		'email' : jQuery('#email2').val(),
		'password' : jQuery('#password').val(),
	};
	if(email == ''){
		error += '<p class="text-danger text-left">Email required.</p>';
		jQuery('#checkout_errors').html(error);
		return false;
	}
	else if(password == ''){
		error += '<p class="text-danger text-left">Password required.</p>';
		jQuery('#checkout_errors').html(error);
		return false;
	}
	else{
		jQuery.ajax({
			url: '/retlug/admin/parsers/validate_login.php',
			method: "post",
			data: data,
			success : function(data){
				if(data != 'passed') {
					jQuery('#checkout_errors').html(data);
				}
				if(data == 'passed') {
					jQuery('#checkout_errors').html("");
					jQuery('#step1').css("display","none");
					jQuery('#step2').css("display","none");
					jQuery('#step3').css("display","none");
					jQuery('#step4').css("display","block");
					jQuery('#btn_back1').css("display","inline-block");
					jQuery('#btn_back2').css("display","none");
					jQuery('#btn_back3').css("display","none");
					jQuery('#submit_guest').css("display","none");
					jQuery('#submit_password').css("display","none");
					jQuery('#submit').css("display","none");
					jQuery('#checkoutModalLabel').html("Payment options");
				}
			},	
			error : function(){alert("Something went wrong");},
		});
	}
}

function logged(){
	jQuery('#checkout_errors').html("");
	jQuery('#step1').css("display","none");
	jQuery('#step2').css("display","none");
	jQuery('#step3').css("display","none");
	jQuery('#step4').css("display","block");
	jQuery('#btn_back1').css("display","inline-block");
	jQuery('#btn_back2').css("display","none");
	jQuery('#btn_back3').css("display","none");
	jQuery('#submit_guest').css("display","none");
	jQuery('#submit_password').css("display","none");
	jQuery('#submit').css("display","none");
	jQuery('#checkoutModalLabel').html("Payment options");	
}

function validate_guest(){
	var error = "";
	jQuery('#checkout_errors').html("");
	var guest_email = jQuery('#guest_email').val();
	var data = 	{
		'guest_email' : jQuery('#guest_email').val(),
	};
	if(guest_email == ''){
		error += '<p class="text-danger text-left">Email required</p>';
		jQuery('#checkout_errors').html(error);
	}
	else{
		jQuery.ajax({
			url: '/retlug/admin/parsers/validate_guest.php',
			method: "post",
			data: data,
			success : function(data){
				if(data != 'passed') {
					jQuery('#checkout_errors').html(data);
				}
				else{
					jQuery('#checkout_errors').html("");
					jQuery('#step1').css("display","none");
					jQuery('#step2').css("display","block");
					jQuery('#step3').css("display","none");
					jQuery('#step4').css("display","none");
					jQuery('#btn_back1').css("display","inline-block");
					jQuery('#btn_back2').css("display","none");
					jQuery('#btn_back3').css("display","none");
					jQuery('#submit_guest').css("display","inline-block");
					jQuery('#submit_password').css("display","none");
					jQuery('#submit').css("display","none");
					jQuery('#checkoutModalLabel').html("Enter your shipping details");
				}
			},	
			error : function(){alert("Something went wrong");},
		});
	}
}


function check_address(){
	var error = "";
	jQuery('#checkout_errors').html("");
	var create = document.getElementById("create");
	var data = 	{
		'guest_email' : jQuery('#guest_email').val(),
		'full_name' : jQuery('#full_name').val(),
		'phone' : jQuery('#phoneNo').val(),
		'street' : jQuery('#street').val(),
		'street2' : jQuery('#street2').val(),
		'region' : jQuery('#region').val(),
		'city' : jQuery('#city').val(),
		'create' : jQuery('#create').val()
	};
	jQuery.ajax({
		url: '/retlug/admin/parsers/check_address.php',
		method: "post",
		data: data,
		success : function(data){
			if(data != 'passed') {
				jQuery('#checkout_errors').html(data);
			}
			if(data == 'passed') {
					if(create.checked){
						jQuery('#checkout_errors').html("");
						jQuery('#step1').css("display","none");
						jQuery('#step2').css("display","none");
						jQuery('#step3').css("display","block");
						jQuery('#step4').css("display","none");
						jQuery('#btn_back1').css("display","none");
						jQuery('#btn_back2').css("display","inline-block");
						jQuery('#btn_back3').css("display","none");
						jQuery('#submit_guest').css("display","none");
						jQuery('#submit_password').css("display","inline-block");
						jQuery('#submit').css("display","none");
						jQuery('#checkoutModalLabel').html("Create your password");
					}
					else{
						jQuery('#checkout_errors').html("");
						jQuery('#step1').css("display","none");
						jQuery('#step2').css("display","none");
						jQuery('#step3').css("display","none");
						jQuery('#step4').css("display","block");
						jQuery('#btn_back1').css("display","none");
						jQuery('#btn_back2').css("display","inline-block");
						jQuery('#btn_back3').css("display","none");
						jQuery('#submit_guest').css("display","none");
						jQuery('#submit_password').css("display","none");
						jQuery('#submit').css("display","none");
						jQuery('#checkoutModalLabel').html("Payment options");
					}
			}
		},	
		error : function(){alert("Something went wrong");},
	});
}

function insert_password(){
	var error = "";
	jQuery('#checkout_errors').html("");
	var g_password = jQuery('#g_password').val();
	var g_confirm = jQuery('#g_confirm').val();
	var data = 	{
		'g_password' : jQuery('#g_password').val(),
		'g_confirm' : jQuery('#g_confirm').val(),
		'create' : jQuery('#create').val(),
	};
	jQuery.ajax({
		url: '/retlug/admin/parsers/add_user.php',
		method: "post",
		data: data,
		success : function(data){
			if(data != 'passed') {
				jQuery('#checkout_errors').html(data);
			}
			if(data == 'passed') {
					jQuery('#checkout_errors').html("");
					jQuery('#step1').css("display","none");
					jQuery('#step2').css("display","none");
					jQuery('#step3').css("display","none");
					jQuery('#step4').css("display","block");
					jQuery('#btn_back1').css("display","none");
					jQuery('#btn_back2').css("display","none");
					jQuery('#btn_back3').css("display","inline-block");
					jQuery('#submit_guest').css("display","none");
					jQuery('#submit_password').css("display","none");
					jQuery('#submit').css("display","inline-block");
					jQuery('#checkoutModalLabel').html("Payment options");
			}
		},	
		error : function(){alert("Something went wrong");},
	});
}

function back_to_step1(){
	jQuery('#checkout_errors').html("");
	jQuery('#step1').css("display","block");
	jQuery('#step2').css("display","none");
	jQuery('#step3').css("display","none");
	jQuery('#step4').css("display","none");
	jQuery('#btn_back1').css("display","none");
	jQuery('#btn_back2').css("display","none");
	jQuery('#btn_back3').css("display","none");
	jQuery('#submit_guest').css("display","none");
	jQuery('#submit_password').css("display","none");
	jQuery('#submit').css("display","none");
	jQuery('#checkoutModalLabel').html("Login");
}

function back_to_step2(){
	jQuery('#checkout_errors').html("");
	jQuery('#step1').css("display","none");
	jQuery('#step2').css("display","block");
	jQuery('#step3').css("display","none");
	jQuery('#step4').css("display","none");
	jQuery('#btn_back1').css("display","inline-block");
	jQuery('#btn_back2').css("display","none");
	jQuery('#btn_back3').css("display","none");
	jQuery('#submit_guest').css("display","inline-block");
	jQuery('#submit_password').css("display","none");
	jQuery('#submit').css("display","none");
	jQuery('#checkoutModalLabel').html("Enter your shipping details");
}

function back_to_step3(){
	jQuery('#checkout_errors').html("");
	jQuery('#step1').css("display","none");
	jQuery('#step2').css("display","none");
	jQuery('#step3').css("display","block");
	jQuery('#step4').css("display","none");
	jQuery('#btn_back1').css("display","none");
	jQuery('#btn_back2').css("display","inline-block");
	jQuery('#btn_back3').css("display","none");
	jQuery('#submit_guest').css("display","none");
	jQuery('#submit_password').css("display","inline-block");
	jQuery('#submit').css("display","none");
	jQuery('#checkoutModalLabel').html("Choose your password");
}


</script>