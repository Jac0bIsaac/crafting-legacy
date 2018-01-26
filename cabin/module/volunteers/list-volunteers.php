<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalVolunteers = (isset($views['totalRows']) && is_numeric($views['totalRows'])) ? $views['totalRows'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>

				<a href="index.php?module=volunteers&action=newVolunteer&volunteerId=0"
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
					<?php  echo $totalVolunteers; ?>
					 Volunteer<?php  echo ( $totalVolunteers != 1 ) ? 's' : ''?>
					in Total
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Email</th>
									<th>Level</th>
									<th>Edit</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php 

								$no = $views['position'];
								foreach ($views['volunteers'] as $volunteer) :

								$no++
								?>
								<tr>

									<td><?php echo $no; ?></td>
									<td><?php echo htmlspecialchars($volunteer['volunteer_firstName'] . " " . $volunteer['volunteer_lastName']); ?>
									</td>
									<td><?php echo htmlspecialchars($volunteer['volunteer_email']); ?>
									</td>
									<td><?php echo htmlspecialchars($volunteer['volunteer_level']); ?>
									</td>
								
									<td><a
										href="index.php?module=volunteers&action=editVolunteer&volunteerId=<?php echo htmlspecialchars($volunteer['ID']) ; ?>&sessionId=<?php echo htmlspecialchars($volunteer['volunteer_session']); ?>"
										class="btn btn-primary"> <i class="fa fa-pencil fa-fw"></i>
											Edit
									</a>
									</td>


									<td><a
										href="javascript:deleteVolunteer('<?php echo $volunteer['ID']; ?>', '<?php echo $volunteer['volunteer_firstName'] . $volunteer['volunteer_lastName']; ?>')"
										class="btn btn-danger"> <i class="fa fa-trash-o fa-fw"></i>
											Delete
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
						<?php if ($totalVolunteers > 10) echo $views['pageLink']; ?>
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
  function deleteVolunteer(id, name)
  {
	  if (confirm("Do you want to delete your volunteer  '" + name + "'"))
	  {
	  	window.location.href = 'index.php?module=volunteers&action=deleteVolunteer&volunteerId=' + id;
	  }
  }
</script>
