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
	<!-- /.row -->

<?php 
	if (isset($views['errorMessage'])) { ?>
	
		<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	    </div>
	
	
	<?php } ?>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>
				<!-- #panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">

							<form method="post"
								action="index.php?module=messages&action=<?php echo $views['formAction']; ?>&messageId=<?php if (isset($views['message_id'])) echo (int)$views['message_id']; ?>"
								role="form">

								<!-- Kepada -->
								<div class="form-group">
									<label> To: </label> <input type="text"
										class="form-control" name="email"
										value="<?php if (isset($views['to'])) echo htmlspecialchars($views['to']); ?>">
								</div>

								<!-- Subject -->
								<div class="form-group">
									<label> Subject: </label> <input type="text"
										class="form-control" name="subject"
										value="<?php if (isset($_POST['subject'])) { echo preventInject($_POST['subject']);}?>">
								</div>

								<!-- Pesan -->
								<div class="form-group">
									<label>Message: </label>
									<textarea name="pesan" class="form-control" rows="10"
										id="sc" maxlength="100000">
										<?php if (isset($views['sender'])) echo "<br><br><br>-------------------------------------------------------------------------------\n\n<br><br>".
									  $views['sender']. " wrote message:\n\n<br><br>".$views['message']; ?>
	                          </textarea>
								</div>

								<input type="submit" class="btn btn-primary" name="send"
									value="Reply" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Cancel</button>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- /#page-wrapper -->