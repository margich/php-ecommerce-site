<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/mobile.php';


$full_name = ((isset($_POST['full_name']))?sanitize($_POST['full_name']):'');
$subject = ((isset($_POST['subject']))?sanitize($_POST['subject']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$comment = ((isset($_POST['comment']))?sanitize($_POST['comment']):'');


?>
<div class="col-md-12" style="padding:20px;">
<span id="contact2_errors"></span>
<h2 style="color:#1C82C2">Get in Touch</h2><hr>
<span id="contact_errors"></span>
	<p><?=SITE_NAME;?> helps customers find their favourite products and businesses sell their products to thousands of customers.</p>
	<p>Email us with any questions or enquires or call anytime for assistance.</p>
	<div class="col-md-3">
		<div class="form-group col-md-12">
			<h4>Phone Number:<h4>
			<h5>0701234567<h5>
		</div>
		<div class="form-group col-md-12">
			<h4>Email:<h4>
			<h4>help@<?=SITE_NAME_SMALL;?>.com<h4>
		</div>
	</div>
	
	<form action="" method="post">
		<div class="col-md-9">
			<div class="col-md-6"> 
				<div class="form-group col-md-12">
					<label for="full_name">Full Name*:</label>
					<input type="text" name="full_name" class="form-control" id="full_name" value="<?=$full_name;?>"></input>
				</div>
				
				<div class="form-group col-md-12">
					<label for="subject">Subject*:</label>
					<input type="text" name="subject" class="form-control" id="subject" value="<?=$subject;?>"></input>
				</div>
				
				<div class="form-group col-md-12">
					<label for="email">Email*:</label><small> Use a working email</small>
					<input type="email" id="email" name="email" class="form-control" value="<?=$email;?>"></input>
				</div>
			</div>
			<div class="col-md-6"> 
				<label for="comment">Comment*:</label><small> Keep it short and concise</small>
				<textarea id="comment" name="comment" class="form-control" rows="7" ><?=$comment;?></textarea>
			</div>
			<div class="form-group col-md-6 text-right" style="margin-top:25px;">
				<input type="button" onclick="contact();" value="send" class="btn btn-lg btn-success">
			</div>
		</div>
	</form>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/retlug/pro.php';
?>