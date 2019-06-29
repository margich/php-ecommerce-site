<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';
//get brands from database
$sql = "SELECT * FROM brand ORDER BY brand";
$results = $conn->query($sql);
$errors = array();

//edit brand
if(isset($_GET['edit']) && !empty($_GET['edit'])) {
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $sql2 = "SELECT * FROM brand WHERE brand_id = '$edit_id'";
  $edit_result = $conn->query($sql2);
  $eBrand = mysqli_fetch_assoc($edit_result);
}

//Delete brand
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
  $delete_id = (int)$_GET['delete'];
  $delete_id = sanitize($delete_id);
  $sql = "DELETE FROM brand WHERE brand_id = '$delete_id'";
  $conn->query($sql);
  header('Location: brands.php');
}

//if add form is submitted
if(isset($_POST['add-submit'])) {
  $brand = sanitize($_POST['brand']);
  //check if brand is blank
  if($_POST['brand'] == '') {
    $errors[] .= 'You must enter brand!';
  }

  //check if brand exists in database
  $sql = "SELECT * FROM brand WHERE brand = '$brand'";
  if(isset($_POST['edit'])){
    $sql = "SELECT * FROM brand WHERE brand = '$brand AND brand_id != '$edit_id'";
  }
  $result = $conn->query($sql);
  $count = mysqli_num_rows($result);
  if($count > 0) {
    $errors[] .= $brand.' already exists. Please select another brand name...';
  }

  //display errors
  if(!empty($errors)) {
    echo display_errors($errors);
  }else{
    //add brand to database
    $sql = "INSERT INTO brand (brand) VALUES ('$brand')";
    if(isset($_GET['edit'])){
      $sql = "UPDATE brand SET brand = '$brand' WHERE brand_id = '$edit_id'";
    }
    $conn->query($sql);
    header('Location: brands.php');
  }
}

 ?>
<h2 class="text-center">Brands</h2><hr>
<!-- Brand Form-->
<div class="text-center">
  <form class="form-inline text-center" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
    <div class="form-group">
      <?php
      $brand_value = '';
      if(isset($_GET['edit'])){
        $brand_value = $eBrand['brand'];
      }else{
        if(isset($_POST['brand'])) {
          $brand_value = sanitize($_POST['brand']);
        }
      } ?>
      <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add A');?> Brand:</label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value;?>">
      <?php if(isset($_GET['edit'])): ?>
        <a href="brands.php" class="btn btn-default">Cancel</a>

      <?php endif; ?>
      <input type="submit" name="add-submit" value="<?= ((isset($_GET['edit']))?'Edit':'Add A'); ?> brand" class="btn btn-success">
    </div>
  </form>
</div><hr>



<table class="table table-bordered table-striped table-hover table-condensed table-auto" id="table-auto">
    <thead>
      <th></th><th>Brands</th><th></th>
    </thead>
    <tbody>
      <?php while($brand = mysqli_fetch_assoc($results)): ?>
      <tr>
        <td><a href="brands.php?edit=<?=$brand['brand_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></td>
        <td><?=$brand['brand'];?></td>
        <td><a href="brands.php?delete=<?=$brand['brand_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
