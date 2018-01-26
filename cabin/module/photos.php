<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$photoId = isset($_GET['photoId']) ? abs((int)$_GET['photoId']) : 0;
$accessLevel = $authentication  -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster' && $accessLevel != 'Editor' && $accessLevel != 'Author') {
 include('../cabin/404.php');

} else {
    
	switch ($action) {
		
		case 'newPhoto':
			
			if (isset($photoId) && $photoId == 0) {
				
				addPhoto();
				
			}
			
			break;
			
		case 'editPhoto':
			
			if($photos -> checkPhotoId($photoId, $sanitize) == false) {
				
		    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=photos&error=photoNotFound">';
				
			} else {
			    
				editPhoto();
				
			}
			
			break;
			
		case 'deletePhoto':
			
		    removePhoto();
			
			break;
			
		default:
			listPhotos();
			break;
			
	}
	
}

function listPhotos()
{
 global $photos;
 
 $views = array();
 $views['pageTitle'] = 'Photos';
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_photos = $photos -> findPhotos($position, $limit);
 
 $views['photos'] = $data_photos['results'];
 $views['totalPhotos'] = $data_photos['totalPhotos'];
 $views['position'] = $position;
 
 // pagination
 $totalPage = $p -> totalPage($views['totalPhotos'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == "photoNotFound") $views['errorMessage'] = "Error:Photo not found !";
 }
 
 if (isset($_GET['status']))  {
 	
 	if ($_GET['status'] == 'photoAdded') $views['statusMessage'] = "New photo added";
 	if ( $_GET['status'] == "photoUpdated") $views['statusMessage'] = "Photo saved";
 	if ( $_GET['status'] == "photoDeleted") $views['statusMessage'] = "Photo deleted";
 	
 }
 
 require('photos/list-photos.php');
 
}

// add new photo
function addPhoto()
{
 global $photos, $albums, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Add new photo";
 $views['formAction'] = "newPhoto";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 
 	$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 	$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 	$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 	$file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
 	$file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
 	
 	$album_id = (int)$_POST['album_id'];
 	$photo_title = preventInject($_POST['title']);
 	$photo_description = preventInject($_POST['description']);
 	$photo_slug = makeSlug($photo_title);
 	$date_uploaded = date("Ymd");
 	
 	// get file name
 	$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 	
 	// get file extension
 	$file_ext = substr($file_name, strripos($file_name, '.'));
 	
 	// rename file
 	$newFileName = renameFile(md5($file_basename)) . $file_ext;
 	
 try {
 	 
 	if (empty($photo_title)) {
 	        
 	 throw new RuntimeException('All column with asterisk(*) sign is required!');
 	        
 	}
 	    
 	if (!isset($file_error) || is_array($file_error)) {
 	     
 	     throw new RuntimeException('Invalid Parameters.');
 	     
 	 }
 	 
 	 switch ($file_error) {
 	     
 	     case UPLOAD_ERR_OK:
 	         
 	         break;
 	     
 	     case UPLOAD_ERR_NO_FILE:
 	         throw new RuntimeException('Please select a picture to upload.');
 	         
 	     case UPLOAD_ERR_INI_SIZE:
 	     case UPLOAD_ERR_FORM_SIZE:
 	         throw new RuntimeException('Exceeded file size limit.');
 	     default:
 	         throw new RuntimeException('Unknown errors.');
 	 }
 	 
 	 if ($file_size > 697856) {
 	     
 	     throw new RuntimeException('Exceeded file size limit.Maximum file size: '.formatSizeUnits(697856));
 	     
 	 }
 	 
 	 $finfo = new finfo(FILEINFO_MIME_TYPE);
 	 $fileContents = file_get_contents($file_location);
 	 $mimeType = $finfo -> buffer($fileContents);
 	 
 	 $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
 	 
 	 $ext = array_search($mimeType, $acceptedImages, true);
 	 
 	 if (false === $ext) {
 	     
 	  throw new RuntimeException('Invalid file format type.Only these file format type are allowed : .jpg, .gif and .png');
 	     
 	 } 
 	 
 	 if (isset($_POST['album_id']) && $_POST['album_id'] == 0) {
 	     
 	     // insert new record to table album
 	     $idAlbum = $albums -> createAlbum('Uncategorized', 'uncategorized', date("Ymd"));
 	     
 	     // get albums
 	     $getAlbum = $albums -> findAlbum($idAlbum, $sanitize);
 	     
 	     unlink("../files/picture/photo/$newFileName");
 	     unlink("../files/picture/photo/thumb/thumb_$newFileName");
 	     
 	     uploadPhoto($newFileName);
 	     
 	     $addPhoto = $photos -> addPhoto($getAlbum['albumID'], 
 	         $photo_title, $photo_slug, $photo_description, $newFileName, date("Ymd"));
 	     
 	 } else {
 	     
 	     unlink("../files/picture/photo/$newFileName");
 	     unlink("../files/picture/photo/thumb/thumb_$newFileName");
 	     
 	     uploadPhoto($newFileName);
 	     
 	     $addPhoto = $photos -> addPhoto($album_id, $photo_title, 
 	         $photo_slug, $photo_description, $newFileName, date("Ymd"));
 	     
 	 }
 	 
 	 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=photos&status=photoAdded">';
 	 
 	} catch (RuntimeException $e) {
 	    
 	   $views['errorMessage'] = $e -> getMessage();
 	   require('photos/edit-photo.php');
 	}
 	
 } else {
 	
 	$views['albumDropDown'] = $albums -> setAlbumDropDown();
 
 	require('photos/edit-photo.php');
 	
 }
 
}

