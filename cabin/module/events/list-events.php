<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
$totalEvents = (isset($views['totalEvents']) && is_numeric($views['totalEvents'])) ? $views['totalEvents'] : '';
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">

			<h1 class="page-header">
				<?php echo $views['pageTitle']; ?>
				<a href="index.php?module=events&action=newEvent&eventId=0"
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
					<?php  echo $totalEvents; ?>
					Event<?php  echo ( $totalEvents != 1 ) ? '' : 's' ?>
					in Total
				</div>
				<!-- /.panel-heading -->

				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Event</th>
									<th>Date</th>
									<th>Time</th>
									<th>Location</th>
									<th>Edit</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];
									
								foreach ( $views['events'] as $event) :
								$no++;
								?>
								<tr>
									<td><?php echo $no; ?></td>
									<td><?php echo $event['name']; ?>
									</td>
									
									<td><?= htmlspecialchars(makeDate($event['start_date'])); ?>
									</td>
									
									<td><?= date("g:i a", strtotime($event['time_started'])) . ' - ' . date("g:i a", strtotime($event['time_ended'])); ?></td>
									
									<td><?= htmlspecialchars($event['location']); ?>
									</td>

									<td><a
										href="index.php?module=events&action=editEvent&eventId=<?php echo htmlspecialchars((int)$event['event_id']); ?> "
										title="Edit" class="btn btn-primary"> <i
											class="fa fa-pencil fa-fw"></i> Edit
									</a>
									</td>

									<td><a
										href="javascript:deleteEvent('<?php echo htmlspecialchars((int)$event['event_id']); ?>', '<?php echo htmlspecialchars($event['name']);  ?>')"
										title="Hapus" class="btn btn-danger"> <i
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
						<?php if ($totalEvents > 10) echo $views['pageLink']; ?>
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
  function deleteEvent(id, event)
  {
	  if (confirm("Do you want to delete your event '" + event + "' ?"))
	  {
	  	window.location.href = 'index.php?module=events&action=deleteEvent&eventId=' + id;
	  }
  }
</script>