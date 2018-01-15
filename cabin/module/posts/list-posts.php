<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalRows = (isset($views['totalPosts']) && is_numeric($views['totalPosts'])) ? $views['totalPosts'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=posts&action=newPost&postId=0"
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
					Post<?php  echo ( $totalRows != 1 && $totalRows > 0 ) ? 's' : '' ?>
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
									<th>Author</th>
									<th>Published</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = isset($views['position']) ? $views['position'] : 0;
									
								foreach ( $views['posts'] as $post ) :
									
								$no++;
									
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									
									<td>
									 <?php echo htmlspecialchars($post['post_title']);  ?>
									</td>
									<td>
									<?php echo htmlspecialchars($post['volunteer_login']); ?>
									</td>

									<td>
									<?php echo htmlspecialchars(makeDate($post['date_created'])); ?>
									</td>
									
									<td>
									<a href="index.php?module=posts&action=editPost&postId=<?php echo (int)$post['postID'];  ?>"
									 class="btn btn-primary" title="Edit"> <i
											class="fa fa-pencil fa-fw"></i> Edit</a></td>
									
									<td><a
										href="javascript:deletePost('<?php echo htmlspecialchars((int)$post['postID']); ?>', '<?php echo htmlspecialchars($post['post_title']);  ?>')"
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
  function deletePost(id, post)
  {
	  if (confirm("Are you sure want to delete '" + post + "'"))
	  {
	  	window.location.href = 'index.php?module=posts&action=deletePost&postId=' + id;
	  }
  }
</script>