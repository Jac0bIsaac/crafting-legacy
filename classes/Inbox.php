<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Inbox extends Model
{
  
  public function __construct()
  {
    
    parent::__construct();
	
  }
  
  public function sendMessage($sender, $email, $phone, $messages, $date_sent, $time_sent)
  {
   $sql = "INSERT INTO inbox(sender, email, phone, messages, date_sent, time_sent)
   		  VALUES(?, ?, ?, ?, ?, ?)";
   
   $data = array($sender, $email, $phone, $messages, $date_sent, $time_sent);
   
   $stmt = $this->statementHandle($sql, $data);
   
   return $this->lastId();
   
  }
  
  public function readMessage($id, $sanitizing)
  {
  	$sql = "SELECT ID, sender, email, phone, messages, date_sent, time_sent
  			FROM inbox WHERE inboxID = ?";
  	
  	$cleanId = $this->filteringId($sanitizing, $id, 'sql');
  	
  	$data = array($cleanId);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  	return $stmt -> fetch();
  	
  }
  
  public function replyMessages($to, $subject, $message, $from)
  {
  	
    $reply_messages = new Mailer();
    $reply_messages -> setSendText(false);
    $reply_messages -> setSendTo(safeEmail($to));
    $reply_messages -> setFrom($from);
    $reply_messages -> setSubject($subject);
    $reply_messages -> setHTMLBody($message);
    $reply_messages -> send();
    
  }
  
  public function deleteMessage($id, $sanitizing)
  {
  	$cleanMessageId = $this->filteringId($sanitizing, $id, 'sql');
  	
  	$sql = "DELETE FROM inbox WHERE ID = ?";
  	
  	$data = array($cleanMessageId);
  	
  	$stmt = $this->statementHandle($sql, $data);
  	
  }
  
  public function showInbox($position, $limit)
  {
    try {
    
      $msg = array();
        
      $sql = "SELECT ID, sender, email, phone, messages, date_sent, time_sent
    		  FROM inbox ORDER BY sender DESC LIMIT :position, :limit";
        
      $stmt = $this->dbc->prepare($sql);
      $stmt -> bindParam(":position", $position, PDO::PARAM_INT);
      $stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
      $stmt -> execute();
    	
      foreach ($stmt -> fetchAll() as $results) {
    	  
    	$msg[] = $results;
    	
      }
    	
    	$numbers = "SELECT ID FROM inbox ";
    	$stmt = $this->dbc->query($numbers);
    	$totalMessages = $stmt -> rowCount();
    	
    	return(array("results" => $msg, "totalMessages" => $totalMessages));
    	
    } catch (PDOException $e) {
    	
    	$this->closeDbConnection();
    	
    	$this->error = LogError::newMessage($e);
    	$this->error = LogError::customErrorMessage();
    	
    }
    
  }
  
  public function checkMessageId($id, $sanitizing)
  {
  	$sql = "SELECT ID FROM inbox WHERE inboxID = ?";
  	
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
  
}