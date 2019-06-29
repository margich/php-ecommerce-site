<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';

$archived_pro = "SELECT * FROM products WHERE archived = 1";
$archived_res= $conn->query($archived_pro);

//restore products
if(isset($_GET['restore'])){
  $restore_id = (int)$_GET['restore'];
  $conn->query("UPDATE products SET archived = 0 WHERE product_id = '$restore_id'");
  header('Location: archived.php');
}
?>
<h2 class="text-center">Archived Products</h2>
<table class="table table-bordered table-condensed table-stripped">
  <thead>
    <th></th> <th>Product</th> <th>Price</th> <th>Category</th> <th>Sold</th>
  </thead>
  </tbody>
  <?php while($archived = mysqli_fetch_assoc($archived_res)):
    $child_id = $archived['category_id'];
    $childQ = "SELECT * FROM categories WHERE category_id = '$child_id'";
    $childCat= $conn->query($childQ);
    $child = mysqli_fetch_assoc($childCat);
    $parent_id = $child['parent'];
    $parentQ = "SELECT * FROM categories WHERE category_id = '$parent_id'";
    $parentCat= $conn->query($parentQ);
    $parent = mysqli_fetch_assoc($parentCat);
    $category = $parent['category'].'~'.$child['category'];
    ?>
    <tr>
      <td><a href="archived.php?restore=<?=$archived['product_id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a></td>
      <td><?=$archived['title'];?></td> <td><?=money($archived['price']);?></td> <td><?=$category;?></td> <td>0</td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>


<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
