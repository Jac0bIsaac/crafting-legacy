<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalPhotos = (isset($views['totalPhotos']) && is_numeric($views['totalPhotos'])) ? $views['totalPhotos'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=photos&action=newPhoto&photoId=0"
					title="add new photo" class="btn btn-outline btn-success"> <i
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
					<?php  echo $totalPhotos; ?>
					photo<?php  echo ( $totalPhotos != 1 && $totalPhotos > 0) ? 's' : '' ?>
					in Total
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Photo</th>
									<th>Title</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];

								foreach ( $views['photos'] as $photo) :

								
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<?php 
									//set up Image
									$image = '../files/picture/photo/' . $photo['photo_filename'];
									if (!is_file($image))
									{
										$image = '../files/picture/photo/nophoto.jpg';
									}

									$image_thumb = '../files/picture/photo/thumb/thumb_' . $photo['photo_filename'];
									if (!is_file($image_thumb))
									{
										$image_thumb = '../files/picture/photo/thumb/nophoto.jpg';
									}
									?>
									<td><a href="<?php echo $image; ?>"><img alt=""
											src="<?php echo $image_thumb; ?>"> </a>
									</td>
									
									<td><?php echo $photo['photo_title']; ?></td>
									
									
									<td><a
										href="index.php?module=photos&action=editPhoto&photoId=<?php echo (int)$photo['photoID']; ?>"
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deletePhoto('<?php echo htmlspecialchars((int)$photo['photoID']); ?>', '<?php echo htmlspecialchars($photo['photo_title']);  ?>')"
										title="Delete" class="btn btn-danger"> <i
											class="fa fa-trash-o fa-fw"></i> Delete
									</a>
									</td>
								</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
						<!-- /table-responsive -->
					</div>

					<div class="pagination">
						<span> 
						<?php if ( $totalPhotos > 10) echo $views['pageLink']; ?>
						</span>
					</div>

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
  function deletePhoto(id, photo)
  {
	  if (confirm("Do you want to delete your photo '" + photo + "'?"))
	  {
	  	window.location.href = 'index.php?module=photos&action=deletePhoto&photoId=' + id;
	  }
  }
</script>