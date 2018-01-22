<?php
if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<?php if (isset($views['siteName']) && empty($views['siteName'])) : ?>
				<a href="index.php?module=configurations&action=setConfig"
					class="btn btn-outline btn-success"><i class="fa fa-wrench fa-fw"></i>
					Setting </a>
				<?php  endif; ?>
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

				<div class="panel-heading"></div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Site Name</th>
									<th>Description</th>
									<th>Tagline</th>
									<th>Logo</th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody>
								<?php 
			
								foreach ($views['configs'] as $option):
									
								?>
								<tr>

									<td><?php echo htmlspecialchars($option['site_name']); ?>
									</td>
									<td><?php echo htmlspecialchars($option['meta_description']); ?>
									</td>
									<td><?php echo htmlspecialchars($option['tagline']); ?>
									</td>

									<?php 
									//set up images
									$logo = '../files/picture/photo/' . $option['logo'];

									if (!is_file($logo)) :

									$logo = '../files/picture/photo/nophoto.jpg';
									endif;


									?>
									<td><a href="<?php echo $logo; ?>"><img
											alt="<?php echo $option['site_name']; ?>"
											src="<?php echo $logo; ?>"> </a>
									</td>

									<td><a
										href="index.php?module=configurations&action=editConfig&configId=<?=$option['config_id']; ?>"
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>
								</tr>

								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->

</div>
<!-- /#page-wrapper -->