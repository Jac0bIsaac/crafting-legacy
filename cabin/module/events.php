<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$eventId = isset($_GET['eventId']) ? abs((int)$_GET['eventId']) : "";
$accessLevel = $authentication ->accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
 
 require('../cabin/404.php');

} else {
	
	switch ($action) {
		
		case 'newEvent':
			
			if (isset($eventId) && $eventId == 0) {
				
	          addEvent();
				
			}
			
			break;
			
		case 'editEvent':
			
			if ($events -> checkEventId($eventId, $sanitize) == false) {
				require('../cabin/404.php');
				
			} else {
			    
			   editEvent();
			   
			}
			
			break;
			
		case 'deleteEvent' :
			
		     removeEvent();
			
			break;
			
		default:
			
			listEvents();
			
			break;
			
	}
	
}


// show all events
function listEvents()
{
 global $events;
 
 $views = array();
 $views['pageTitle'] = "Events";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_events = $events -> findEvents($position, $limit);
 
 $views['events'] = $data_events['results'];
 $views['totalEvents'] = $data_events['totalEvents'];
 $views['position'] = $position;
 
 // pagination
 $totalPage = $p -> totalPage($views['totalEvents'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == 'eventNotFound') $views['errorMessage'] = "Event not found !";
 }
 
 if (isset($_GET['status'])) {
 	if($_GET['status'] == 'eventAdded') $views['statusMessage'] = "Event added";
 	if($_GET['status'] == 'eventUpdated') $views['statusMessage'] = "Event has been updated";
 	if($_GET['status'] == 'eventDeleted') $views['statusMessage'] = "Event has been deleted";
 }
 
 require('events/list-events.php');
 
}


function addEvent()
{
 global $events, $vid;
 
 $views = array();
 $views['pageTitle'] = "Add new event";
 $views['formAction'] = "newEvent";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
    $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
    $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
    $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
    $file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
    $file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
    
    $file_basename = substr($file_name, 0, strripos($file_name, '.'));
    
    // get file extension
    $file_ext = substr($file_name, strripos($file_name, '.'));
    
    $newFileName = renameFile(md5(rand(0, 999) . $file_basename)) . $file_ext;
    
 	$sender_id = (int)$_POST['sender_id'];
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	$description = preventInject($_POST['description']);
 	$location = preventInject($_POST['location']);
 	$time_started = isset($_POST['time_started']) ? $_POST['time_started'] : '';
 	$time_ended = isset($_POST['time_ended']) ? $_POST['time_ended'] : '';
 	$start_date = isset($_POST['date_started']) ? tgl_ind_to_eng($_POST['date_started']) : '';
 	$end_date = isset($_POST['date_ended']) ? tgl_ind_to_eng($_POST['date_ended']) : '';
 	$date_created = date("Ymd");
 	$time_created = date(("H:i:s"));
 	
 	try {
 	    
 	    if (empty($title) || empty($description) || empty($location)
 	        || empty($time_started) || empty($start_date)) {
 	            
 	      throw new RuntimeException('All column with asterisk(*) sign is required!');
 	            
 	    }
 	    
 	    if (empty($file_location) || empty($file_basename)) {
 	        
 	        $add_event = $events -> createEvent($sender_id, $title, $slug, $description,
 	            $location, $time_started, $time_ended, $start_date, $end_date,
 	            $date_created, $time_created);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=events&status=eventAdded">';
 	        
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
 	        
 	        $add_event = $events -> createEvent($sender_id, $title, $slug, $description,
 	            $location, $time_started, $time_ended, $start_date, $end_date,
 	            $date_created, $time_created, $newFileName);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=events&status=eventAdded">';
 	        
 	    }
 	    
 	} catch (RuntimeException $e) {
 	    
 	    $views['errorMessage'] = $e -> getMessage();
 	    require('events/edit-event.php');
 	    
 	}
 	
 	
 } else {
 	
 	$views['event'] = $events;
 	$views['sender_id'] = $vid;
 	require('events/edit-event.php');
 }
 
}

