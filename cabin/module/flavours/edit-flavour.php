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
	
 <?php 
 
	if (isset($views['errorMessage'])) { ?>

   <div class="alert alert-danger ">
		<h4>Error!</h4>
		<p>
			<?php echo $views['errorMessage']; ?>
			<button type="button" class="btn btn-danger"
				onClick="self.history.back();">Repeat</button>
		</p>
	</div>
	
	<?php } else { ?>


	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>
				<!-- #panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">

<form method="post" action="index.php?module=flavours&action=<?php echo $views['formAction']; ?>&flavourId=<?php if (isset($views['flavourId'])) { echo (int)$views['flavourId']; }
  else { echo (int)'0'; } ?>" role="form">
	<input type="hidden" name="flavour_id" value="<?php if (isset($views['flavourId'])) echo htmlspecialchars((int)$views['flavourId']); ?>">
					
<!-- title -->
	<div class="form-group">
		<label>*Title</label> 
		<input type="text" name="title" class="form-control" value="<?php if (isset($views['flavour_title'])) echo  htmlspecialchars($views['flavour_title']); ?>">
	</div>
					
<input type="submit" class="btn btn-primary" name="submit" value="Save" />

<button type="button" class="btn btn-danger" onClick="self.history.back();">Cancel</button>

</form>				
</div>
</div>
				</div>
			</div>
		</div>
	</div>

	<?php }?>
</div>
<!-- /#page-wrapper -->