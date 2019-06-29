</div>


<div class="container-fluid footer">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="row">
        <div class="col-md-12 col-sm-12" style="border-top: 1px solid #ccc;">
          <ul class="nav navbar-nav">
            <form class="navbar-form navbar col-md-12 col-sm-12 col-xs-12 ">
				<label><h4 class="text-center">Sign up to get the latest products and deals</h4></label>
					<div class="input-group">
						<input type="text" class="center-block form-control" title="Enter you email." placeholder="Enter your email address">
						<span class="input-group-btn"><button class="btn btn-primary" type="button">OK</button></span>
					</div>
            </form>
          </ul>
        </div>
      </div>
        <div class="col-md-4 col-sm-12">
          <ul class="nav navbar-nav">
			<li><a style="color:inherit;" href="https://www.facebook.com/retlug" class="fa fa-facebook fa-5x" aria-hidden="true" style="padding:20px;"></a></li>
			<li><a style="color:inherit;" href="https://www.instagram.com" class="fa fa-instagram fa-5x" aria-hidden="true" style="padding:20px;"></a></li>
			<li><a style="color:inherit;"href="https://twitter.com/retlug" class="fa fa-twitter fa-5x" aria-hidden="true" style="padding:20px;"></a></li>
			<li><a style="color:inherit;" href="https://www.pinterest.com" class="fa fa-pinterest fa-5x" aria-hidden="true" style="padding:20px;"></a></li>
          </ul>
        </div>
        <div class="col-md-4 col-sm-12">
          <ul class="nav navbar-nav">
            <li><a href="/retlug/footer/contact_us.php">Contact Us</a></li>
            <li><a href="/retlug/footer/privacy.php">Privacy Policy</a></li>
			<li><a href="/retlug/footer/terms.php">Terms and Conditions</a></li>
          </ul>
        </div>
        <div class="col-md-4 col-sm-12">
          <ul class="nav navbar-nav">
            <li><a href="/retlug/popular.php">Most Popular</a></li>
            <li><a href="/retlug/recent.php">Most Recent</a></li>
			<?php 
			$sql = "SELECT * FROM categories WHERE parent = 0 ORDER BY category";
			$pquery = $conn->query($sql);
			while($parent = mysqli_fetch_assoc($pquery)): ?>
                <li><a href="/retlug/category.php?category=<?=$parent['category'];?>"><?=$parent['category'];?></a></li>
            <?php endwhile; ?>
          </ul>
        </div>

      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="text-center">
            &copy; Copyright 2018 <?=SITE_NAME;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

	jQuery('#color').change(function(){
		var available = jQuery('#color option:selected').data("available");
		jQuery('#available').val(available);
	})

  jQuery(document).ready(function($) {
 
	$('#myCarousel').carousel({
			interval: 5000
	});

	$('#carousel-text').html($('#slide-content-0').html());

	//Handles the carousel thumbnails
   $('[id^=carousel-selector-]').click( function(){
		var id = this.id.substr(this.id.lastIndexOf("-") + 1);
		var id = parseInt(id);
		$('#myCarousel').carousel(id);
	});


	// When the carousel slides, auto update the text
	$('#myCarousel').on('slid.bs.carousel', function (e) {
			 var id = $('.item.active').data('slide-number');
			$('#carousel-text').html($('#slide-content-'+id).html());
	});
});



$(function(){
    
    $(".input-group-btn .dropdown-menu li a").click(function(){

        var selText = $(this).html();
    
        //working version - for single button //
       //$('.btn:first-child').html(selText+'<span class="caret"></span>');  
       
       //working version - for multiple buttons //
       $(this).parents('.input-group-btn').find('.btn-search').html(selText);

   });

});


function subscribe(){
	var error = "";
	jQuery('#menu_errors').html("");
	var subscriber = jQuery("#subscriber").val();
	var data = 	{
		'subscriber' : jQuery('#subscriber').val(),
	};
	if(subscriber == ''){
		error += '<p class="text-danger">Email required</p>';
		jQuery('#menu_errors').html(error);
	}
	else{
		jQuery.ajax({
		url: '/retlug/subscribe.php',
		method: "post",
		data: data,
		success: function(){
			if(data != 'passed') {
				jQuery('#menu_errors').html(data);
			}
			if(data == 'passed') {
				location.reload();
			}
		},
		error: function(){alert("Error subscribing");}
	});
	}
}

