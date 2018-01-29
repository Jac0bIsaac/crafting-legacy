<?php 
/**
 * Class LogError
 * Handling Error Log
 *
 * @package   PiLUS_CART
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.2
 * @since     Since Release 1.5
 *
 */

class LogError
{
	/**
	 *
	 * @var string
	 */
	private static $_printError = false;
	
	/**
	 * @method customErrorMessage
	 */
	public static function customErrorMessage()
	{
		 
	 echo '<article>';
	 echo '<div class="container">';
	 echo '<div class="row">';
	 echo '<div class="col-lg-8 col-md-10 mx-auto">';
	 echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>ERROR!</strong><br>
          Please check your error log and send it to: alanmoehammad@gmail.com
          </div>';
	 echo '</div>';
	 echo '</div>';
	 echo '</div>';
	 echo '</article>';
	  
	  exit();
			
	}
	
	/**
	 * exception handler
	 *
	 * @method exceptionHandler
	 * @param string $e
	 */
	public static function exceptionHandler($e)
	{
		self::newMessage($e);
		self::customErrorMessage();
	}
	
	
	/**
	 *
	 * errorHandler
	 *
	 * @method errorHandler
	 * @param integer $errorNumber
	 * @param string $errorString
	 * @param string $file
	 * @param string $line
	 * @return number
	 */
	public static function errorHandler($errorNumber, $errorString, $file, $line)
	{
		
	  if (!(error_reporting() & $errorNumber)) {
		  
	      return false;
	  }
		  
	  switch ($errorNumber) {
		    
	      case E_PARSE:
	      case E_ERROR:
	      case E_CORE_ERROR:
	      case E_COMPILE_ERROR:
		  case E_USER_ERROR:
		        
		       $errorMessage = "<b>ERROR: </b> [$errorNumber] - $errorString<br />\n";
		       $errorMessage .= " Fatal error on line $line in file $file";
		       $errorMessage .= " PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
		       
		       self::errorMessage($errorMessage);
		       self::customErrorMessage();
		       
		       break;
		      
		   case E_WARNING:
		   case E_COMPILE_WARNING:
		   case E_RECOVERABLE_ERROR:
		   case E_USER_WARNING :
		        
		       $errorMessage = "<b>WARNING: </b> [$errorNumber] - $errorString<br />\n";
		       
		         self::errorMessage($errorMessage);
		         self::customErrorMessage();
		       
		         break;
		      
		   case E_NOTICE:
		   case E_USER_NOTICE :
		        
		      $errorMessage = "<b>NOTICE: </b> [$errorNumber] - $errorString";
		        
		       self::errorMessage($errorMessage);
		       self::customErrorMessage();
		       
		       break;
		       
		   default:
		        
		        $errorMessage =  "Unknown error type: [$errorNumber] - $errorString<br />\n";
		        
		        self::errorMessage($errorMessage);
		        self::customErrorMessage();
		        
		        break;
		}
		
		/* Don't execute PHP internal error handler */
		return true;
		
	}
	
	/**
	 * @static method newMessage
	 * @param Exception $exception
	 * @param string $_printError
	 * @param string $clear
	 * @param string $error_file
	 */
	
	public static function newMessage(Exception $exception, $_printError = false, $clear = false, $error_file = 'logerror.html')
	{
	    
	    $message = $exception->getMessage();
	    $code = $exception->getCode();
	    $file = $exception->getFile();
	    $line = $exception->getLine();
	    $trace = $exception->getTraceAsString();
	    $date = date('M d, Y G:iA');
	    
	    $log_message = "<h3>Exception information:</h3>\n
		<p><strong>Date:</strong> {$date}</p>\n
		<p><strong>Message:</strong> {$message}</p>\n
		<p><strong>Code:</strong> {$code}</p>\n
		<p><strong>File:</strong> {$file}</p>\n
		<p><strong>Line:</strong> {$line}</p>\n
		<h3>Stack trace:</h3>\n
		<pre>{$trace}</pre>\n
		<hr />\n";
	    
	    if( is_file($error_file) === false ) {
	        file_put_contents($error_file, '');
	    }
	    
	    if( $clear ) {
	        $content = '';
	    } else {
	        $content = file_get_contents($error_file);
	    }
	    
	    file_put_contents($error_file, $log_message . $content);
	    
	    if($_printError == true){
	        echo $log_message;
	        
	        exit();
	        
	    }
	    
	}
	
	/**
	 * @static method errorMessage
	 * @param string $error
	 * @param string $_printError
	 * @param string $error_file
	 */
	public static function errorMessage($error, $_printError = false, $error_file =  'errorlog.html')
	{
	    
	    $date = date('M d, Y G:iA');
	    $log_message = "<p>Error on $date - $error</p>";
	    
	    if( is_file($error_file) === false ) {
	        file_put_contents($error_file, '');
	    }
	    
	    $content = file_get_contents($error_file);
	    file_put_contents($error_file, $log_message . $content);
	    
	    if($_printError == true){
	        echo $log_message;
	        
	        exit();
	        
	    }
	    
	}
	
}