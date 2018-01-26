<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$fileId = isset($_GET['fileId']) ? abs((int)$_GET['fileId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster' ) {
	
 include('../cabin/404.php');
	
} else {

	switch ($action) {
		
		case 'newFile':
			
			if (isset($fileId) && $fileId == 0) {
				
				addFile();
				
			}
			
			break;
			
		case 'editFile':
			
			if ($files -> checkFileId($fileId, $sanitize) == false) {
				
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&error=fileNotFound">';
				
			} else {
				
              editFile();
				
			}
			
			break;
			
		case 'deleteFile':
			
			removeFile();
			
			break;
			
		default:
			
			listFiles();
			
			break;
			
	}
	
}

function listFiles()
{
 global $files;
 
 $views = array();
 $views['pageTitle'] = "Files";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_files = $files -> findFiles($position, $limit);
 $views['files'] = $data_files['results'];
 $views['totalFiles'] = $data_files['totalFiles'];
 $views['position'] = $position;
 
 // pagination
 $totalPage = $p ->totalPage($views['totalFiles'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == "fileNotFound") $views['errorMessage'] = "Error:File Not Found !";
 }
 
 if (isset($_GET['status']))  {
 	
 	if ($_GET['status'] == "fileAdded") $views['statusMessage'] = "New File added";
 	if ( $_GET['status'] == "fileUpdated") $views['statusMessage'] = "File updated";
 	if ( $_GET['status'] == "fileDeleted") $views['statusMessage'] = "File deleted";
 	
 }
 
 require('download/list-downloads.php');

}

function addFile()
{
 global $files;
 
 $views = array();
 $views['pageTitle'] = "Add new file";
 $views['formAction'] = "newFile";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 
 	$file_location = isset($_FILES['fdoc']['tmp_name']) ? $_FILES['fdoc']['tmp_name'] : '';
 	$file_type = isset($_FILES['fdoc']['type']) ? $_FILES['fdoc']['type'] : '';
 	$file_name = isset($_FILES['fdoc']['name']) ? $_FILES['fdoc']['name'] : '';
 	$file_size = isset($_FILES['fdoc']['size']) ? $_FILES['fdoc']['size'] : '';
 	$file_error = isset($_FILES['fdoc']['error']) ? $_FILES['fdoc']['error'] : '';
 	
 	$title = preventInject($_POST['title']);
 	$date_uploaded = date("Ymd");
 	$slug = makeSlug($title);

 	$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 	
 	// get file extension
 	$file_ext = substr($file_name, strripos($file_name, '.'));
 	
 	$newFileName = renameFile(rand(0, 99).$file_basename) . $file_ext;
 	
 	try {
 	   
 	 if (!isset($file_error) || is_array($file_error)) {
 	        
 	   throw new RuntimeException('Invalid Parameters.');
 	        
 	 }
 	 
 	 switch ($file_error) {
 	     
 	     case UPLOAD_ERR_OK:
 	         
 	         break;
 	         
 	     case UPLOAD_ERR_NO_FILE:
 	         throw new RuntimeException('No file uploaded.');
 	         
 	     case UPLOAD_ERR_INI_SIZE:
 	     case UPLOAD_ERR_FORM_SIZE:
 	         throw new RuntimeException('Exceeded filesize limit.');
 	     default:
 	         throw new RuntimeException('Unknown errors.');
 	 }
 	 
 	 if ($file_size > 524876) {
 	     throw new RuntimeException('Exceeded filesize limit. Maximum file size is '.formatSizeUnits(524876));
 	 }
 	  
 	 if (empty($title)) {
 	   throw new RuntimeException('Please insert a file title');
 	 }
 	 
 	 $finfo = new finfo(FILEINFO_MIME_TYPE);
 	 $fileContents = file_get_contents($file_location);
 	 $mimeType = $finfo -> buffer($fileContents);
 	 
 	 $acceptedFiles = array('pdf' => 'application/pdf', 'doc' => 'application/msword', 'rar' => 'application/rar', 
 	               'zip' => 'application/zip', 'xls' => 'application/vnd.ms-excel', 'xls' => 'application/octet-stream', 
 	               'exe' => 'application/octet-stream', 'ppt' => 'application/vnd.ms-powerpoint',
 	               'jpeg' => 'image/jpg', 'jpg' => 'image/jpg', 'png' => 'image/png', 'gif' => 'image/gif');
 	 
 	 $ext = array_search($mimeType, $acceptedFiles, true);
 	 
 	 if (false === $ext) {
 	   throw new RuntimeException('Invalid file format.');
 	 }
 	 
 	 
 	 
 	 uploadFile($newFileName);
 	 
 	 $add_file = $files -> addFile($title, $newFileName, $date_uploaded, $slug);
 	 
 	 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileAdded">';
 	 
 	} catch (RuntimeException $e) {
 	    
 	   $views['errorMessage'] = $e -> getMessage();
 	   require('download/edit-download.php');
 	   
 	}
 	
 } else {
 	
 	require('download/edit-download.php');
 	
 }
	
}

