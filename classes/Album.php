<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Album extends Model
{
  
  public function __construct()
  {	
    parent::__construct();
  }
  
  public function createAlbum($albumTitle, $slug, $date_created, $albumPicture = '')
  {
  	if (!empty($albumPicture)) {
  		
  	  $sql = "INSERT INTO album(album_title, album_picture, album_slug, 
  	  		date_created)
  			VALUES(?, ?, ?, ?)";
  	
  	  $data = array($albumTitle, $albumPicture, $slug, $date_created);
  	
  	} else {
  	
  	  $sql = "INSERT INTO album(album_title, album_slug, date_created)
  			 VALUES(?, ?, ?)";
  	  
  	  $data = array($albumTitle, $slug, $date_created);
  	   
  	}
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  	return $this->lastId();
  	
  }
  
  public function updateAlbum($albumTitle, $slug, $date_modified, $albumID, $albumPicture = '')
  {
    if (!empty($albumPicture)) {
      $sql = "UPDATE album SET album_title = ?, album_picture = ?, 
      		 album_slug = ?, date_modified = ? WHERE albumID = ? "; 
      
      $data = array($albumTitle, $albumPicture, $slug, $date_modified, $albumID);
      
    } else {
    	
      $sql = "UPDATE album SET album_title = ?,
      		 album_slug = ?, date_modified = ? WHERE albumID = ?";
      
      $data = array($albumTitle, $slug, $date_modified, $albumID);
      
    }
    
    $stmt = $this->statementHandle($sql, $data);
    
  }
  
  public function deleteAlbum($albumId, $sanitizing)
  {
  	$cleanAlbumId = $this->filteringId($sanitizing, $albumId, 'sql');
  	
  	$sql = "DELETE FROM album WHERE albumID = ?";
  	
  	$data = array($cleanAlbumId);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function findAlbums($position = '', $limit = '')
  {
  	
  	try {
  	
  	    $albums = array();
  	    
  		if (empty($position) && empty($limit)) {
  			
  			$sql = "SELECT albumID, album_title, album_picture,
  			        album_slug, date_created, date_modified
  			        FROM album ORDER BY album_title";
  			
  			$stmt = $this->dbc->query($sql);
  		
  			foreach ($stmt -> fetchAll() as $row) {
  				$albums[] = $row;
  			}
  			
  			return $albums;
  			
  		} else {
  			
  			$sql = "SELECT albumID, album_title, album_picture,
  			album_slug, date_created, date_modified
  			FROM album ORDER BY album_title DESC LIMIT :position, :limit";
  			
  			$stmt = $this->dbc->prepare($sql);
  			
  			$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  			$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  			
  			$stmt -> execute();
  		  			
  			foreach ( $stmt -> fetchAll() as $row) {
  				
  				$albums[] = $row;
  			}
  			
  			$numbers = "SELECT albumID FROM album";
  			$stmt = $this->dbc->query($numbers);
  			$totalAlbums = $stmt -> rowCount();
  			
  			return(array("results" => $albums, "totalAlbums" => $totalAlbums));
  			
  		}
  		
  	} catch (PDOException $e) {
  		
  		$this->closeDbConnection();
  		
  		$this->error = LogError::newMessage($e);
  		$this->error = LogError::customErrorMessage();
  		
  	}
  		
  }
  
  public function findAlbum($id, $sanitizing)
  {
  	
  	$sql = "SELECT albumID, album_title, album_picture, album_slug,
  			date_created, date_modified FROM album WHERE albumID = ?";
  
  	$id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
  	
  	$data = array($id_sanitized);
  	
  	$stmt = $this->statementHandle($sql, $data);
  		
  	return $stmt -> fetch();
  	
  }
  
  public function showAlbums()
  {
      $data_album = array();
      
      $sql = "SELECT album.albumID, album.album_title, album.album_picture, album.album_slug,
          COUNT(photo.photoID) AS total_picture
          FROM album LEFT JOIN photo
          ON album.albumID = photo.album_id
          WHERE album.albumID GROUP BY album.album_title";
      
      $stmt = $this->dbc->query($sql);
      
      while ($row = $stmt -> fetch()) {
          $data_album[] = $row;
      }
      
      return $data_album;
      
  }
  
  public function checkAlbumId($id, $sanitizing)
  {
  	
    $sql = "SELECT albumID FROM album WHERE albumID = ?";
   
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
  
  public function setAlbumDropDown($selected = '') 
  {
    $option_selected = '';
    
    if ($selected) {
    	
    	$option_selected = 'selected="selected"';
    }
    
    // get albums
    $albums = $this->findAlbums();
    
    $html  = array();
    
    $html[] = '<label>Select Album</label>';
    $html[] = '<select class="form-control" name="album_id">';
    
    foreach ($albums as $album) {
    	
      if ((int)$selected == (int)$album['albumID']) {
      	
      	$option_selected='selected="selected"';
      	
      }
      
      $html[] = '<option value="'.$album['albumID'].'"'.$option_selected.'>'. $album['album_title'] . '</option>';
      
      // clear out the selected option flag
      $option_selected = '';
      
    } // end of foreach
    
    if (empty($selected) || empty($album['albumID'])) {
    	
     $html[] = '<option value="0" selected>-- Uncategorized --<option>';
    	
    }
    
    $html[] = '</select>';
    
    return implode("\n", $html);
    
  }
  
}