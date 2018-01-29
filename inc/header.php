<?php
function setHeader($match, $param = null)
{
 global $configurations;

 $views = array();
 
 $data_configs = $configurations -> findConfigs();
 $meta_tags = $data_configs['results'];
 
 foreach ($meta_tags as $meta_tag => $m) {
   $views['meta_title'] = htmlspecialchars($m['site_name']); 
   $views['meta_description'] = htmlspecialchars($m['meta_description']);
   $views['meta_keywords'] = htmlspecialchars($m['meta_keywords']);
   $views['tagline'] = htmlspecialchars($m['tagline']);
   $views['phone'] = htmlspecialchars($m['phone']);
 }
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $views['meta_description']; ?>">
    <meta name="keywords" content="<?php echo $views['meta_keywords']; ?>">

    <title><?php echo "{$views['tagline']} | {$views['meta_title']} | Call Us: {$views['phone']}";  ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo APP_PUBLIC; ?>home/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?php echo APP_PUBLIC; ?>home/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="<?php echo APP_PUBLIC; ?>home/css/freelancer.min.css" rel="stylesheet">
    
    <!-- Icon -->
    <link href="<?php echo APP_PUBLIC; ?>home/img/favicon.ico" rel="Shortcut Icon" />
    

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a href="<?php echo APP_DIR; ?>" class="navbar-brand js-scroll-trigger" href="#page-top">
        <img class="img-fluid" alt="kartatopia" src="<?php echo APP_PUBLIC; ?>home/img/kartatopia.png">
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#product" title="Our products" >Products</a>
            </li>
             <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#blog" title="Blog" >Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#contact" title="Contact Us" >Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about" title="About Us" >About</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
 <?php 
 if (!$match && empty($param)) :
 
 ?>
  
    <header class="masthead">
      <div class="container">
        <img class="img-fluid" src="<?php echo APP_PUBLIC; ?>home/img/error404.png" alt="powering your online store">
        <div class="intro-text">
          <span class="name">Page not Found</span>
          <hr class="star-light">
           <span class="skills">Error - Page not Found !</span>
        </div>
      </div>
    </header>
   
<?php 
else :
?>
   <header class="masthead">
      <div class="container">
        <img class="img-fluid" src="<?php echo APP_PUBLIC; ?>home/img/piluscart.png" alt="powering your online store">
        <div class="intro-text">
          <span class="name"><?php echo "{$views['tagline']}"; ?></span>
          <hr class="star-light">
           <span class="skills">Free - Open Source - eCommerce Software - Online Shop</span>
        </div>
      </div>
    </header>
<?php 
endif; 
?>
   
<?php     
}

