<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php"); ?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
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
	<script type="text/javascript">function leave() {  window.location = "index.php?module=photos&action=newPhoto&photoId=0";} setTimeout("leave()", 5000);</script>
	</div>
	
	<?php } else { ?>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">

<form method="post" onsubmit="return ValidateImage(this);" action="index.php?module=photos&action=<?php echo $views['formAction']; ?>&photoId=<?php if (isset($views['photoID'])) { echo (int)$views['photoID']; } 
	else { echo (int)'0'; }?>" role="form" enctype="multipart/form-data">
<input type="hidden" name="photo_id" value="<?php if (isset($views['photoID'])) echo htmlspecialchars((int)$views['photoID']); ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />
							
								<!-- photo Title-->
<div class="form-group">
							<label>*Title :</label> 
<input type="text" class="form-control" placeholder="photo's title" name="title"
										value="<?php if (isset($views['photo_title'])) echo htmlspecialchars($views['photo_title']); ?>"
										required>
</div>

<!-- photo Picture -->
<?php 
	if (!empty($views['picture'])) :
?>
								<div class="form-group">
								  <label>Your current photo :</label>
								<?php 
									
								$image = '../files/picture/photo/'.$views['picture'];
								$imageThumb = '../files/picture/photo/thumb/thumb_' . $views['picture'];

									if (!is_readable($imageThumb)) :

									$imageThumb = '../files/picture/photo/nophoto.jpg';

									endif;

									if (is_readable($image)) :

									?>
									<br> 
		<a href="<?php echo $image; ?>"><img src="<?php  echo $imageThumb; ?>"></a><br>
		<label>change picture</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
									
		<?php else : ?>

	<br><img src="<?php echo $imageThumb; ?>"> <br> 
	<label>change picture</label> 
	<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
	<img id="output" />
    <p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>								
<?php endif; ?>
</div>
								
<?php  else : ?>
<div class="form-group">
<label>*Upload Picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
								</div>
								<?php endif; ?>
								
								<!-- Album -->
								<div class="form-group">
									<?php if (isset($views['albumDropDown'])) echo $views['albumDropDown']; ?>
								</div>
								
								<!-- Description -->
								<div class="form-group">
									<label>Description</label>
									<textarea class="form-control" id="sc" name="description" rows="3"
										required maxlength="500">
										<?php if (isset($views['photo_desc'])) echo $views['photo_desc']; ?>
									</textarea>
								</div>
								
								<input type="submit" class="btn btn-primary" name="submit"
									value="Save" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Cancel</button>

							</form>
						</div>
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-6"></div>
						<!-- /.col-lg-6 (nested) -->
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php } ?>
	<!-- /.row -->
</div>
<script type="text/javascript">
  var loadFile = function(event) {
	    var output = document.getElementById('output');
	    output.src = URL.createObjectURL(event.target.files[0]);
	  };
  </script>
<!-- #Page-Wrapper -->