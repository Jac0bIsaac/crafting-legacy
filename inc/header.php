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
        <img class="img-fluid" alt="kartatopia" src="public/home/img/kartatopia.png">
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
 if (!$match) :
 
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
    global $configurations;
    
    $data_configs = $configurations -> findConfigs();
    $configs = $data_configs['results'];
    
    foreach ($configs as $config => $c) {
        $meta_title = htmlspecialchars($c['site_name']);
        $meta_desc = htmlspecialchars($c['meta_description']);
        $meta_key = htmlspecialchars($c['meta_keywords']);
        $tagline = htmlspecialchars($c['tagline']);
        $phone = htmlspecialchars($c['phone']);
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
    <meta name="description" content="<?php echo "$match | $param"; ?>">
    <meta name="keywords" content="<?php echo "$param"; ?>">
    <?php 
    else :
    ?>
    <meta name="description" content="<?php echo "$match-$meta_desc"; ?>">
    <meta name="keywords" content="<?php echo " $param-$meta_key"; ?>">
   <?php endif; ?>
    <title>
    <?php 
     if (!empty($param)) : 
          echo "$match | $param";
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

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand" href="<?php echo APP_DIR; ?>"><i class="fa fa-arrow-left"></i> Back to Homepage</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.html">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="post.html">Sample Post</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('<?php echo APP_PUBLIC; ?>blog/img/home-bg.jpg')">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1>
               <?php 
                 if (!empty($param)):
                 
                    echo $param;
                 
                 else:
                 
                    echo $meta_title;
                   
                 endif;
               ?>
              </h1>
              <?php
              if (!empty($param)) :
              ?>
              <span class="subheading"><?php echo $param; ?></span>
              <?php 
              else :
              ?>
              <span class="subheading"><?php echo "$tagline | $phone"; ?></span>
              <?php 
              endif;
              ?>
            </div>
          </div>
        </div>
      </div>
    </header>

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