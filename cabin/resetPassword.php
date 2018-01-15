<?php 
include('../inc/config.php');
include('login-theme.php');

$pageTitle = "Reset Password";

$errors = array();

if (isset($_POST['reset']) && $_POST['reset'] == 'reset_pass') {
	
 $email_address = preventInject($_POST['email']);
 
 if (empty($email_address)) {
 	
 	$errors[] = "ERROR:Enter email address";
 
 } else {
 	
 	// validate email address
 	if (is_valid_email_address($email_address) == 0) {
 		$errors[] = "ERROR:Invalid email address";
 	}
 	
 	if ($authentication -> isEmailExists($email_address) == false) {
 		
 	   $errors[] = "ERROR:There is no volunteer registered with that email address";
 	}
 	
 }
 
 if (empty($errors) === true) {
 	
 // create temporary token
 $tempToken = md5(uniqid(rand(),true));
 
 // update record on table volunteer
 $sql = "UPDATE volunteer SET volunteer_resetKey = :resetKey, 
        volunteer_resetComplete = 'No' WHERE volunteer_email = :email";
 
 $stmt = $dbc -> prepare($sql);
 $stmt -> bindParam(":resetKey", $tempToken, PDO::PARAM_STR);
 $stmt -> bindParam(":email", $email_address, PDO::PARAM_STR);
 
 try {
 	
 	$stmt -> execute();
 	
 	if ($row = $stmt -> rowCount() == 1) {
 		
 	 // Send an email
 	 $to = safeEmail($email_address);
 	 $subject = "Password Reset";
 	 $message = "<html><body>
                If you have never requested an information message about forgotten passwords, please feel free to ignore this email.<br />
                But If you are indeed asking for this information, then please click on the link below: <br /><br />
               <a href=".APP_CONTROL_PANEL."recoverPassword.php?tempToken=$tempToken >Recover Password</a><br /><br />
               Thank You, <br />
               <b>Volunteer South Carolina Exchange Student</b>
               </body></html>";
 	 
 	 $headers  = "MIME-Version: 1.0" . "\r\n";
 	 $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
 	 $headers .= "From: <".APP_SITEEMAIL.">\r\n";
 	 $headers .= "Reply-To: ".APP_SITEEMAIL."";
 	 
 	 mail($to, $subject, $message, $headers);
 	 
 	 // redirect to reset password's page
 	 header("Location:".APP_CONTROL_PANEL."resetPassword.php?status=reset");
 	 
 	}
 	
 } catch (PDOException $e) {
 	
 	$dbc = null;
 	
 	LogError::newMessage($e);
 	LogError::customErrorMessage();
 	
 }
 
 }
 
}

loginHeader($pageTitle);

?>

<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Please enter your email address</h3>
					</div>
					<div class="panel-body"
						OnLoad="document.reset.admin_email.focus();">
						<?php if ( empty($errors) == false ) { ?>
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert"
								aria-hidden="true">&times;</button>
							<?php echo implode('<div></div>', $errors); ?>
						</div>
						<?php } ?>


						<form method="post" action="resetPassword.php"
							onSubmit="return validasi(this)" role="form" autocomplete="off">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="Email"
										name="email" type="text" autofocus required>
								</div>
							
								<input type="hidden" name="reset" value="reset_pass" />
								<button type="submit" class="btn btn-primary btn-lg btn-block">
									Get New Password</button>
							</fieldset>
						</form>
					</div>

				</div>
				<?php if ( isset($_GET['status']) ) { ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">&times;</button>
				 password has been <strong><?php echo $_GET['status']; ?> </strong>.
					check your e-mail !
				</div>
				<?php } ?>
				<a href="<?php echo APP_CONTROL_PANEL; ?>">Log in</a>
			</div>

		</div>

	</div>
<?php loginFooter(); ?>