<?php
/**
 * Class Simple Cache Improved
 * @author jason
 * @link http://www.xeweb.net/2011/11/03/simple-php-caching-improved/
 *
 */

class SimpleCache
{
 public $cache_expires = 3600;
 
 public $cache_folder = APP_SYSPATH . 'public/cache/';
 
 public $include_query_strings = true;
 
 private $cache_file = "";
 
 
 /**
  * Set the current cache file from the page URL
  */
 public function __construct() 
 {
     
     $file = $_SERVER['REQUEST_URI'];
     if (!$this->include_query_strings && strpos($file, "?") !== false) {
         $qs = explode("?", $file);
         $file = $qs[0];
     }
     
     $this->cache_file = $this->cache_folder . md5($file) . ".html";
 }
 
 /**
  * Checks whether the page has been cached or not
  * @return boolean
  */
 public function is_cached() 
 {
     $modified = (file_exists($this->cache_file)) ? filemtime($this->cache_file) : 0;
     return ((time() - $this->cache_expires) < $modified);
 }
 
 /**
  * Reads from the cache file
  * @return string the file contents
  */
 public function read_cache() 
 {
     return file_get_contents($this->cache_file);
 }
 
 /**
  * Writes to the cache file
  * @param string $contents the contents
  * @return boolean
  */
 public function write_cache($contents) 
 {
     return file_put_contents($this->cache_file, $contents);

 }
 
 public function __destruct()
 {
    $this->cache_file = null;
 }
 
}