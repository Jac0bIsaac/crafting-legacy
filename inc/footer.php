<?php
function setFooter()
{
?>
 <!-- Footer -->
    <footer class="text-center">
      <div class="footer-above">
        <div class="container">
          <div class="row">
            <div class="footer-col col-md-4">
              <h3>Location</h3>
              <p>Malang
                <br>East Java, Indonesia 65132</p>
            </div>
            <div class="footer-col col-md-4">
              <h3>Follow us</h3>
              <ul class="list-inline">
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://www.facebook.com/getpiluscart/">
                    <i class="fa fa-fw fa-facebook"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://plus.google.com/u/0/112507269401413909774">
                    <i class="fa fa-fw fa-google-plus"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://twitter.com/PilusCart" title="PilusCart on Twitter">
                    <i class="fa fa-fw fa-twitter"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://sourceforge.net/projects/pilus/" title="PilusCart E-commerce Software">
                    <i class="fa fa-fw fa-cloud-download"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a class="btn-social btn-outline" href="https://github.com/PiLUS-Cart/PiLUS" title="free and open source e-commerce software">
                    <i class="fa fa-fw fa-github"></i>
                  </a>
                </li>
              </ul>
            </div>
            <div class="footer-col col-md-4">
              <h3>About Kartatopia</h3>
              <p>Kartatopia is a micro ISV, an independent software vendor specializing in
                <a href="http://www.kartatopia.com" title="Powering Online Shop">open source innovation</a>.</p>
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

 
    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top d-lg-none">
      <a class="btn btn-primary js-scroll-trigger" href="#page-top">
        <i class="fa fa-chevron-up"></i>
      </a>
    </div>
    
    <?php include dirname(__FILE__) . ' /../public/modal.php'; ?>
    
    
    <!-- Bootstrap core JavaScript -->
    <script src="public/home/vendor/jquery/jquery.min.js"></script>
    <script src="public/home/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="public/home/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="public/home/js/jqBootstrapValidation.js"></script>
    <script src="public/home/js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="public/home/js/freelancer.min.js"></script>

  </body>

</html>
<?php 
}
?>