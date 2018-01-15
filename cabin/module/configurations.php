<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset( $_GET['action'] ) ? htmlentities(strip_tags($_GET['action'])) : "";
$configId = isset($_GET['configId']) ? abs((int)$_GET['configId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'WebMaster') {
	
  include('../cabin/404.php');

} else {
  
	switch ($action) {
		
		case 'setConfig':
			
			if ($configurations -> checkToSetup() == false) {
				
				require('../cabin/404.php');
				
			} else {
				
				setupConfig();
			}
			
			break;
			
		case 'editConfig':
			
			if ($configurations -> checkConfigId($configId, $sanitize) == false) {
				
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=configurations&error=configNotFound">';
				
			} else {
				
				updateConfig();
				
			}
			
			break;
			
		default:
			
			showConfig();
			
			break;
	}
	
}

function showConfig()
{
	global $configurations;
	
	$views = array();
	$views['pageTitle'] = "General setting";
	
	$data_option = $configurations -> findConfigs();
	
	$views['configs'] = $data_option['results'];
	
	foreach ($views['configs'] as $optional) :
	
	  $siteName = $optional['site_name'];
	
	endforeach;
	
	$views['siteName'] = isset($siteName) ? $siteName : "";
	
	if ( isset($_GET['error'])) {
		
		if ( $_GET['error'] == "configNotFound" ) $views['errorMessage'] = "Error: Configuration Not Found";
		
	}
	
	if ( isset($_GET['status'])) {
		
		if ( $_GET['status'] == "configAdded") $views['statusMessage'] =   "Configuration has been saved";
		if ( $_GET['status'] == "configUpdated") $views['statusMessage'] = "Configuration updated";
		
	}
	
	require('configs/show-setting.php');
	
}

function setupConfig()
{
 global $configurations;
 
 $views = array();
 $views['pageTitle'] = "Setting";
 $views['formAction'] = "setConfig";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 	$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 	$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 	$file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
 	
 	$siteName = isset($_POST['site_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['site_name']) : '';
 	$metaDescription = isset($_POST['description']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['description']) : '';
 	$metaKeyword = isset($_POST['keywords']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['keywords']) : '';
 	$tagline = isset($_POST['tagline']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tagline']) : '';
 	$address = isset($_POST['address']) ? preventInject($_POST['address']) : '';
 	$instagram = isset($_POST['instagram']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['instagram']) : '';
 	$twitter = isset($_POST['twitter']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['twitter']) : '';
 	$facebook = isset($_POST['facebook']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['facebook']) : '';
 	
 	// get file name
 	$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 	
 	// get file extension
 	$file_ext = substr($file_name, strripos($file_name, '.'));
 	
 	$allowed_file_types = array('.jpg','.jpeg','.png','.gif', '.JPG', '.JPEG');
 	
 	$phone = str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']);
 	
 	if (!preg_match('/^[0-9]{10,13}$/', $phone)) {
 		
 		$views['errorMessage'] = "Your phone number is not valid!";
 		require('configs/setting.php');
 		
 	}
 	
 	$faxNumber = str_replace(array(' ', '-', '(', ')'), '', $_POST['fax']);
 	if ((isset($faxNumber)) && (!empty($faxNumber))) {
 		
 		if (!preg_match('/^[0-9]{10,13}$/', $faxNumber)) {
 			
 			$views['errorMessage'] = "Your fax number is not valid!";
 			require('configs/setting.php');
 			
 		}
  		
 	}
 	
 	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
 	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 		$views['errorMessage'] = "Your email is not valid!";
 		require('configs/setting.php');
 		
 	} elseif (!empty($file_location)) {
 		
 		if ((in_array($file_ext, $allowed_file_types)) 
 				&& (file_exists("../files/picture/" . $newFileName))) {
 		
 		   $newFileName = renameFile(md5($file_basename)) . $file_ext;
 		  
 		   unlink("../files/picture/" . $newFileName);
 		   
 		   uploadLogo($newFileName);
 		   
 		   $setup_setting = $configurations -> createConfig($siteName, $metaDescription,
 		   		$metaKeyword, $tagline, $address, $email,
 		   		$phone, $faxNumber, $instagram, $twitter, $facebook, $newFileName);
 		   
 		   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=configurations&status=configAdded">';
 		   
 		}
 		
 	} else {
 		
 		$setup_setting = $configurations -> createConfig($siteName, $metaDescription, 
 				$metaKeyword, $tagline, $address, $email, 
 				$phone, $faxNumber, $instagram, $twitter, $facebook);
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=configurations&status=configAdded">';
 		
 	}
 	
 } else {
 	
    require('configs/setting.php');	
 }
 	
}


function updateConfig()
{
 global $configurations, $configId, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Edit Setting";
 $views['formAction'] = "editConfig";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 
 $siteName = isset($_POST['site_name']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['site_name']) : '';
 $metaDescription = isset($_POST['description']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['description']) : '';
 $metaKeyword = isset($_POST['keywords']) ? preg_replace('/[^ \'\,\.\-a-zA-Z0-9]/', '', $_POST['keywords']) : '';
 $tagline = isset($_POST['tagline']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['tagline']) : '';
 $address = isset($_POST['address']) ? preventInject($_POST['address']) : '';
 $instagram = isset($_POST['instagram']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['instagram']) : '';
 $twitter = isset($_POST['twitter']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['twitter']) : '';
 $facebook = isset($_POST['facebook']) ? preg_replace('/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/', '', $_POST['facebook']) : '';
 $id = isset($_POST['config_id']) ? (int)$_POST['config_id'] : "";
 
 // get file name
 $file_basename = substr($file_name, 0, strripos($file_name, '.'));
 
 // get file extension
 $file_ext = substr($file_name, strripos($file_name, '.'));
 
 $allowed_file_types = array('.jpg','.jpeg','.png','.gif', '.JPG', '.JPEG');
 
 $phone = str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']);
 if (!preg_match('/^[0-9]{10,13}$/', $phone)) {
 	
 	$views['errorMessage'] = "Your phone number is not valid!";
 	require('configs/setting.php');
 	
 }
 
 $faxNumber = str_replace(array(' ', '-', '(', ')'), '', $_POST['fax']);
 if ((isset($faxNumber)) && (!empty($faxNumber))) {
 	
 	if (!preg_match('/^[0-9]{10,13}$/', $faxNumber)) {
 		
 		$views['errorMessage'] = "Your fax number is not valid!";
 		require('configs/setting.php');
 		
 	}
 	
 }
 
 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 	$views['errorMessage'] = "Your email is not valid!";
 	require('configs/setting.php');
 	
 } elseif (!empty($file_location)) {
 	
 	 if ((in_array($file_ext, $allowed_file_types))
 			&& (file_exists("../files/picture/" . $newFileName))) {
 				
 		$newFileName = renameFile(md5($file_basename)) . $file_ext;
 				
 		unlink("../files/picture/" . $newFileName);
 				
 		uploadLogo($newFileName);
 				
 		$update_config = $configurations -> updateConfig($id, $siteName, $metaDescription, $metaKeywords, 
 		$tagline, $address, $email, $phone, $faxNumber, $instagram, $twitter, $facebook, $newFileName);
 				
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=configurations&status=configUpdated">';
 				
 	 }
 
 } else {
 	
 	$update_config = $configurations -> updateConfig($id, $siteName, $metaDescription, 
 	$metaKeyword, $tagline, $address, $email, $phone, $faxNumber, 
 	$instagram, $twitter, $facebook);
 	
 	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=configurations&status=configUpdated">';
 }
 
 
 } else {
 	
 	$setup = $configurations -> findConfig($configId, $sanitize);
 	$views['config_id'] = $setup['config_id'];
 	$views['site_name'] = $setup['site_name'];
 	$views['meta_desc'] = $setup['meta_description'];
 	$views['meta_key'] = $setup['meta_keywords'];
 	$views['tagline'] = $setup['tagline'];
 	$views['address'] = $setup['address'];
 	$views['email'] = $setup['email'];
 	$views['phone'] = $setup['phone'];
 	$views['fax'] = $setup['fax'];
 	$views['instagram'] = $setup['instagram'];
 	$views['twitter'] = $setup['twitter'];
 	$views['facebook'] = $setup['facebook'];
 	$views['logo'] = $setup['logo'];
 	
 	require('configs/setting.php');
 	
 }
 
}