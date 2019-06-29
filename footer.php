</div>

<footer class="text-center" id="footer">
  <div class="col-md-12 text-center" >&copy; Copyright 2018 <?=SITE_NAME;?></div>
</footer>

<script>




function updateColors(){
  var colorString = '';
  for(var i = 1; i<=12; i++){
	  if(jQuery('#color'+i).val() != ''){
    colorString += jQuery('#color'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+','
  }
  }
   jQuery('#colors').val(colorString)
}

function get_child_options(selected){
  if(typeof selected === 'undefined'){
    var selected = '';
  }

  var parentID = jQuery('#parent').val();
  jQuery.ajax({
    url: '/retlug/admin/parsers/child_categories.php',
    type: 'POST',
    data: {parentID : parentID, selected : selected},
    success: function(data){
      jQuery('#child').html(data);
    },
    error: function(){alert("Error with child options.")},
  });
}
jQuery('select[name="parent"]').change(function(){
  get_child_options();
});
</script>

</body>
</html>
