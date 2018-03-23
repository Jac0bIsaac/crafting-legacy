<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Widget extends Model
{
 public function __construct()
 {
   parent::__construct();
 }
 
 public function setNextNavigation($currentId, $sanitize)
 {
  $id_sanitized = $sanitize -> sanitasi($currentId, 'sql');
  
  $nextQuery = "SELECT postID, post_title, post_slug, post_type
                FROM posts WHERE postID > '$id_sanitized' 
                AND post_status = 'publish' AND post_type = 'blog' 
                ORDER BY postID LIMIT 1";
  
  $stmt = $this->dbc->query($nextQuery);
  
  $nextLink = array();
  
  while ($row = $stmt -> fetch()) {
      
      $nextLink[] = $row;
      
  }
  
  return(array('results' => $nextLink));
  
 }
 
 public function setPrevNavigation($currentId, $sanitize)
 {
     
   $id_sanitized = $sanitize -> sanitasi($currentId, 'sql');
     
   $prevQuery = "SELECT postID, post_title, post_slug, post_type 
                 FROM posts WHERE postID < '$id_sanitized' 
                 AND post_status = 'publish' AND post_type = 'blog'
                 ORDER BY postID LIMIT 1";
     
   $stmt = $this->dbc-> query($prevQuery);
     
   $prevLink = array();
     
   while ($row = $stmt -> fetch()) {
         
     $prevLink[] = $row;
         
   }
     
   return(array('results' => $prevLink));
     
 }
 
 public function setSidebarCategories()
 {
     
  $catQuery = "SELECT categoryID, category_title, category_slug, status
              FROM category WHERE status = 'Y' ORDER BY category_title DESC";
     
  $stmt = $this->dbc->query($catQuery);
     
  $catLink = array();
     
  while ($row = $stmt -> fetch()) {
         
    $catLink[] = $row;
         
  }
     
  return(array('categories' => $catLink));
     
 }
 
 public function showRecentPosts($status, $position, $limit)
 {
  $items = array();
  
  try {
  
  $sql = "SELECT
             postID, post_image, post_author,
             date_created, date_modified, post_title, post_slug,
             post_content, post_status, post_type
  		FROM
            posts
  		WHERE
            post_status = :status AND post_type = 'blog'
  		ORDER BY postID DESC LIMIT :position, :limit";
      
      $stmt = $this->dbc->prepare($sql);
      $stmt -> bindParam(':status', $status, PDO::PARAM_STR);
      $stmt -> bindParam(':position', $position, PDO::PARAM_INT);
      $stmt -> bindParam(':limit', $limit, PDO::PARAM_INT);
  
      $stmt -> execute();
      
      foreach ($stmt -> fetchAll() as $row) {
          
          $items[] = $row;
      }
      
      return(array('recentPosts' => $items));
      
  } catch (PDOException $e) {
      
    $this->closeDbConnection();
    $this->error = LogError::newMessage($e);
    $this->error = LogError::customErrorMessage();
    
  }
  
 }
 
 public function showUpcomingEvents()
 {
   $eventListing = array();
   
   try {
       
    $sql = "SELECT e.event_id, e.event_image, e.name, e.slug, e.description,
            e.location, e.time_started, e.time_ended, e.start_date, e.end_date 
            FROM event AS e 
            WHERE TIMESTAMP(e.start_date, e.time_started) >= NOW() LIMIT 1";
    
    $stmt = $this->dbc->query($sql);
    
    while ($row = $stmt -> fetch()) {
        
        $eventListing[] = $row;
    }
    
    return(array("upcomingEvents" => $eventListing));
    
     
   } catch (PDOException $e) {
       
      $this->closeDbConnection();
      
      $this->error = LogError::newMessage($e);
      $this->error = LogError::customErrorMessage();
      
   }
   
 }
 
 public function showPastEvents()
 {
     $eventListing = array();
     
     try {
         
         $sql = "SELECT e. event_id, e.event_image, 
                   e.name, e.slug, e.description,
                   e.location, e.time_started, e.time_ended, 
                   e.start_date, e.end_date
            FROM event AS e
            WHERE TIMESTAMP(e.start_date, e.time_started) <= NOW() LIMIT 3";
         
         $stmt = $this->dbc->query($sql);
         
         while ($row = $stmt -> fetch()) {
             
             $eventListing[] = $row;
         }
         
         return(array("pastEvents" => $eventListing));
         
         
     } catch (PDOException $e) {
         
         $this->closeDbConnection();
         
         $this->error = LogError::newMessage($e);
         $this->error = LogError::customErrorMessage();
         
     }
     
 }
  
 public function __destruct()
 {
   parent::__destruct();
 }
}