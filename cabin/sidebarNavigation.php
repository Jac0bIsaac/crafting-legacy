<?php

function sidebarNavigation($level, $pageTitle = '') {
?>

<!-- Sidebar -->
	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<!-- Dashboard -->
				<li><a href="?module=dashboard"> <i class="fa fa-dashboard fa-fw"></i>
						Dashboard
				</a>
				</li>

<?php if ($level == 'Administrator' || $level == 'Editor' || $level == 'Author' || $level == 'Contributor' || $level == 'WebMaster') :?>
				<!-- Posts -->
				<li <?php if ($pageTitle == 'posts' || $pageTitle == 'categories') echo 'class="active"'; ?>> 
				<a href="#"><i class="fa fa-pencil fa-fw"></i> Posts<span class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=posts"> All Posts</a>
						</li>
						<li><a href="?module=posts&action=newPost&postId=0"> New Post</a>
						</li>
						<?php if ($level == 'Administrator' || $level == 'Editor' || $level == 'WebMaster') { ?>
						<li><a href="?module=categories"> Categories</a>
						</li>
						<?php } ?>
						
						
					</ul> <!-- /.nav-second-level -->
				</li>
			<?php  endif; ?>

				<!-- Media -->
<?php if ($level == 'Administrator' || $level == 'Editor' || $level == 'Author' || $level == 'WebMaster' ) : ?>
				<li <?php if ($pageTitle == 'albums' || $pageTitle == 'photos' || $pageTitle == 'files') echo 'class="active"'; ?>><a href="#"><i class="fa fa-image fa-fw"></i> Media<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=albums"> Albums</a></li>
						<li><a href="?module=photos"> Photos</a></li>
						<?php if ($level == 'Administrator' || $level == 'WebMaster') { ?>
						<li><a href="?module=files"> Files</a></li>
						<?php } ?>
					</ul>
				</li>

 <?php  endif; ?>
 
				<!-- Pages -->
<?php if ($level == 'Administrator' || $level == 'WebMaster') : ?>
				<li <?php if ($pageTitle == 'pages') echo 'class="active"'; ?>><a
					href="#"><i class="fa fa-file fa-fw"></i> Pages<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=pages&action=listPages"> All Pages</a>
						</li>
						<li><a href="?module=pages&action=newPage"> Add New</a>
						</li>

					</ul> <!-- /.nav-second-level -->
				</li>
<?php 
endif; 
?>

<?php 
if ($level == 'Administrator' || $level == 'WebMaster') :
?>
<!-- Product -->

<li <?php if ($pageTitle == 'products' || $pageTitle == 'flavours') echo 'class="active"'; ?>> 
				<a href="#"><i class="fa fa-briefcase fa-fw"></i> Products<span class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=products"> All Products</a>
						</li>
						<li><a href="?module=products&action=newProduct&productId=0"> New Product</a>
						</li>
						<?php if ($level == 'Administrator' || $level == 'WebMaster') { ?>
						<li><a href="?module=flavours"> Flavours</a>
						</li>
						<?php } ?>
			</ul> <!-- /.nav-second-level -->
	</li>
	
<?php 
endif;
?>		
		
<?php if ($level == 'Administrator' || $level == 'WebMaster') : ?>
			<!-- Event-->
				<li <?php if ($pageTitle == 'events') echo 'class="active"'; ?>><a href="#"><i class="fa fa-calendar fa-fw"></i> Events<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						<li><a href="?module=events"> All Events</a></li>
						<li><a href="?module=events&action=newEvent&eventId=0"> Add New</a></li>
					</ul>
				</li>
<?php endif; ?>
		
		
<?php if ($level == 'WebMaster') : ?>		
				<!-- Menus -->
	<li><a href="?module=menus"><i class="fa fa-navicon fa-fw"></i>Menus</a></li>
<?php endif; ?>
				
                 <!-- Volunteer -->
<?php if ($level == 'Administrator' || $level == 'WebMaster' || $level == 'Editor' || $level == 'Author' || $level == 'Contributor' ) : ?>

				<li <?php if ($pageTitle == 'volunteers') echo 'class="active"'; ?>><a href="#"><i class="fa fa-users fa-fw"></i> Volunteers<span
						class="fa arrow"></span> </a>
					<ul class="nav nav-second-level">
						
<?php if ($level != 'Administrator' && $level != 'WebMaster') { ?>
						
					<li><a href="?module=volunteers"> My Profile</a></li>
<?php } else {  ?>

<li><a href="?module=volunteers"> All Volunteers</a></li>

<?php } ?>

<?php if ($level == 'Administrator' || $level == 'WebMaster') { ?>
						<li><a href="?module=volunteers&action=newVolunteer&volunteerId=0"> Add New</a></li>
<?php } ?>
					</ul> <!-- /.nav-second-level -->
				</li>

<?php endif; ?>

<!-- Settings -->
<?php if ($level == 'WebMaster') : ?>
<li><a href="?module=configurations"><i class="fa fa-wrench fa-fw"></i>Settings</a></li></ul>
<?php endif; ?>

		</div>
		<!-- /.sidebar-collapse -->
		
		</div>
		
<?php } ?>