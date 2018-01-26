<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php"); ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
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
									<th>#</th>
									<th>Username</th>
									<th>Fullname</th>
									<th>Email</th>
									<th>Level</th>
									<th>Edit</th>

								</tr>
							</thead>
							<tbody>

								<tr>
									<td></td>
									<td><?php if (isset($views['logIn'])) echo htmlspecialchars($views['logIn']); ?>
									</td>
									<td><?php if (isset($views['firstName']) && isset($views['lastName'])) echo htmlspecialchars($views['firstName']. ' ' .$views['lastName']); ?>
									</td>
									<td><?php if (isset($views['email'])) echo htmlspecialchars($views['email']); ?>
									</td>
									<td><?php if (isset($views['level'])) echo htmlspecialchars($views['level']); ?>
									</td>

									<td><a
										href="index.php?module=volunteers&action=editVolunteer&volunteerId=<?php if (isset($views['ID'])) echo $views['ID']; ?>&sessionId=<?php if (isset($views['token'])) echo $views['token']; ?>"
										class="btn btn-primary"> <i class="fa fa-pencil fa-fw"></i>
											Edit
									</a>
									</td>

								</tr>

							</tbody>
						</table>
						<!-- /table-responsive -->

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