function blogHeader($match, $param = null)
{
    global $configurations, $posts, $widgets, $frontContent, $frontPaginator, $sanitize;
    
    $data_configs = $configurations -> findConfigs();
    $configs = $data_configs['results'];
    
    foreach ($configs as $config => $c) {
        $meta_title = htmlspecialchars($c['site_name']);
        $meta_desc = htmlspecialchars($c['meta_description']);
        $meta_key = htmlspecialchars($c['meta_keywords']);
        $tagline = htmlspecialchars($c['tagline']);
        $phone = htmlspecialchars($c['phone']);
    }
    
   // get all post published
   $postPublished = $frontContent -> grabAllPosts($posts, $frontPaginator, $sanitize);
   $totalPostPublished = $postPublished['totalRows'];
    
   // get post categories
   $postCategories = $widgets -> setSidebarCategories();
   $navcats = $postCategories['categories'];
    
   // get detail post
   if (!empty($param)) {
   $r = $frontContent -> readPost($posts, $param, $sanitize);
   $post_image = $r['post_image'];
   $postId = (int)$r['postID'];
   }
   
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php 
    if (!empty($param)):
    ?> 
    <meta name="description" content="<?php echo $match . " | " . $r['post_title']; ?>">
    <meta name="keywords" content="<?php echo $r['post_title']; ?>">
    <?php 
    else :
    ?>
    <meta name="description" content="<?php echo "$match-$meta_desc"; ?>">
    <meta name="keywords" content="<?php echo "$param-$meta_key"; ?>">
   <?php 
   endif; 
   ?>
   
    <title>
    <?php 
     if (!empty($param)) : 
          echo  $r['post_title'];
     else :
          echo "$match | $meta_title | Call Us: $phone";
     
      endif;    
    ?> 
    </title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo APP_PUBLIC; ?>blog/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?php echo APP_PUBLIC; ?>blog/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="<?php echo APP_PUBLIC; ?>blog/css/clean-blog.min.css" rel="stylesheet">
    <link href="<?php echo APP_PUBLIC; ?>blog/css/pagination.css" rel="stylesheet">
    
  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand" href="<?php echo APP_DIR; ?>"><i class="fa fa-arrow-left"></i> Home</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
          
         <?php 
         if (isset($totalPostPublished) && $totalPostPublished > 0) :
         ?>
         <li class="nav-item">
          <a class="nav-link" href="<?php echo APP_DIR . 'posts'; ?>" title="All Posts">
           Posts
          </a>
         </li>
         <?php 
         endif;
         ?>
         <?php 
         if (isset($navcats)) :
         ?>  
        
          <?php 
           
           foreach ($navcats as $navcat) :
            
          ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo APP_DIR .'category/'.htmlspecialchars($navcat['category_slug']); ?>" title="<?= $navcat['category_title']; ?>">
              <?php echo htmlspecialchars($navcat['category_title']); ?>
              </a>
            </li>
          <?php 
          endforeach;
          endif;
          ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <?php 
    if (!empty($param)) :
    ?>
   
     <header class="masthead" style="background-image: url('<?php echo APP_PICTURE . $post_image; ?>')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="post-heading">
              <h1>
              <?php
              echo htmlspecialchars($r['post_title']);
              ?>
              </h1>
            
              <span class="meta"><i class="fa fa-user"></i>
              <a href="#">
              <?php 
              echo htmlspecialchars($r['volunteer_login']);
              ?>
              </a>
              <i class="fa fa-calendar"></i>
              <?php 
              echo makeDate($r['date_created'], 'id'); 
              ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </header>
    
    <?php
    else :
    ?>
      <header class="masthead" style="background-image: url('public/blog/img/teamwork.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1><?php echo $meta_title; ?></h1>
              <span class="subheading"><?php echo "$tagline | $phone"; ?></span>
            </div>
          </div>
        </div>
      </div>
    </header>
    <?php 
    endif;
    ?>
 
<?php 
}

function contactHeader() 
{
    
  global $configurations;
    
  $views = array();
    
  $data_configs = $configurations -> findConfigs();
  $meta_tags = $data_configs['results'];
    
  foreach ($meta_tags as $meta_tag => $m) {
     $views['meta_title'] = htmlspecialchars($m['site_name']);
     $views['meta_description'] = htmlspecialchars($m['meta_description']);
     $views['meta_keywords'] = htmlspecialchars($m['meta_keywords']);
     $views['tagline'] = htmlspecialchars($m['tagline']);
     $views['phone'] = htmlspecialchars($m['phone']);
  }
  
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $views['meta_description']; ?>">
    <meta name="keywords" content="<?php echo $views['meta_keywords']; ?>">

    <title><?php echo "{$views['tagline']} | {$views['meta_title']} | Call Us: {$views['phone']}";  ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo APP_PUBLIC; ?>home/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?php echo APP_PUBLIC; ?>home/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="<?php echo APP_PUBLIC; ?>home/css/freelancer.min.css" rel="stylesheet">
    
    <!-- Icon -->
    <link href="<?php echo APP_PUBLIC; ?>home/img/favicon.ico" rel="Shortcut Icon" />
    

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a href="<?php echo APP_DIR; ?>" class="navbar-brand js-scroll-trigger" href="#page-top">
        <img class="img-fluid" alt="kartatopia" src="public/home/img/kartatopia.png">
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="<?php echo APP_DIR; ?>#product" title="Our products" >Products</a>
            </li>
             <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="<?php echo APP_DIR; ?>#blog" title="Blog" >Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="<?php echo APP_DIR; ?>#contact" title="Contact Us" >Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="<?php echo APP_DIR; ?>#about" title="About Us" >About</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

 <header class="masthead">
      <div class="container">
        <img class="img-fluid" src="<?php echo APP_PUBLIC; ?>home/img/sendMail.png" alt="powering your online store">
        <div class="intro-text">
          <span class="name">Contact Us</span>
          <hr class="star-light">
           <span class="skills">Please feel free to contact us if you need any further information</span>
        </div>
      </div>
    </header>
<?php
}