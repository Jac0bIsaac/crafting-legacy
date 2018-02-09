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
 
 public function generatePostFeed($title, $link, $description)
 {
   $dataPosts = $this->getPostFeed();
   
   $rssFile = $this->setFileXML('rss.xml', 'w');
   
   $headerInit = '<?xml version="1.0" encoding="UTF-8"?> 
                   <rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"> 
                   <channel>
                   <atom:link href="'.$link.'rss.xml" rel="self" type="application/rss+xml" /> 
                  <title>'.$title.'</title> 
                  <link>'.$link.'</link> 
                  <description>'.$description.'</description> 
                  <language>id</language>';
   
   fwrite($rssFile, $headerInit);
   
   foreach ($dataPosts as $dataPost) {
       
     //build the full URL to the post
     $url = APP_DIR . 'post'.'/'.(int)$dataPost['postID'].'/'.$dataPost['post_slug'];
     
     // date post created
     $published = date(DATE_RSS, strtotime($dataPost['date_created']));
     
     // paragraf
     $content = htmlentities(strip_tags(nl2br(html_entity_decode($dataPost['post_content']))));
     $paragraph = substr($content, 0, 220);
     $paragraph = substr($content, 0, strrpos($paragraph," "));
     
     // uniquid
     $guid = uniqid($dataPost['postID']);
     
     $body = '<item>
             <title>'.$dataPost['post_title'].'</title>
             <description>'.$paragraph.'..</description>
             <link>'.$url.'</link>
             <guid isPermaLink="false">'.$guid.'</guid>
             <pubDate>'.$published.'</pubDate>
             </item>';
     
     fwrite($rssFile, $body);
     
   }
 
   $footerInit = "</channel></rss>";
   
   fwrite($rssFile, $footerInit);
   fclose($rssFile);
   
 }
 
}