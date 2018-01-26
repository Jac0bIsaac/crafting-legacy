<?php
if (!defined('APP_KEY')) header("Location: ../../../cabin/403.php");
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?php 
	if (isset($views['errorMessage'])) { ?>

	<div class="alert alert-danger ">
		<h4>Error!</h4>
		<p>
			<?php echo $views['errorMessage']; ?>
			<button type="button" class="btn btn-danger"
				onClick="self.history.back();">Repeat</button>
		</p>
	</div>
	

	<?php } else { ?>
	
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php if (isset($views['pageTitle'])) echo $views['pageTitle']; ?>
				</div>
				<!-- .panel-heading -->

				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							
	<form name="option" onSubmit="return cekForm(this)" method="post"
								action="index.php?module=configurations&action=<?php echo $views['formAction']; ?>&configId=<?php if (isset($views['config_id'])) { echo (int)$views['config_id']; } else { echo "0"; }; ?>"
								role="form" enctype="multipart/form-data" autocomplete="off">
					<input type="hidden" name="config_id"
				value="<?php if (isset($views['config_id'])) echo $views['config_id']; ?>" />

								<!-- site name -->
								<div class="form-group">
									<label>*sitename</label> <input type="text"
										name="site_name" class="form-control"
										placeholder="sitename"
										value="<?php if (isset($views['site_name'])) echo htmlspecialchars($views['site_name']); ?>"
										required>
								</div>

								<!-- tagline -->
								<div class="form-group">
									<label>*Tagline</label> <input type="text" name="tagline"
										class="form-control" placeholder="tagline or slogan"
										value="<?php if (isset($views['tagline'])) echo htmlspecialchars($views['tagline']); ?>"
										required>
								</div>

								<!-- address -->
								<div class="form-group">
									<label>*Address</label> <input type="text"
										name="address" class="form-control" placeholder="address"
										value="<?php if (isset($views['address'])) echo htmlspecialchars($views['address']); ?>"
										required>
								</div>

								<!-- owner e-mail -->
								<div class="form-group">
									<label>*E-mail </label> <input type="text" name="email"
										class="form-control" placeholder="E-mail address"
										value="<?php if (isset($views['email'])) echo htmlspecialchars($views['email']); ?>"
										required>
								</div>

								<!-- Phone-->
								<div class="form-group">
									<label>*Phone</label> <input type="text" name="phone"
										class="form-control" placeholder="phone number"
										value="<?php if (isset($views['phone'])) echo htmlspecialchars($views['phone']); ?>"
										required>
								</div>
								
					
                                <!--fax number-->
								<div class="form-group">
									<label>Faximile</label> 
									<input type="text" name="fax" class="form-control" placeholder="fax number"
										value="<?php if (isset($views['fax'])) echo htmlspecialchars($views['fax']); ?>">
								</div>
								
								<!-- Instagram -->
								<div class="form-group">
									<label>Instagram</label> <input type="text" name="instagram"
										class="form-control" placeholder="instagram"
										value="<?php if (isset($views['instagram'])) echo htmlspecialchars($views['instagram']); ?>">
								</div>
								
								<!-- Twitter -->
								<div class="form-group">
									<label>Twitter</label> <input type="text" name="twitter"
										class="form-control" placeholder="twitter"
										value="<?php if (isset($views['twitter'])) echo htmlspecialchars($views['twitter']); ?>">
								</div>
								
								<!-- Facebook -->
								<div class="form-group">
									<label>Facebook</label> <input type="text" name="facebook"
										class="form-control" placeholder="facebook"
										value="<?php if (isset($views['facebook'])) echo htmlspecialchars($views['facebook']); ?>">
								</div>
								
								<!-- Meta Description -->
								<div class="form-group">
									<label>Meta Description</label> <input type="text"
										name="description" class="form-control" maxlength="255"
										value="<?php if (isset($views['meta_desc'])) echo htmlspecialchars($views['meta_desc']); ?>">
								</div>

								<!-- Meta Keyword -->
								<div class="form-group">
									<label>Meta Keyword</label> 
									<input type="text" name="keywords"
										class="form-control" maxlength="255"
										value="<?php if (isset($views['meta_key'])) echo htmlspecialchars($views['meta_key']); ?>">
								</div>

								<!-- Favicon -->
								<?php if (!empty($views['logo'])) { ?>
								<div class="form-group">
									<label>Logo</label>
									<?php 
									$image = '../files/picture/' . $views['logo'];

									if (!is_file($image)) :

									$image = '../content/uploads/images/thumbs/nophoto.jpg';

									endif;

									if (is_file($image)) :

									?>
									<br> <img src="<?php  echo $image; ?>"> <br> <label>change 
										logo</label> <input type="file" name="image"
										accept="image/png" />

									<?php else : ?>

									<br> <img src="<?php echo $image; ?>"> <br> <label>change logo</label> <input type="file" name="image"
										accept="image/png" />

									<?php endif; ?>
								</div>
								<?php } else { ?>

								<div class="form-group">
									<label>Logo</label> <input type="file" name="image"
										accept="image/png">
								</div>
								<?php } ?>
								<input type="submit" class="btn btn-primary" name="submit"
									value="Save" />

								<button type="button" class="btn btn-danger"
									onClick="self.history.back();">Cancel</button>

							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<?php } ?>
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
//JavaScript Document
function cekForm(form){
	
if (option.site_name.value == ""){
alert("You have not filled in site name");
option.site_name.focus();
return false;
}

if (option.tagline.value == ""){
alert("You have not filled in tagline");
option.tagline.focus();
return false;
}

if (option.address.value == ""){
alert("You have not filled in tagline");
option.address.focus();
return false;
}

if (option.email.value == ""){
alert("You have not filled in email");
option.email.focus();
return false;
}

if (option.phone.value == ""){
alert("You have not filled in phone");
option.phone.focus();
return false;
}

return true;

}</script>
