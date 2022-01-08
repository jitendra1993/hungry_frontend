<?php require_once(APPPATH.'views/admin/includes/admin_metatag.php'); ?>

<body>
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="login.html">
					<img src="<?=base_url()?>assets/admin/vendors/images/deskapp-logo.svg" alt="">
				</a>
			</div>
			<div class="login-menu">
				<ul>
					<li><a href="<?=base_url('admin')?>">Login</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<img src="<?=base_url()?>assets/admin/vendors/images/forgot-password.png" alt="">
				</div>
				<div class="col-md-6">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Forgot Password</h2>
						</div>
						<?php if(validation_errors()){ ?>
						<ul class="list-group">
						<?php echo form_error('username', '<li class="list-group-item list-group-item-danger">', '</li>'); ?>
						</ul>
						<br>
					  <?php } ?>

					  <?php if($this->session->flashdata('msg_success')){ ?>
					  <div class="alert alert-success alert-dismissible"> 
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?=($this->session->flashdata('msg_success'))?> 
					  </div>
					  <?php }
					  if($this->session->flashdata('error_msg')){ ?>
					  <div class="alert alert-danger alert-dismissible"> 
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?=($this->session->flashdata('error_msg'));?> 
					  </div> 
					  <?php
					  }?>
					  
						<h6 class="mb-20">Enter your email address to reset your password</h6>
						<?php $attributes = array('id' => 'forgot_password_admin'); 
							echo form_open('admin/auth/forgot-password',$attributes);
							?>
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" name="username" id="username"  placeholder="Email" value="<?php echo set_value('username');?>">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
								</div>
								
							</div>
							<span class=" errContactEmail has-danger form-control-feedback" style="margin-top: -27px !important;float:left;"></span>
							<div class="row align-items-center mt-5">
								<div class="col-5">
									<div class="input-group mb-0">
										<input class="btn btn-primary btn-lg btn-block disable" type="submit" value="Submit">
									</div>
								</div>
								<div class="col-2">
									<div class="font-16 weight-600 text-center" data-color="#707373" style="color: rgb(112, 115, 115);">OR</div>
								</div>
								<div class="col-5">
									<div class="input-group mb-0">
										<a class="btn btn-outline-primary btn-lg btn-block" href="<?=base_url('admin')?>">Login</a>
									</div>
								</div>
							</div>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php  include_once(APPPATH.'views/admin/includes/admin_footer.php'); ?>
