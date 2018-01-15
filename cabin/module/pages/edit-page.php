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

	<div class="alert alert-danger ">
		<h4>Error!</h4>
		<p>
			<?php echo $views['errorMessage']; ?>
			<button type="button" class="btn btn-danger"
				onClick="self.history.back();">Repeat</button>
		</p>
	</div>

	<?php } ?>

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

<form method="post" action="index.php?module=pages&action=<?php echo $views['formAction']; ?>&pageId=<?php if (isset($views['page_id'])) { echo (int)$views['page_id']; } 
 else { echo (int)'0'; }?>" role="form" enctype="multipart/form-data">

<input type="hidden" name="page_id" value="<?php if (isset($views['page_id'])) echo htmlspecialchars($views['page_id']); ?>">
<input type="hidden" name="type" value="<?php if (isset($views['type'])) echo htmlspecialchars($views['type'] );  ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />
								
								<!-- post title -->
								<div class="form-group">
								 <label>*Title</label>
								 <input type="text" name="title" class="form-control" placeholder="Title" 
								 value="<?php if (isset($views['title'])) echo $views['title']; ?>" >
								</div>

							
								<!-- photo Picture -->
								<?php 
								if (!empty($views['picture'])) {
								?>
								<div class="form-group">
								<?php 
									
								   $image = '../files/picture/photo/thumb/thumb_' . $views['picture'];

							
									if (!is_file($image)) :

									$image_thumb = '../files/picture/photo/nophoto.jpg';

									endif;

									if (is_file($image)) :

									?>
									<br> <a href="<?php echo $image; ?>"><img
										src="<?php  echo $image; ?>"> </a> <br> <label>change picture</label> 
							<input type="file" name="image" id="file"
										accept="image/*" onchange="loadFile(event)"  maxlength="512" />
										<img id="output" />
								<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
									<?php else : ?>

									<br> <img src="<?php echo $image; ?>"> <br> <label>change picture</label> 
						<input type="file" name="image" id="file"
										accept="image/*" onchange="loadFile(event)"  maxlength="512" />
										<img id="output" />
								<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
									<?php endif; ?>

								</div>
								<?php } else { ?>
								<div class="form-group">
									<label>*Upload Picture</label> 
			<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
										<img id="output" />
						<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
								</div>
								<?php } ?>
								
								<!-- description -->
								<div class="form-group">
									<label>*Content</label>
		<textarea class="form-control" id="sc" name="content" rows="10" maxlength="100000">
										<?php if (isset($views['content'])) echo $views['content'];  ?> 
									</textarea>
								</div>
								
								<!-- post status -->
								<div class="form-group">
									<?php if (isset($views['post_setting'])) echo $views['post_setting']; ?>
								</div>

								<!-- Comment status -->
								<div class="form-group">
									<?php if (isset($views['comment_setting'])) echo $views['comment_setting']; ?>
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

	<!-- /.row -->
</div>
<script type="text/javascript">
  var loadFile = function(event) {
	    var output = document.getElementById('output');
	    output.src = URL.createObjectURL(event.target.files[0]);
	  };
</script>
<!-- #Page-Wrapper -->