<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalCategories = (isset($views['totalCategories']) && is_numeric($views['totalCategories'])) ? $views['totalCategories'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=categories&action=newCategory&categoryId=0"
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
					<?= $totalCategories; ?>
					Categor<?= ( $totalCategories != 1 && $totalCategories > 0 ) ? 'ies' : 'y' ?>
					in Total
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Category</th>
									<th>Actived</th>
									<th>Edit</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
									
								foreach ( $views['categories'] as $category) :
								$no++;
								?>
								<tr>
									<td><?php echo $no; ?></td>
									<td><?php echo htmlspecialchars($category['category_title']); ?>
									</td>
									
									<td><?php echo htmlspecialchars($category['status']); ?>
									</td>

									<td><a
										href="index.php?module=categories&action=editCategory&categoryId=<?php echo (int)$category['categoryID']; ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit</a>
									</td>

								<td><a
										href="javascript:delCategory('<?php echo htmlspecialchars((int)$category['categoryID']); ?>', '<?php echo htmlspecialchars($category['category_title']);  ?>')"
										title="Delete" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Delete
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
						<?php if ( $totalCategories > 10) echo $views['pageLink']; ?>
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
  function delCategory(id, category)
  {
	  if (confirm("Do you want to delete your category '" + category + "' ?"))
	  {
	  	window.location.href = 'index.php?module=categories&action=deleteCategory&categoryId=' + id;
	  }
  }
</script>