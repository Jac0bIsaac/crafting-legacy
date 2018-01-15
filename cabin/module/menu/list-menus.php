<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalMenus = (isset($views['totalMenus']) && is_numeric($views['totalMenus'])) ? $views['totalMenus'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=menus&action=newMenu&menuId=0"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Add New 
				</a>
			</h1>

		</div>
		<!-- /.col-lg-12 -->

	</div>
	
	<!-- /.row -->

	<?php 
   if (isset($views['errorMessage'])) { ?>

	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	</div>

	<?php }?>
	<?php 
   if ( isset( $views['statusMessage'] ) ) { ?>

	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['statusMessage']; ?>
	</div>

	<?php }?>


	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">

				<div class="panel-heading">
					<?php  echo $totalMenus; ?>
					Menu<?php  echo ( $totalMenus != 1 && $totalMenus > 0 ) ? 's' : '' ?>
					in Total
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Link</th>
									<th>Parent</th>
									<th>Edit</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
									
								foreach ( $views['menus'] as $menu) :
								$no++;
								?>
								<tr>
									<td><?php echo $no; ?></td>
									
									<td>
									<?php echo htmlspecialchars($menu['title']); ?>
									</td>
									
									<td><?php echo htmlspecialchars($menu['link']); ?></td>
									
									<td>
									<?php 
									 echo $menu_parent = $menus -> findParentMenu($menu['parent']);
									?>
									</td>
									
									<td><a
										href="index.php?module=menus&action=editMenu&menuId=<?php echo $menu['ID']; ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

								<td><a
										href="javascript:deleteMenu('<?php echo $menu['ID']; ?>', '<?php echo htmlspecialchars($menu['title']);  ?>')"
										title="Delete" class="btn btn-danger"> <i class="fa fa-trash-o fa-fw"></i> Delete
									</a>
									</td>

								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /table-responsive -->
					<div class="pagination">
						<span> 
						<?php if ( $totalMenus > 10) echo $views['pageLink']; ?>
						</span>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- #page-wrapper -->
<script type="text/javascript">
  function deleteMenu(id, menu)
  {
	  if (confirm("Do you want to delete your menu '" + menu + "' ?"))
	  {
	  	window.location.href = 'index.php?module=menus&action=deleteMenu&menuId=' + id;
	  }
  }
</script>