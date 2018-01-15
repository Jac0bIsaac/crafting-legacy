<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Event extends Model
{
  
  protected $linkEvents;
  
  public function __construct()
  {
  	parent::__construct();
  }
    
  public function createEvent($sender_id, $name, $slug, $description, $location, 
  		          $time_started, $time_ended, $start_date, $end_date, 
                  $date_created, $time_created, $image = '')
  {
      
    if (!empty($image)) {
        
        $sql = "INSERT INTO event(sender_id, event_image, name, slug, description, location,
  			time_started, time_ended, start_date, end_date, date_created, time_created)
  			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $data = array($sender_id, $image, $name, $slug, $description, $location, $time_started,
            $time_ended, $start_date, $end_date, $date_created, $time_created);
        
    } else {
        
        $sql = "INSERT INTO event(sender_id, name, slug, description, location,
  			time_started, time_ended, start_date, end_date, date_created, time_created)
  			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $data = array($sender_id, $name, $slug, $description, $location, $time_started,
            $time_ended, $start_date, $end_date, $date_created, $time_created);
        
    }
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  	return $this->lastId();
  	
  }
  
  public function updateEvent($eventId, $sender_id, $name, $slug, $description, 
  		$location, $time_started, $time_ended, $start_date, $end_date, 
        $date_modified, $time_modified, $image = '')
  {
    
    if (!empty($image)) {
        
     $sql = "UPDATE event SET sender_id = ?, event_image = ?, name = ?, slug = ?, description = ?,
  			location = ?, time_started = ?, time_ended = ?, start_date = ?,
            end_date = ?, date_modified = ?, time_modified = ? WHERE event_id = ?";
        
      $data = array($sender_id, $image, $name, $slug, $description, $location, 
            $time_started, $time_ended, $start_date, $end_date, $date_modified, $time_modified, $eventId);
        
    } else {
        
        $sql = "UPDATE event SET sender_id = ?, name = ?, slug = ?, description = ?,
  			location = ?, time_started = ?, time_ended = ?, start_date = ?,
            end_date = ?, date_modified = ?, time_modified = ? WHERE event_id = ?";
        
        $data = array($sender_id, $name, $slug, $description, $location, $time_started,
            $time_ended, $start_date, $end_date, $date_modified, $time_modified, $eventId);
        
    }
    
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function deleteEvent($eventId, $sanitizing)
  {
  
  	$cleanEventId = $this->filteringId($sanitizing, $eventId, 'sql');
  	
  	$sql = "DELETE FROM event WHERE event_id = ?";
  	
  	$data = array($cleanEventId);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function findEvents($position, $limit)
  {
      
  try {
  		
  	 $sql = "SELECT 
                  event_id, sender_id, 
                  event_image, name, slug, description,
  			      location, time_started, time_ended, start_date, end_date,
  			      date_created, date_modified, time_created,
  			      time_modified 
            FROM 
                 event ORDER BY event_id DESC
  			LIMIT :position, :limit";
  	    
  	    $stmt = $this->dbc->prepare($sql);
  	    $stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  	    $stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  	    
  		$stmt -> execute();
  		
  		$events = array();
  		
  		foreach ($stmt -> fetchAll() as $row) {
  		  $events[] = $row;
  		}
  		
  		$numbers = "SELECT event_id FROM event";
  		$stmt = $this->dbc->query($numbers);
  		$totalEvents = $stmt -> rowCount();
  		
  		return(array("results" => $events, "totalEvents" => $totalEvents));
  		
  	} catch (PDOException $e) {
  	
  		$this->closeDbConnection();
  		
  		$this->error = LogError::newMessage($e);
  		$this->error = LogError::customErrorMessage();
  		
  	}
  	
  }
  
  public function findEvent($event_id, $sanitizing)
  {
  	
  	$sql = "SELECT event_id, sender_id, event_image, name, slug, description, 
  			location, time_started, time_ended, start_date, end_date, 
  			date_created, date_modified, time_created, 
  			time_modified FROM event WHERE event_id = ?";
  	
  	$id_sanitized = $this->filteringId($sanitizing, $event_id, 'sql');
  	
  	$data = array($id_sanitized);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  	return $stmt -> fetch();
  	
  }
 
  public function findEventBySlug($slug, $sanitizing)
  {
    $sql = "SELECT 
              event_id, sender_id, event_image, name, slug, description,
  			  location, time_started, time_ended, start_date, end_date,
  			  date_created, date_modified, time_created,
  			  time_modified 
            FROM event 
            WHERE slug = :slug";
    
    $slug_sanitized = $this->filteringId($sanitizing, $slug, 'xss');
    
    $data = array(':slug' => $slug_sanitized);
    
    $stmt = $this -> statementHandle($sql, $data);
    
    return $stmt -> fetch();
    
  }
  
  public function showAllEvents(Paginator $perPage, $sanitize)
  {
    $data_event = array();
    
    $pagination = null;
    
    $this->linkEvents = $perPage;
    
    try {
       
     $stmt = $this->dbc->query("SELECT event_id FROM event");
     
     $this->linkEvents->set_total($stmt -> rowCount());
     
     $sql = "SELECT 
              e.event_id, e.sender_id, e.event_image, e.name,
              e.slug, e.description, e.location, e.time_started, e.time_ended,
              e.start_date, e.end_date, e.date_created, v.volunteer_login 
            FROM 
               event AS e
             INNER JOIN volunteer AS v ON e.sender_id = v.ID
             ORDER BY e.event_id DESC " . $this->linkEvents->get_limit();
     
     $stmt = $this->dbc->query($sql);
     
     foreach ($stmt -> fetchAll() as $results) {
         $data_event[] = $results;
     }
     
     $pagination = $this->linkEvents->page_links($sanitize);
     
     $totalRows = $stmt -> rowCount();
     
     return(array("allEvents" => $data_event, "totalRows" => $totalRows, "paginationLink" => $pagination));
              
    } catch (PDOException $e) {
       
      $this->closeDbConnection();
      $this->error = LogError::newMessage($e);
      $this->error = LogError::customErrorMessage();
        
    }
    
  }
  
  public function checkEventId($id, $sanitizing)
  {
   
   $sql = "SELECT event_id FROM event WHERE event_id = ?";
   
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
  
}