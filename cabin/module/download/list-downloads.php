<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalFiles = (isset($views['totalFiles']) && is_numeric($views['totalFiles'])) ? $views['totalFiles'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=files&action=newFile&fileId=0"
					class="btn btn-outline btn-success"> <i
					class="fa fa-plus-circle fa-fw"></i> Add New
				</a>
			</h1>

		</div>
		<!-- /.col-lg-12 -->


	</div>
	<!-- #row -->
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
					<?php echo htmlspecialchars($totalFiles); ?>
					File
					<?php echo ( $totalFiles != 1 ) ? 's' : '' ?>
					in total.
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Title</th>
									<th>Filename</th>
									<th>Uploaded</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
								foreach ($views['files'] as $file) {

                              $no++;
                              
                              ?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($file['file_title']); ?>
									</td>
									<td><?php echo htmlspecialchars($file['file_name']); ?>
									</td>
									<td><?php echo htmlspecialchars(makeDate($file['file_uploaded']));  ?>
									</td>

									<td><a
										href="index.php?module=files&action=editFile&fileId=<?php echo htmlspecialchars((int)$file['fileID']); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteFile('<?php echo htmlspecialchars((int)$file['fileID']); ?>', '<?php echo htmlspecialchars($file['file_title']); ?>')"
										title="Delete" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Delete
									</a>
									</td>

								</tr>

								<?php } ?>
								
							</tbody>
							
						</table>
						
					</div>
					<!-- /table-responsive -->
					<div class="pagination">
						<span> <?php if ($totalFiles > 10) echo $views['pageLink']; ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
  function deleteFile(id, file)
  {
	  if (confirm("Are you want to delete  '" + file + "'"))
	  {
	  	window.location.href = 'index.php?module=files&action=deleteFile&fileId=' + id;
	  }
  }
</script>
