<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$pageId = isset($_GET['pageId']) ? abs((int)$_GET['pageId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
    include('../cabin/404.php');
} else {
    
   switch ($action) {
        
        case 'newPage':
            
            if (isset($pageId) && $pageId == 0) {
                addPage();
            }
            
            break;
            
        case 'editPage':
            
            if ($pages -> checkPageId($pageId, $sanitize) == false) {
                
                echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&error=pageNotFound">';
                
            } else {
                
              editPage();
                
            }
            
            break;
            
        case 'deletePage':
            
            removePage();
            
            break;
            
        default:
            
            listPages();
            
            break;
            
    }
    
}

function listPages()
{
global $pages;

$views = array();
$views['pageTitle'] = "Pages";

$p = new Pagination();
$limit = 10;
$position = $p -> getPosition($limit);

$data_pages = $pages -> findPages($position, $limit, 'page');
$views['pages'] = $data_pages['results'];
$views['totalPages'] = $data_pages['totalPages'];
$views['position'] = $position;

$totalPage = $p -> totalPage($views['totalPages'], $limit);
$pageLink = $p -> navPage($_GET['order'], $totalPage);
$views['pageLink'] = $pageLink;

if (isset($_GET['error'])) {
	if ($_GET['error'] == "pageNotFound") $views['errorMessage'] = "Error: page Not Found !";
}

if (isset($_GET['status']))  {
	
	if ($_GET['status'] == "pageAdded") $views['statusMessage'] = "New page added";
	if ( $_GET['status'] == "pageUpdated") $views['statusMessage'] = "Page has been updated";
	if ( $_GET['status'] == "pageDeleted") $views['statusMessage'] = "Page deleted";
	
}

require('pages/list-pages.php');
 
}

function addPage()
{
 global $pages, $vid;
 
 $views = array();
 $views['pageTitle'] = "Add new page";
 $views['formAction'] = "newPage";
  
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 
 	$file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 	$file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 	$file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 	$file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
 	$file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
 	
 	$tgl_sekarang = date("Ymd");
 	$title = preventInject($_POST['title']);
 	$content = preventInject($_POST['content']);
 	$slug = makeSlug($title);
 	$post_setting = preventInject($_POST['post_status']);
 	$comment_setting = preventInject($_POST['comment_status']);
 	$type = "page";
 
 	// get filename
 	$file_basename = substr($file_name, 0, strripos($file_name, '.'));
 	
 	// get file extension
 	$file_ext = substr($file_name, strripos($file_name, '.'));
 	
 	$newFileName = renameFile(md5(rand(0, 999) . $file_basename)) . $file_ext;
 	
 	
  try {
 	   
      if (empty($title) || empty($content)) {
          
          throw new RuntimeException('All column with asterisk(*) sign must be filled!');
          
      }
      
      if (empty($file_basename) || empty($file_location)) {
          
          $add_page = $pages -> createPage($vid, $tgl_sekarang, $title, $slug, $content,
              $post_setting, $type, $comment_setting);
          
          echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageAdded">';
          
      } else {
        
          if (!isset($file_error) || is_array($file_error)) {
              throw new RuntimeException('Invalid Parameters.');
          }
          
          switch ($file_error) {
              
              case UPLOAD_ERR_OK:
                  
                  break;
                  
              case UPLOAD_ERR_INI_SIZE:
              case UPLOAD_ERR_FORM_SIZE:
                  throw new RuntimeException('Exceeded filesize limit');
              default:
                  throw new RuntimeException('Unknown errors');
          }
          
          if ($file_size > 524876) {
              throw new RuntimeException('Exceeded filesize limit');
              
          }
          
          $finfo = new finfo(FILEINFO_MIME_TYPE);
          $fileContents = file_get_contents($file_location);
          $mimeType = $finfo -> buffer($fileContents);
          
          $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
          
          $ext = array_search($mimeType, $acceptedImages, true);
          
          if (false === $ext) {
              
            throw new RuntimeException('Invalid file format');
              
          }
          
          uploadPhoto($newFileName);
          
          $add_page = $pages -> createPage($vid, $tgl_sekarang, $title, $slug, $content,
              $post_setting, $type, $comment_setting, $newFileName);
          
          echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageAdded">';
          
      }
      
 	} catch (RuntimeException $e) {
 	    
 	    $views['errorMessage'] = $e -> getMessage();
 	    require('pages/edit-page.php');
 	    
 	}
 	
 } else {
     
     $views['post_setting'] = $pages -> postStatusDropDown();
     $views['comment_setting'] = $pages -> commentStatusDropDown();
     
      require('pages/edit-page.php');
     
 }
 		
}

