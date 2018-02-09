<?php

class RssFeed
{
 protected $dbc;
 
 protected $error;
 
 public function __construct($dbc)
 {
  $this->dbc = $dbc;
 }
 
 protected function getPostFeed()
 {
   $postFeed = array();
   
   $sql = "SELECT p.postID, p.post_image, p.post_author,
             p.date_created, p.date_modified, p.post_title,
             p.post_slug, p.post_content, p.post_type,
             p.post_status, v.volunteer_login
  		   FROM posts AS p
  		   INNER JOIN volunteer AS v ON p.post_author = v.ID
  		   WHERE p.post_type = 'blog' AND p.post_status = 'publish'
  		   ORDER BY p.postID DESC LIMIT 10";
   
   $stmt = $this->dbc->query($sql);
   
   foreach ($stmt -> fetchAll() as $results) {
       
      $postFeed[] = $results;
      
   }
   
   return $postFeed;
          
 }
 
 public function setFileXML($filename, $mode)
 {
   return fopen($filename, $mode);
 }
 
 public function generatePostFeed($title, $link)
 {
   $dataPosts = $this->getPostFeed();
   
   $rssFile = $this->setFileXML('rss.xml', 'w');
   
   $headerInit = '<?xml version="1.0" encoding="UTF-8"?> 
                   <rss version="2.0"> 
                   <channel> 
                  <title>'.$title.'</title> 
                  <link>'.$link.'</link> 
                  <description>Feed Description</description> 
                  <language>id</language>';
   
   fwrite($rssFile, $headerInit);
   
   foreach ($dataPosts as $dataPost) {
       
     //build the full URL to the post
     $url = APP_DIR . 'post'.'/'.(int)$dataPost['postID'].'/'.$dataPost['post_slug'];
     
     // date post created
     $published = date(DATE_RSS, strtotime($dataPost['date_created']));
     
     // paragraf
     $content = html_entity_decode(strip_tags(nl2br($dataPost['post_content'])));
     $paragraph = substr($content, 0, 500);
     $paragraph = substr($content, 0, strrpos($paragraph," "));
     
     // uniquid
     $guid = uniqid($dataPost['postID']);
     
     $body = "<item>
             <title>{$dataPost['post_title']}</title>
             <description>{$paragraph}...</description>
             <link>{$url}</link>
             <guid>{$guid}</guid>
             <pubDate>{$published}</pubDate>
             </item>";
     
     fwrite($rssFile, $body);
     
   }
 
   $footerInit = "</channel></rss>";
   
   fwrite($rssFile, $footerInit);
   fclose($rssFile);
   
 }
 
}