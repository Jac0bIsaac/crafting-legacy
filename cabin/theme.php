<?php if (!defined('APP_KEY')) header("Location: 403.php"); 

function scHeader($pageTitle = NULL)
{
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php if (isset($pageTitle)) echo $pageTitle; ?></title>

<!-- Bootstrap Core CSS -->
<link href="<?php echo APP_CONTROL_PANEL; ?>/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo APP_CONTROL_PANEL; ?>/css/datetimepicker.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="<?php echo APP_CONTROL_PANEL; ?>/css/plugins/metisMenu/metisMenu.min.css"
	rel="stylesheet">

<!-- font awesome -->
<link href="<?php echo APP_CONTROL_PANEL; ?>/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">

<link href="<?php echo APP_CONTROL_PANEL; ?>/css/sb-admin.css" rel="stylesheet">

<!-- Icon -->
 <link href="<?php echo APP_CONTROL_PANEL; ?>/img/favicon.ico" rel="Shortcut Icon" />
   
<!-- wysiwyg editor-->
<script src="<?php echo APP_CONTROL_PANEL; ?>/wysiwyg/tiny_mce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="<?php echo APP_CONTROL_PANEL; ?>/wysiwyg/tiny_mce/tinymce.min.js" type="text/javascript"></script>
<script src="<?php echo APP_CONTROL_PANEL; ?>/wysiwyg/tiny_mce/tinysc.js" type="text/javascript"></script>

<!-- Date + Time -->
<link rel="stylesheet" href="<?php echo APP_CONTROL_PANEL; ?>/kalender/calendar.css" type="text/css">
<script type="text/javascript" src="<?php echo APP_CONTROL_PANEL; ?>/kalender/calendar.js"></script>
<script type="text/javascript" src="<?php echo APP_CONTROL_PANEL; ?>/kalender/calendar2.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>
<body>

	<div id="wrapper">

<?php 
}

function scFooter()
{
?>
</div>
<!-- /#wrappe

<!-- Jquery -->
<script src="<?php echo APP_CONTROL_PANEL; ?>/js/jquery.js"></script>

<!-- Checklogin -->
<script src="<?php echo APP_CONTROL_PANEL; ?>/js/checklogin.js"></script>

<!-- Validate Image -->
<script src="<?php echo APP_CONTROL_PANEL; ?>/js/image-validation.js"></script>
<script src="<?php echo APP_CONTROL_PANEL; ?>/js/imagesizechecker.js"></script>

<!-- Toggle Fields -->
<script src="<?php echo APP_CONTROL_PANEL; ?>/js/toggle_fields.js"></script>

<script src="<?php echo APP_CONTROL_PANEL; ?>/js/bootstrap.min.js"></script>

<script src="<?php echo APP_CONTROL_PANEL; ?>/js/datetimepicker.js"></script>

<script src="<?php echo APP_CONTROL_PANEL; ?>/js/plugins/metisMenu/metisMenu.min.js"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="<?php echo APP_CONTROL_PANEL; ?>/js/sb-admin.js"></script>
<script>
jQuery('#timepicker1').datetimepicker({
	  datepicker:false,
	  format:'H:i'
	});

jQuery('#timepicker2').datetimepicker({
	  datepicker:false,
	  format:'H:i'
	});
</script>

<script>
jQuery('#datepicker1').datetimepicker({
	  timepicker:false,
	  format:'d/m/Y'
	});

jQuery('#datepicker2').datetimepicker({
	  timepicker:false,
	  format:'d/m/Y'
	});
</script>

</body>
</html>
<?php 

}