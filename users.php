<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}

if(!has_permission('admin')){
  permission_error_redirect('/retlug/admin/index.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

if(isset($_GET['delete'])){
  $delete_id = (int)$_GET['delete'];
  $delete_id = sanitize($delete_id);
  $sql = "DELETE FROM admin WHERE admin_id = '$delete_id'";
  $conn->query($sql);
  $_SESSION['success_flash'] = 'User has been deleted!';
  header('Location: /retlug/admin/users.php');
}
//add new user
if(isset($_GET['add'])){
$name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$phoneNo = ((isset($_POST['phoneNo']))?sanitize($_POST['phoneNo']):'');
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$permissions= ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
$errors = array();
if($_POST){
  $emailQuery = $conn->query("SELECT * FROM admin WHERE email = '$email'");
  $emailCount = mysqli_num_rows($emailQuery);
  if($emailCount != 0){
    $errors[] = 'That email already exists in our database.';
  }

  $required = array('name', 'email', 'phoneNo', 'password','confirm', 'permissions');
  foreach ($required as $f) {
    if(empty($_POST[$f])){
      $errors[] = 'You must fill out all fields.';
      break;
    }
  }

  if(strlen($password) < 6){
    $errors[]= 'Password must be at least 6 characters.';
  }

  if($password != $confirm){
    $errors[]= 'Your passwords do not match.';
  }

  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[]= 'You must enter a valid email address.';
  }



  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    //add user to database
    $hashed = password_hash($password,PASSWORD_DEFAULT);
    //$conn->query("INSERT INTO users (`full_name`,`email`,`password`,`permissions`) VALUES ('$name','$email','$hashed','$permissions')");
    $ssql = "INSERT INTO admin (`full_name`,`email`,`password`,`phoneNo`,`permissions`) VALUES ('$name','$email','$hashed','$phoneNo','$permissions')";
    $conn->query($ssql);
    $_SESSION['success_flash'] = 'User has been added';
    header('Location: /retlug/admin/users.php');
}
}
?>
<h2 class="text-center">Add new user</h2>
<hr>
<form action="users.php?add=1" method="post">
  <div class="form-group col-md-6">
    <label for="name">Name*:</label>
    <input type="text" id="name" name="name" class="form-control" value="<?=$name;?>"></input>
  </div>
  <div class="form-group col-md-6">
    <label for="email">Email*:</label>
    <input type="text" id="email" name="email" class="form-control" value="<?=$email;?>"></input>
  </div>
  <div class="form-group col-md-6">
    <label for="phoneNo">Phone No*:</label>
    <input type="text" id="phoneNo" name="phoneNo" class="form-control" value="<?=$phoneNo;?>"></input>
  </div>
  <div class="form-group col-md-6">
    <label for="password">Password*:</label>
    <input type="password" id="password" name="password" class="form-control" value="<?=$password;?>"></input>
  </div>
  <div class="form-group col-md-6">
    <label for="password">Confirm Password*:</label>
    <input type="password" id="confirm" name="confirm" class="form-control" value="<?=$confirm;?>"></input>
  </div>
  <div class="form-group col-md-6">
    <label for="permissions">Permissions*:</label>
    <select class="form-control" name="permissions">
      <option value=""<?=(($permissions == '')?' selected':'');?>></option>
      <option value="editor"<?=(($permissions == 'editor')?' selected':'');?>>Editor</option>
      <option value="admin,editor"<?=(($permissions == 'admin,editor')?' selected':'');?>>Admin</option>
    </select>
  </div>
  <div class="form-group col-md-6 text-right pull-right" style="margin-top:25px;">
    <a href="/retlug/admin/users.php" class="btn btn-default">Cancel</a>
    <input type="submit" value="Add User" class="btn btn-primary">
  </div>
</form>
<?php
}else{
$adminQuery = $conn->query("SELECT * FROM admin ORDER BY full_name");
?>

<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add new user</a>
<hr>
<table class="table table-bordered table-condensed table-stripped">
  <thead><th></th> <th>Name</th> <th>Email</th> <th>Phone No</th> <th>Join Date</th> <th>Last Login</th> <th>Permissions</th></thead>
  <tbody>
    <?php while($admin = mysqli_fetch_assoc($adminQuery)): ?>
      <tr>
        <td>
          <?php if($admin['admin_id'] != $admin_data['admin_id']): ?>
          <a href="users.php?delete=<?=$admin['admin_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span><a>
          <?php endif; ?>
        </td>
        <td><?=$admin['full_name'];?></td>
        <td><?=$admin['email'];?></td>
		<td><?=$admin['phoneNo'];?></td>
        <td><?=pretty_date($admin['join_date']);?></td>
        <td><?=(($admin['last_login'] == '1970-01-01 08:00:00')?'Never':pretty_date($admin['last_login']));?></td>
        <td><?=$admin['permissions'];?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
 <?php } include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