function editFile()
{
 global $files, $fileId, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Edit file";
 $views['formAction'] = "editFile";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$file_location = isset($_FILES['fdoc']['tmp_name']) ? $_FILES['fdoc']['tmp_name'] : '';
 	$file_type = isset($_FILES['fdoc']['type']) ? $_FILES['fdoc']['type'] : '';
 	$file_name = isset($_FILES['fdoc']['name']) ? $_FILES['fdoc']['name'] : '';
 	$file_size = isset($_FILES['fdoc']['size']) ? $_FILES['fdoc']['size'] : '';
 	
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	$file_id = (int)$_POST['file_id'];
 	
 	if (empty($file_location)) {
 	
 	$edit_file = $files -> updateFile($file_id, $title, $slug);
 	
 	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileAdded">';
 		
 	
 	} else {
 		
 		$file_extension = strtolower(substr(strrchr($file_name,"."),1));
 		
 		switch ($file_extension)
 		{
 			
 			case "pdf": $ctype="application/pdf"; break;
 			case "exe": $ctype="application/octet-stream"; break;
 			case "zip": $ctype="application/zip"; break;
 			case "rar": $ctype="application/rar"; break;
 			case "doc": $ctype="application/msword"; break;
 			case "xls": $ctype="application/vnd.ms-excel"; break;
 			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
 			case "gif": $ctype="image/gif"; break;
 			case "png": $ctype="image/png"; break;
 			case "jpeg":
 			case "jpg": $ctype="image/jpg"; break;
 			default: 
 			   
 			 throw new RuntimeException("Unknown Errors");
 			
 		}
 		
 		if ($file_extension == 'php' || $file_extension == 'sh'
 				|| $file_extension == '.bash' || $file_extension == '.pl'
 				|| $file_extension == '.py') {
 					$views['errorMessage'] = "upload fail, forbidden file extension !";
 					require('download/edit-download.php');
 					
 				} else {
 					
 					$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 					
 					// get file extension
 					$file_ext = substr($file_name, strripos($file_name, '.'));
 					
 					$newFileName = renameFile(rand(0, 99).$file_basename) . $file_ext;
 					
 					uploadFile($newFileName);
 					
 					$edit_file = $files -> updateFile($fileId, $title, $slug);
 					
 					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileAdded">';
 					
 				}
 				
 	}
 	
 } else {
 	
    $file = $files -> findFile($fileId, $sanitize);
 	$views['fileID'] = $file['fileID'];
 	$views['file_title'] = $file['file_title'];
 	$views['file_name'] = $file['file_name'];
 	
 	require('download/edit-download.php');
 	
 }
 
}

// delete Download File
function removeFile()
{
 global $files, $fileId, $sanitize;
 
 if (!$file = $files -> findFile($fileId, $sanitize)) {
    
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&error=fileNotFound">';
    
 }
 
 $fileName = $file['file_name'];
 if ($fileName != '') {
 	  
    $delete_File = $files -> deleteFile($fileId, $sanitize);
     
    if (is_readable("../files/document/$fileName")) {
 	 
      unlink("../files/document/$fileName");
        
    }
 	
 } else {
     
     $delete_File = $files -> deleteFile($fileId, $sanitize);
     
 }
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=files&status=fileDeleted">';
 
}