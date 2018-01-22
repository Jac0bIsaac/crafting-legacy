<?php if (!defined('APP_KEY')) header("Location: 403.php"); 

$module = '';
$pathToError = "404.php";
$pathToModule = null;

if (isset($_GET['module']) && $_GET['module'] != '') {
	$module = htmlentities(strip_tags(strtolower($_GET['module'])));
	$module = filter_var($module, FILTER_SANITIZE_STRING);
	$pathToModule = "module/$module.php";
}

// cek direktori
if (!is_readable($pathToModule) || empty($module)) {

  include($pathToError);

} else {

  include($pathToModule);
  
}