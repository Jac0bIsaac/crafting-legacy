<?php
function loginHeader($pageTitle)
{
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title><?php echo $pageTitle; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="apple-mobile-web-app-capable" content="yes"> 

<!-- Bootstrap Core CSS -->
<link href="<?php echo APP_CONTROL_PANEL; ?>/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="<?php echo APP_CONTROL_PANEL; ?>/css/plugins/metisMenu/metisMenu.min.css"
	rel="stylesheet">

<!-- font awesome -->
<link href="<?php echo APP_CONTROL_PANEL; ?>/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">

<link href="<?php echo APP_CONTROL_PANEL; ?>/css/sb-admin.css" rel="stylesheet">

 <!-- Icon -->
 <link href="<?php echo APP_CONTROL_PANEL; ?>/img/favicon.ico" rel="Shortcut Icon" />
    

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="../cabin/js/html5shiv.js"></script>
<script src="../cabin/js/respond.min.js"></script>
<![endif]-->

</head>
<body OnLoad="document.login.username.focus();" >


<?php 
}


function loginFooter()
{
?>

	  <!-- Jquery -->
    <script src="<?php echo APP_CONTROL_PANEL; ?>/js/jquery.js"></script>
    
    <!-- Checklogin -->
	<script src="<?php echo APP_CONTROL_PANEL; ?>/js/checklogin.js"></script>

    <!-- Validate Image -->
	<script src="<?php echo APP_CONTROL_PANEL; ?>/js/image-validation.js"></script>
    
	<!-- Toggle Fields -->
	<script src="<?php echo APP_CONTROL_PANEL; ?>/js/toggle_fields.js"></script>

	<script src="<?php echo APP_CONTROL_PANEL; ?>/js/bootstrap.min.js"></script>

	<script src="<?php echo APP_CONTROL_PANEL; ?>/js/plugins/metisMenu/metisMenu.min.js"></script>

	<!-- SB Admin Scripts - Include with every page -->
	<script src="<?php echo APP_CONTROL_PANEL; ?>/js/sb-admin.js"></script>
	
</body>
</html>


<?php 	
}
?>