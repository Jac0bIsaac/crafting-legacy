<?php

class Authentication
{
  
 protected $dbc;
 
 protected $error;
 
 public function __construct($dbc)
 {
 	$this->dbc = $dbc;
 }
 
 public function findIdByEmail($email)
 {
 	$sql = "SELECT ID FROM volunteer WHERE volunteer_email = :email";
 	
 	$stmt = $this->dbc->prepare($sql);
 	
 	$stmt -> execute(array(":email" => $email));
 	
 	$row = $stmt -> fetch();
 	
 	return $row['ID'];
 	
 }
 
 public function isEmailExists($email)
 {
 	$sql = "SELECT `volunteer_email` FROM `volunteer` WHERE `volunteer_email` = ? ";
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindValue(1, $email);
 	
 	try {
 		
 	    $stmt -> execute();
 		$rows = $stmt -> rowCount();
 		
 		if ($rows > 0) {  // if rows are found for query
 			
 			return true;
 		}
 		else
 		{
 			return false;
 		}
 		
 	} catch (PDOException $e) {
 		
 		$this->dbc = null;
 		
 		$this->error = LogError::newMessage($e);
 		$this->error = LogError::customErrorMessage();
 			
 	}
 	
 }
 
 public function validateVolunteer($email, $password)
 {
 	$volunteer_id = $this->findIdByEmail($email);
 	
    $hash_password = $this -> _verifyHashPassword($password, $volunteer_id);
 	
 	$sql = "SELECT ID, volunteer_firstName, 
            volunteer_lastName, volunteer_login, volunteer_email, volunteer_pass, 
            volunteer_level, volunteer_session
            FROM volunteer WHERE volunteer_email = :email 
            AND volunteer_pass = :password ";
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindParam(":email", $email, PDO::PARAM_STR);
 	$stmt -> bindParam(":password", $hash_password, PDO::PARAM_STR);
 	
 	try {
 		
 	 $stmt -> execute();
 	 
 	 return $stmt -> fetch();
 	 
 	} catch (PDOException $e) {
 		
 	  $this->dbc = null;
 	  
 	  $this->error = LogError::newMessage($e);
 	  $this->error = LogError::customErrorMessage();
 	  
 	}
 	
 }
 
 public function updateVolunteerSession($sessionKey, $email)
 {

   // update session
 	$sql = "UPDATE volunteer SET volunteer_session = :session 
           WHERE volunteer_email = :email";
 	
 	$generateKey = generateSessionKey($sessionKey);
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindparam(":session", $generateKey, PDO::PARAM_STR);
 	$stmt -> bindparam(":email", $email, PDO::PARAM_STR);
 	$stmt -> execute();
 	
 	// retrieve data volunteer
 	$dataVolunteer = $this->findPrivilege($email);
 	
 	if (isset($_SESSION['volunteerLoggedIn']) && $_SESSION['volunteerLoggedIn'] == true) {
  	 
 	 $_SESSION['ID'] = $dataVolunteer['ID'];
 	 $_SESSION['Login'] = $dataVolunteer['volunteer_login'];
 	 $_SESSION['FirstName'] = $dataVolunteer['volunteer_firstName'];
 	 $_SESSION['LastName'] = $dataVolunteer['volunteer_lastName'];
 	 $_SESSION['Email'] = $dataVolunteer['volunteer_email'];
 	 $_SESSION['Level'] = $dataVolunteer['volunteer_level'];
 	 $_SESSION['Token'] = $dataVolunteer['volunteer_session'];
 	 $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
 	 
 	 header('Location:' . APP_CONTROL_PANEL. DS .'index.php?module=dashboard');
 		
 	}
 	
 }
 
 public function isVolunteerLoggedIn()
 {
  
  $_SESSION['volunteerLoggedIn'] = false;
 	
  if (isset($_SESSION['volunteerLoggedIn']) 
      && $_SESSION['volunteerLoggedIn'] == true) {
 	  
     return  true;
     
  } 
 	
 }
 
 public function accessLevel()
 {
 	if (isset($_SESSION['Level'])) {
 	
 	  return $_SESSION['Level'];
 	
 	} else {
 	
 		return false;
 	}
 	
 }
 
 public function signOutVolunteer()
 {
  if (!isset($_SESSION['ID'])) {
  	
  	directPage();
  	
  } else {
  	
  	$_SESSION = array();
  	
  	session_destroy();
  	
  	setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
  	
  	//Redirect to Login Page
  	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
  	$host     = $_SERVER['HTTP_HOST'];
  	
  	$logInPage = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/';
  	
  	header('Location:' . $logInPage);
  	
  }
  
 }
 
 public function recoverPassword($id, $password, $token)
 {
  
 $sql = "UPDATE volunteer SET volunteer_pass = :password, volunteer_resetComplete = 'Yes' 
          WHERE volunteer_resetKey = :token AND ID = :id";
 
 $hash_password = shieldPass($password, $id);
 
 try {
 	
 $stmt = $this->dbc->prepare($sql);
 
 $stmt -> execute(array(":token"=>$token, ":id"=>$id));

 if ($rows = $stmt -> rowCount() == 1) {
 		
 	// redirect to login page
 	$logInPage = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
 		
 	header('Location: ' . $logInPage . 'login.php?status=changed');
 		
 	}
 	
 } catch (PDOException $e) {
 	
   $this->dbc = null;
   
   $this->error = LogError::newMessage($e);
   $this->error = LogError::customErrorMessage();
   
 }
  
}
 
 private function _verifyHashPassword($password, $id)
 {
 	return shieldPass($password, $id);
 }
 
 protected function findPrivilege($email) 
 {
  $sql = "SELECT ID, volunteer_firstName, volunteer_lastName, volunteer_login,
         volunteer_email, volunteer_phone, volunteer_level, volunteer_resetKey,
         volunteer_resetComplete, volunteer_session, date_registered, 
         time_registered FROM volunteer WHERE volunteer_email = :email";
  
  $stmt = $this->dbc->prepare($sql);
  
  try {
  	
  	$stmt -> execute(array(":email" => $email));
  	
  	$this->dbc = null;
  	
  	return $stmt -> fetch();
  	
  } catch (PDOException $e) {
  	
  	$this->dbc = null;
  	
  	$this->error = LogError::newMessage($e);
  	$this->error = LogError::customErrorMessage();
  	
  }
  
 }
 
}