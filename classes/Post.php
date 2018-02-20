<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Post extends Model
{
 
protected $linkPosts;

public function __construct()
{
  parent::__construct();	
}
  
public function createPost($catID, $author, $created, $title, $slug, $content, $post_status, $comment_status, $picture = '')
{
  	if (!empty($picture)) {
  		
  		// insert into posts
     $sql = "INSERT INTO posts(post_image, 
  		   post_author, date_created, post_title, 
  		   post_slug, post_content, post_status, 
  		   comment_status)VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
  		
     $data = array($picture, $author, $created, $title, $slug, 
                  $content, $post_status, $comment_status);
  		 
     } else {
  			
  	  $sql = "INSERT INTO posts(post_author, 
                date_created, post_title, post_slug, 
                post_content, post_status, comment_status)
  				VALUES(?, ?, ?, ?, ?, ?, ?)";
  		  
  	 $data = array($author, $created, $title, $slug, $content, $post_status, $comment_status);
  		  
  	}
  	
  		
  	$stmt = $this->statementHandle($sql, $data);
  		
  	$postID = $this->lastId();
  		
  	if (is_array($catID)) {
  			
  			foreach ($_POST['catID'] as $catID) {
  			 
  			$stmt = $this->statementHandle("INSERT INTO post_category(postID, categoryID)
  					VALUES(?, ?)", array($postID, $catID));
  			
  			}
  			
  		} else {
  		    
  		    $stmt = $this->statementHandle("INSERT INTO post_category(postID, categoryID)
  					VALUES(?, ?)", array($postID, $catID));
  		}
  		
}
  	
public function updatePost($id, $catID, $author, $modified, $title, $slug, 
                        $content, $post_status, $comment_status, $picture = '') 
{
  	  
 if (!empty($picture)) {
  	  	
  	 $sql = "UPDATE posts SET post_image = ?, post_author = ?, 
  	  			date_modified = ?, post_title = ?, post_slug = ?,
  	  			post_content = ?, post_status = ?, comment_status = ? 
                WHERE postID = ?";
  	  	
  	  
  	 $data = array($picture, $author, $modified, $title, $slug, 
  	  			 $content, $post_status, $comment_status, $id);
  	  	
  } else {
  	  	
  	  	$sql = "UPDATE posts SET post_author = ?,
  	  			date_modified = ?, post_title = ?, post_slug = ?,
  	  			post_content = ?, post_status = ?, comment_status = ? 
  	  			WHERE postID = ?";
  	  	
  	  	$data = array($author, $modified, $title, $slug, $content, 
  	  			$post_status, $comment_status, $id);
  	  	
  }
  	   
  	$stmt = $this->statementHandle($sql, $data);
  	  
     // delete post_category 
  	  $deleteCategoryByPostId = "DELETE FROM post_category WHERE postID = :postID";
  	  $stmt = $this->dbc->prepare($deleteCategoryByPostId);
  	  $stmt -> execute(array(':postID'=> $id));
  	  
  	  if (is_array($catID)) {
  	     
  	     foreach ($_POST['catID'] as $catID) {
  	        $stmt = $this->dbc->prepare("INSERT INTO post_category(postID, categoryID)VALUES(:postID, :categoryID)");
  	        $stmt -> execute(array(':postID' => $id, ':categoryID' => $catID)); 
  	     }
  	     
  	  } else {
  	      
  	      $stmt = $this->dbc->prepare("INSERT INTO post_category(postID, categoryID)VALUES(:postID, :categoryID)");
  	      $stmt -> execute(array(':postID' => $id, ':categoryID' => $catID)); 
  	  }
  	  
  	  
}
  	
  	public function deletePost($id, $sanitizing)
  	{
  	  $sql = "DELETE FROM posts WHERE postID = ?";
  	  
  	  $sanitized_id = $this->filteringId($sanitizing, $id, 'sql');
  	  
  	  $data = array($sanitized_id);
  	  
  	  $stmt = $this->statementHandle($sql, $data);
  	  
  	}
  	
  	public function findPosts($position, $limit, $author = null)
  	{
  		
  	try {
  			
  		if (!is_null($author)) {
  			
  			$sql = "SELECT p.postID, p.post_image, p.post_author, 
                p.date_created, p.date_modified, p.post_title, p.post_slug, 
                p.post_content, p.post_status, p.post_type, v.volunteer_login
  				FROM posts AS p
  				INNER JOIN volunteer AS v ON p.post_author = v.ID
  				WHERE p.post_author = :author
  				AND p.post_type = 'blog'
  				ORDER BY p.postID DESC
  		        LIMIT :position, :limit";
  			
  			
  			$stmt = $this->dbc->prepare($sql);
  			$stmt -> bindParam(":author", $author, PDO::PARAM_STR);
  			$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  			$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  			
  		} else {
  			
  	      $sql = "SELECT p.postID, p.post_image, p.post_author, 
                p.date_created, p.date_modified, p.post_title, 
                p.post_slug, p.post_content, p.post_status, p.post_type, v.volunteer_login
  		    FROM 
                 posts AS p
  		    INNER JOIN 
                 volunteer AS v ON p.post_author = v.ID
  		    WHERE 
                 p.post_type = 'blog'
  			ORDER BY p.postID DESC LIMIT :position, :limit";
  
  			$stmt = $this->dbc->prepare($sql);
  			$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  			$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  			
  		}
  			$stmt -> execute();
  			
  			$posts = array();
  			
  			foreach ($stmt -> fetchAll() as $row) {
  			 
  			 $posts[] = $row;
  			 
  			}
  			
  			$numbers = "SELECT postID FROM posts WHERE post_type = 'blog'";
  			$stmt = $this->dbc->query($numbers);
  			$totalPosts = $stmt -> rowCount();
  			
  			return(array("results" => $posts, "totalPosts" => $totalPosts));
  			
  		} catch (PDOException $e) {
  			
  		$this->closeDbConnection();
  			
  		$this->error = LogError::newMessage($e);
  		$this->error = LogError::customErrorMessage();
  			
  		}
  		
  	}
  	
  	public function findPost($postId, $sanitizing, $author = null)
  	{
  	
  	  $sanitized_id = $this->filteringId($sanitizing, $postId, 'sql');
  	  
  	 if (!empty($author)) {
  	 	
  	 	$sql = "SELECT postID, post_image, post_author,
  	  		  date_created, date_modified, post_title,
  	  		  post_slug, post_content, post_status,
  	  		  post_type, comment_status
  	  		  FROM posts 
  	  		  WHERE postID = ? AND post_author = ?
  			  AND post_type = 'blog'";
  	 	
  	 	$data = array($sanitized_id, $author);
  	 	
  	 } else {
  	 	
  	 	$sql = "SELECT postID, post_image, post_author,
  	  		  date_created, date_modified, post_title,
  	  		  post_slug, post_content, post_status,
  	  		  post_type, comment_status
  	  		  FROM posts 
  	  		  WHERE postID = ? AND post_type = 'blog'";
  	 	
  	 	$data = array($sanitized_id);
  	 	
  	 }
  	  
  	  $stmt = $this->statementHandle($sql, $data);
  	  
  	  return $stmt -> fetch();
  	  
  	}
  
  	public function showPostById($id, $sanitizing)
  	{
  	    $sql = "SELECT p.postID, p.post_image, p.post_author, 
                p.date_created, p.date_modified, p.post_title, 
                p.post_slug, p.post_content, p.post_status, 
                p.post_type, p.comment_status, v.volunteer_login
  				FROM posts AS p
  				INNER JOIN volunteer AS v ON p.post_author = v.ID
  				WHERE p.postID = :ID AND p.post_type = 'blog'";
  	    
  	    $sanitized_id = $this->filteringId($sanitizing, $id, 'sql');
  	    
  	    $data = array(':ID' => $sanitized_id);
  	    
  	    $stmt = $this->statementHandle($sql, $data);
  	    
  	    return $stmt -> fetch();
  	    
  	}
  	
  	public function showRelatedPosts($post_title)
  	{
  	    
  	  $sql = "SELECT 
                 postID, post_image, post_author, date_created,
                 post_title, post_slug, post_content, 
             MATCH(post_title, post_content) AGAINST(?) AS score
             FROM 
               posts 
             WHERE MATCH(post_title, post_content) AGAINST(?)
             ORDER BY score DESC LIMIT 3";
  	  
  	  $relatedPosts = array();
  	  
  	  $stmt = $this->dbc->prepare($sql);
  	  $stmt -> bindParam(1, $post_title, PDO::PARAM_STR);
  	  $stmt -> bindParam(2, $post_title, PDO::PARAM_STR);
  	  $stmt -> execute();
  	  
  	  while ($row = $stmt -> fetch()) {
  	    $relatedPosts[] = $row;
  	  }
  	  
  	  $this->closeDbConnection();
  	  
  	  return(array("relatedPosts" => $relatedPosts));
  	  
  	}
  	
  	public function showAllPostPublished(Paginator $perPage, $sanitize)
  	{
  	    
  	  $data_posts = array();
  	  
  	  $pagination = null;
  	  
  	  $this->linkPosts = $perPage;
  	  
  	  try {
  	      
  	      $stmt = $this->dbc->query("SELECT postID FROM posts WHERE post_status = 'publish' AND post_type = 'blog'");
  	      
  	      $this->linkPosts->set_total($stmt -> rowCount());
  	      
  	      $sql = "SELECT p.postID, p.post_image, p.post_author,
                     p.date_created, p.date_modified, p.post_title,
                     p.post_slug, p.post_content, p.post_type, 
                     p.post_status, v.volunteer_login
  			      FROM posts AS p
  			      INNER JOIN volunteer AS v ON p.post_author = v.ID
  			      WHERE p.post_type = 'blog' AND p.post_status = 'publish'
  			      ORDER BY p.postID DESC " . $this->linkPosts->get_limit($sanitize);
  	      
  	     $stmt = $this->dbc->query($sql);
  	      
  	     foreach ($stmt -> fetchAll() as $results) {
  	         
  	         $data_posts[] = $results;
  	         
  	     }
  	     
  	     $pagination = $this->linkPosts->page_links($sanitize);
  	     
  	     $totalRows = $stmt -> rowCount();
  	     
  	return(array("allPostsPublished" => $data_posts, "totalRows" => $totalRows, "paginationLink" => $pagination));
  	      
  	  } catch (PDOException $e) {
  	     
  	     $this->closeDbConnection();
  	     $this->error = LogError::newMessage($e);
  	     $this->error = LogError::customErrorMessage();
  	     
  	  }
  	       
  	}
  	
  	public function checkPostById($id, $sanitizing)
  	{
  		$sql = "SELECT postID FROM posts WHERE postID = ? AND post_type = 'blog'";
  		
  		$stmt = $this->dbc->prepare($sql);
  		
  		$cleanUpId = $this->filteringId($sanitizing, $id, 'sql');
  		
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
    
 	$html[] = '<label>Post setting :</label>';
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
 	
 	$html[] = '<label>comments setting :</label>';
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