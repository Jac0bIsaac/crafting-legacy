<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$albumId = isset($_GET['albumId']) ? abs((int)$_GET['albumId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster' 
		&& $accessLevel != 'Editor' && $accessLevel != 'Author') {
	
  include('../cabin/404.php');
  
} else {
 
 switch ($action) {
		
  case 'newAlbum':
			
	if (isset($albumId) && $albumId == 0) {
	    addAlbum();
	}
			
	break;
			
  case 'editAlbum':
			
	if ($albums -> checkAlbumId($albumId, $sanitize) == false) {
				
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&error=albumNotFound">';
				
	} else {
				
	  editAlbum();
				
	}
			
	break;
			
  case 'deleteAlbum':
			
    removeAlbum();
			
	break;
			
  default:
			
    listAlbums();
			
	break;
	
 }
	
}

// menampilkan semua album
function listAlbums()
{
 global $albums;
 
 $views = array();
 $views['pageTitle'] = "Albums";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_albums = $albums -> findAlbums($position, $limit);
 $views['albums'] = $data_albums['results'];
 $views['totalAlbums'] = $data_albums['totalAlbums'];
 $views['position'] = $position;
 
 // pagination 
 $totalPage = $p -> totalPage($views['totalAlbums'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == "albumNotFound") $views['errorMessage'] = "Error:Album Not Found !";
 }
 
 if (isset($_GET['status']))  {
 	
 	if ($_GET['status'] == "albumAdded") $views['statusMessage'] = "New album added";
 	if ( $_GET['status'] == "albumUpdated") $views['statusMessage'] = "Album updated";
 	if ( $_GET['status'] == "albumDeleted") $views['statusMessage'] = "Album deleted";
 	
 }
 
 require('albums/list-albums.php');
 
}

// add new album
function addAlbum()
{
 
 global $albums;
 
 $views = array();
 $views['pageTitle'] = "Add new album";
 $views['formAction'] = "newAlbum";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 	$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 	$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 	$file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
 	$file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
 	
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	$date_created = date("Ymd");
 	
 	// get file name
 	$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 	
 	// get file extension
 	$file_ext = substr($file_name, strripos($file_name, '.'));
 	
 	// rename filename
 	$newFileName = renameFile(md5($file_basename)) . $file_ext;
 	
 try {
 	    
 	   if (empty($title)) {
 	        
 	       throw new RuntimeException('Please fill out a title field');
 	       
 	    }
 	    
 	    if (empty($file_basename) || empty($file_location)) {
 	        
 	        $add_album = $albums -> createAlbum($title, $slug, $date_created);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&status=albumAdded">';
 	        
 	    } else {
 	        
 	        if (!isset($file_error) || is_array($file_error)) {
 	            
 	            throw new RuntimeException('Invalid Parameters.');
 	            
 	        }
 	        
 	        switch ($file_error) {
 	            
 	            case UPLOAD_ERR_OK:
 	                
 	                break;
 	                
 	            case UPLOAD_ERR_INI_SIZE:
 	            case UPLOAD_ERR_FORM_SIZE:
 	                throw new RuntimeException('Exceeded filesize limit.');
 	            default:
 	                throw new RuntimeException('Unknown errors.');
 	        }
 	        
 	        if ($file_size > 697856) {
 	            
 	            throw new RuntimeException('Exceeded filesize limit.Maximum file size: '.formatSizeUnits(697856));
 	            
 	        }
 	        
 	        $finfo = new finfo(FILEINFO_MIME_TYPE);
 	        $fileContents = file_get_contents($file_location);
 	        $mimeType = $finfo -> buffer($fileContents);
 	        
 	        $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
 	        
 	        $ext = array_search($mimeType, $acceptedImages, true);
 	        
 	        if (false === $ext) {
 	            
 	            throw new RuntimeException('Invalid file format type.Only these file format type are allowed : .jpg, .gif and .png');
 	            
 	        }
 	        
 	        if (is_readable("../files/picture/album/".$newFileName)) unlink("../files/picture/album/".$newFileName);
 	        
 	        uploadAlbum($newFileName);
 	        
 	        $add_album = $albums -> createAlbum($title, $slug, $date_created, $newFileName);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&status=albumAdded">';
 	        
 	    }
 	    
 	} catch (RuntimeException $e) {
 	    
 	   $views['errorMessage'] = $e -> getMessage();
 	   require('albums/edit-album.php');
 	   
 	}
 	
 } else {
 	
 	$views['album'] = $albums;
 	require('albums/edit-album.php');
 	
 }
 
}

