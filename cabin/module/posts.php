<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$postId = isset($_GET['postId']) ? abs((int)$_GET['postId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster' 
    && $accessLevel != 'Editor' && $accessLevel != 'Author' && $accessLevel != 'Contributor') {
        require('../cabin/404.php');
} else {
    
    switch ($action) {
        
        case 'newPost':
            
          if (isset($postId) && $postId == 0) {
            addPost();
          }  
            break;
            
        case 'editPost':
            
            if ($posts -> checkPostById($postId, $sanitize) == false) {
                
                echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&error=postNotFound">';
                
            } else {
                
                editPost();
                
            }
            
            break;
            
        case 'deletePost':
            
            removePost();
            
            break;
            
        default:
            
            listPosts();
            
            break;
            
    }
    
}

function listPosts()
{
 global $posts;
 
 $views = array();
 $views['pageTitle'] = "Posts";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_posts = $posts -> findPosts($position, $limit);
 $views['posts'] = $data_posts['results'];
 $views['totalPosts'] = $data_posts['totalPosts'];
 $views['position'] = $position;
 
 $totalPage = $p -> totalPage($views['totalPosts'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == "postNotFound") $views['errorMessage'] = "Error:Post Not Found !";
 }
 
 if (isset($_GET['status']))  {
 	
 	if ($_GET['status'] == "postAdded") $views['statusMessage'] = "New post added";
 	if ( $_GET['status'] == "postUpdated") $views['statusMessage'] = "Post has been updated";
 	if ( $_GET['status'] == "postDeleted") $views['statusMessage'] = "Post deleted";
 	
 }
 
 require('posts/list-posts.php');
 
}

function addPost()
{

 global $posts, $categories, $vid, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Add New Post";
 $views['formAction'] = 'newPost';
 
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
    $post_setting = $_POST['post_status'];
    $comment_setting = $_POST['comment_status'];
    
    // get filename
    $file_basename = substr($file_name, 0, strripos($file_name, '.'));
    
    // get file extension
    $file_ext = substr($file_name, strripos($file_name, '.'));
    
    $newFileName = renameFile(md5(rand(0, 999) . $file_basename)) . $file_ext;
    
    try {
        
       if (empty($title) || empty($content)) {
           
        throw new RuntimeException('All column with asterisk(*) sign is required!');
       
       }
         
       if (empty($file_location) || empty($file_basename)) {
              
           if (isset($_POST['catID']) && $_POST['catID'] == 0) {
           
           // insert uncategorized to table category
           $idCategory = $categories -> createCategory('Uncategorized', 'uncategorized');
           
           // get record from table category
           $getCategory = $categories -> findCategory($idCategory, $sanitize);
           
           // insert new record to table post and post_category
           $add_post = $posts -> createPost($getCategory['categoryID'], 
               $vid, $tgl_sekarang, $title, $slug, $content, 
               $post_setting, $comment_setting);
           
           } else {
           
            $add_post = $posts -> createPost($_POST['catID'],
                  $vid, $tgl_sekarang, $title, $slug, $content,
                  $post_setting, $comment_setting);
              
           }
          
          echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postAdded">';
      
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
             
             throw new RuntimeException('Invalid file format.');
               
           }
             
           if (isset($_POST['catID']) && $_POST['catID'] == 0) {
               
               
               // insert uncategorized to table category
               $idCategory = $categories -> createCategory('Uncategorized', 'uncategorized');
               
               // get record from table category
               $getCategory = $categories -> findCategory($idCategory, $sanitize);
               
               uploadPhoto($newFileName);
               
               $add_post = $posts -> createPost($getCategory['categoryID'], $vid, $tgl_sekarang,
                   $title, $slug, $content, $post_setting, $comment_setting, $newFileName);
               
           } else {
               
               uploadPhoto($newFileName);
               
               $add_post = $posts -> createPost($_POST['catID'], $vid, $tgl_sekarang,
                   $title, $slug, $content, $post_setting, $comment_setting, $newFileName);
               
           }
            
           echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postAdded">';
           
       }
      
    } catch (RuntimeException $e) {
        
       $views['errorMessage'] = $e -> getMessage();
       require('posts/edit-post.php');
        
    }
    
 } else {
 	
    $views['category'] = $categories -> setCategoryChecked();
 	$views['post_setting'] = $posts -> postStatusDropDown();
 	$views['comment_setting'] = $posts -> commentStatusDropDown();
 	
 	require('posts/edit-post.php');
 	
 }
 
}

function editPost()
{
 global $posts, $categories, $postId, $sanitize, $vid;
    
 $views = array();
 $views['pageTitle'] = "Edit post";
 $views['formAction'] = "editPost";
 
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
     $post_id = (int)$_POST['post_id'];
     
     // get filename
     $file_basename = substr($file_name, 0, strripos($file_name, '.'));
     
     // get file extension
     $file_ext = substr($file_name, strripos($file_name, '.'));
     
     $newFileName = renameFile(md5(rand(0, 999) . $file_basename)) . $file_ext;
       
 	try {
 	    
 	    if (empty($title) || empty($content)) {
 	        
 	     throw new RuntimeException('All column with asterisk(*) sign is required!');
 	        
 	    }
 	   
 	    if (empty($file_location) || empty($file_basename)) {
 	        
 	       $edit_post = $posts -> updatePost($post_id, $_POST['catID'], 
 	           $vid, $tgl_sekarang, $title, 
 	           $slug, $content, $post_setting, $comment_setting);
 	        
 	      echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postUpdated">';
 	        
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
 	            
 	         throw new RuntimeException('Invalid file format.');
 	            
 	        } 
 	        
 	        uploadPhoto($newFileName);
 	        
 	        $edit_post = $posts -> updatePost($post_id, $_POST['catID'],
 	            $vid, $tgl_sekarang, $title, $slug, 
 	            $content, $post_setting, $comment_setting,  $newFileName);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postUpdated">';
 	        
 	    }
 	    
 	} catch (RuntimeException $e) {
 	    
 	    $views['errorMessage'] = $e -> getMessage();
 	    require('posts/edit-post.php');
 	     
 	}
 	
 } else {
 	
 	$data_posts = $posts -> findPost($postId, $sanitize);
 	$views['post_id'] = $data_posts['postID']; 
 	$views['postImage'] = $data_posts['post_image'];
 	$views['author'] = $data_posts['post_author'];
 	$views['title'] = htmlspecialchars($data_posts['post_title']);
 	$views['content'] = $data_posts['post_content'];
 	$views['category'] = $categories -> setCategoryChecked($data_posts['postID']);
 	$views['post_setting'] = $posts -> postStatusDropDown($data_posts['post_status']);
 	$views['comment_setting'] = $posts -> commentStatusDropDown($data_posts['comment_status']);
 	
 	require('posts/edit-post.php');
 	
 }
 
}

function removePost()
{
 global $posts, $sanitize, $postId;
 
 if (!$post = $posts -> findPost($postId, $sanitize)) {
   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&error=postNotFound">';
 }
 
 $picture = $post['post_image'];
 if ($picture != '') {
 
   $delete_post = $posts -> deletePost($postId, $sanitize);
   
    if (is_readable("../files/picture/photo/$picture")) {
       
     unlink("../files/picture/photo/$picture");
     unlink("../files/picture/photo/thumb/thumb_$picture");
     
    }
   
 } else {
     
   $delete_post = $posts -> deletePost($postId, $sanitize);
     
 }
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=posts&status=postDeleted">';
 
}