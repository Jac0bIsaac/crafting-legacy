<?php if (!defined('APP_KEY')) header("Location: 403.php");

// Homepage
function grabHome()
{
  global $products, $posts;
  
  $views = array();
  
  $data_products = $products -> findProducts(0, 6);
  $data_posts = $posts -> findPosts(0, 6);
  
  // latest products
  $views['products'] = $data_products['results'];
  $views['totalProducts'] = $data_products['totalProducts'];
  
  // latest posts
  $views['posts'] = $data_posts['results'];
  $views['totalPosts'] = $data_posts['totalPosts'];
  
  if (empty($data_products['totalProducts'])) $views['unavailable'] = "Product Unavailable";
  
  if (empty($data_posts['totalPosts'])) $views['unpublished'] = "No post";
   
  require 'home.php';
  
}

// post
function grabPost($param = null)
{
 global $dbc, $posts, $post_cats, $widgets, $frontContent, $sanitize, $frontPaginator;
 
 $views = array();
  
 if (!is_null($param)) {
     
   $read = $frontContent -> readPost($posts, $param, $sanitize);
   
   if (!$read) {
       
      ErrorNotFound();
      
   } else {
       
       // show detail post
       $views['post_id'] = (int)$read['postID'];
       $views['post_title'] = htmlspecialchars($read['post_title']);
       $views['post_image'] = htmlspecialchars($read['post_image']);
       $views['post_slug'] = htmlspecialchars($read['post_slug']);
       $views['post_content'] = html_entity_decode($read['post_content']);
       $views['post_created'] = makeDate($read['date_created']);
       $views['post_status'] = $read['post_status'];
       $views['comment_status'] = $read['comment_status'];
       $views['post_author'] = htmlspecialchars($read['volunteer_login']);
       
       // related posts
       $data_related_post = $posts -> showRelatedPosts((int)$read['postID']);
       $views['relatedPosts'] = $data_related_post['relatedPosts'];
      
       // previous next link article
       $dataLinkNext = $widgets -> setNextNavigation($read['postID'], $sanitize);
       $dataLinkPrev = $widgets -> setPrevNavigation($read['postID'], $sanitize);
       $views['linkNext'] = $dataLinkNext['results'];
       $views['linkPrev'] = $dataLinkPrev['results'];
       
       require 'post.php';
       
   } 
   
 } else {
     
    $postsPublished = $frontContent -> grabAllPosts($posts, $frontPaginator, $sanitize);
     
    if (empty($postsPublished['totalRows'])) {
         
      $views['errorMessage'] = "Sorry, there aren't any posts published";
         
     } else {
         
         $views['allPosts'] = $postsPublished['allPostsPublished'];
         $views['totalPostPublished'] = $postsPublished['totalRows'];
         $views['pageLink'] = $postsPublished['paginationLink'];
         
         // list categories on sidebar
         $postCategories = $widgets -> setSidebarCategories();
         $views['postCategories'] = $postCategories['categories'];
         
         // recent posts on sidebar
         $recentPosts = $widgets -> showRecentPosts('publish', 0, 3);
         $views['recentPosts'] = $recentPosts['recentPosts'];
         
     }
     
    require 'posts.php';
     
 }
 
}

// post category
function grabCategory($param)
{
 global $categories, $post_cats, $frontContent, $sanitize;
    
 $views = array();
    
 $catId = $categories -> findCategoryBySlug($param, $sanitize);
    
 if (!$catId) ErrorNotFound();
    
 $catPost = $frontContent -> grabCategoryPost($post_cats, (int)$catId['categoryID'], $sanitize);
    
 $views['catPosts'] = $catPost['catPosts'];
 $views['totalCatPost'] = $catPost['totalRows'];
 
 require 'category.php';
    
}


// submit message from contact form
function submitMessage()
{
 global $inbox;
 
 $views = array();
 
 $name = $email = $phone = $msg = "";
 
 $form_fields = array("name"=>90, "email"=>180, "phone" => 13, "message"=>500);
 
 try {
     
     if (isset($_POST['send']) && $_POST['send'] == 'Submit') {
         
         $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
         $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
         $phone = isset($_POST['phone']) ? str_replace(array(' ', '-', '(', ')'), '', $_POST['phone']) : "";
         $msg = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
         
         $badCSRF = true; // check CSRF
         
         if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
             || $_POST['csrf'] !== $_SESSION['CSRF']) {
                 
                 throw new InboxException("Sorry, there is a security issue");
                 $badCSRF = true;
                 
             } elseif (empty($name) || empty($email) || empty($phone) || empty($msg)) {
                 
                 throw new InboxException("All Column must be filled !");
                 
             } elseif (!preg_match('/^[0-9]{10,13}$/', $phone)) {
                 
                 throw new InboxException("Your phone number is not valid !");
                 
             } elseif (is_valid_email_address(trim($email)) == 0) {
                 
                 throw new InboxException("Please enter a valid email address");
                 
             } elseif ( !preg_match('/^[a-zA-Z ]*$/', $name)) {
                 
                 throw new InboxException("Please enter a your valid name");
                 
             } elseif (preg_match("/\b(?:(?:https?|ftp|http):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $msg)) {
                 
                 throw new InboxException("Error, please remove URLs");
                 
             } else {
                 
                 $badCSRF = false;
                 unset($_SESSION['CSRF']);
                 
                 if (valueSizeValidation($form_fields)) {}
                 
                 $date_sent = date("Ymd");
                 $time_sent = date("H:i:s");
                 $submit_message = $inbox -> sendMessage($name, $email, $phone, preventInject($msg), $date_sent, $time_sent);
                 
                 if ($submit_message) {
                     
                     // Create the email and send the message
                     $to = safeEmail('hello@kartatopia.com'); // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
                     $email_subject = "You get contact form message from:  $name";
                     $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail:".safeEmail($email)."\n\nPhone: $phone\n\nMessage:\n$msg";
                     $headers = "From: noreply@kartatopia.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
                     $headers .= "Reply-To: $email";
                     mail($to,$email_subject,$email_body,$headers);
                     
                     $views['successMessage'] = "Your Message sent";
                     
                     require 'contact.php';
                     
                 }
             }
             
     } else {
         
         require 'contact.php';
     }
     
 } catch (InboxException $e) {
     
   $views['errorMessage'] = $e -> getMessage();
   include 'contact.php';
     
 }
 
}

// 404 Error Not Found
function ErrorNotFound()
{
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  include '404.php';
}