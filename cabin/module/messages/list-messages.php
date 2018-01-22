<?php  if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");

$totalRows = (isset($views['totalMessages'])) ? $views['totalMessages'] : '';

?>

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
   
   if (isset( $views['statusMessage'] ) ) { ?>

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
					<?php  echo htmlspecialchars($totalRows); ?>
					Message
					<?php  echo ( $totalRows != 1 && $totalRows > 0 ) ? 's' : ''?>
					in Total
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Sender</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Date</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = $views['position'];

								foreach ($views['messageListing'] as $message => $m ) :
								$no++;
								?>
								<tr>
									<td><?php echo htmlspecialchars($no); ?></td>
									<td><?php echo htmlspecialchars($m['sender']); ?>
									</td>
									<td>
									<a href="index.php?module=messages&action=replyMessage&messageId=<?php echo htmlspecialchars($m['ID']); ?>"><?php echo htmlspecialchars($m['email']); ?>
									</a>
									</td>
									<td><?php echo htmlspecialchars($m['phone']); ?></td>
									<td><?php echo htmlspecialchars(makeDate($m['date_sent'])); ?>
									</td>
									<td><a
										href="javascript:deleteMessage('<?php echo htmlspecialchars((int)$m['ID']); ?>', '<?php echo htmlspecialchars($m['sender']); ?> ')"
										title="delete" class="btn btn-danger"><i
											class="fa fa-trash-o fa-fw"></i> Delete </a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<!-- /table-responsive -->
					</div>

					<div class="pagination">
						<span> <?php if ( $totalRows > 10) echo $views['pageLink']; ?>
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
  function deleteMessage(id, sender)
  {
	  if (confirm("Are you sure want to delete message from '" + sender + "'"))
	  {
	  	window.location.href = 'index.php?module=messages&action=deleteMessage&messageId=' + id;
	  }
  }
</script>
