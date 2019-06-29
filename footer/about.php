<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/off-nav.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/leftbar.php';


?>
<div class="col-md-12" style="margin-top:10px;padding:20px;">
<h2 style="color:#1C82C2;">About <?=SITE_NAME;?></h2><hr>
	<p><?=SITE_NAME;?> was crafted with the grand ambition of making it more effecient for <b>Anyone Anywhere Anytime</b> to purchase or sell their products.</p>
	<p>Since inception we have strived to make this goal a reality.</p>
</div>




<?php
include $_SERVER['DOCUMENT_ROOT'].'/retlug/includes/rightbar.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/pro.php';
?>