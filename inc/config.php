<?php

define('DS', DIRECTORY_SEPARATOR);

//database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'kartatopia');
define('DB_NAME', 'kartatopia');

// Site configuration
define('APP_DIR', "http://localhost/legacysite" . DS);  // define site path
define('APP_PUBLIC',  APP_DIR . 'public' . DS); 
define('APP_SITEEMAIL', 'alanmoehammad@gmail.com');
define('APP_SITEKEY', 'd0d48739c3b82db413b3be8fbc5d7ea1c1fd3e2792605d3cbfda1HEM78!!');
define('APP_INC', 'inc');
define('APP_CLASS', 'classes');
define('APP_CONTROL_PANEL', APP_DIR . 'cabin');
define('APP_LIBRARY', APP_INC  . DS . 'library' . DS);
define('APP_FILES', APP_DIR . 'files' . DS);
define('APP_PICTURE', APP_FILES . 'picture' . DS . 'photo' . DS);
define('SITEPAGE_DEFAULT_LASTMODIFIED_VALUE' , -1 ) ;

if (!defined('APP_SYSPATH')) define('APP_SYSPATH', dirname(dirname(__FILE__)) . '/');

if (!defined('PHP_EOL')) define('PHP_EOL', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? "\r\n" : "\n");

$key = 'e0aa8df8a945a35a77f617945f3ded43687a3a456f63c7b4fb6c0ae6e7f622b4';
$checkIncKey = sha1(mt_rand(1, 1000000).$key);
define('APP_KEY', $checkIncKey);

require 'utilities.php';
require 'rules.php';
require 'init.php';
include 'header.php';
include 'footer.php';

$errors = array();