function contact(){
	var error = "";
	jQuery('#contact_errors').html("");
	var full_name = jQuery("#full_name").val();
	var subject = jQuery("#subject").val();
	var email = jQuery("#email").val();
	var comment = jQuery("#comment").val();
	var data = 	{
		'full_name' : jQuery('#full_name').val(),
		'subject' : jQuery('#subject').val(),
		'email' : jQuery('#email').val(),
		'comment' : jQuery('#comment').val(),
	};
	if(full_name == ''){
		error += '<h4 class="text-danger">Full name required</h4>';
		jQuery('#contact_errors').html(error);
	}
	if(subject == ''){
		error += '<h4 class="text-danger">Subject required</h4>';
		jQuery('#contact_errors').html(error);
	}
	if(email == ''){
		error += '<h4 class="text-danger">Email required</h4>';
		jQuery('#contact_errors').html(error);
	}
	if(comment == ''){
		error += '<h4 class="text-danger">Comment required</h4>';
		jQuery('#contact_errors').html(error);
	}
	else{
		jQuery.ajax({
		url: '/retlug/admin/parsers/contact.php',
		method: "post",
		data: data,
		success: function(){
			if(data != 'passed') {
				jQuery('#contact_errors').html(data);
			}
			if(data == 'passed') {
				location.reload();
			}
		},
		error: function(){alert("Error sending message");}
	});
	}
}

function fg_pass(){
	var error = "";
	jQuery('#fg_errors').html("");
	var email = jQuery("#email").val();
	var data = 	{
		'email' : jQuery('#email').val(),
	};
	if(email == ''){
		error += '<h4 class="text-danger">Email required.</h4>';
		jQuery('#fg_errors').html(error);
	}
	else{
		jQuery.ajax({
		url: '/retlug/admin/parsers/forgot_password.php',
		method: "post",
		data: data,
		success: function(){
			if(data != 'passed') {
				jQuery('#fg_errors').html(data);
			}
			if(data == 'passed') {
				location.reload();
			}
		},
		error: function(){alert("Error sending message");}
	});
	}
}

function detailsmodal(id) {
	var data = {"id" : id};
	jQuery.ajax({
		url: '/retlug/includes/detailsmodal.php',
		method: "post",
		data: data,
		success: function(data){
			jQuery('body').append(data);
			jQuery('#details-modal').modal('toggle');
		},
		error: function(){
			alert("Error fetching modal!");
		}
	});
}

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

function add_to_wishlist(){
	var item_name = jQuery('#item_name').val();
	var item_id = jQuery('#item_id').val();
	var data = 	{
		'item_name' : jQuery('#item_name').val(),
		'item_id'	: jQuery('#item_id').val(),
	};
	 jQuery.ajax({
	  url: '/retlug/admin/parsers/add_wishlist.php',
	  method: "post",
	  data: data,
	  success : function(){
		location.reload();
	  },
	  error : function(){alert("Error adding to wishlist!");}
	});
}


function add_to_cart(){
  jQuery('#modal_errors').html("");
  var color = jQuery('#color').val();
  var quantity = jQuery('#quantity').val();
  var shop_id = jQuery('#shop_id').val();
  var available = jQuery('#available').val();
  var error = '';
  var data = jQuery('#add_product_form').serialize();
  if(color == ''){
	error += '<h3><p class="text-danger text-center">You must choose a color</p></h3>';
	jQuery('#modal_errors').html(error);
	jQuery('#product_errors').html(error);
	return false;
  }
  else if(quantity > available){
    error += '<h3><P class="text-danger text-center">There are only '+available+' available.</p></h3>';
	jQuery('#modal_errors').html(error);
	jQuery('#product_errors').html(error);
    return false;
  }else{
    jQuery.ajax({
      url: '/retlug/admin/parsers/add_cart.php',
      method: "post",
      data: data,
      success : function(){
        location.reload();
      },
      error : function(){alert("Error adding to cart!");}
    });
	return true;
  }
}

</script>

</body>
</html>
