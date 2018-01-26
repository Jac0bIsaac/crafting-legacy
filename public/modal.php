<?php 
if (isset($totalProducts) && $totalProducts > 0) :

$no = 0;
foreach ($views['products'] as $p) :
$no++;
?>   
   
 <!-- Portfolio Modals -->
    <div class="portfolio-modal modal fade" id="<?php echo '#portfolioModal'.$no; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <h2><?php echo $p['product_name']; ?></h2>
                  <hr class="star-primary">
                  <?php 
                  if ($p['product_image'] != '') :
                  ?>
                  <img class="img-fluid img-centered" src="public/home/img/portfolio/cabin.png" alt="">
                  <?php 
                  endif; 
                  ?>
                  <p><?php echo html_entity_decode($p['product_description']); ?></p>
                  <ul class="list-inline item-details">
                    <li>Version:
                      <strong>
                        <a href="#"><?php echo htmlspecialchars($p['product_version']) ?></a>
                      </strong>
                    </li>
                    <li>Published:
                      <strong>
                        <a href="#"><?php echo htmlspecialchars(makeDate($p['date_published'])); ?></a>
                      </strong>
                    </li>
                    <li>Features:
                      <strong>
                        <a href="#"><?php echo htmlspecialchars($p['product_module']); ?></a>
                      </strong>
                    </li>
                  </ul>
                  <button class="btn btn-success" type="button" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                    Close
                   </button>
                  <?php if ($p['product_link'] != '') : ?>
                    <a class="btn btn-success" href="<?php  echo autolink($p['product_link']);  ?>"  target="__blank" title="" >
                    <i class="fa fa-download"></i>
                       Download
                    </a>
                    <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php 
endforeach; endif;
?>