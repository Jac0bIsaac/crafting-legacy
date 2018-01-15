<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Volunteer extends Model
{
			
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function createVolunteer($firstName, $lastName, $userName, $email, 
			                        $password, $phone, $level, $token, $dateReg, 
			                       $timeReg) 
	{
		
		// check email
		$this->checkEmail($email);
		
		$sql = "INSERT INTO volunteer(volunteer_firstName, volunteer_lastName, 
		        volunteer_login, volunteer_email, volunteer_pass, 
		        volunteer_phone, volunteer_level, volunteer_session, 
			    date_registered, time_registered)
			    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		
		$volunteerSesi = self::createSessionKey($token);
		
		$data = array($firstName, $lastName, $userName, $email, 
				    $password, $phone, $level, $volunteerSesi, $dateReg, $timeReg);
		
		
		$stmt = $this->statementHandle($sql, $data);
		
		$volunteer_id = $this -> lastId();
		
		if ($volunteer_id) {
			
			$shieldPass = shieldPass($password, $volunteer_id);
			
			$sesiVolunteer = date("H:i:s") . $password;
		    
			$newSession = self::createSessionKey($shieldPass);
		 
			$updatePassword = "UPDATE volunteer SET volunteer_pass = ?, volunteer_session = ? WHERE ID = '$volunteer_id' ";
			
			$dataPassUpdated = array($shieldPass, $newSession);
			
			$stmt = $this->statementHandle($updatePassword, $dataPassUpdated);
			
			$this -> closeDbConnection();
			
		}
		
	}
	
	public function updateVolunteer($firstName, $lastName, $email, $phone, 
			$level, $ID,  $accessLevel, $password = null)
	{
		try {
			
		if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
			
			if (empty($password)) {
				
				$sql = "UPDATE volunteer SET volunteer_firstName = ?, volunteer_lastName = ?,
						volunteer_email = ?,
						volunteer_phone = ? WHERE ID = ?";
				
				
				$data = array($firstName, $lastName, $email, $phone, $ID);
				
			} else {
				
				$hashPassword = shieldPass($password, $ID);
				
				$sql = "UPDATE volunteer SET volunteer_firstName = ?, volunteer_lastName = ?,
						volunteer_email = ?, volunteer_pass = ?,
						volunteer_phone = ? WHERE ID = ?";
				
				$data = array($firstName, $lastName, $email, $hashPassword, $phone, $ID);
				
			} 
			
			$stmt = $this->statementHandle($sql, $data);
			
		} else {
		
			if (empty($password)) {
				
				$sql = "UPDATE volunteer SET volunteer_firstName = ?, volunteer_lastName = ?,
						volunteer_email = ?,
						volunteer_phone = ?, volunteer_level = ? WHERE ID = ?";
				
				
				$data = array($firstName, $lastName, $email, $phone, $level, $ID);
				
			} else {
				
				$hashPassword = shieldPass($password, $ID);
				
				$sql = "UPDATE volunteer SET volunteer_firstName = ?, volunteer_lastName = ?,
						volunteer_email = ?, volunteer_pass = ?,
						volunteer_phone = ?, volunteer_level = ? WHERE ID = ?";
				
				$data = array($firstName, $lastName, $email, $hashPassword, $phone, $level, $ID);
				
			}
			
			$stmt = $this->statementHandle($sql, $data);
			
		}
			
		} catch (PDOException $e) {
			
		  $this -> closeDbConnection();
		
		  $this->error = LogError::newMessage($e);
		  $this->error = LogError::customErrorMessage();
				 
		}
		
	}
	
	public function deleteVolunteer($ID, $sanitizing)
	{
		
		$cleanId = $this->filteringId($sanitizing, $ID, 'sql');
		
		$sql = "DELETE FROM volunteer WHERE ID = ?";
		
		$data = array($cleanId);
		
		$stmt = $this->statementHandle($sql, $data);
		
	}
	
	public function setVolunteerlevels($selected = '')
	{
		$option_selected = "";
	
		if (!$selected) {
				
			$option_selected = 'selected="selected"';
		}
	
		$levels = array('Administrator', 'Editor', 'Author',  'Contributor', 'WebMaster');
	
		$html = array();
	
		$html[] = '<label>Role*</label>';
		$html[] = '<select class="form-control" name="level">';
		
		foreach ( $levels as $g => $level) {
			
			if ($selected == $level) {
				
				$option_selected = 'selected="selected"';
			}
			
			// set up the option line
			$html[]  =  '<option value="' . $level. '"' . $option_selected . '>' . $level . '</option>';
			
			// clear out the selected option flag
			$option_selected = '';
			
		}
		
		if ( empty($selected) || empty($level))
		{
			$html[] = '<option value="0" selected> -- Select Role -- </option>';
		}
	
		$html[] = '</select>';
		
		return implode("\n", $html);
	
	}
	
	public function findAllVolunteers($position, $limit)
	{
		
		$sql = "SELECT ID, volunteer_firstName, volunteer_lastName, volunteer_login, 
				volunteer_email, volunteer_pass, volunteer_phone, 
				volunteer_level, volunteer_resetKey, 
				volunteer_resetComplete, volunteer_session,
				date_registered, time_registered
				FROM volunteer ORDER BY volunteer_lastName
				LIMIT :position, :limit";
		
		$stmt = $this->dbc->prepare($sql);
		
		$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
		$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
		
		try {
			
			$stmt -> execute();
			
			$volunteers = array();
			
			foreach ($stmt -> fetchAll() as $row) {
				
				$volunteers[] = $row;
			}
			
			$numbers = "SELECT ID FROM volunteer";
			$stmt = $this->dbc->query($numbers);
			$totalVolunteers = $stmt -> rowCount();
			
			return (array("results" => $volunteers, "totalVolunteers" => $totalVolunteers));
			
		} catch (PDOException $e) {
			
			$this->closeDbConnection();
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
			
		}
		
	}
	
	public function findVolunteer($volunteerId, $sanitizing)
	{
	
		$sql = "SELECT ID, volunteer_firstName, volunteer_lastName, 
                volunteer_login, volunteer_email, volunteer_phone, volunteer_level, 
				volunteer_session FROM volunteer 
				WHERE ID = ?";
		
		$id_sanitized = $this->filteringId($sanitizing, $volunteerId, 'sql');
		
		$data = array($id_sanitized);
		
		$stmt = $this -> statementHandle($sql, $data);

		return $stmt -> fetch();
		
	}
	
	public function isUsernameExists($volunteer_login)
	{
		$sql = "SELECT COUNT(ID) FROM volunteer WHERE volunteer_login = ?";
		
		$stmt = $this->dbc->prepare($sql);
		$stmt -> bindValue(1, $volunteer_login);
		
		try {
			$stmt -> execute();
			$rows = $stmt -> fetchColumn();
			
			if ($rows == 1) {
			
				return true;
			
			} else {
			
				return false;
			
			}
			
		} catch (PDOException $e) {
			
			$this->closeDbConnection();
			
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
			
		}
	}
	
	public function checkVolunteerId($id, $sanitizing)
	{
	 
	 $sql = "SELECT ID FROM volunteer WHERE ID = ?";

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
	 	
	 	$this->closeDbConnection();
	 	
	 	$this->error = LogError::newMessage($e);
	 	$this->error = LogError::customErrorMessage();
	 	
	 }
	 
	}
	
	public function checkVolunteerSession($sesi)
	{
      $sql = "SELECT COUNT(ID) FROM volunteer WHERE volunteer_session = ?";
      
      $stmt = $this->dbc->prepare($sql);
      
      $stmt -> bindValue(1, $sesi);
      
      try {
      	$stmt -> execute();
      	$rows = $stmt -> fetchColumn();
      	
      	if ($rows == 1) {
      		
      		return true;
      		
      	} else {
      		
      		return false;
      		
      	}
      	
      } catch (PDOException $e) {
      	
      	$this->closeDbConnection();
      	
      	$this->error = LogError::newMessage($e);
      	$this->error = LogError::customErrorMessage();
      	
      }
      
	}
	
	private function checkEmail($email)
	{
		$sql = "SELECT volunteer_email FROM volunteer WHERE volunteer_email = ?";
	
		$data = array($email);
	
		$sth = $this->statementHandle($sql, $data);
	
		if ( $sth -> rowCount() == 1) {
	
			$e = new Exception("Error: '$email' has been used. Please use other e-mail address !");
	
			throw $e;
			
		}
	
	}
	
	protected static function createSessionKey($key)
	{
	 // create token
	 $salt = 'cTtd*7xMCY-MGHfDagnuC6[+yez/DauJUmHTS).t,b,T6_m@TO^WpkFBbm,L<%C';
	 $key = sha1(mt_rand(10000, 99999) . time(). $salt);
	 return $key;
	}
	
}