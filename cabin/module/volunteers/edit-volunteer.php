<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php"); ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle']))  echo $views['pageTitle']; ?>
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
						<div class="col-lg-6">

<form method="post" action="index.php?module=volunteers&action=<?php echo $views['formAction']; ?>&volunteerId=<?php if (isset($views['ID']))
	 echo (int)$views['ID']; ?>&sessionId=<?php if (isset($views['token'])) echo htmlspecialchars($views['token']); ?>"
							role="form">
								<input type="hidden" name="volunteer_id" value="<?php if (isset($views['ID'])) echo htmlspecialchars($views['ID']); ?>" />
								<input type="hidden" name="session_id" value="<?php if ( isset($views['token']))  echo htmlspecialchars($views['token']); ?>" />
								
								<!-- Volunteer_login -->
								<div class="form-group">
									<label>username*</label> <input type="text" name="username" class="form-control" placeholder="username"
										value="<?php if (isset($_POST['username'])) { echo htmlspecialchars($_POST['username']);} 
										if (isset($views['userName'])) echo htmlspecialchars($views['userName']); ?>"
										<?php if((isset($views['userName']) && $views['userName'] != '')) { ?> <?php echo "disabled"; }?>>
								</div>
								<!-- Volunteer_firstName -->
								<div class="form-group">
									<label>Firstname*</label> <input type="text"
										name="firstname" class="form-control"
										placeholder="firstname" value="<?php if(isset($_POST['firstname'])) { echo preventInject($_POST['firstname']);} 
										if(isset($views['firstName'])) echo htmlspecialchars($views['firstName']); ?>"
										required>
								</div>
								
								<!-- Volunteer_lastName -->
								<div class="form-group">
									<label>lastname*</label> <input type="text"
										name="lastname" class="form-control"
										placeholder="lastname"
										value="<?php if (isset($_POST['lastname'])) { echo preventInject($_POST['lastname']); } 
										if (isset($views['lastName']))  echo htmlspecialchars($views['lastName']); ?>"
										required>
								</div>
								
								<!-- Volunteer_email -->
								<div class="form-group">
									<label>E-mail*</label> <input
										type="text" name="email" class="form-control"
										placeholder="E-mail address"
										value="<?php if (isset($_POST['email'])) { echo preventInject($_POST['email']);}
										if (isset($views['email']))  echo htmlspecialchars($views['email']);  ?>"
										required>
								</div>
								
								<!-- Phone -->
								<div class="form-group">
									<label>Phone*</label> <input type="text" name="phone" class="form-control"
										value="<?php if (isset($_POST['phone'])) { echo preventInject($_POST['phone']);} 
										if (isset($views['phone'])) echo htmlspecialchars($views['phone']); ?>">
								</div>
													
								<!-- Volunteer_pass -->
								<div class="form-group">
									<label>Password*</label> <input type="password"
										name="password" class="form-control" placeholder="password">
								</div>
								
								<!-- confirm_Volunteer_pass -->
								<?php if (isset($views['email']) && !empty($views['email'])) { ?>
								<div class="form-group">
									<label>Confirm Password*</label> <input type="password"
										name="confirm" class="form-control"
										placeholder="confirm password">
								</div>
								<?php } ?>

								<!-- Volunteer_level -->
								<div class="form-group">
									<?= (isset($views['level'])) ? $views['level'] : ""; ?>
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
<!-- #Page-Wrapper -->