<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$volunteerId = isset($_GET['volunteerId']) ? abs((int)$_GET['volunteerId']) : 0;
$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : "";
$accessLevel = $authentication -> accessLevel();

switch ($action) {
	
	case 'newVolunteer':
	  
		if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
			
		 require('../cabin/404.php');
		 
		} else {
			
		  newVolunteer($volunteers);
			
		}
		
	break;
	
	case 'editVolunteer' :
		
		if ($volunteers -> checkVolunteerId($volunteerId, $sanitize) == false) {
			

	    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&error=volunteerNotFound">';
     
		
		} elseif ($volunteers -> checkVolunteerSession($sessionId) == false) {
		 
	   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&error=volunteerNotFound">';
			
		
		} else {
			
			if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
				
				updateMyProfile($volunteers, $sanitize, $volunteerId, $sessionId, $accessLevel);
			
			} else {
			  
				updateVolunteer($volunteers, $sanitize, $volunteerId, $sessionId, $accessLevel);
			}
			
		}
		 
		break;
	
	case 'deleteVolunteer' :
		
		if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
			
			require('../cabin/404.php');
			
		} else {
			
			deleteVolunteerById($volunteers, $sanitize, $volunteerId);
		}
		
		break;
		
	default:
		
		if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
			
		 showMyProfile($volunteers, $sanitize, $vid);
		 
		} else {
		
		 listVolunteers($volunteers);
		  
		}
		
	break;
	
}

function listVolunteers($volunteers) 
{
	$views = array();
	$views['pageTitle'] = "Volunteers";
	
	$p = new Pagination();
	$limit = 10;
	$position = $p -> getPosition($limit);
	
	$data_volunteers = $volunteers -> findAllVolunteers($position, $limit);
	
	$views['volunteers'] = $data_volunteers['results'];
	$views['totalRows'] = $data_volunteers['totalVolunteers'];
	$views['position'] = $position;
	
	// pagination
	$totalPage = $p ->totalPage($views['totalRows'], $limit);
	$pageLink = $p -> navPage($_GET['order'], $totalPage);
	$views['pageLink'] = $pageLink;
	
	if (isset($_GET['error'])) {
	
	 if ($_GET['error'] == "volunteerNotFound" ) $views['errorMessage'] = "Error: Volunteer Not Found !";
	
	}
	
	if ( isset($_GET['status'])) {
	
		if ( $_GET['status'] == "volunteerAdded") $views['statusMessage'] =  "New volunteer added";
		if ( $_GET['status'] == "volunteerUpdated") $views['statusMessage'] = "Volunteer updated";
		if ( $_GET['status'] == "volunteerDeleted") $views['statusMessage'] = "Volunteer deleted";
	}
	
	require('volunteers/list-volunteers.php');
	
}

function showMyProfile($volunteers, $sanitize, $vid)
{
 $views = array();
 $views['pageTitle'] = "My Profile";
 
 $data_profile = $volunteers -> findVolunteer($vid, $sanitize);
 
 $views['ID'] = $data_profile['ID'];
 $views['firstName'] = $data_profile['volunteer_firstName'];
 $views['lastName'] = $data_profile['volunteer_lastName'];
 $views['logIn'] = $data_profile['volunteer_login'];
 $views['email'] = $data_profile['volunteer_email'];
 $views['token'] = $data_profile['volunteer_session'];
 $views['level'] = $data_profile['volunteer_level'];
 
 if (isset($_GET['error'])) {
 	
 	if ($_GET['error'] == "volunteerNotFound" ) $views['errorMessage'] = "Error: Your data is not found !";
 	
 }
 
 if ( isset($_GET['status'])) {
 	
 	if ( $_GET['status'] == "volunteerUpdated") $views['statusMessage'] = "Your profile has been updated";
 	if ( $_GET['status'] == "volunteerDeleted") $views['statusMessage'] = "your profile deleted";
 }
 
 require('volunteers/myprofile.php');
 
}

// function add new volunteer
function newVolunteer($volunteers)
{
 $views = array();
 $views['pageTitle'] = "Add New Volunteer";
 $views['formAction'] = "newVolunteer";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$username = preventInject($_POST['username']);
 	$first_name = preventInject($_POST['firstname']);
 	$last_name = preventInject($_POST['lastname']);
 	$email = preventInject($_POST['email']);
 	$password = preventInject($_POST['password']);
 	$registered = date("Ymd");
 	$token = createToken($_POST['password']);
 	$level = trim($_POST['level']);
 	$phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
 	
 	if (empty($username) || empty($first_name) || empty($last_name) || empty($email)
 			|| empty($password) || $level == '0') {
 		
 		$views['errorMessage'] = "All Column with asterisk(*) must be filled !";
 		require('volunteers/edit-volunteer.php');
 	
 	} else {
 		
 		// checking username
 		if ($volunteers -> isUsernameExists($username) == true) {
 			$views['errorMessage'] = "Username has been used. Please use the other names";
 			require('volunteers/edit-volunteer.php');
 		}
 		
 		if (!(ctype_alnum($username))) {
 			
 			$views['errorMessage'] = "Type in a username using only numbers and letters";
 			require('volunteers/edit-volunteer.php');
 		
 		// checking first name	
 		} elseif (!preg_match('/^[A-Z \'.-]{2,40}$/i', $first_name)) {
 			$views['errorMessage'] = "Please enter your first name";
 			require('volunteers/edit-volunteer.php');
 		// checking last name
 		} elseif (!preg_match('/^[A-Z \'.-]{2,60}$/i', $last_name)) {
 			$views['errorMessage'] = "Please enter your last name";
 			require('volunteers/edit-volunteer.php');
 			
 		// checking email
 		} elseif (is_valid_email_address(trim($email)) == 0) {
 			$views['errorMessage'] = "Please enter a valid email address";
 			require('volunteers/edit-volunteer.php');
 		
 	    // checking password
 		} elseif (strlen($password) < 8) {
 			
 			$views['errorMessage'] = "Your password must consist of least 8 characters!";
 			require('volunteers/edit-volunteer.php');
 		
 		// checking phone number 
 		} elseif (!preg_match('/^[0-9]{10,13}$/', $phone)) {
 			
 			$views['errorMessage'] = "Your phone number is not valid !";
 			require('volunteers/edit-volunteer.php');
 		}
 	
 	}
 	
 	if (empty($views['errorMessage']) === true) {
 		 
 		$add_volunteer = $volunteers -> createVolunteer($first_name, $last_name, $username,
 				$email, $password, $phone, $level, $token, $registered, date("H:i:s"));
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&status=volunteerAdded">';
 	
 	}
 	
 } else {
 	
   $views['level'] = $volunteers -> setVolunteerlevels();
   
   require('volunteers/edit-volunteer.php');
   
 }
 
}

