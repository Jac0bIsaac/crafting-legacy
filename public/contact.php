<?php if (!defined('APP_KEY')) header("Location: 403.php");

$erroMessage = (isset($views['errorMessage'])) ? $views['errorMessage'] : "";
$successMessage = (isset($views['successMessage'])) ? $views['successMessage'] : "";

?>
<!-- Contact Section -->
<section id="contact">
      <div class="container">
        <h3 class="text-center">Contact Form</h3>
        <hr class="star-primary">
         <?php 
   if ($erroMessage) :
   ?>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>ERROR!</strong> <?php echo $erroMessage; ?>.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <?php 
  elseif($successMessage) :
  ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> <?php echo $successMessage; ?>.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <?php 
  endif;
  ?>
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