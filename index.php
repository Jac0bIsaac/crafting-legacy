<?php 
$start = microtime(true);
require 'inc/config.php';

$findParam = $dispatching-> findRequestParam(); // finding request URI

$matched = (is_array($findParam) && array_key_exists(0, $findParam)) ? $findParam[0] : '';
$param1 = (is_array($findParam) && array_key_exists(1, $findParam)) ? $findParam[1] : '';
$param2 = (is_array($findParam) && array_key_exists(2, $findParam)) ? $findParam[2] : '';

$action = $dispatching -> URLElement(0); // call 1st element action path

// initializing and checking parameters
$parameters = (isset($matched) || is_array($action)
    || is_array($dispatching->URLElement(1))
    || is_array($dispatching->URLElement(2))) ? $param1 : $param2;
    
if ($action == 'posts' || $action == 'post') {
   blogHeader($action, $param1);
} elseif ($action == 'contact') {
   contactHeader(); 
} else {
   setHeader($matched, $action);
}

include 'public/content.php';

if (!$action) {
    
   grabHome();

} else {
    
   switch ($action) {
    
       case 'post':
       
           if ($parameters) {
               
              checkDetailRequest($action, $param1);
              
           } else {
               
              ErrorNotFound();
           }
           
           break;
       
       case 'posts':
           
           grabPost();
           
           break;
           
       case 'contact':
           
           submitMessage();
           
           break;
           
       default:
           
           ErrorNotFound();
          
           break;
           
   }
   
}

if ($action == 'posts' || $action == 'post') {
    
   blogFooter();
   
} else {

   setFooter();
    
}

ob_end_flush();

echo ' '.(microtime(true)-$start);