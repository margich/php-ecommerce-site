<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/retlug/core/init.php';
if(!is_logged_in_admin()){
  header('Location: /retlug/admin/login.php');
}
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/head.php';
include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/navigation.php';
$i =  1;
$sql = $conn->query("SELECT search, COUNT(search) AS Times FROM search GROUP BY search ORDER BY Times DESC Limit 12");

$sql2 = $conn->query("SELECT search, COUNT(search) AS Times FROM search
					WHERE search_date >= SUBDATE( CURRENT_DATE, INTERVAL 48 HOUR)
					GROUP BY search ORDER BY Times DESC Limit 12");
?>
<h2 class="text-center">Search Analytics</h2><hr>

<div class="col-md-4">
	<h3 class="text-center">Most Popular</h3>
	<table class="table table-bordered table-condensed table-stripped">
		<thead>
			<th></th><th>Most Popular</th><th>#</th>
		</thead>
		<tbody>
			<?php while($search = mysqli_fetch_assoc($sql)): ?>
			<tr>
				<td><?=$i;?></td>
				<td><?=ucfirst($search['search']);?></td>
				<td><?=$search['Times'];?></td>
			</tr>
			<?php $i++; endwhile;?>
		</tbody>
	</table>
</div>

<div class="col-md-4">
	<h3 class="text-center">By Month</h3>
	<table class="table table-bordered table-condensed table-stripped">
		<thead>
			<th>Month</th><th>Top Search</th><th>#</th>
		</thead>
		<tbody>
			<?php for($i = 1; $i <= 12; $i++): 
				$dt = DateTime::createFromFormat('!m',$i);

					$sql3 = $conn->query("SELECT search, COUNT(search) AS Times FROM search
							WHERE MONTH(search_date) = '$i'
							GROUP BY search ORDER BY Times DESC Limit 12");
							$search = mysqli_fetch_assoc($sql3);
			?>
				<tr<?=(date("m") == $i)?' class="info"':'';?>>
					<td><?=$dt->format("F");?></td>
					<td><?=ucfirst($search['search']);?></td>
					<td><?=$search['Times'];?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</div>

<div class="col-md-4">
	<h3 class="text-center">Trending</h3>
	<table class="table table-bordered table-condensed table-stripped">
		<thead>
			<th></th><th>Trending</th><th>#</th>
		</thead>
		<tbody>
			<?php $i = 1; while($search = mysqli_fetch_assoc($sql2)):?>
			<tr>
				<td><?=$i;?></td>
				<td><?=ucfirst($search['search']);?></td>
				<td><?=$search['Times'];?></td>
			</tr>
			<?php $i++; endwhile; ?>
		</tbody>
	</table>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/retlug/admin/includes/footer.php'; ?>