function updateVolunteer($volunteers, $sanitize, $volunteerId, $sessionId, $accessLevel)
{
 $views = array();
 $views['pageTitle'] = "Edit Volunteer";
 $views['formAction'] = "editVolunteer";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$volunteer_id = (int)$_POST['volunteer_id'];
 	$first_name = preventInject($_POST['firstname']);
 	$last_name = preventInject($_POST['lastname']);
 	$email = preventInject($_POST['email']);
 	$password = preventInject($_POST['password']);
 	$confirm = preventInject($_POST['confirm']);
 	$level = trim($_POST['level']);
 	$sesi = trim(md5($password));
 	$phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
 	
 	if (!preg_match('/^[0-9]{10,13}$/', $phone)) {
 		
 		$views['errorMessage'] = 'Phone number is not valid';
 		require('volunteers/edit-volunteer.php');
 	
 	} elseif (empty($password)) {
 			
 		$edit_volunteer = $volunteers -> updateVolunteer($first_name, $last_name,
 				$email, $phone, $level, $volunteer_id, $accessLevel);
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&status=volunteerUpdated">';
 		
 	} else {
 		
 		$edit_volunteer = $volunteers -> updateVolunteer($first_name, $last_name,
 				$email, $phone, $level, $volunteer_id, $accessLevel, $password);
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&status=volunteerUpdated">';
 		
 	}
 	
 } else {
 	
 	$volunteer = $volunteers -> findVolunteer($volunteerId, $sanitize);
 	$views['ID'] = $volunteer['ID'];
 	$views['firstName'] = $volunteer['volunteer_firstName'];
 	$views['lastName'] = $volunteer['volunteer_lastName'];
 	$views['userName'] = $volunteer['volunteer_login'];
 	$views['email'] = $volunteer['volunteer_email'];
 	$views['token'] = $volunteer['volunteer_session'];
 	$views['phone'] = $volunteer['volunteer_phone'];
 	
 	$views['level'] = $volunteers -> setVolunteerlevels($volunteer['volunteer_level']);
 	
 	require('volunteers/edit-volunteer.php');
 	
 }
 
}

function updateMyProfile($volunteers, $sanitize, $volunteerId, $sessionId, $accessLevel)
{
 
 $views = array();
 $views['pageTitle'] = "Edit My Profile";
 $views['formAction'] = "editVolunteer";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$volunteer_id = (int)$_POST['volunteer_id'];
 	$first_name = preventInject($_POST['firstname']);
 	$last_name = preventInject($_POST['lastname']);
 	$email = preventInject($_POST['email']);
 	$password = preventInject($_POST['password']);
 	$confirm = preventInject($_POST['confirm']);
 	$level = trim($_POST['level']);
 	$sesi = trim(md5($password));
 	$phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
 	
 	if (!preg_match('/^[0-9]{10,13}$/', $phone)) {
 		$views['errorMessage'] = 'Phone number is not valid';
 		require('volunteers/edit-myprofile.php');
 		
 	} elseif (empty($password)) {
 		
 		$editMyProfile = $volunteers -> updateVolunteer($first_name, $last_name,
 				$email, $phone, $level, $volunteer_id, $accessLevel);
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&status=volunteerUpdated">';
 		
 	} else {
 		
 		$editMyProfile = $volunteers -> updateVolunteer($first_name, $last_name,
 				$email, $phone, $level, $volunteer_id, $accessLevel, $password);
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&status=volunteerUpdated">';
 		
 	}
 	
 } else {
 	
 	$volunteer = $volunteers -> findVolunteer($volunteerId, $sanitize);
 	$views['ID'] = $volunteer['ID'];
 	$views['firstName'] = $volunteer['volunteer_firstName'];
 	$views['lastName'] = $volunteer['volunteer_lastName'];
 	$views['userName'] = $volunteer['volunteer_login'];
 	$views['email'] = $volunteer['volunteer_email'];
 	$views['token'] = $volunteer['volunteer_session'];
 	$views['phone'] = $volunteer['volunteer_phone'];
 	$views['level'] = $volunteer['volunteer_level'];
 	
 	require('volunteers/edit-myprofile.php');
 	
 }
 
}

function deleteVolunteerById($volunteers, $sanitize, $volunteerId)
{
 
 if (!$volunteer = $volunteers -> findVolunteer($volunteerId, $sanitize)) {
 	
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&error=volunteerNotFound">';
   
 }
 
 $delete_volunteer = $volunteers -> deleteVolunteer($volunteerId, $sanitize);
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=volunteers&status=volunteerDeleted">';
 
}