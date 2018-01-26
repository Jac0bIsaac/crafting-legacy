<?php

$rules = array(
           
    '/'        => "/",
    'category' => "/category/(?'category'[\w\-]+)",
    'event'    => "/event/[0-9]{2}/[0-9]{2}/[0-9]{4}/(?'text'[^/]+)",
    'events'   => "/events/",
    'page'     => "/page/(?'page'[^/]+)",
    'post'     => "/post/(?'id'\d+)/(?'post'[\w\-]+)",
    'posts'    => "/posts/([^/]*)",
    'pictures' => "/pictures/",
    'picture'  => "/picture/(?'text'[^/]+)/(?'id'\d+)",
    'search'   => "(?'search'[\w\-]+)" 
  
);
