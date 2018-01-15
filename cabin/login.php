<?php

include_once('../inc/config.php');

include_once('login-theme.php');

$loginFormSubmitted = isset($_POST['Log-In']);

if (empty($loginFormSubmitted) == false) {

 $email = isset($_POST['email']) ? preventInject($_POST['email']) : "";
 $passWord = isset($_POST['password']) ? preventInject($_POST['password']) : "";
 
 $badCSRF = true; // check CSRF
  
 $_SESSION['volunteerLoggedIn'] = false;
 
 if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
 	|| $_POST['csrf'] !== $_SESSION['CSRF']) {
 			
 	$errors['errorMessage'] = 'Sorry, there was a security issue';
 	
 	$badCSRF = true;
 	
 } elseif (empty($email) || empty($passWord)) {
 	
 	$errors['errorMessage'] = 'All Column must be filled';
 	
 } elseif (is_valid_email_address($email) == 0) {
 	
 	$errors['errorMessage'] = "Please enter a valid email address";
 
 } elseif (!ctype_alnum($passWord)) {
 	
 	$errors['errorMessage'] = "Please enter a valid password";
 
 } elseif ($authentication -> isEmailExists($email) == false) { 
 	
 	$errors['errorMessage'] = "Your email is not registered !";
 
 } elseif (strlen($passWord) < 8) {
 	
 	$errors['errorMessage'] = "Your password must consist of least 8 characters";
 
 } elseif ((!$login = $authentication -> validateVolunteer($email, $passWord))) {
 	
 	$errors['errorMessage'] = "Check your password !";
 
 } else {
 	
 	$badCSRF = false;
 	unset($_SESSION['CSRF']);
 	
 	$_SESSION['volunteerLoggedIn'] = true;
 	
 	$_SESSION['KCFINDER']=array();
 	
 	$_SESSION['KCFINDER']['disabled'] = false;
 	
 	$_SESSION['KCFINDER']['uploadURL'] = "../pictures";
 	
 	$_SESSION['KCFINDER']['uploadDir'] = "";
 	
 	//Time limit for accessing administrator page
 	$_SESSION['limit'] = 1;
 	timeKeeper();
 	
 	$old_session = session_id();
 	
 	session_regenerate_id();
 	
 	$new_session = session_id();
 	
 	$sessionUpdated = $authentication -> updateVolunteerSession($new_session, $email);
 	
 }
 
} 

loginHeader("Log In");

?>

<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
				   <h3 class="panel-title">
				   <img src="<?php echo APP_CONTROL_PANEL; ?>/img/profile.png" />
				   </h3>
				</div>
				<div class="panel-body">
					<?php if (isset($errors['errorMessage'])) { ?>

        
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $errors['errorMessage']; ?>
	</div>
	
<?php 	} 

	if (isset($_GET['status']) && $_GET['status'] == 'ganti'){

     echo '<div class="alert alert-info alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Kata sandi sudah di' . $_GET['status'] . '. Silahkan masuk!</div>';

	}elseif (isset($_GET['status']) && $_GET['status'] == 'aktif')
	{
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Akun sudah di' . $_GET['status'] . 'kan. Silahkan masuk!</div>';
	}

	?>
					<form name="formlogin" method="post" action="login.php"
						onSubmit="return validasi(this)" role="form" autocomplete="off">
						<fieldset>
							<div class="form-group">
								<input type="text" class="form-control" placeholder="E-mail"
									name="email" required autofocus maxlength="255" />
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Password"
									name="password" type="password" required maxlength="32" autocomplete="off" />
							</div>

							

    <?php 
    // prevent CSRF
    $key= '1Af/MdfyPFO42PB+xK9C+iquu6ZU6QOVDpQfQ4oWU9Q=';
    $CSRF = bin2hex(openssl_random_pseudo_bytes(32).$key);
    $_SESSION['CSRF'] = $CSRF;
    ?>
     <input type="hidden" name="csrf" value="<?php echo $CSRF; ?>"/>
     <input type="submit" name="Log-In" class="btn btn-primary btn-lg btn-block" value="Login" />

						</fieldset>
					</form>
				</div>
			</div>

			<a href="resetPassword.php">Lost your password?</a>

		</div>
	</div>
</div>
<!-- Core Scripts - Include with every page -->

<?php 
loginFooter();
?>