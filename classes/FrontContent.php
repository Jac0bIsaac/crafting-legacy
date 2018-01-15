<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class FrontContent extends AppContent
{
  
  protected $errors;
  
  public function slideHeadliner($headlines, $position, $limit)
  {
      
    try {
    
       if (is_object($headlines)) {
            
            return $this->setSlider($headlines, $position, $limit);
       
       } else {
           
          throw new Exception('Sorry, the variable requested is not an object');
          
       }
        
    } catch (Exception $e) {
       
       $this->errors = LogError::newMessage($e);
       $this->errors = LogError::customErrorMessage();
       
    }
    
  }
  
  public function latestEvent($latestEvent, $position, $limit)
  {
      
    try {
        
     if (is_object($latestEvent)) {
         
       return $this->setLatestEvent($latestEvent, $position, $limit);
         
     } else {
         
       throw new Exception('Sorry, the variable requested is not an object');
         
     }
     
    } catch (Exception $e) {
       
      $this->errors = LogError::newMessage($e);
      $this->errors = LogError::customErrorMessage();
      
    }
    
  }
  
  public function readPost($singlePost, $id, $sanitize)
  {
    try {
        
      if (is_object($singlePost)) {
          
        return $this->getPostById($singlePost, $id, $sanitize);
        
      } else {
        
       throw new Exception('Sorry, the variable requested is not an object');
          
      }
      
    } catch (Exception $e) {
        
       $this->errors = LogError::newMessage($e);
       $this->errors = LogError::customErrorMessage();
       
    }
    
  }
  
  public function readPage($singlePage, $slug, $sanitize)
  {
   try {
      if (is_object($singlePage)) {
          
        return $this->getPageBySlug($singlePage, $slug, $sanitize);
       
      } else {
        
        throw new Exception('Sorry, the variable requested is not an object');
          
      }
      
   } catch (Exception $e) {
     
     $this->errors = LogError::newMessage($e);
     $this->errors = LogError::customErrorMessage();
       
   }
   
  }
  
 public function grabAllPosts($posts, $perPage, $sanitize)
 {
   
   try {
       
       if (is_object($posts) && is_object($perPage) && is_object($sanitize)) {
           
           return $this->getAllPosts($posts, $perPage, $sanitize);
       
       } else {
           
           throw new Exception('Sorry, the variable requested is not an object');
       }
       
   } catch (Exception $e) {
      
       $this->errors = LogError::newMessage($e);
       $this->errors = LogError::customErrorMessage();
       
   }
   
 }
 
 public function grabCategoryPost($post_cats, $catId, $sanitize)
 {
   try {
       
      if (is_object($post_cats) && is_object($sanitize)) {
          
       return $this->getCategoryPost($post_cats, $catId, $sanitize);
       
      } else {
        
          throw new Exception('Sorry, the variable requested is not an object');
         
      }
      
   } catch (Exception $e) {
       
       $this->errors = LogError::newMessage($e);
       $this->errors = LogError::customErrorMessage();
       
   }
   
 }
 
 public function searchingPost($searching, $data)
 {
  try {
      
    if (is_object($searching)) {
        
      return $this->seekingPost($searching, $data);
      
    } else {
        
      throw new Exception('Sorry, the variable requested is not an object');
        
    }
    
  } catch (Exception $e) {
      
    $this->errors = LogError::newMessage($e);
    $this->errors = LogError::customErrorMessage();
      
  }
  
 }
 
 public function showEvent($event, $sanitize, $slug)
 {
   try {
       
     if (is_object($event) && is_object($sanitize)) {
         
       return $this->getEventBySlug($event, $sanitize, $slug);
        
     } else {
         
       throw new Exception('Sorry, the variable requested is not an object');
         
     }
     
   } catch (Exception $e) {
     
    $this->errors = LogError::newMessage($e);
    $this->errors = LogError::customErrorMessage();
        
   } 
   
 }
 
 public function showEvents($events, $perPage, $sanitize)
 {
  try {
     
    if (is_object($events) && is_object($perPage) && is_object($sanitize)) {
      
      return $this->getAllEvents($events, $perPage, $sanitize);     
    
    } else {
        
      throw new Exception('Sorry, the variable requested is not an object');
    
    }
    
  } catch (Exception $e) {
      
     $this->errors = LogError::newMessage($e);
     $this->errors = LogError::customErrorMessage();
      
  } 
  
 }
 
 public function showPhotos($picture, $perPage, $sanitize)
 {
   try {
      
     if (is_object($picture) && is_object($perPage) && is_object($sanitize)) {
         
      return  $this->getPhotoListing($picture, $perPage, $sanitize);
     
     } else {
         
      throw new Exception('Sorry, the variable requested is not an object');
         
     }
     
   } catch (Exception $e) {
       
       $this->errors = LogError::newMessage($e);
       $this->errors = LogError::customErrorMessage();
       
   }
   
 }
 
 public function showPhoto($photo, $slug, $sanitize)
 {
   try {
      
     if (is_object($photo) && is_object($sanitize)) {
      
       return $this->getPhotoBySlug($photo, $slug, $sanitize);
        
     }
   } catch (Exception $e) {
     
      $this->errors = LogError::newMessage($e);
      $this->errors = LogError::customErrorMessage();
    
   }  
 }
 
}