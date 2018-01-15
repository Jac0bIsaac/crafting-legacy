<?php

include('../inc/config.php');

$pageTitle = "Change password";

$errors = array();

$tempToken = isset($_GET['tempToken']) ? htmlentities(strip_tags($_GET['tempToken'])) : '';

$stmt = $dbc -> prepare("SELECT ID, volunteer_resetKey, volunteer_resetComplete 
                        FROM volunteer WHERE volunteer_resetKey = :resetKey");

$stmt -> execute(array(":resetKey" => $tempToken));

$row = $stmt -> fetch(PDO::FETCH_ASSOC);

if (empty($row['volunteer_resetKey'])) {
	
  $stop = "Token is not valid. Check your email!";
  
} elseif ($row['volunteer_resetComplete'] == 'Yes') {
	
  $stop = "Your password has been changed";
  
}

if (isset($_POST['submit']) && $_POST['submit'] == 'recoverPass') {
	
 $password = preventInject($_POST['pass1']);
 $confirmPass = preventInject($_POST['pass2']);

 if (empty($password) || empty($confirmPass)) {
 
  $errors[] = "All Column must be filled";
  
 } else {
 	
  if (strlen($password) < 8) {
  	
  	$errors[] = "Password must consist of least 8 characters";
  	
  } 
  
  if ($password != $confirmPass) {
  	
  	$errors[] = 'Password does not match!';
  	
  }
  
 }
 
 if (empty($errors) == true) {
 	
  $authentication -> recoverPassword($row['ID'], $password, $row['volunteer_resetKey']);
  
 }
 
}

loginHeader($pageTitle);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $pageTitle; ?></title>
<!-- Core CSS - Include with every page -->
<link href="<?php echo APP_DIR; ?>cabin/css/bootstrap.min.css"
	rel="stylesheet">
<link
	href="<?php echo APP_DIR; ?>cabin/font-awesome/css/font-awesome.css"
	rel="stylesheet">
<!-- Page-Level Plugin CSS - Dashboard -->
<!-- Page-Level Plugin CSS - Tables -->
<link
	href="<?php echo APP_DIR; ?>cabin/css/plugins/dataTables/dataTables.bootstrap.css"
	rel="stylesheet">
<link
	href="<?php echo APP_DIR; ?>cabin/css/plugins/morris/morris-0.4.3.min.css"
	rel="stylesheet">
<link
	href="<?php echo APP_DIR; ?>cabin/css/plugins/timeline/timeline.css"
	rel="stylesheet">
<link href="<?php echo APP_DIR; ?>cabin/css/sb-admin.css"
	rel="stylesheet">

</head>
<body OnLoad="document.recover.email.focus();">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Enter Your New Password</h3>
					</div>
					<div class="panel-body" OnLoad="document.recover.pass1.focus();">
						<?php if ( empty($errors) == false ) { ?>
						<div class="alert alert-danger ">

							<?php echo implode('<div></div>', $errors); ?>

						</div>
						<?php } ?>


						<?php if (isset($stop)) {
							?>
						<div class="alert alert-danger">

							<?= $stop; ?>
							<script type="text/javascript">function leave() {  window.location = "<?php echo APP_DIR; ?>";} setTimeout("leave()", 3640);</script>
							
						</div>
						<?php  } else { ?>

						<form method="post" action="recoverPassword.php" onSubmit="return validasi(this)"
							role="form" autocomplete="off">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="new password"
										name="pass1" type="password" required>
								</div>
								<div class="form-group">
									<input class="form-control"
										placeholder="confirm your new password" name="pass2"
										type="password" autofocus required>
								</div>

								<input type="hidden" name="submit" value="recoverPass" />
					<button type="submit" class="btn btn-primary btn-lg btn-block">Change Password</button>
							</fieldset>
						</form>
					</div>

				</div>
				<?php if ( isset($_GET['status']) ) { ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">&times;</button>
					Kata sandi berhasil di<strong><?php echo $_GET['status']; ?> </strong>
				</div>
				<a href="<?php echo APP_CONTROL_PANEL; ?> ">Log in </a>
				<?php } 
}?>
			</div>

		</div>

	</div>

	<script type="text/javascript">
function validasi(form){
if (form.pass1.value == ""){
alert("Please enter your password");
form.pass1.focus();
return (false);
}
     
if (form.pass2.value == ""){
alert("Please enter your confirm password");
form.pass2.focus();
return (false);
}
return (true);
}
</script>
	<!-- Core Scripts - Include with every page -->
	<script src="<?php echo APP_DIR; ?>cabin/js/jquery-1.10.2.js"></script>
	<script src="<?php echo APP_DIR; ?>cabin/js/bootstrap.min.js"></script>
	<script
		src="<?php echo APP_DIR; ?>cabin/js/plugins/metisMenu/jquery.metisMenu.js"></script>

	<!-- SB Admin Scripts - Include with every page -->
	<script src="<?php echo APP_DIR; ?>cabin/js/sb-admin.js"></script>
</body>
</html>