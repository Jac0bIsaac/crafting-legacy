<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalRows = (isset($views['totalProducts']) && is_numeric($views['totalProducts'])) ? $views['totalProducts'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=products&action=newProduct&productId=0"
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
	<?php 
   }
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
					<?php  echo $totalRows; ?>
					Product<?php  echo ( $totalRows != 1 && $totalRows > 0 ) ? 's' : '' ?>
					in Total
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Product</th>
									<th>version</th>
									<th>Published</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = isset($views['position']) ? $views['position'] : 0;
									
								foreach ( $views['products'] as $product ) :
									
								$no++;
									
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									
									<td>
									 <?php echo htmlspecialchars($product['product_name']);  ?>
									</td>
									<td>
									<?php echo htmlspecialchars($product['product_version']); ?>
									</td>

									<td>
									<?php echo htmlspecialchars(makeDate($product['date_published'])); ?>
									</td>
									
									<td>
									<a href="index.php?module=products&action=editProduct&productId=<?php echo (int)$product['ID'];  ?>"
									 class="btn btn-primary" title="Edit"> <i
											class="fa fa-pencil fa-fw"></i> Edit</a></td>
									
									<td><a
										href="javascript:deleteProduct('<?php echo htmlspecialchars((int)$product['ID']); ?>', '<?php echo htmlspecialchars($product['product_name']);  ?>')"
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
						<span> <?php if ( $totalRows > 10) echo $views['pageLink']; ?>
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
  function deleteProduct(id, product)
  {
	  if (confirm("Are you sure want to delete '" + product + "'"))
	  {
	  	window.location.href = 'index.php?module=products&action=deleteProduct&productId=' + id;
	  }
  }
</script>