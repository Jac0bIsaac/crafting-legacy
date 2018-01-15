<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Files extends Model
{
  
  public function __construct()
  {
	
    parent::__construct();
	
  }
  
  public function addFile($title, $fileName, $dateUploaded, $slug)
  {
  	$sql = "INSERT INTO files(file_title, file_name, file_uploaded, file_slug)
  			VALUES(?, ?, ?, ?)";
  	
  	$data = array($title, $fileName, $dateUploaded, $slug);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function updateFile($fileId, $title, $slug, $fileName = '')
  {
  	if (!empty($fileName)) {
  	 
  	 $sql = "UPDATE files SET file_title = ?, file_name = ?, file_slug = ?
  			   WHERE fileID = ?";
  		
  	 $data = array($title, $fileName, $slug, $fileId);
  	 
  	} else {
  	 
  	 $sql = "UPDATE files SET file_title = ?, file_slug = ?
  			 WHERE fileID = ?";
  		
  	 $data = array($title, $slug, $fileId);
  	
  	} 
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
	
  public function deleteFile($fileId, $sanitizing)
  {
  	$cleanFileId = $this->filteringId($sanitizing, $fileId, 'sql');
  	
  	$sql = "DELETE FROM files WHERE fileID = ?";
  	
  	$data = array($cleanFileId);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function updateHits($fileName)
  {
  	global $sanitize;
  	
  	$cleanFileName = $sanitize -> sanitasi($fileName, 'xss');
  	
  	$sql = "UPDATE files SET file_hits = file_hits + 1 WHERE file_name = ?";
  		
  	$data = array($cleanFileName);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function findFiles($position, $limit)
  {
  	$sql = "SELECT fileID, file_title, file_name, file_uploaded, file_hits,
  			file_slug FROM files ORDER BY file_title 
  			DESC LIMIT :position, :limit";
  	
  	$stmt = $this->dbc->prepare($sql);
  	$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  	$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  	
  	try {
  		
  		$stmt -> execute();
  		
  		$files = array();
  		
  		foreach ($stmt -> fetchAll() as $row) {
  			$files[] = $row;
  		}
  		
  		$numbers = "SELECT fileID FROM files";
  		$stmt = $this->dbc->query($numbers);
  		$totalFiles = $stmt -> rowCount();
  		
  		return(array("results" => $files, "totalFiles" => $totalFiles));
  		
  	} catch (PDOException $e) {
  		
  		$this->closeDbConnection();
  		
  		$this->error = LogError::newMessage($e);
  		$this->error = LogError::customErrorMessage();
  		
  	}
  	
  }
  
  public function findFile($id, $sanitizing)
  {
  	
  	$sql = "SELECT fileID, file_title, file_name, 
  			file_slug FROM files WHERE fileID = ?";
  	
  	$id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
  	
  	$data = array($id_sanitized);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  	return $stmt -> fetch();
  	
  }
  
  public function checkFileId($id, $sanitizing)
  {
  
   $sql = "SELECT fileID FROM files WHERE fileID = ?";
   
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
  
}