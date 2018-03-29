<?php
date_default_timezone_set('Asia/Jakarta');
ini_set('memory_limit', '2M');
//ini_set("session.cookie_secure", "True");  //secure
ini_set("session.cookie_httponly", "True"); // httpOnly
//header("Content-Security-Policy: default-src https:; font-src 'unsafe-inline' data: https:; form-action 'self' https://kartatopia.com;img-src data: https:; child-src https:; object-src 'self' www.google-analytics.com ajax.googleapis.com platform-api.sharethis.com kartatopia-studio.disqus.com; script-src 'unsafe-inline' https:; style-src 'unsafe-inline' https:;");

define('DS', DIRECTORY_SEPARATOR);

//database credentials
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

// Site configuration
define('APP_DIR', "http://" . DS);  // define absolute site URL
define('APP_PUBLIC',  APP_DIR . 'public' . DS); 
define('APP_SITEEMAIL', 'alanmoehammad@gmail.com');
define('APP_SITEKEY', 'd0d48739c3b82db413b3be8fbc5d7ea1c1fd3e2792605d3cbfda1HEM78!!');
define('APP_INC', 'inc');
define('APP_CLASS', 'classes');
define('APP_CONTROL_PANEL', APP_DIR . 'cabin');
define('APP_LIBRARY', APP_INC  . DS . 'library');
define('APP_FILES', APP_DIR . 'files' . DS);
define('APP_PICTURE', APP_FILES . 'picture' . DS . 'photo' . DS);

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