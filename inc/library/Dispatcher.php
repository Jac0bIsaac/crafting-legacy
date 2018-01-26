<?php

class Dispatcher
{
/**
 * Errors 
 * @var string errors
 */
protected $errors;
 
protected $rules;

public function __construct()
{
    
 if (Registry::isKeySet('route')) $this->rules = Registry::get('route');
     
}

public function findRules()
{
  $keys = array();
  $values = array();
  
  foreach ($this->rules as $key => $value) {
      
      $keys[] = $key; 
      $values[] = $value;    
  }
  
  return(array("keys" => $keys, "values" => $values));
  
}

public function findRequestParam()
{
  $parameters = array();
  
  foreach ($this->rules as $key => $value) {
      
    if (preg_match('~^'.$value.'$~i', $this->requestURI(), $matches)) {
        
      return $parameters[] = $matches;
   
    }
   
  }
  
}

public function parseQuery($var)
{
    /**
     *  Use this function to parse out the query array element from
     *  the output of parse_url().
     */
    $var  = parse_url($var, PHP_URL_QUERY);
    $var  = html_entity_decode($var);
    $var  = explode('&', $var);
    $arr  = array();
    
    foreach($var as $val)
    {
        $x          = explode('=', $val);
        $arr[$x[0]] = $x[1];
    }
    
    unset($val, $x, $var);
    
    return $arr;
    
}

public function URLDispatcher($action = '', $param = null)
{
 
 try {
 
    $views = array();
     
    foreach ($this->rules as $r => $rule) {
          
       if (preg_match('~^'.$rule.'$~i', $this->requestURI())) {
              
           if (!empty($action) && $action == $r) $fileName = strtolower($r) . '.php';
           
           if (is_readable(APP_SYSPATH . 'public' . DS . $fileName)) {
          
               if (!is_null($param)) $views['param'] = preventInject($param);
               
               include(APP_SYSPATH . 'public' . DS . $fileName);
           
           }
        }
          
     }
                 
  } catch (RuntimeException $e) {
      
      $this->errors = LogError::newMessage($e);
      $this->errors = LogError::customErrorMessage();
  }
     
 }
 
 public function URLElement($args)
 {
   $element = $this->requestPath();
   $element = explode('/', $element);
   
   if (isset($element[$args])) {
       
       return $element[$args];
   
   } else {
       
       return false;
   }
   
 }
  
 protected function requestPath()
 {
   $request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
   $script_name = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
   $parts = array_diff_assoc($request_uri, $script_name);
     
   if (empty($parts)) {
      return '/';
   }
     
   $path = implode('/', $parts);
   
   if (($position = strpos($path, '?')) !== FALSE) {
     $path = substr($path, 0, $position);
   }
     
   return $path;
     
 }
 
 protected function requestURI()
 {
   $uri = rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/' );
   $uri = '/' . trim(str_replace( $uri, '', $_SERVER['REQUEST_URI'] ), '/' );
   $uri = urldecode($uri);
   return $uri;
 }
 
}