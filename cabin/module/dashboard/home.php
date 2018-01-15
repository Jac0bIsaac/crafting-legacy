<?php
if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->

<!-- /.row kolom kotak xs-9 -->
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-image fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
		<?php if (isset($views['totalPhotos']) && $views['totalPhotos'] >= 0) { ?>
		<div class="huge">
		 <?= htmlspecialchars($views['totalPhotos']); ?>
		</div>
		<div>
		   Photo<?php echo ($views['totalPhotos'] != 1 && $views['totalPhotos'] > 0 ) ? 's' : ''; ?>
		</div>
		
		<?php } ?>
						</div>
					</div>
				</div>
				<a <?= ($views['totalPhotos'] == 0 ) ? 'href="#"' : 'href="?module=photos"'; ?>>
					<div class="panel-footer">
					 <?php if ($views['totalPhotos'] > 0) { ?>
					 
					 <span class="pull-left">View Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
					 
					 <?php } else { ?>
					 
					 <span class="pull-left">No photo</span> <span
							class="pull-right"> <i class="fa fa-exclamation-circle"></i>
						</span>
						<div class="clearfix"></div>
					 
					 <?php } ?>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-green">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-pencil fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
						<?php if (isset($views['totalPosts']) && $views['totalPosts'] >= 0) { ?>
						
		<div class="huge">
		 <?= htmlspecialchars($views['totalPosts']); ?>
		</div>
		<div>
		   Post<?php echo ($views['totalPosts'] != 1 && $views['totalPosts'] > 0 ) ? 's' : ''; ?>
		</div>
						<?php } ?>
						</div>
					</div>
				</div>
				
				<a <?= ($views['totalPosts'] == 0 ) ? 'href="#"' : 'href="?module=posts"'; ?>>
				
					<div class="panel-footer">
					<?php if ($views['totalPosts'] > 0) { ?>
					 
					 <span class="pull-left">View Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
					 
					 <?php } else { ?>
					 
					 <span class="pull-left">No post</span> <span
							class="pull-right"> <i class="fa fa-exclamation-circle"></i>
						</span>
						<div class="clearfix"></div>
					 <?php } ?>
					</div>
				</a>
			</div>
		</div>
		<!-- panel yellow -->
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-yellow">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-calendar fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
					<?php if (isset($views['totalEvents']) && $views['totalEvents'] >= 0) { ?>
					<div class="huge">
		 <?= htmlspecialchars($views['totalEvents']); ?>
		</div>
		<div>
		   Event<?php echo ($views['totalEvents'] != 1 && $views['totalEvents'] > 0 ) ? 's' : ''; ?>
		</div>
					
					<?php } ?>
						</div>
					</div>
				</div>
				<a <?= ($views['totalEvents'] == 0 ) ? 'href="#"' : 'href="?module=events"'; ?>>
					<div class="panel-footer">
					<?php if ($views['totalEvents'] > 0) { ?>
					 
					 <span class="pull-left">View Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
					 
					 <?php } else { ?>
					 
					 <span class="pull-left">No Event</span> <span
							class="pull-right"> <i class="fa fa-exclamation-circle"></i>
						</span>
						<div class="clearfix"></div>
					 <?php } ?>
					</div>
				</a>
			</div>
		</div>

		<!-- Panel Merah -->
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-red">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-envelope fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<?php if (isset($views['totalMessages']) && $views['totalMessages'] >= 0) {?>
							<div class="huge">
		 <?= htmlspecialchars($views['totalMessages']); ?>
		</div>
		<div>
		   Message<?php echo ($views['totalMessages'] != 1 && $views['totalMessages'] > 0 ) ? 's' : ''; ?>
		</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<a <?= ($views['totalMessages'] == 0 ) ? 'href="#"' : 'href="?module=messages"'; ?>>
					<div class="panel-footer">
				<?php if ($views['totalMessages'] > 0) { ?>
					 
					 <span class="pull-left">View Details</span> <span class="pull-right"> <i
							class="fa fa-arrow-circle-right"></i>
						</span>
						<div class="clearfix"></div>
					 
					 <?php } else { ?>
					 
					 <span class="pull-left">No Message</span> <span
							class="pull-right"> <i class="fa fa-exclamation-circle"></i>
						</span>
						<div class="clearfix"></div>
					 <?php } ?>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">

	</div>
	<!-- /.row -->
	
	<div class="row">
	  <!-- /.col-lg-6 -->
	        
	      
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->