<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalRows = (isset($views['totalPages']) && is_numeric($views['totalPages'])) ? $views['totalPages'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=pages&action=newPage&pageId=0"
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
					Page<?php  echo ( $totalRows != 1 && $totalRows > 0 ) ? 's' : '' ?>
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
									<th>Published</th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = isset($views['position']) ? $views['position'] : 0;
									
								foreach ( $views['pages'] as $page ) :
									
								$no++;
									
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									
									<td>
									 <?php echo htmlspecialchars($page['post_title']);  ?>
									</td>
									

									<td>
									<?php echo htmlspecialchars(makeDate($page['date_created'])); ?>
									</td>
									
									<td>
									<a href="index.php?module=pages&action=editPage&pageId=<?php echo (int)$page['postID'];  ?>"
									 class="btn btn-primary" title="Edit"> <i
											class="fa fa-pencil fa-fw"></i> Edit</a></td>
											
									<td><a
										href="javascript:deletePage('<?php echo htmlspecialchars((int)$page['postID']); ?>', '<?php echo htmlspecialchars($page['post_title']);  ?>')"
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
  function deletePage(id, page)
  {
	  if (confirm("Are you sure want to delete '" + page + "'"))
	  {
	  	window.location.href = 'index.php?module=pages&action=deletePage&pageId=' + id;
	  }
  }
</script>