function editPage()
{
 global $pages, $sanitize, $pageId;
 
 $views = array();
 $views['pageTitle'] = "Edit page";
 $views['formAction'] = "editPage";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
     
 $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
 $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
 $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
 $file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
 $file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
     
 $tgl_sekarang = date("Ymd");
 $title = preventInject($_POST['title']);
 $content = preventInject($_POST['content']);
 $slug = makeSlug($title);
 $post_setting = preventInject($_POST['post_status']);
 $comment_setting = preventInject($_POST['comment_status']);
 $type = "page";
 $page_id = (int)$_POST['page_id'];
 
 // get filename
 $file_basename = substr($file_name, 0, strripos($file_name, '.'));
 
 // get file extension
 $file_ext = substr($file_name, strripos($file_name, '.'));
 
 $newFileName = renameFile(md5(rand(0, 999) . $file_basename)) . $file_ext;

 try {
     
     if (empty($title) || empty($content)) {
         throw new RuntimeException("All Column with asterisk(*) sign must be filled !");
     }
       
     if (empty($file_basename) || empty($file_location)) {
         
        $edit_page = $pages -> updatePage($page_id, $tgl_sekarang, 
            $title, $slug, $content, $post_setting, $comment_setting, $type);
         
         echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageUpdated">';
         
     } else {
       
         if (!isset($file_error) || is_array($file_error)) {
             throw new RuntimeException('Invalid Parameters.');
         }
         
         switch ($file_error) {
             
             case UPLOAD_ERR_OK:
                 
                 break;
                     
             case UPLOAD_ERR_INI_SIZE:
             case UPLOAD_ERR_FORM_SIZE:
                 throw new RuntimeException('Exceeded filesize limit');
             default:
                 throw new RuntimeException('Unknown errors');
         }
         
         if ($file_size > 524876) {
             throw new RuntimeException('Exceeded filesize limit');
             
         }
         
         $finfo = new finfo(FILEINFO_MIME_TYPE);
         $fileContents = file_get_contents($file_location);
         $mimeType = $finfo -> buffer($fileContents);
         
         $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
         
         $ext = array_search($mimeType, $acceptedImages, true);
         
         if (false === $ext) {
             throw new RuntimeException('Invalid file format');
             
         }
         
         uploadPhoto($newFileName);
         
        $edit_page = $pages -> updatePage($page_id, $tgl_sekarang, $title, 
            $slug, $content, $post_setting, $comment_setting, $type, $newFileName);
         
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageUpdated">';
         
     }
     
 } catch (RuntimeException $e) {
     
     $views['errorMessage'] = $e -> getMessage();
     require('pages/edit-page.php');
     
 }
 
 } else {
     
     $data_page = $pages -> findPageById($pageId, 'page', $sanitize);
     $views['page_id'] = $data_page['postID'];
     $views['picture'] = $data_page['post_image'];
     $views['title'] = $data_page['post_title'];
     $views['content'] = $data_page['post_content'];
     $views['type'] = $data_page['post_type'];
     $views['post_setting'] = $pages -> postStatusDropDown($data_page['post_status']);
     $views['comment_setting'] = $pages -> commentStatusDropDown($data_page['comment_status']);
     
     require('pages/edit-page.php');
     
 }
 
}

function removePage()
{
 global $pages, $sanitize, $pageId;
 
 if (!$page = $pages -> findPageById($pageId, 'page', $sanitize)) {
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&error=pageNotFound">';
 }
 
 $picture = $page['post_image'];
 if ($picture != '') {
     
     $delete_page = $pages -> deletePage($pageId, $sanitize, 'page');
     
     if (is_readable("../files/picture/photo/$picture")) {
         
         unlink("../files/picture/photo/$picture");
         unlink("../files/picture/photo/thumb/thumb_$picture");
         
     }
     
 } else {
     
   $delete_page = $pages -> deletePage($pageId, $sanitize, 'page');
     
 }
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=pages&status=pageDeleted">';
 
}