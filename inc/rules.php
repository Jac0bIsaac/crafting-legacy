<?php

$rules = array(
           
    '/'        => "/",
    'category' => "/category/(?'category'[\w\-]+)",
    'contact'  => "/contact/([^/]*)",
    'event'    => "/event/[0-9]{2}/[0-9]{2}/[0-9]{4}/(?'text'[^/]+)",
    'events'   => "/events/",
    'page'     => "/page/(?'page'[^/]+)",
    'post'     => "/post/(?'id'\d+)/(?'post'[\w\-]+)",
    'posts'    => "/posts/([^/]*)",
    'products' => "/products/",
    'product'  => "/product/(?'id'\d+)/(?'text'[^/]+)",
    'search'   => "(?'search'[\w\-]+)" 
  
);