// edit album
function editAlbum()
{

 global $albums, $sanitize, $albumId;
 
 $views = array();
 $views['pageTitle'] = "Edit album";
 $views['formAction'] = "editAlbum";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 	$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 	$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 	$file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
 	$file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
 	
 	$album_id = (int)$_POST['album_id'];
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	
 	$date_modified = date("Ymd");
 	
 	// get file name
 	$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 	
 	// get file extension
 	$file_ext = substr($file_name, strripos($file_name, '.'));
 	
 	// rename filename
 	$newFileName = renameFile(md5($file_basename)) . $file_ext;
 	
try {
 	 
    if (empty($title)) {
        
       throw new RuntimeException('Please fill out title field');
       
    }
    
    if (empty($file_basename) || empty($file_location)) {
        
        $edit_album = $albums -> updateAlbum($title, $slug, $date_modified, $album_id);
        
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&status=albumUpdated">';
        
    } else {
        
        if (!isset($file_error) || is_array($file_error)) {
            
            throw new RuntimeException('Invalid Parameters.');
            
        }
        
        switch ($file_error) {
            
            case UPLOAD_ERR_OK:
                
                break;
                
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
        
        if ($file_size > 697856) {
            
            throw new RuntimeException('Exceeded filesize limit.Maximum file size: '.formatSizeUnits(697856));
            
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $fileContents = file_get_contents($file_location);
        $mimeType = $finfo -> buffer($fileContents);
        
        $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
        
        $ext = array_search($mimeType, $acceptedImages, true);
        
        if (false === $ext) {
            
            throw new RuntimeException('Invalid file format type.Only these file format type are allowed : .jpg, .gif and .png');
            
        }
        
        if (is_readable("../files/picture/album/$newFileName")) unlink("../files/picture/album/$newFileName");
        
        uploadAlbum($newFileName);
        
        $edit_album = $albums -> updateAlbum($title, $slug, $date_modified, $album_id, $newFileName);
        
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&status=albumUpdated">';
        
    }
    
 	} catch (RuntimeException $e) {
 	    
 	    $views['errorMessage'] = $e -> getMessage();
 	    require('albums/edit-album.php');
 	}
 	
 } else {
 	
 	$album = $albums -> findAlbum($albumId, $sanitize);
 	$views['albumID'] = $album['albumID'];
 	$views['album_title'] = $album['album_title'];
 	$views['album_picture'] = $album['album_picture'];
 	$views['album_slug'] = $album['album_slug'];
 	
 	require('albums/edit-album.php');
 	
 }
 
}

// remove album
function removeAlbum()
{
 global $albums, $sanitize, $albumId;
 
 if (!$album = $albums -> findAlbum($albumId, $sanitize)) {
 
 	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&error=albumNotFound">';
 }
 
 $pictureAlbum = $album['album_picture'];
 if ($pictureAlbum != '') {
 	
    $deleteAlbum = $albums -> deleteAlbum($albumId, $sanitize);
     
    if (is_readable("../files/picture/album/$pictureAlbum")) {
       unlink("../files/picture/album/$pictureAlbum");
     }
  
 } else {
     
     $deleteAlbum = $albums -> deleteAlbum($albumId, $sanitize);
     
 }
 
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=albums&status=albumDeleted">';
 
  
}