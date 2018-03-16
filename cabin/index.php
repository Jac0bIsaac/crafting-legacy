<?php

require dirname(dirname(__FILE__)) . '/inc/config.php' ;
include 'theme.php';

$pageTitle = isset($_GET['module']) ? htmlentities(strip_tags($_GET['module'])) : null;

if (isset($_SESSION['limit']) && $_SESSION['limit'] == 1) {
 
  if (!validateTimeLogIn()) {
    $_SESSION['limit'] = 0;	
 }
 
}

if (isset($_SESSION['limit']) && $_SESSION['limit'] == 0) {
 
 header('Location: login.php');
 
} else {
	
if ((!isset($_SESSION['agent'])) || ($_SESSION['agent'] != sha1($_SERVER['HTTP_USER_AGENT']))) {
	
	header('Location: login.php');
	 
 } elseif (!$authentication -> isVolunteerLoggedIn() && $_SESSION['limit'] == 0) {
 	
 	header('Location: login.php');
 	
 } else {
 	
 	$vid = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : 0;
 	$volunteer_firstname = isset($_SESSION['FirstName']) ? htmlentities($_SESSION['FirstName']) : "";
 	$volunteer_lastname = isset($_SESSION['LastName']) ? htmlentities($_SESSION['LastName']) : "";
 	$volunteer_level = isset($_SESSION['Level']) ? htmlentities($_SESSION['Level']) : "";
 	$volunteer_email = isset($_SESSION['Email']) ? htmlentities($_SESSION['Email']) : "";
 	$volunteer_login = isset($_SESSION['Login']) ? htmlentities($_SESSION['Login']) : "";
 	$volunteer_token = isset($_SESSION['Token']) ? htmlentities($_SESSION['Token']) : "";
 	
 	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
 	$host     = $_SERVER['HTTP_HOST'];
 	
 	$url_host = rtrim($protocol . '://' . $host, "/").$_SERVER['PHP_SELF'];
 	$clean_url_host = preg_replace("/\/cabin\/(index\.php$)/","",$url_host);
 	
 	$data_messages = $dashboards -> messageNotifications();
 	$messages = $data_messages['results'];
 	$totalMessages = $dashboards -> totalMessages();
 	
 	back_office_header("$pageTitle\n");
 	
 	include 'navigation.php';
 	
 	include 'route.php';
 	
 	back_office_footer();
 	
 	ob_end_flush();
 	
 }
 
}