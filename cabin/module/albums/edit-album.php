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
	<script type="text/javascript">function leave() {  window.location = "index.php?module=albums&action=newAlbum&albumId=0";} setTimeout("leave()", 5000);</script>
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
						<div class="col-lg-6">

<form method="post" action="index.php?module=albums&action=<?php echo $views['formAction']; ?>&albumId=<?php if (isset($views['albumID'])) { echo (int)$views['albumID']; } 
else { echo (int)'0'; } ?>" onsubmit="return ValidateImage(this);" role="form" enctype="multipart/form-data">
<input type="hidden" name="album_id" value="<?php if (isset($views['albumID'])) echo htmlspecialchars($views['albumID']); ?> " />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />

								<!-- Album Title-->
								<div class="form-group">
	<label>*Title</label> 
	<input type="text" class="form-control" placeholder="album's title" name="title"
value="<?php if (isset($views['album_title'])) echo htmlspecialchars($views['album_title']); ?>"
										required>
								</div>

													
								<!-- Album Picture -->
								<?php if (isset($views['album_picture'])) { ?>
								<div class="form-group">
									<label>Your current picture :</label>
								<?php 
								 $image_thumb = '../files/picture/album/'.$views['album_picture'];
								 
								 if (is_null($views['album_picture']) || !is_file($image_thumb)) :
								 
								 $image_thumb = '../files/picture/nophoto.jpg';
								 
								 endif;
								?>
									
							     <br>
							     <img alt="" src="<?php echo $image_thumb; ?>" />
							    
							    </div>
							    
							    <?php } ?>
								
								<div class="form-group">
									<label><?php if (isset($views['album_picture'])) { echo 'Change picture '; } else { echo  'Upload picture '; } ?>: </label> 
									<input type="file" name="image" id="file"
										accept="image/*" onchange="loadFile(event)"  maxlength="512" />
										<img id="output" />
									<p class="help-block">*maximum size : <?php echo formatSizeUnits(524867); ?></p>
									<div id="NotOk"></div>
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