<?php if (!defined('APP_KEY')) header("Location: 403.php"); 

require('sidebarNavigation.php'); 

?>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation"
	style="margin-bottom: 0">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse"
			data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
			<span class="icon-bar"></span> <span class="icon-bar"></span>
		</button>
<a class="navbar-brand" href="http://www.kartatopia.com/" 
title="Powering online shop">Kartatopia</a>
	</div>
	<!-- /.navbar-header -->

	<ul class="nav navbar-top-links navbar-right">
	       
<!-- Pesan Masuk -->
<?php if (isset($volunteer_level) && $volunteer_level == 'WebMaster' OR $volunteer_level == 'Administrator') { ?>
	    
	<li class="dropdown">
	         
<a class="dropdown-toggle" data-toggle="dropdown" href="#">
   <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i></a>
                       
  <ul class="dropdown-menu dropdown-messages">
<?php 
if (isset($messages)) {
    
  foreach ($messages as $message) :
      
  $data_content = (strip_tags($message['messages']));
  $content = substr($data_content, 0, 100);
  $content = substr($data_content, 0, strrpos($content, " "));
  
?>     <li>
       <a href="?module=inbox&action=replyMessage&messageId=<?= (int)$message['ID']; ?>">                
           <strong><?php echo htmlspecialchars($message['sender']); ?></strong>
<div>
<span class="pull-right text-muted">
 <em><?= timeAgo($message['time_sent']); ?></em>
</span>
</div>
<div><?= htmlspecialchars($content); ?></div>
                                
                                
                            </a>
                        </li>
                        <li class="divider"></li>
                       
                       <?php endforeach; } ?>
                       
                        <li>
                            <a class="text-center" href="<?php if (isset($totalMessages) && $totalMessages > 0) { print "?module=messages"; }else{ print "#"; } ?>">
                                <strong><?php if ( isset($totalMessages) && $totalMessages == 0) { echo "No message"; }else{ echo "Read messages"; } ?></strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                        
                    </ul>
                    
                    <!-- /.dropdown-messages -->
              
                  </li>
                
                    <?php } // end of if volunteer_level ?> 
                    
           <!-- user -->
		<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"
			href="#"> <i class="fa fa-user fa-fw"></i> 
				<i class="fa fa-caret-down"></i>
		</a>
			<ul class="dropdown-menu dropdown-user">
				<li>
<a href="?module=volunteers&action=editVolunteer&volunteerId=
<?php if (isset($vid)) echo $vid; ?>&sessionId=<?php if (isset($volunteer_token)) echo $volunteer_token; ?>">
				<i class="fa fa-user fa-fw"></i><?php if (isset($volunteer_login)) echo $volunteer_login; ?> </a>
				</li>
				
				<li>
				<a href="<?php if (isset($clean_url_host)) echo $clean_url_host; ?>" target="_blank"><i class="fa fa-home fa-fw"></i> visit site</a>
				</li>
				<li class="divider"></li>
				<li><a href="?module=logout"><i class="fa fa-sign-out fa-fw"></i>
						Logout</a>
				</li>
			</ul> <!-- /.dropdown-user -->
		</li>
		<!-- /.dropdown -->
	</ul>
	<!-- /.navbar-top-links -->
	
	<?php echo sidebarNavigation($volunteer_level, $pageTitle); ?>

</nav>
<!-- /.navbar-static-side -->