<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".myMenu" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Logo</a>
    </div>

    <div class="collapse navbar-collapse myMenu">
    <a href="/retlug/admin/index.php" class="navbar-brand"><?=SITE_NAME;?> Admin</a>
      <ul class="nav navbar-nav">
		<li><a href="/retlug/admin/index.php">Dashboard</a></li>
		<li><a href="/retlug/admin/search.php">Search</a></li>
		<li><a href="/retlug/admin/shipped.php">Shipped</a></li>
		<li><a href="/retlug/admin/brands.php">Brands</a></li>
        <li><a href="/retlug/admin/categories.php">Categories</a></li>
        <li><a href="/retlug/admin/products.php">Products</a></li>
        <li><a href="/retlug/admin/archived.php">Archived</a></li>
		<li><a href="/retlug/admin/sellers.php">Sellers</a></li>
        <?php if(has_permission('admin')): ?>
          <li><a href="/retlug/admin/users.php">Users</a></li>
        <?php endif; ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$admin_data['first'];?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
			<li><a href="/retlug/admin/edit_account.php">Edit Account</a></li>
            <li><a href="/retlug/admin/change_password.php">Change Password</a></li>
            <li><a href="/retlug/admin/logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid" style="padding-top:50px;">
