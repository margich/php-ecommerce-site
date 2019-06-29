<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();
?>
    <?php
      if($_POST){
        //form validation
        if(empty($_POST['email']) || empty($_POST['password'])){
          $errors[] = 'Email and password required.';
        }
        //validate email
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
          $errors[] = 'You must enter a valid email.';
        }
        //check if password is more than 6 characters
        if(strlen($password) < 6){
          $errors[] = 'Password must be at least 6 characters.';
        }


        //check if user exists in database
        $query = $conn->query("SELECT * FROM admin WHERE email = '$email'");
        $admin = mysqli_fetch_assoc($query);
        $adminCount = mysqli_num_rows($query);
        if($adminCount < 1){
          $errors[] = 'That email does not exist in our database.';
        }

        if(!(password_verify($password,$admin['password']))){
          $errors[] = 'The password does not match that user name.';
        }

        //check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        }else {
          //log user in
          $admin_id = $admin['admin_id'];
          loginAdmin($admin_id);
        }
      }

    ?>

	
	
<div class="container">
<div class="" style="margin: 0 auto; max-width: 600px;">
  <h2 class="text-center">Admin Login</h2><hr>
    <form action="/retlug/admin/login.php" method="post">
      <div class="form-group">
        <input type="email" name="email" placeholder="enter username" id="email" class="form-control" value="<?=$email;?>" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="enter password" id="password" class="form-control" value="<?=$password;?>" required>
      </div>
      <div class="form-group" >
        <input type="submit" class="btn btn-primary" id="btn-login" value="Login">
      </div>
      <div class="form-group">
        <p class="text-right"><a href="/retlug/index.php" alt="home">Visit Site</a></p>
      </div>
    </form>
</div>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
