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
	if (isset($views['errorMessage'])) : ?>

    <div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	<script type="text/javascript">function leave() {  window.location = "index.php?module=posts&action=newPost&postId=0";} setTimeout("leave()", 5000);</script>
	
	</div>

	<?php  endif; ?>

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

<form method="post" action="index.php?module=products&action=<?php echo $views['formAction']; ?>&productId=<?php if (isset($views['product_id'])) { echo (int)$views['product_id']; } 
 else { echo (int)'0'; } ?> " role="form" enctype="multipart/form-data">

<input type="hidden" name="post_id" value="<?php if (isset($views['product_id'])) echo htmlspecialchars($views['product_id']); ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />

<!-- product name -->
<div class="form-group">
<label>*Name :</label> 
<input type="text" name="name" class="form-control" placeholder="Product name"
value="<?php if (isset($views['name'])) : echo $views['name'];  endif; ?>" required>
</div>

<div class="form-group">
<label>*Version :</label> 
<input type="text" name="version" class="form-control" placeholder="Product version"
value="<?php if (isset($views['version'])) : echo $views['version'];  endif; ?>" required>
</div>
<div class="form-group">
<label>*Module :</label> 
<input type="text" name="module" class="form-control" placeholder="Features or Modules"
value="<?php if (isset($views['module'])) : echo $views['module'];  endif; ?>" required>
</div>
<div class="form-group">
<label>Link :</label> 
<input type="text" name="link" class="form-control" placeholder="Link"
value="<?php if (isset($views['link'])) : echo $views['link'];  endif; ?>" >
</div>
<!-- Flavour -->
<?php if (isset($views['flavour'])) : echo $views['flavour']; endif; ?>

				
<!-- photo Picture -->
<?php 
if (isset($views['image'])) :
?>
			
<div class="form-group">
	<?php 
									
	$image = '../files/picture/photo/'. $views['postImage'];
								   
	$imageThumb = '../files/picture/photo/thumb/thumb_' . $views['postImage'];
								   
	if (!is_readable($imageThumb)) :

		$imageThumb = '../files/picture/photo/thumb/nophoto.jpg';

	endif;

	if (is_readable($image)) :	
	
	?>
				
<br><a href="<?php echo $image; ?>"><img src="<?php  echo $imageThumb; ?>"></a><br> 
<label>change picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)" maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
<?php else : ?>
<br><img src="<?php echo $imageThumb; ?>"><br> 
<label>change picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<br>
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
<?php endif; ?>
</div>
<?php else : ?>
<div class="form-group">
<label>Upload Picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<br>
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
</div>
<?php endif; ?>
								
<!-- description -->
<div class="form-group">
<label>*Description</label>
<textarea class="form-control" id="sc" name="content" rows="10" maxlength="100000">
<?php if (isset($views['content'])) : echo $views['content']; endif; ?> 
</textarea>
</div>
								
<input type="submit" class="btn btn-primary" name="submit" value="Save" />
<button type="button" class="btn btn-danger" onClick="self.history.back();">Cancel</button>

							</form>
						</div>
						
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