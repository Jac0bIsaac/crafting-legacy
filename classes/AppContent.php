<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class AppContent
{
 
 protected $slides;
 
 protected $latestEvent;
 
 protected $singlePost;
 
 protected $singlePage;
 
 protected $allPosts;
  
 protected $catpost;
 
 protected $searchResults;
 
 protected $singleEvent;
 
 protected $allEvents;
 
 protected $photoListing;
 
 protected $singlePhoto;
  
 protected function setSlider(Post $slides, $position, $limit)
 {
     $this->slides = $slides;
     
     $results = $this->slides->findPosts($position, $limit);
     
     return $results;
 }
 
 protected function setLatestEvent(Event $latestEvent, $position, $limit)
 {
   
     $this->latestEvent = $latestEvent;
     
     $results = $this->latestEvent->findEvents($position, $limit);
     
     return $results;
   
 }
  
 protected function getPostById(Post $singlePost, $id, $sanitize)
 {
   $this->singlePost = $singlePost;
   
   $results = $this->singlePost->showPostById($id, $sanitize);
   
   return $results;
   
 }
 
 protected function getPageBySlug(Page $singlePage, $slug, $sanitize)
 {
  $this->singlePage = $singlePage;
  
  $results = $this->singlePage->findPageBySlug($slug, $sanitize);
  
  return $results;
  
 }
 
 protected function getCategoryPost(PostCategory $postCat, $catId, $sanitize)
 {
   $this->catpost = $postCat;
   
   $results = $this->catpost->showCategoryPost($catId, $sanitize);
   
   return $results;
   
 }
 
 protected function getAllPosts(Post $allPosts, $perPage, $sanitize)
 {
   $this->allPosts = $allPosts;
   
   $results = $this->allPosts->showAllPostPublished($perPage, $sanitize);
   
   return $results;
   
 }
 
 protected function seekingPost(SearchSeeker $searching, $data)
 {
   
   $this->searchResults = $searching;
   
   $results = $this->searchResults->searchPost($data);
   
   return $results;
   
 }
 
 protected function getEventBySlug(Event $event, $sanitize, $slug)
 {
  
  $this->singleEvent = $event;
  
  $results = $this->singleEvent->findEventBySlug($slug, $sanitize);
  
  return $results;
  
 }
 
 protected function getAllEvents(Event $event, $perPage, $sanitize)
 {
   $this->allEvents = $event;
   
   $results = $this->allEvents->showAllEvents($perPage, $sanitize);
   
   return $results;
   
 }
 
 
 protected function getPhotoListing(Photo $picture, $perPage, $sanitize)
 {
   $this->photoListing = $picture;
   
   $results = $this->photoListing->showPhotosByAlbum($perPage, $sanitize);
   
   return $results;
   
 }
 
 protected function getPhotoBySlug(Photo $photo, $slug, $sanitize)
 {
   $this->singlePhoto = $photo;
   
   $results = $this->singlePhoto->showPhotoBySlug($slug, $sanitize);
   
   return $results;
   
 }
 
}