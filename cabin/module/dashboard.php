<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$accessLevel = $authentication -> accessLevel();

switch ($action) {
	
	default:
		
	 if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
	 	
	 	welcomeCrew();
	 
	 } else {
	 	
	 	welcomeAdmin();
	 }
	 
	 break;
	 
}

function welcomeAdmin()
{
 global $dashboards;
 
 $views = array();
 $views['pageTitle'] = "Dashboard";
 
 // get messages's total record
 $views['totalMessages'] = $dashboards -> totalMessages();

 // get photos's  total record
 $views['totalPhotos'] = $dashboards -> totalPhotos();
 
 // get event's total records
 $views['totalEvents'] = $dashboards -> totalEvents();
 
// get posts's total records
$views['totalPosts'] = $dashboards -> totalPosts();

 require('dashboard/home.php');
 
}

function welcomeCrew()
{
global $volunteer_firstname, $volunteer_lastname;

$views = array();
$views['pageTitle'] = "Hello, " . $volunteer_firstname . ' ' . $volunteer_lastname;

require('dashboard/homecrew.php');

}