function editPhoto()
{
global $photos, $albums, $photoId, $sanitize;

$views = array();
$views['pageTitle'] = "Edit Photo";
$views['formAction'] = "editPhoto";
	
if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
		
$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
$file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
$file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';

$album_id = (int)$_POST['album_id'];
$photo_title = preventInject($_POST['title']);
$photo_description = preventInject($_POST['description']);
$photo_slug = makeSlug($photo_title);
$photo_id = (int)$_POST['photo_id'];
	    
// get file name
$file_basename = substr($file_name, 0, strripos($file_name, '.'));
	    
$file_ext = substr($file_name, strripos($file_name, '.'));
	    
$newFileName = renameFile(md5($file_basename)) . $file_ext;

try {
 
if (empty($photo_title)) {
        
  throw new RuntimeException('All column with asterisk(*) sign is required!');
        
}

if (empty($file_basename) || empty($file_location)) {
$edit_photo = $photos -> updatePhoto($album_id, $photo_title, $photo_slug, $photo_description, $photo_id);

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=photos&status=photoAdded">';

} else {
    
   if (!isset($file_error) || is_array($file_error)) {
        throw new RuntimeException('Invalid Parameters.');
   }
   
   switch ($file_error) {
       
       case UPLOAD_ERR_OK:
           
           break;
           
       case UPLOAD_ERR_INI_SIZE:
       case UPLOAD_ERR_FORM_SIZE:
           throw new RuntimeException('Exceeded file size limit.');
       default:
           throw new RuntimeException('Unknown errors.');
   }
   
   if ($file_size > 697856) {
       
       throw new RuntimeException('Exceeded file size limit.Maximum file size: '.formatSizeUnits(697856));
       
   }
   
   $finfo = new finfo(FILEINFO_MIME_TYPE);
   $fileContents = file_get_contents($file_location);
   $mimeType = $finfo -> buffer($fileContents);
   
   $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
   
   $ext = array_search($mimeType, $acceptedImages, true);
   
   if (false === $ext) {
       
    throw new RuntimeException('Invalid file format.');
       
   }
   
   unlink("../files/picture/photo/$newFileName");
   unlink("../files/picture/photo/thumb/thumb_$newFileName");
   
   uploadPhoto($newFileName);

   $edit_photo = $photos -> updatePhoto($album_id, $photo_title, $photo_slug, 
       $photo_description, $photo_id, $newFileName);
   
   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=photos&status=photoAdded">';
   
   
}

} catch (RuntimeException $e) {
    
   $views['errorMessage'] = $e -> getMessage();
   require('photos/edit-photo.php');
   
}
	   
} else {
		
 $data_photos = $photos -> findPhoto($photoId, $sanitize);
 $views['photoID'] = $data_photos['photoID'];
 $views['photo_title'] = $data_photos['photo_title'];
 $views['photo_desc'] = $data_photos['photo_desc'];
 $views['picture'] = $data_photos['photo_filename'];
 $views['albumDropDown'] = $albums -> setAlbumDropDown($data_photos['album_id']);
 require('photos/edit-photo.php');
 
}
	
}

function removePhoto()
{
 global $photos, $photoId, $sanitize;
 
 if (!$photo = $photos -> findPhoto($photoId, $sanitize)) {
     
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=photos&error=photoNotFound">';
  
 }
 
 $picture = $photo['photo_filename'];
 if ($picture != '') {
 	
   $delete_photo = $photos -> deletePhoto($photoId, $sanitize);
    
    if (is_readable("../files/picture/photo/$picture")) {
        
        unlink("../files/picture/photo/$picture");
        unlink("../files/picture/photo/thumb/thumb_$picture");
        
    } 
 	   
 } else {
     
     $delete_photo = $photos -> deletePhoto($photoId, $sanitize);
     
 }
 
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=photos&status=photoDeleted">';
 
}