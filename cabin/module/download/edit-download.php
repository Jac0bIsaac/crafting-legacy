<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php"); ?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php  echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php 
	if (isset($views['errorMessage'])) { ?>

	 <div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	<script type="text/javascript">function leave() {  window.location = "index.php?module=files&action=newFile&fileId=0";} setTimeout("leave()", 5000);</script>
	</div>

	<?php } else { ?>
	
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php  echo $views['pageTitle']; ?>
				</div>
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
<form method="post" action="index.php?module=files&action=<?php echo $views['formAction']; ?>&fileId=<?php if (isset($views['fileID'])) { echo (int)$views['fileID']; } 
 else { echo (int)'0'; } ?>" role="form" enctype="multipart/form-data">

								<input type="hidden" name="file_id"
									value="<?php if (isset($views['fileID'])) echo htmlspecialchars((int)$file['fileID']); ?>">
								<!-- title -->
								<div class="form-group">
									<label>*Title</label> <input type="text" name="title" class="form-control" placeholder="Title"
										value="<?php if (isset($views['file_title'])) echo htmlspecialchars($views['file_title']); ?>" required>
								</div>

								<!-- File Berkas -->
								<?php if (!empty($views['file_name'])) { ?>
								<div class="form-group">
									<label>File</label> <input type="text" class="form-control"
										value="<?php if (isset($views['file_name'])) echo htmlspecialchars($views['file_name']); ?>"
										disabled>
								</div>

								<?php } ?>
								<!-- Upload berkas -->
								<div class="form-group">
									<label>*Upload new file</label> <input type="file"
										name="fdoc">
								</div>

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

	<?php } ?>
</div>
<!-- #page-wrapper -->
