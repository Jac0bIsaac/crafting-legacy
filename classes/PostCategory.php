<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class PostCategory extends Model
{
  
  public function __construct()
  {
    parent::__construct();
        
  }
  
  public function findPostCategory($postId)
  {
    $sql = "SELECT category_title, category_slug 
            FROM category, post_category
            WHERE category.categoryID = post_category.categoryID
            AND post_category.postID = :postID";
    
    try {
        
        $post_categories = array();
        
        $stmt = $this->dbc->prepare($sql);
        $stmt -> bindParam(':postID', $postId, PDO::PARAM_INT);
        $stmt -> execute();
        
        while ($row = $stmt -> fetch()) {
            
            $post_categories[] = $row;
        }
        
        return $post_categories;
        
    } catch (PDOException $e) {
      
      $this->closeDbConnection();
      $this->error = LogError::newMessage($e);
      $this->error = LogError::customErrorMessage();
      
    }
    
  }
  
  public function setLinkCategories($postId, $position = 'meta')
  {
    
    $html = array();
   
    $linkCategories = $this->findPostCategory($postId);
   
    foreach ($linkCategories as $l => $linkCategory) {
       
        if (!$position) {
        
            $html[] = '<a href="'.APP_DIR.'category/'.preventInject($linkCategory['category_slug']).'" class="tag-name">'.preventInject($linkCategory['category_title']).'</a>';
        
        } else {
            
            $html[] = preventInject($linkCategory['category_title']);
            
        }
    }
   
    return implode(", ", $html);
  
  }
  
  public function showCategoryPost($catId, $sanitize)
  {
    
    $dataCatPost = array();
    
    $catIdSanitized = $this->filteringId($sanitize, $catId, 'sql');
    
 try {
               
   $sql = "SELECT
                posts.postID, posts.post_author, posts.date_created,
                posts.post_title, posts.post_slug, posts.post_content, 
                posts.post_status, posts.post_type, volunteer.volunteer_login
           FROM
                posts, post_category, volunteer
           WHERE
                posts.postID = post_category.postID
                AND post_category.categoryID = :categoryID
                AND posts.post_author = volunteer.ID
                AND posts.post_status = 'publish' AND posts.post_type = 'blog'
           ORDER BY 
                posts.postID DESC ";
        
        $stmt = $this->dbc->prepare($sql);
        $stmt -> execute(array(':categoryID' => $catIdSanitized));
        
        foreach ($stmt -> fetchAll() as $results) {
            $dataCatPost[] = $results;
        }
         
       $totalRows = $stmt -> rowCount();
        
    return(array("catPosts" => $dataCatPost, 'totalRows' => $totalRows));
        
        
  } catch (PDOException $e) {
       
       $this->closeDbConnection();
       $this->error = LogError::newMessage($e);
       $this->error = LogError::customErrorMessage();
       
  }
               
 }
  
}