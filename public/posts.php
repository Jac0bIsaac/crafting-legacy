<!-- Main Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
<?php 
if (isset($views['errorMessage'])) :
?>
<div class="alert alert-danger alert-dismissable">			
<h3>ERROR !</h3>	
<p><strong><?= $views['errorMessage']; ?></strong></p>
</div>
<?php 
else : 
 foreach (array_values($views['allPosts']) as $allPost => $p) :
?>

          <div class="post-preview">
            <a href="<?= APP_DIR . 'post/'.(int)$p['postID'].'/'.htmlspecialchars($p['post_slug']); ?>" title="<?= $p['post_title']; ?>">
              <h2 class="post-title">
                <?php 
                echo htmlspecialchars($p['post_title']);
                ?>
              </h2>
<?php 
$article = strip_tags($p['post_content']);
$paragraph_article = substr($article, 0, 240);
$paragraph_article = substr($article, 0, strrpos($paragraph_article, " "));
?>
            <h3 class="post-subtitle">
            <?= html_entity_decode($paragraph_article); ?>       
             </h3>
            </a>
            <p class="post-meta"><i class="fa fa-user"></i>
              <a href="#"><?= htmlspecialchars($p['volunteer_login']); ?></a>
              <i class="fa fa-calendar"></i>
             <?= makeDate($p['date_created'], 'id'); ?>
             </p>
          </div>
          <hr>
<?php 
endforeach; endif;
?>
          <!-- Pager -->
          <div class="clearfix">
            <?=(isset($views['totalPostPublished']) && $views['totalPostPublished'] > 0) ? $views['pageLink'] : ""; ?> 
          </div>
        </div>
      </div>
    </div>

    <hr>