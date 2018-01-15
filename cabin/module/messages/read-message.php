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

								<!-- Subjek -->
								<div class="form-group">
									<label> Subject: </label> <input type="text"
										class="form-control" name="subject"
										value="Re: <?php if (isset($views['subject'])) echo htmlspecialchars($views['subject']); ?>">
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

	<?php }?>
</div>
<!-- /#page-wrapper -->