<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalAlbums = (isset($views['totalAlbums']) && is_numeric($views['totalAlbums'])) ? $views['totalAlbums'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=albums&action=newAlbum&albumId=0"
					title="add new album" class="btn btn-outline btn-success"> <i
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
					<?php  echo $totalAlbums; ?>
					Album<?php  echo ( $totalAlbums != 1 && $totalAlbums > 0 ) ? 's' : '' ?>
					in Total
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Picture</th>
									<th>Title</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];

								foreach ( $views['albums'] as $album) :

								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<?php 
									//set up Image
									$image = '../files/picture/album/' . $album['album_picture'];
									
									if (!is_file($image))
									{
										$image = '../files/picture/album/nophoto.jpg';
									}

								
									?>
									<td><a href="<?php echo $image; ?>">
									<img alt="<?php echo $album['album_title']; ?>" src="<?php echo $image; ?>"> </a>
									</td>
									
									<td><?php echo $album['album_title']; ?></td>
									
									
									<td><a
										href="index.php?module=albums&action=editAlbum&albumId=<?php echo htmlspecialchars((int)$album['albumID']); ?>"
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteAlbum('<?php echo htmlspecialchars((int)$album['albumID']); ?>', '<?php echo htmlspecialchars($album['album_title']);  ?>')"
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
						<?php if ( $totalAlbums > 10) echo $views['pageLink']; ?>
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
  function deleteAlbum(id, album)
  {
	  if (confirm("Do you want to delete your album '" + album + "'"))
	  {
	  	window.location.href = 'index.php?module=albums&action=deleteAlbum&albumId=' + id;
	  }
  }
</script>