function editEvent()
{
 global $events, $eventId, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Edit event";
 $views['formAction'] = "editEvent";
 
if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
    $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
    $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
    $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
    $file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
    $file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
    
    // get filename
    $file_basename = substr($file_name, 0, strripos($file_name, '.'));
    
    // get file extension
    $file_ext = substr($file_name, strripos($file_name, '.'));
    
    $newFileName = renameFile(md5(rand(0, 999) . $file_basename)) . $file_ext;
    
 	$event_id = (int)$_POST['event_id'];
 	$sender_id = (int)$_POST['sender_id'];
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	$description = preventInject($_POST['description']);
 	$location = preventInject($_POST['location']);
 	$time_started = isset($_POST['time_started']) ? $_POST['time_started'] : '';
 	$time_ended = isset($_POST['time_ended']) ? $_POST['time_ended'] : '';
 	$start_date = isset($_POST['date_started']) ? tgl_ind_to_eng($_POST['date_started']) : '';
 	$end_date = isset($_POST['date_ended']) ? tgl_ind_to_eng($_POST['date_ended']) : '';
 	$date_modified = date("Ymd");
 	$time_modified = date("H:i:s");
 
 	try {
 	    
 	    if (empty($title) || empty($description) || empty($location)
 	        || empty($time_started) || empty($start_date)) {
 	            
 	      throw new RuntimeException('All column with asterisk(*) sign is required!');
 	            
 	    }
 	    
 	    if (empty($file_location) || empty($file_basename)) {
 	        
 	        $edit_event = $events -> updateEvent($event_id, $sender_id, $title,
 	            $slug, $description, $location, $time_started,
 	            $time_ended, $start_date, $end_date, $date_modified, $time_modified);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=events&status=eventUpdated">';
 	        
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
 	        
 	        $edit_event = $events -> updateEvent($event_id, $sender_id, $title,
 	            $slug, $description, $location, $time_started,
 	            $time_ended, $start_date, $end_date, $date_modified, $time_modified, $newFileName);
 	        
 	        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=events&status=eventUpdated">';
 	        
 	        
 	    }
 	  
 	} catch (RuntimeException $e) {
 	    
 	    $views['errorMessage'] = $e -> getMessage();
 	    require('events/edit-event.php');
 	  
 	}
 	
 } else {
 	
 	$event = $events -> findEvent($eventId, $sanitize);
 	$views['event_id'] = $event['event_id'];
 	$views['sender_id'] = $event['sender_id'];
 	$views['event_image'] = htmlspecialchars($event['event_image']);
 	$views['name'] = htmlspecialchars($event['name']);
 	$views['description'] = $event['description'];
 	$views['location'] = $event['location'];
 	$views['time_started'] = date("g:i a", strtotime($event['time_started']));
 	$views['time_ended'] = date("g:i a", strtotime($event['time_ended']));
 	$views['start_date'] = tgl_eng_to_ind($event['start_date']);
 	$views['end_date'] = tgl_eng_to_ind($event['end_date']);
 	$views['date_modified'] = $event['date_modified'];
 	$views['date_created'] = $event['date_created'];
 	$views['time_created'] = $event['time_created'];
 	$views['time_modified'] = $event['time_modified'];
 	
 	require('events/edit-event.php');
 	
 }
 
}

function removeEvent()
{
 
 global $events, $eventId, $sanitize;
 
 if (!$event = $events -> findEvent($eventId, $sanitize)) {
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=events&error=eventNotFound">';
 }
 
 $image = $event['event_image'];
 if ($image != '') {
     
     $delete_event = $events -> deleteEvent($eventId, $sanitize);
    
     if (is_readable("../files/picture/photo/$image")) {
         
        unlink("../files/picture/photo/$image");
        unlink("../files/picture/photo/thumb/thumb_$image");
         
     }
     
 } else {
     
     $delete_event = $events -> deleteEvent($eventId, $sanitize);
 }
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=events&status=eventDeleted">';
 
}