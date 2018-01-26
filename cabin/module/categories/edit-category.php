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

<form method="post" action="index.php?module=categories&action=<?php echo $views['formAction']; ?>&categoryId=<?php if (isset($views['categoryID'])) { echo (int)$views['categoryID']; }
  else { echo (int)'0'; } ?>"
								role="form">
	<input type="hidden" name="category_id"
									value="<?php if (isset($views['categoryID'])) echo htmlspecialchars((int)$views['categoryID']); ?>">
					
					<!-- title -->
								<div class="form-group">
									<label>*Title</label> <input type="text"
										name="title" class="form-control"
										value="<?php if (isset($views['category_title'])) echo  htmlspecialchars($views['category_title']); ?>">
								</div>

								
								<!-- Actived -->
								<?php if (isset($views['status']) && $views['status'] != '') { ?>
								<div class="form-group">
									<label>*Actived</label> <label class="radio-inline"> <input
										type="radio" name="status" id="optionsRadiosInline1" value="Y"
										<?php if (isset($views['status']) && $views['status'] == 'Y') echo 'checked="checked"'; ?>>
										Ya
									</label> <label class="radio-inline"> <input type="radio"
										name="status" id="optionsRadiosInline1" value="N"
										<?php if (isset($views['status']) && $views['status'] == 'N') echo 'checked="checked"'; ?>>
										Tidak
									</label>
								</div>
								
								<?php } ?>
								
								<input type="submit" class="btn btn-primary" name="submit"
									value="Save" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Cancel</button>

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
