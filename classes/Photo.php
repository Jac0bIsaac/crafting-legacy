<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Photo extends Model 
{
  
  protected $linkPhotos;
  
  public function __construct()
  {
    parent::__construct();
	
  }
   
  public function addPhoto($albumId, $title, $slug, $description, $fileName, $date_created)
  {
    $sql = "INSERT INTO photo(album_id, photo_title, photo_slug, 
    		photo_desc, photo_filename, date_created)
    		VALUES(?, ?, ?, ?, ?, ?)";
    
    $data = array($albumId, $title, $slug, $description, $fileName, $date_created);
    
    $stmt = $this->statementHandle($sql, $data);
    
    return $this->lastId();
    
  }
  
  public function updatePhoto($albumId, $title, $slug, $description, $photoID, $fileName = '')
  {
    if (!empty($fileName)) {
      $sql = "UPDATE photo SET album_id = ?, photo_title = ?, 
      		  photo_slug = ?, photo_desc = ?, photo_filename = ? 
      		  WHERE photoID = ?";
      
      $data = array($albumId, $title, $slug, $description, $fileName, $photoID);
      
    } else {
    	
      $sql = "UPDATE photo SET album_id = ?, photo_title = ?,
      		  photo_slug = ?, photo_desc = ?
      		  WHERE photoID = ?";
    	
      $data = array($albumId, $title, $slug, $description, $photoID);
      
    }
    
    $stmt = $this->statementHandle($sql, $data);
    
  }
  
  public function deletePhoto($photoID, $sanitizing)
  {
  	
  	$cleanId = $this->filteringId($sanitizing, $photoID, 'sql');
  
  	$sql = "DELETE FROM photo WHERE photoID = ?";
  	
  	$data = array($cleanId);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function findPhotos($position, $limit)
  {
    $sql = "SELECT photoID, album_id, photo_title, photo_slug, photo_desc,
    		photo_filename, date_created FROM photo ORDER BY photoID DESC 
    		LIMIT :position, :limit";
    
    $stmt = $this->dbc->prepare($sql);
    $stmt -> bindParam(":position", $position, PDO::PARAM_INT);
    $stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
    
    try {
    	
    	$stmt -> execute();
    	
    	$photos = array();
    	
    	foreach ($stmt -> fetchAll() as $row) {
    		
    	$photos[] = $row;
    	
    	} 
    	
    	$numbers = "SELECT photoID from photo";
    	$stmt = $this->dbc->query($numbers);
    	$totalPhotos = $stmt -> rowCount();
    	
    	return(array("results" => $photos, "totalPhotos" => $totalPhotos));
    	
    } catch (PDOException $e) {
    	
    	$this->closeDbConnection();
    	
    	$this->error = LogError::newMessage($e);
    	$this->error = LogError::customErrorMessage();
    
    }
    
  }
   
  public function showPhotosByAlbum(Paginator $perPage, $sanitize)
  {
     $data_pictures = array();
     
     $this->linkPhotos = $perPage;
     
     $pagination = null;
     
      try {
          
        $stmt = $this->dbc->query("SELECT photoID FROM photo");
          
        $this->linkPhotos->set_total($stmt -> rowCount());
          
        $sql = "SELECT
                 p.photoID, p.album_id, p.photo_title, p.photo_slug, p.photo_desc,
                 p.photo_filename, a.album_title, a.album_slug
               FROM photo AS p
               INNER JOIN album AS a ON p.album_id = a.albumID
               ORDER BY a.album_slug DESC " . $this->linkPhotos->get_limit();
     
       $stmt = $this->dbc->query($sql);
       
       foreach ($stmt -> fetchAll() as $results) {
           
          $data_pictures[] = $results;
          
       }
       
       $pagination = $this->linkPhotos->page_links($sanitize);
       
       $totalRows = $stmt -> rowCount();
       
     return(array("allPictures"=>$data_pictures, "totalRows"=>$totalRows, "paginationLink" => $pagination));
          
      } catch (Exception $e) {
          
          $this->closeDbConnection();
          
          $this->error = LogError::newMessage($e);
          $this->error = LogError::customErrorMessage();
          
      }
      
  }
  
  public function findPhoto($id, $sanitizing)
  {
  	
  	$sql = "SELECT photoID, album_id, photo_title, 
  			photo_slug, photo_desc, photo_filename,
  			date_created FROM photo WHERE photoID = ?";
  	
  	$id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
  	
  	$data = array($id_sanitized);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  	return $stmt -> fetch();
  	
  }
  
  public function showPhotoBySlug($slug, $sanitizing)
  {
    $sql = "SELECT p.photoID, p.album_id, p.photo_title, p.photo_slug, p.photo_desc, 
            p.photo_filename, p.date_created, a.album_title
            FROM photo AS p
            INNER JOIN album AS a ON p.album_id = a.albumID
            WHERE p.photo_slug = :slug";
    
    $sanitized = $this->filteringId($sanitizing, $slug, 'xss');
    
    $data = array(":slug" => $sanitized);
    
    $stmt = $this->statementHandle($sql, $data);
    
    return $stmt -> fetch();
    
  }
  
  public function checkPhotoId($id, $sanitizing)
  {
   $sql = "SELECT photoID FROM photo WHERE photoID = ?";
  	
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