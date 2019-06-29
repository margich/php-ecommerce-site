</div>

<footer class="text-center" id="footer">
  <div class="col-md-12 text-center" >&copy; Copyright 2018 <?=SITE_NAME;?></div>
</footer>

<script>


function subscribe(){
	var subscriber = jQuery("#subscriber").val();
	
	if(subscriber == ''){
		jQuery('#subscriber').html("Required");
		jQuery('#subscriber').css("border","1px solid red");
	}
	else{
		jQuery.ajax({
		url: '/retlug/subscribe.php',
		method: "post",
		data: {subscriber : subscriber},
		success: function(){
			location.reload();
		},
		error: function(){alert("Error subscribing");}
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
