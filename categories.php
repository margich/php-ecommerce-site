<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

$sql= "SELECT * FROM categories WHERE parent = 0";
$result = $conn->query($sql);
$errors = array();
$category = '';
$post_parent = '';

//edit category
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $edit_sql = "SELECT * FROM categories WHERE category_id = '$edit_id'";
  $edit_result = $conn->query($edit_sql);
  $edit_category = mysqli_fetch_assoc($edit_result);
}

//delete category
if(isset($_GET['delete']) && !empty($_GET['delete'])){
  $delete_id = (int)$_GET['delete'];
  $delete_id = sanitize($delete_id);
  $sql = "SELECT * FROM categories WHERE category_id = '$delete_id'";
  $result = $conn->query($sql);
  $category = mysqli_fetch_assoc($result);
  if($category['$parent'] == 0){
    $sql = "DELETE FROM categories WHERE parent = '$delete_id'";
    $conn->query($sql);
  }
  $dsql = "DELETE FROM categories WHERE category_id = '$delete_id'";
  $conn->query($dsql);
  header('Location: categories.php');
}

//Process form
if(isset($_POST) && !empty($_POST)) {
  $post_parent = sanitize($_POST['parent']);
  $category = sanitize($_POST['category']);
  $sqlform = "SELECT * FROM categories WHERE category = '$category'";
  if(isset($_GET['edit'])){
    $id = $edit_category['id'];
    $sqlform = "SELECT * FROM categories WHERE category = '$category' AND category_id != '$id'";
  }
  $fresult = $conn->query($sqlform);
  $count = mysqli_num_rows($fresult);
  //if category is blank
  if($category == ''){
    $errors[] .= 'The category cannot be left blank';
  }
  //if category exists in the database
  if($count > 0){
    $errors[] .= $category. ' already exists';
  }

  //display errors or update database
  if(!empty($errors)){
    //display errors
    $display = display_errors($errors); ?>
    <script>
      jQuery('document').ready(function(){
        jQuery('#errors').html('<?= $display; ?>')
      });
    </script>
  <?php }else{
    //update database
    $updatesql = "INSERT INTO categories (category, parent) VALUES ('$category', '$post_parent')";
    if(isset($_GET['edit'])){
      $updatesql = "UPDATE categories SET category = '$category', parent = '$post_parent' WHERE category_id = '$edit_id'";
    }
    $conn->query($updatesql);
    header('Location: categories.php');
  }
}
$category_value = '';
$parent_value = 0;
if(isset($_GET['edit'])){
  $category_value = $edit_category['category'];
  $parent_value = $edit_category['parent'];
}else{
  if(isset($_POST)){
    $category_value = $category;
    $parent_value = $post_parent;
  }
}
?>

<h2 class="text-center">Categories</h2>
<hr>
<div class="row">

  <!--Form-->
  <div class="col-md-6">
    <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
      <legend><?=((isset($_GET['edit']))?'Edit ':'Add a ');?>Category</legend>
      <div id="errors"></div>
      <div class="form-group">
        <label for="parent">Parent</label>
        <select class="form-control" name="parent" id="parent">
          <option value="0"<?=(($parent_value == 0)?' selected=="selected"':'')?>>Parent</option>
          <?php while($parent = mysqli_fetch_assoc($result)) : ?>
          <option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['category_id'])?' selected=="selected"':'')?>><?=$parent['category'];?></option>
          <?php endwhile ;?>
      </div>
      <div class="form-group">
        <input type="text" class="hidden" id="category" >
    </div>
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" value="<?=$category_value;?>">
      </div>
      <div class="form-group">
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add a ');?>Category" class="btn btn-success">
      </div>
    </form>
  </div>

  <!--Category Table-->
  <div class="col-md-6">
    <table class="table table-bordered">
      <thead>
        <th>Category</th>  <th>Parent</th>  <th></th>
      </thead>
      <tbody>
        <?php
        $sql= "SELECT * FROM categories WHERE parent = 0";
        $result = $conn->query($sql);
        while($parent = mysqli_fetch_assoc($result)):
         $parent_id = (int)$parent['category_id'];
         $sql2= "SELECT * FROM categories WHERE parent = '$parent_id'";
         $cresult = $conn->query($sql2);
         ?>
        <tr class="bg-primary">
          <td><?= $parent['category']; ?></td>
          <td>PARENT</td>
          <td>
            <a href="categories.php?edit=<?= $parent['category_id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="categories.php?delete=<?= $parent['category_id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
        <?php while($child = mysqli_fetch_assoc($cresult)): ?>
          <tr class="bg-info">
            <td><?= $child['category']; ?></td>
            <td><?= $parent['category']; ?></td>
            <td>
              <a href="categories.php?edit=<?= $child['category_id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
              <a href="categories.php?delete=<?= $child['category_id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>

