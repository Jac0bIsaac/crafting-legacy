<?php
/**
 * class Registry
 * @author maoelana
 *
 */

class Registry
{
 
 private static $_data = array();
 
 public static function get($key)
 {
   return (isset(self::$_data[$key]) ? self::$_data[$key] : null);
 }
 
 public static function set($key, $value)
 {
   self::$_data[$key] = $value;
 }
 
 public static function setAll(array $key = array()) 
 {
   self::$_data = $key;
 }
 
 public static function isKeySet($key)
 {
   return (isset(self::$_data[$key]));  
 }
 
}