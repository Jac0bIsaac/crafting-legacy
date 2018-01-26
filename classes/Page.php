<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Page extends Model
{

 public function __construct()
 {
  parent::__construct();
 }

 public function createPage($author, $date_created, $title, $slug, $content,
 		           $post_setting, $type, $comment_setting, $picture = "")
 {
 
 if (!empty($picture)) {
 
 	$sql = "INSERT INTO posts(post_image, post_author, date_created,
		 post_title, post_slug, post_content, post_status, post_type,
		comment_status)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
 	
 	$data = array($picture, $author, $date_created, $title, $slug, $content, 
 	             $post_setting, $type, $comment_setting);
 	
 } else {
 	
 	$sql = "INSERT INTO posts(post_author, date_created,
		 post_title, post_slug, post_content, post_status, post_type,
		comment_post_setting)VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
 	
 	$data = array($author, $date_created, $title, $slug, $content,
 	    $post_setting, $type, $comment_setting);
 	
 }
 
 	
 $stmt = $this->statementHandle($sql, $data);
	
 return $this->lastId();
	
 }
 
 public function updatePage($id, $date_modified, $title, $slug, 
        $content, $post_setting, $comment_setting, $type, $picture = "")
 {
 
 if (empty($picture)) {
 
 	$sql = "UPDATE posts SET date_modified = ?,
 		post_title = ?, post_slug = ?,
 		post_content = ?, post_status = ?, comment_status = ?
 		WHERE postID = ? AND post_type = ?";
 	
 	$data = array($date_modified, $title, $slug, $content, 
 	    $post_setting, $comment_setting, $id, $type);
 	
 } else {
 	
 	$sql = "UPDATE posts SET post_image = ?, date_modified = ?,
 		post_title = ?, post_slug = ?,
 		post_content = ?, post_status = ?, comment_status = ?
 		WHERE postID = ? AND post_type = ?";
 	
 	$data = array($picture, $date_modified, $title, $slug, 
 	    $content, $post_setting, $comment_setting, $id, $type);
 	
 	
 }
 
  $stmt = $this->statementHandle($sql, $data);
 	
 }
 
 public function deletePage($id, $sanitizing, $type)
 {
   
 $sql = "DELETE FROM posts WHERE postID = ? AND post_type = ?";
 
 $sanitized_id = $this->filteringId($sanitizing, $id, 'sql');
   
 $data = array($sanitized_id, $type);
   
 $stmt = $this->statementHandle($sql, $data);
   
 }
 
 public function findPages($position, $limit, $type)
 {
 
 try {
 
 	$sql = "SELECT postID, post_author, date_created, date_modified,
  		  post_title, post_type
  		  FROM posts WHERE post_type = :type
  		  ORDER BY postID
  		  LIMIT :position, :limit";
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindParam(":type", $type, PDO::PARAM_STR);
 	$stmt -> bindParam(":position", $position, PDO::PARAM_STR);
 	$stmt -> bindParam(":limit", $limit, PDO::PARAM_STR);
 	
 	$stmt -> execute();
 	
 	$items = array();
 	
 	foreach ($stmt -> fetchAll() as $row) {
 	 $items[] = $row;
 	}
 	
 	$numbers = "SELECT postID FROM posts WHERE post_type = '$type'";
 	$stmt = $this->dbc->query($numbers);
 	$totalPages = $stmt -> rowCount();
 	
 	return(array("results" => $items, "totalPages" => $totalPages));
 	
 } catch (PDOException $e) {
 	
 	$this->closeDbConnection();
 	
 	$this->error = LogError::newMessage($e);
 	$this->error = LogError::customErrorMessage();
 	
 }
   
 }
 
 public function findPageById($pageId, $post_type, $sanitizing)
 {
   $sql = "SELECT postID, post_image, post_author, 
  	  	   date_created, date_modified, post_title, 
  	  	   post_slug, post_content, post_status, 
  	  	   post_type, comment_status
  	  	   FROM posts 
  	  	   WHERE postID = ? AND post_type = ? ";
   
   $id_sanitized = $this -> filteringId($sanitizing, $pageId, 'sql');
   
   $data = array($id_sanitized, $post_type);
   
   $stmt = $this->statementHandle($sql, $data);
   
   return $stmt -> fetch();
   
 }
 
 public function findPageBySlug($slug, $sanitizing = null)
 {
   $sql = "SELECT 
              posts.postID, posts.post_image, posts.post_author, 
  	  	      posts.date_created, posts.date_modified, posts.post_title, 
  	  	      posts.post_slug, posts.post_content, posts.post_status, 
  	  	      posts.post_type, posts.comment_status, volunteer.volunteer_login 
  	  	   FROM 
               posts, volunteer
  	  	   WHERE 
              posts.post_slug = :slug 
              AND posts.post_status = 'publish' 
              AND posts.post_type = 'page' ";
 
   if (!is_null($sanitizing)) {
       
       $page_slug = $this->filteringId($sanitizing, $slug, 'xss');
       
       $data = array(':slug' => $page_slug);
       
   } else {
       
       $data = array(':slug' => $slug);
   }
  
   $stmt = $this->statementHandle($sql, $data);
   
   return $stmt -> fetch();
   
 }
 
 public function checkPageId($id, $sanitizing)
 {
   $sql = "SELECT postID FROM posts WHERE postID = ?";
   
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
 
 public function postStatusDropDown($selected = "")
 {
     
     $option_selected = "";
     
     if (!$selected) {
         
         $option_selected = 'selected="selected"';
     }
     
     // list position in array
     $posts_status = array('publish', 'draft');
     
     $html = array();
     
     $html[] = '<label>Post setting</label>';
     $html[] = '<select class="form-control" name="post_status">';
     
     foreach ($posts_status as $s => $status) {
         
         if ($selected == $status) {
             $option_selected = 'selected="selected"';
         }
         
         // set up the option line
         $html[]  =  '<option value="' . $status. '"' . $option_selected . '>' . $status . '</option>';
         
         // clear out the selected option flag
         $option_selected = '';
         
     }
     
     $html[] = '</select>';
     
     return implode("\n", $html);
     
 }
 
 public function commentStatusDropDown($selected = '')
 {
     $option_selected = "";
     
     if (!$selected) {
         
         $option_selected = 'selected="selected"';
     }
     
     // list position in array
     $comment_status = array('open', 'close');
     
     $html = array();
     
     $html[] = '<label>comments setting</label>';
     $html[] = '<select class="form-control" name="comment_status">';
     
     foreach ($comment_status as $c => $comment) {
         
         if ($selected == $comment) {
             $option_selected = 'selected="selected"';
         }
         
         // set up the option line
         $html[]  =  '<option value="' . $comment. '"' . $option_selected . '>' . $comment . '</option>';
         
         // clear out the selected option flag
         $option_selected = '';
         
     }
     
     $html[] = '</select>';
     
     return implode("\n", $html);
     
 }
 
}