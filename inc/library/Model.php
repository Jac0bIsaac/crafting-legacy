<?php 

class Model
{
 public $dbc;
 
 protected $error;
 
 protected $sanitizing;
  
 public function __construct() 
 {
   if (Registry::isKeySet('dbc')) $this->dbc = Registry::get('dbc');
 }
	
 protected function statementHandle($sql, $data = null)
 {
  
 $statement = $this->dbc->prepare($sql);
	    
  try {
				
	$statement->execute($data);
				
  } catch (PDOException $e) {
			
	$this->closeDbConnection();
			
	$this->error = LogError::newMessage($e);
	$this->error = LogError::customErrorMessage();
				
  }
	
  return $statement;
	
 }
	
 protected function lastId()
 {
   return $this->dbc->lastInsertId();
 }
	
 protected function closeDbConnection()
 {
   $this->dbc = null;
 }
	
 protected function setDataTransaction()
 {
   return $this->dbc->beginTransaction();    
 }
	
 protected function filteringId(Sanitize $sanitize, $str, $type)
 {

 try {

   $this->sanitizing = $sanitize;
	 	
   switch ($type) {
      
      case 'sql':
        
          if (filter_var($str, FILTER_SANITIZE_NUMBER_INT)) {
              
              return $this->sanitizing->sanitasi($str, 'sql');
              
          } else {
              
              throw new Exception("ERROR: this - $str - Id is considered invalid.");
              
          }
          
          break;
      
      case 'xss':
            
          if (preventInject($str)) {
              
            return $this->sanitizing->sanitasi($str, 'xss');
              
          } else {
              
              throw new Exception("ERROR: this - $str - is considered invalid.");
          }
          
          break;
      
       }
	 	
      } catch (Exception $e) {
	 	
    $this->error = LogError::newMessage($e);
	$this->error = LogError::customErrorMessage();}
			
	}
	
}