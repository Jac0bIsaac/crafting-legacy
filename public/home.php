<?php if (!defined('APP_KEY')) header("Location: 403.php");

$totalProducts = (isset($views['totalProducts'])) ? $views['totalProducts'] : 0;
$totalPosts = (isset($views['totalPosts'])) ? $views['totalPosts'] : 0;

?>

<!-- Product Grid Section -->
    <section id="product">
      <div class="container">
       
        <h3 class="text-center">
        <?php 
        echo (isset($views['unavailable'])) ? $views['unavailable'] : "Products";
        ?>
        </h3>
        <hr class="star-primary">
        <div class="row">
        <?php 
        
        if ($totalProducts > 0) :
           $no = 0;
           foreach ($views['products'] as $product) :
            $no++;
           
        ?>
          <div class="col-sm-4 portfolio-item">
            <a class="portfolio-link" href="<?php echo '#portfolioModal'.$no; ?>" data-toggle="modal">
              <div class="caption" >
                <div class="caption-content">
                  <i class="fa fa-search-plus fa-3x"></i>
                </div>
              </div>
              <?php 
              if ($product['product_image'] != '') :
              ?>
              <img class="img-fluid" src="<?php echo APP_PICTURE . $product['product_image']; ?>" alt="<?php echo $product['product_title']; ?>">
             <?php 
              endif;
             ?>
            </a>
          </div>
          
       <?php 
       endforeach;
       endif;
       ?>
         
        </div>
      </div>
    </section>
 
  <?php include 'modal.php'; ?>
  
    <!-- Blog -->
    <section class="success" id="blog">
      <div class="container">
        <h2 class="text-center">
         <?php 
        echo (isset($views['unpublished'])) ? $views['unpublished'] : "Blog";
        ?>
        </h2>
        <hr class="star-light">
        <div class="row">
          <div class="col-lg-8 mx-auto">
          <?php 
          if ($totalPosts > 0) :
          
            foreach ($views['posts'] as $post) :
          ?>
             <div class="card text-white bg-info mb-3">
             <div class="card-header ">
              <i class="fa fa-calendar"></i>
                 <?php 
                 echo $published = makeDate($post['date_created'], 'id');
                 ?>
             </div>
              <div class="card-body">
               <h5 class="card-title">
               <a href="<?php echo APP_DIR . 'post' . '/' . (int)$post['postID'] . '/'. htmlspecialchars($post['post_slug']); ?>" title="<?php echo $post['post_title']; ?>">
               <?php echo htmlspecialchars($post['post_title']); ?>
               </a>
               </h5>
               <?php 
               $article = strip_tags($post['post_content']);
               $article_content = substr($article, 0, 200);
               $article_content = substr($article, 0, strrpos($article_content, " "));
               ?>
               <p class="card-text"><?php echo html_entity_decode($article_content); ?></p>
              <a href="<?php echo APP_DIR . 'post' . '/' . (int)$post['postID'] . '/'. htmlspecialchars($post['post_slug']); ?>" title="<?php echo $post['post_title'] ?>" class="btn btn-primary">
              Lanjutkan membaca 
              <i class="fa fa-arrow-right"></i></a>
             </div>
           </div>
           <?php 
           endforeach; 
           endif; 
           ?>
          </div>
        </div>
      </div>
    </section>
    
      <!-- Contact Section -->
    <section id="contact">
      <div class="container">
        <h3 class="text-center">Contact Form</h3>
        <hr class="star-primary">
        <div class="row">
          <div class="col-lg-8 mx-auto">
          
           <form name="sentMessage" method="post" action="contact" id="contactForm" onSubmit="return validateContactForm(this)" novalidate>
              <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                  <label>Name</label>
                  <input class="form-control" name="name" id="name" type="text" placeholder="Name" required data-validation-required-message="Please enter your name.">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                  <label>Email Address</label>
                  <input class="form-control" id="email" name="email" type="email" placeholder="Email Address" required data-validation-required-message="Please enter your email address.">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                  <label>Phone Number</label>
                  <input class="form-control" name="phone" id="phone" type="text"  placeholder="Phone Number" required data-validation-required-message="Please enter your phone number.">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
                 
              <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                  <label>Message</label>
                  <textarea class="form-control" id="message" name="message" rows="5" placeholder="Message" required data-validation-required-message="Please enter a message."></textarea>
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <br>
              <div id="success"></div>
              <div class="form-group">
                <?php 
                   // create token for prevent CSRF
                   // prevent CSRF
                   $key= '1Af/MdfyPFO42PB+xK9C+iquu6ZU6QOVDpQfQ4oWU9Q=';
                   $CSRF = bin2hex(openssl_random_pseudo_bytes(32).$key);
                   $_SESSION['CSRF'] = $CSRF;
                 ?>
                <input type="hidden" name="csrf" value="<?= $CSRF; ?>"/>
                <input type="submit" class="btn btn-success btn-lg" name="send" value="Submit"/>
                
              </div>
            </form>
          
      
          </div>
        </div>
      </div>
    </section>
    
     <!-- About Section -->
    <section class="success" id="about">
      <div class="container">
        <h2 class="text-center">About Us</h2>
        <hr class="star-light">
        <div class="row">
          <div class="col-lg-4 ml-auto">
            <p>We are a distributed Indonesia company specializing in ecommerce innovation. 
             We provide software that empowers everyone to have a successful online store.
              </p>
          </div>
          <div class="col-lg-4 mr-auto">
            <p>Our common goal is making solution that delightfully useful for thousands of micro 
             and small business owners so that they can sell, sell, sell more.</p>
          </div>
          
        </div>
      </div>
    </section>
    