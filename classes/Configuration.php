<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Configuration
{
  
 protected $dbc;
  
 public function __construct($dbc)
 {
		$this->dbc = $dbc;
 }
 
 
 public function createConfig($siteName, $metaDescription, $metaKeywords, 
 		                     $tagline, $address, $email, $phone, $fax, 
 		                     $instagram, $twitter, $facebook, $logo = '')
 {
   if (!empty($logo)) {
   	 
   	$sql = "INSERT INTO configuration(site_name, meta_description, meta_keywords, 
   	 		tagline, address, email, phone, fax, instagram, twitter, 
   	 		facebook, logo)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
   	 
   	$data = array($siteName, $metaDescription, $metaKeywords, $tagline, 
   			$address, $email, $phone, $fax, $instagram, $twitter, $facebook, $logo);
   	 
   } else {
   
   	$sql = "INSERT INTO configuration(site_name, meta_description, meta_keywords,
   	 		tagline, address, email, phone, fax, instagram, twitter,
   	 		facebook)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
   	 
   	$data = array($siteName, $metaDescription, $metaKeywords, $tagline, $address, $email,
   			$phone, $fax, $instagram, $twitter, $facebook);
   	 
   }
   
   $stmt = $this->statementHandle($sql, $data);
   
 }
 
 public function updateConfig($id, $siteName, $metaDescription, $metaKeywords, $tagline, $address, $email,
 		$phone, $fax, $instagram, $twitter, $facebook, $logo = '')
 {
 
  if (empty($logo)) {
  	
  $sql = "UPDATE configuration SET site_name = ?, meta_description = ?,
  		 meta_keywords = ?, tagline = ?, address = ?, email = ?,
  		phone = ?, fax = ?, instagram = ?, twitter = ?, facebook = ?
  		WHERE config_id = ?";
  	 
  $data = array($siteName, $metaDescription, $metaKeywords, $tagline, $address, $email,
  		$phone, $fax, $instagram, $twitter, $facebook, $id);
  	 
  } else {
  	
  	$sql = "UPDATE configuration SET site_name = ?, meta_description = ?,
  		 meta_keywords = ?, tagline = ?, address = ?, email = ?,
  		phone = ?, fax = ?, instagram = ?, twitter = ?, facebook = ?, logo = ?
  		WHERE config_id = ?";
  	
  	$data = array($siteName, $metaDescription, $metaKeywords, $tagline, $address, $email,
  			$phone, $fax, $instagram, $twitter, $facebook, $logo, $id);

  }
  
  $stmt = $this->statementHandle($sql, $data);
  
 }
  
  
 
 public function checkConfigId($id, $sanitizing) 
 {
 
 $sql = "SELECT config_id FROM configuration WHERE config_id = ?";
 
 $cleanUpId = $this->filteringId($sanitizing, $id, 'sql');
 
 $stmt = $this->dbc->prepare($sql);
  
  $stmt -> bindValue(1, $cleanUpId);
  
  try {
  	$stmt -> execute();
  	$rows = $stmt -> rowCount();
  	
  	if ($rows > 0) {
  		
  		return true;
  		
  	} else {
  		
  		return false;
  		
  	}
  	
  } catch (PDOException $e) {
  	
  	$this->dbc = null;
  	
  	throw new PDOException($e);
  	
  }
  
 }
 
 public function findConfigs()
 {
 	$sql = "SELECT config_id, site_name, meta_description, meta_keywords,
  		 tagline, address, email, phone, fax, instagram,
         twitter, facebook, logo FROM configuration LIMIT 1";
 	
 	$setup = array();
 	
 	$stmt = $this->dbc->query($sql);
 	
 	while ($row = $stmt -> fetch()) {
 		
 		$setup[] = $row;
 	}
 	
 	$this->dbc = null;
 	
 	return(array('results' => $setup));
 	
 }
 
 public function findConfig($id, $sanitizing)
 {
 
  $sql = "SELECT config_id, site_name, meta_description, meta_keywords,
  	 	 tagline, address, email, phone, fax, instagram, twitter, facebook, logo
  		 FROM configuration WHERE config_id = ?";
 	
 $id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
 	
 $data = array($id_sanitized);
 	
 $stmt = $this->statementHandle($sql, $data);
 	
 return $stmt -> fetch();
 	
 }
 
 public function checkToSetup()
 {
   $sql = "SELECT config_id FROM configuration";
   
   try {
   	
   	$stmt = $this->dbc->query($sql);
   	
   	$founded = $stmt -> rowCount();
   	
   	if ($founded < 1) {
   		
   		return true;
   		
   	} else {
   		
   		return false;
   	}
   	
   } catch (PDOException $e) {
   
   	 $this->dbc = null;

   	 throw new PDOException($e);
   	 
   }
   
 }
 
 protected function statementHandle($sql, $data = NULL)
 {
 	
 	$statement = $this->dbc->prepare($sql);
 	
 	try {
 		
 		$statement->execute($data);
 		
 	} catch (PDOException $e) {
 		
 		$this->dbc = null;
 		
 		$this->error = LogError::newMessage($e);
 		$this->error = LogError::customErrorMessage();
 		
 	}
 	
 	return $statement;
 	
 }
 
 protected function filteringId(Sanitize $sanitize, $str, $type)
 {
 	$this->sanitizing = $sanitize;
 	
 	$sanitized_var = filter_var($str, FILTER_SANITIZE_NUMBER_INT);
 	
 	if (filter_var($sanitized_var, FILTER_VALIDATE_INT)) {
 		
 		return $this->sanitizing->sanitasi($sanitized_var, $type);
 		
 	} else {
 		
 		$exception = "This Id is considered invalid";
 		
 		LogError::newMessage($exception);
 		LogError::customErrorMessage();
 		
 	}
 	
 }
 
}