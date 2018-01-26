<?php if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php"); ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle']))  echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	
	<?php 
	if (isset($views['errorMessage'])) : ?>
	
		<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">&times;</button>
		<?php echo $views['errorMessage']; ?>
	    </div>
	
	<?php endif; ?>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">

<form method="post" action="index.php?module=events&action=<?php echo $views['formAction']; ?>&eventId=<?php if (isset($views['event_id'])) { echo (int)$views['event_id']; }
  else { echo (int)'0'; } ?>" role="form" enctype="multipart/form-data">
								
<input type="hidden" name="event_id" value="<?php if (isset($views['event_id'])) echo (int)$views['event_id']; ?>" />
<input type="hidden" name="sender_id" value="<?php if (isset($views['sender_id'])) echo (int)$views['sender_id']; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />
								
								<!-- Event -->
								<div class="form-group">
									<label>Event*</label> 
					<input type="text" name="title" class="form-control" placeholder="event's title"
										value="<?php if (isset($views['name'])) echo htmlspecialchars($views['name']); ?>" required>
								</div>
								
								<!-- Location -->
								<div class="form-group">
									<label>Location*</label> 
<input type="text" name="location" class="form-control" placeholder="location" 
value="<?php if (isset($views['location'])) echo htmlspecialchars($views['location']); ?>"
										required>
								</div>
								
								<!-- Time started -->
								<div class="form-group">
									<label>Time started*</label> 
<div class="input-group date" >
<input type="text" id="timepicker1" name="time_started" class="form-control" placeholder="Time Start" 
										value="<?php if (isset($views['time_started'])) echo $views['time_started']; ?>"
										required>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
		</div>
		
						<!-- Time ended -->
								<div class="form-group">
									<label>Time Ended</label> 
<div class="input-group date" >
<input type="text" id="timepicker2" name="time_ended" class="form-control" placeholder="Time End" 
										value="<?php if (isset($views['time_ended'])) echo $views['time_ended']; ?>"
										required>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                </div>
		</div>
		
		
		
					<!-- Date Started -->
								<div class="form-group">
									<label>Date Started*</label> 
							<div class="input-group date">
<input type="text" id="datepicker1" name="date_started" class="form-control" placeholder="Date Start" id="tgl1"
										value="<?php if (isset($views['start_date'])) echo htmlspecialchars($views['start_date']); ?>"
										required>

<span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    </span>
							</div>
							</div>
							
										<!-- Date Ended -->
								<div class="form-group">
									<label>Date Ended</label> 
							<div class="input-group date">
<input type="text" id="datepicker2" name="date_ended" class="form-control" placeholder="Date End" id="tgl2"
										value="<?php if (isset($views['end_date'])) echo htmlspecialchars($views['end_date']); ?>">

<span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
							</div>
							</div>
							
<!-- event image -->
<?php if (isset($views['event_image'])) : ?>

<div class="form-group">
<?php 
$image = '../files/picture/photo/'. $views['event_image'];

$imageThumb = '../files/picture/photo/thumb/thumb_' . $views['event_image'];

if (!is_readable($imageThumb) && !file_exists($imageThumb)) :

$imageThumb = '../files/picture/photo/thumb/nophoto.jpg';

endif;

if (is_readable($image)) :

?>

<br><a href="<?php echo $image; ?>"><img src="<?php  echo $imageThumb; ?>"></a><br> 
<label>change picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)" maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
<?php else : ?>
<br><img src="<?php echo $imageThumb; ?>"><br> 
<label>change picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
<?php endif; ?>
</div>
<?php else : ?>
<div class="form-group">
<label>Upload Picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= formatSizeUnits(697856); ?></p>
<div id="NotOk"></div>
</div>
<?php endif; ?>

<!--Description -->
								<div class="form-group">
									<label>*Description</label>
<textarea class="form-control" rows="3" id="sc" name="description" maxlength="500" required>
										<?php if (isset($views['description'])) echo $views['description']; ?>
									</textarea>
								</div>
                             
								
								<input type="submit" class="btn btn-primary" name="submit"
									value="Save" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Cancel</button>

							</form>
						</div>
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-6"></div>
						<!-- /.col-lg-6 (nested) -->
					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- #Page-Wrapper -->
<script type="text/javascript">
  var loadFile = function(event) {
	    var output = document.getElementById('output');
	    output.src = URL.createObjectURL(event.target.files[0]);
	  };
</script>