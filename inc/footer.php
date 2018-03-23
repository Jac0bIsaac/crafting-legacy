<?php
function setFooter()
{
?>
 
    <footer class="text-center">
      <div class="footer-above">
        <div class="container">
          <div class="row">
            <div class="footer-col col-md-4">
              <h3>Send E-mail to:</h3>
              <p><a href="mailto:hello@kartatopia.com"><i class="fa fa-envelope"></i> hello@kartatopia.com</a></p>
            </div>
            <div class="footer-col col-md-4">
              <h3>Follow us</h3>
              <ul class="list-inline">
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://www.facebook.com/kartatopia/" title="kartatopia on facebook">
                    <i class="fa fa-fw fa-facebook"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://plus.google.com/+kartatopiastudio">
                    <i class="fa fa-fw fa-google-plus"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://twitter.com/kartatopia" title="Kartatopia">
                    <i class="fa fa-fw fa-twitter"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://sourceforge.net/projects/pilus/" title="PilusCart E-commerce Software">
                    <i class="fa fa-fw fa-cloud-download"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://github.com/PiLUS-Cart/Piluscart-1.5" title="free and open source e-commerce software">
                    <i class="fa fa-fw fa-github"></i>
                  </a>
                </li>
              </ul>
            </div>
            <div class="footer-col col-md-4">
              <h3>Support</h3>
              <p>Get support for <a href="https://piluscart.zulipchat.com" title="Piluscart community forum" >Piluscart</a> 
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-below">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              Copyright &copy; 
              
                <?php 
                   
                    $starYear = 2013;
                    $thisYear = date ( "Y" );
                    if ($starYear == $thisYear) {
                        echo $starYear;
                    } else {
                        echo "{$starYear}&#8211; {$thisYear}";
                    }
                 ?>
                 
                 Kartatopia.com
            </div>
          </div>
        </div>
      </div>
    </footer>

    <div class="scroll-top d-lg-none">
      <a class="btn btn-primary js-scroll-trigger" href="#page-top">
        <i class="fa fa-chevron-up"></i>
      </a>
    </div>
     
    <script src="<?php echo APP_PUBLIC; ?>home/vendor/jquery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" integrity="sha384-feJI7QwhOS+hwpX2zkaeJQjeiwlhOP+SdQDqhgvvo1DsjtiSQByFdThsxO669S2D" crossorigin="anonymous"></script>

    <script src="<?php echo APP_PUBLIC; ?>home/vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="<?php echo APP_PUBLIC; ?>home/js/jqBootstrapValidation.js"></script>
    <script src="<?php echo APP_PUBLIC; ?>home/js/form_validate.js"></script>

    <script src="<?php echo APP_PUBLIC; ?>home/js/freelancer.min.js"></script>
   
  </body>

</html>
<?php 
}

function blogFooter()
{
?>
<footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <ul class="list-inline text-center">
              <li class="list-inline-item">
                <a href="https://twitter.com/kartatopia">
                  <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                  </span>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="https://www.facebook.com/kartatopia/">
                  <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                  </span>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="<?php echo APP_DIR.'rss.xml'; ?>" title="RSS Feeds">
                  <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-rss fa-stack-1x fa-inverse"></i>
                  </span>
                </a>
              </li>
            </ul>
            <p class="copyright text-muted">Copyright &copy; 
             <?php 
                   
                $starYear = 2013;
                $thisYear = date ( "Y" );
                    
                if ($starYear == $thisYear) {
                    echo $starYear;
                } else {
                    echo "{$starYear}&#8211; {$thisYear}";
                 }
                 
               ?>
            kartatopia.com</p>
          </div>
        </div>
      </div>
    </footer>

    <script src="<?php echo APP_PUBLIC; ?>blog/vendor/jquery/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" integrity="sha384-feJI7QwhOS+hwpX2zkaeJQjeiwlhOP+SdQDqhgvvo1DsjtiSQByFdThsxO669S2D" crossorigin="anonymous"></script>

    <script src="<?php echo APP_PUBLIC; ?>blog/js/clean-blog.min.js"></script>
    
  </body>

</html>
<?php 
}