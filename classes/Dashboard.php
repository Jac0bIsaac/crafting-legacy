<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Dashboard extends Model
{
  
 public function __construct()
 {
   parent::__construct();
        
 }
 
 public function totalMessages()
 {
   $sql = "SELECT ID FROM inbox";
   
   $stmt = $this->dbc->query($sql);
   
   return $stmt -> rowCount();
   
   $this->closeDbConnection();
   
 }
 
 public function totalPhotos()
 {
   $sql = "SELECT photoID FROM photo";
   
   $stmt = $this->dbc->query($sql);
   
   return $stmt -> rowCount();
   
   $this->closeDbConnection();
 }
 
 public function totalEvents()
 {
   $sql = "SELECT event_id FROM event";
   
   $stmt = $this->dbc->query($sql);
   
   return $stmt -> rowCount();
   
   $this->closeDbConnection();
   
 }
 
 public function totalPosts()
 {
    $sql = "SELECT postID FROM posts WHERE post_type = 'blog'";
    
    $stmt = $this->dbc->query($sql);
    
    return  $stmt->rowCount();
    
    $this->closeDbConnection();
    
 }
 
 public function messageNotifications()
 {
    $sql = "SELECT ID, sender, email, messages, date_sent, time_sent
           FROM inbox ORDER BY time_sent DESC LIMIT 5";
    
    $stmt = $this->dbc->query($sql);
    
    $messages = array();
    
    foreach ($stmt -> fetchAll() as $results) {
     $messages[] = $results;    
    }
    
    return(array("results" => $messages));
    
 }
 
}