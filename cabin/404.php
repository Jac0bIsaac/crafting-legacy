<?php 

if (!defined('APP_KEY')) {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); 
    header("Location: 403.php"); 
    exit();
}
?>


<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">404 - Page Not Found!</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">function leave() {  window.location = "index.php?module=dashboard";} setTimeout("leave()", 5000);</script>
