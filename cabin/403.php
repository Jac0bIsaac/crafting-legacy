<?php 
require("../inc/init.php"); 

if (!$authentication -> isVolunteerLoggedIn()) { header("Location: ../cabin/login.php");}
?>
<html>

<title>403 - South Carolina Exchange Student</title>

<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	background-color: #283e78;
	font-size: 12px;
	line-height: 22px;
	color: #5f5f5f;
	min-height: 100%;
	position: relative;
}

.errorWrapper {
	top: 50%;
	bottom: 0;
	margin-top: 100px;
	text-align: center;
	left: 0;
	right: 0;
}

.errorContent {
	width: 380px;
	margin: auto;
	margin-top: 35px;
}

.errorWrapper .errorTitle {
	display: block;
	text-shadow: 1px 0 0 #fff;
	text-align: center;
	font-size: 20px;
	border-bottom: 1px solid #cdcdcd;
	padding: 20px 14px;
	font-weight: bold;
	color: #d76a6a;
	font-style: italic;
}

.errorWrapper .errorNum {
	color: #fff;
	font-size: 200px;
	text-stroke: 1px transparent;
	padding: 110px 0 80px 0;
	display: block;
	text-shadow: 0 1px 0 #ccc, 0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0
		#b9b9b9, 0 5px 0 #aaa, 0 6px 1px rgba(0, 0, 0, .1), 0 0 5px
		rgba(0, 0, 0, .1), 0 1px 3px rgba(0, 0, 0, .3), 0 3px 5px
		rgba(0, 0, 0, .2), 0 5px 10px rgba(0, 0, 0, .25), 0 10px 10px
		rgba(0, 0, 0, .2), 0 20px 20px rgba(0, 0, 0, .15);
}

.errorDesc {
	display: block;
	margin: 10px 0 10px 0;
	font-weight: bold;
	font-size: 14px;
	color: #ffffff
}

.errorDesc .purple {
	color: #b2bcd2;
}
</style>

<body>

	<div class="errorWrapper">

		<span class="errorNum">403</span>

		<div class="errorContent">

			<span class="errorDesc">Oops! Sorry, an error has occured. Forbidden!<br />&copy;
				2017 <span class="purple"> sccexchangestudent.org</span>
			</span>

		</div>

	</div>
	<script type="text/javascript">function leave() {  window.location = "index.php?module=dashboard";} setTimeout("leave()", 3640);</script>
</body>

</html>