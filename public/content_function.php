<?php if (!defined('APP_KEY')) header("Location: 403.php");

function grabHome($products, $posts)
{
    $views = array();
    if (is_object($products) && is_object($posts)) {
        
        $grabProducts = function() use ($products)
        {
            return $data_products = $products -> findProducts(0, 6);
        };
        
        $grabPosts = function() use ($posts)
        {
            return  $data_posts = $posts -> findPosts(0, 6);
        };
        
        $grabProducts();
        $grabPosts();
        
        $views['products'] = $data_products['results'];
        $views['posts'] = $data_posts['results'];
        $views['totalProducts'] = $data_products['totalProducts'];
        $views['totaPosts'] = $data_posts['totalPosts'];
        
        if (empty($data_products['totalProducts'])) $views['unavailable'] = "Product Unavailable";
        
        if (empty($data_posts['totalPosts'])) $views['unpublished'] = "No post";
        
        require 'home.php';
        
        
    }
}
