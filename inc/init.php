<?php

spl_autoload_register(null, false);

// spesifikasi file php yang akan diload
spl_autoload_extensions('.php');

if (!function_exists('autoloader')) {
    
    function autoloader($class)
    {
        try {
            
            $className = APP_SYSPATH . APP_CLASS . DS . $class . '.php';
            $libraryName = APP_SYSPATH. APP_LIBRARY . DS . $class . '.php';
            
            if (is_file($libraryName) && !class_exists($libraryName)) {
                
                require $libraryName;
                
            } elseif (is_readable($className) && !class_exists($className)) {
                
                require $className;
            }
            
        } catch (Exception $e) {
            
            echo 'Exception caught :', $e -> getMessage(), "\n";
            
        }
        
    }
}


if (version_compare(PHP_VERSION, '5.4', '>=')) {
    
    if (version_compare(PHP_VERSION, '5.6', '>=')) {
        
        spl_autoload_register('autoloader');
        
    }
    
} 

$dbc = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
$dbc -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbc -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

Registry::setAll(array('dbc' => $dbc, 'route' => $rules));

$authentication = new Authentication($dbc);
$sanitize = new Sanitize();
$volunteers = new Volunteer();
$categories = new Category();
$configurations = new Configuration($dbc);
$albums = new Album();
$events = new Event();
$photos = new Photo();
$files =  new Files();
$pages = new Page();
$posts = new Post();
$post_cats = new PostCategory();
$productFlavours = new ProductFlavour();
$products = new Product();
$searchPost = new SearchSeeker($dbc);
$inbox = new Inbox();
$menus = new Menu();
$dashboards = new Dashboard();
$widgets = new Widget();
$dispatching = new Dispatcher();
$frontContent = new FrontContent();
$frontPaginator = new Paginator(6, 'p');
$postFeeds = new RssFeed($dbc);

if ( !startSessionOnSite() ) {
    session_id( uniqid() );
    session_start();
    session_regenerate_id();
}

@ob_start();

require(APP_SYSPATH . APP_LIBRARY . DS . 'open-graph-protocol.php');

$imageGraphProtocol = new OpenGraphProtocolImage();
$ogp = new OpenGraphProtocol();

set_exception_handler('LogError::exceptionHandler');
set_error_handler('LogError::errorHandler');