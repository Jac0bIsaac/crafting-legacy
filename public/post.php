<?php 
if (isset($views['param'])) grabPost($views['param']);

$post_id = isset($views['post_id']) ? $views['post_id'] : '';
$post_title = isset($views['post_title']) ? $views['post_title'] : "";
$post_image = isset($views['post_image']) ? $views['post_image'] : "";
$post_slug = isset($views['post_slug']) ? $views['post_slug'] : '';
$post_created = isset($views['post_created']) ? $views['post_created'] : "";
$post_author = isset($views['post_author']) ? $views['post_author'] : "";
$post_content = isset($views['post_content']) ? $views['post_content'] : "";
$comment_status = isset($views['comment_status']) ? $views['comment_status'] :"";
$sidebarPost = isset($views['sidebar']) ? $views['sidebar'] : "";


if ($post_id) :

?>    
    <!-- Post Content -->
    <article>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
          <div class="sharethis-inline-share-buttons"></div>
            <p>
            <?php echo $post_content; ?>
            </p>
          </div>

           <div class="col-lg-8 col-md-10 mx-auto">
           <nav class="blog-pagination">
           <?php 
           foreach ($views['linkPrev'] as $previous) :
           ?>
            <a class="btn btn-outline-primary" href="<?php echo APP_DIR.'post'. '/'.(int)$previous['postID'].'/'.$previous['post_slug']; ?>">Baca sebelumnya</a>
           <?php
           endforeach;
           ?>
           <?php 
           foreach ($views['linkNext'] as $next) :
           ?>
            <a class="btn btn-outline-secondary" href="<?php echo APP_DIR.'post'.'/'.(int)$next['postID'].'/'.$next['post_slug'] ;?>">Baca selanjutnya</a>
           <?php 
           endforeach;
           ?>
          </nav>
          <p></p>
           </div>
       
        <?php 
        if ($comment_status === 'open') :
        ?>
     <div class="col-lg-8 col-md-10 mx-auto">
   <div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://kartatopia-studio.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                            
        <?php 
        endif;
        ?>
       </div>
        </div>
      </div>
   
</article>

<hr>
<?php 
endif;
?>