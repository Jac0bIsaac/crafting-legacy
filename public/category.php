<?php 
if (isset($views['param'])) grabCategory($views['param']);

$categoryPost = isset($views['catPosts']) ? $views['catPosts'] : "";

if ($categoryPost) :

?>
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
endif;
 
foreach ($categoryPost as $catPost => $c) :
?>

<div class="post-preview">
<a href="<?= APP_DIR . 'post/'.(int)$c['postID'].'/'.htmlspecialchars($c['post_slug']); ?>" title="<?= $c['post_title']; ?>">
              <h2 class="post-title">
                <?php 
                echo htmlspecialchars($c['post_title']);
                ?>
              </h2>
<?php 
$article = strip_tags($c['post_content']);
$paragraph_article = substr($article, 0, 240);
$paragraph_article = substr($article, 0, strrpos($paragraph_article, " "));
?>
            <h3 class="post-subtitle">
            <?= html_entity_decode($paragraph_article); ?>       
             </h3>
            </a>
            <p class="post-meta"><i class="fa fa-user"></i>
              <a href="#"><?= htmlspecialchars($c['volunteer_login']); ?></a>
              <i class="fa fa-calendar"></i>
             <?= makeDate($c['date_created'], 'id'); ?>
             <i class="fa fa-folder"></i>
             category:
             <?= $linkPostCat = $post_cats ->setLinkCategories($c['postID']); ?>
             </p>
          </div>
          <hr>
<?php 
endforeach; endif;
?>
          <!-- Pager -->
          <div class="clearfix">
          
          </div>
        </div>
      </div>
    </div>

    <hr>