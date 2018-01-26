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
	<script type="text/javascript">function leave() {  window.location = "index.php?module=menus&action=newMenu&menuId=0";} setTimeout("leave()", 5000);</script>
	</div>
	
	<?php } else { ?>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php  echo $views['pageTitle']; ?>
				</div>

<div class="panel-body">
  <div class="row">
		<div class="col-lg-6">
			
<form method="post" action="index.php?module=menus&action=<?php echo $views['formAction']; ?>&menuId=<?php if (isset($views['menu_id'])) { echo $views['menu_id']; }
else { echo (int)'0'; } ?>" role="form">

<input type="hidden" name="menu_id" value="<?php if (isset($views['menu_id'])) echo $views['menu_id']; ?>" >
								
<div class="form-group"><label>Label</label> 
<input type="text" name="label" class="form-control" placeholder="enter your menu label" value="<?php if (isset($views['label'])) echo  $views['label']; ?>"
										required>
</div>

								<!-- tautan -->
								<div class="form-group">
									<label>Link</label> <input type="text" name="link"
										class="form-control" placeholder="enter your menu link"
										value="<?php if (isset($views['link'])) echo $views['link']; ?>"
										required>
								</div>

								<!-- Menu utama -->
								<?php  ?>
								<div class="form-group">
									<?php if (isset($views['selectMenuParent'])) echo $views['selectMenuParent']; ?>
								</div>

								<?php if (isset($views['sort'])) : ?>
                                <div class="form-group">
                               <label>Sort</label>
  <input type="text" name="sort" class="form-control" value="<?php echo $views['sort']; ?>" >
                                </div>
                               <?php endif; ?>
                               
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

<?php } ?>