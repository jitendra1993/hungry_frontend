<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Social Media Settting</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Social Media Settting</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php 
	if(isset($error) && !empty($error)){ ?>
		<div class="alert alert-danger" role="alert">
			 <?php echo $error; ?>
		</div>
		<?php 
	}

	if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<?php //echo validation_errors(); ?>
			<?php echo form_error('mail_from_email', '<span>', '</span><br>'); ?>
			<?php echo form_error('mail_from_name', '<span>', '</span><br>'); ?>
			<?php echo form_error('mail_host', '<span>', '</span><br>'); ?>
			<?php echo form_error('mail_port', '<span>', '</span><br>'); ?>
			<?php echo form_error('mail_username', '<span>', '</span><br>'); ?>
			<?php echo form_error('mail_password', '<span>', '</span><br>'); ?>
		</div>
		<?php 
	}
	if($this->session->flashdata('msg_success')){ ?>
		<div class="alert alert-success alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('msg_success'))?> 
		</div>
		<?php 
	} 
		  
	$attributes = array('class' => 'sm_setting', 'id' => 'sm_setting'); 
	echo form_open_multipart(isset($sm_setting->user_hash_id)?'admin/setting/sm-setting/':'admin/setting/sm-setting',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($sm_setting->user_hash_id)?set_value('name',$sm_setting->user_hash_id):set_value('id'); ?>">
	
			
	<div class="title"><h5 class="text-blue h5 mb-20"> Facebook setting</h5></div>
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Enabled Facebook Login</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="enable_fb_sm" name="enable_fb_sm" value="1" <?php echo (isset($sm_setting->enable_fb_sm) && $sm_setting->enable_fb_sm==1)?'checked':''; ?>>
				<label class="custom-control-label" for="enable_fb_sm"></label>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Facebook App Id</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="facebook_app_id" id="facebook_app_id"  class="form-control" placeholder="Facebook App Id" value="<?php echo isset($sm_setting->facebook_app_id)?set_value('facebook_app_id',$sm_setting->facebook_app_id):set_value('facebook_app_id'); ?>">
				<div class="has-danger form-control-feedback err_facebook_app_id"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Facebook App Secret</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="facebook_app_secret" id="facebook_app_secret"  class="form-control" placeholder="Facebook App Secret" value="<?php echo isset($sm_setting->facebook_app_secret)?set_value('facebook_app_secret',$sm_setting->facebook_app_secret):set_value('facebook_app_secret'); ?>">
				<div class="has-danger form-control-feedback err_facebook_app_secret"></div>
		</div>
	</div>
	
	<div class="title"><h5 class="text-blue h5 mb-20">Google Setting</h5></div>
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Enabled Google Login</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="enable_google_sm" name="enable_google_sm" value="1" <?php echo (isset($sm_setting->enable_google_sm) && $sm_setting->enable_google_sm==1)?'checked':''; ?>>
				<label class="custom-control-label" for="enable_google_sm"></label>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Google Client Id</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="google_client_id" id="google_client_id"  class="form-control" placeholder="Google Client Id" value="<?php echo isset($sm_setting->google_client_id)?set_value('google_client_id',$sm_setting->google_client_id):set_value('google_client_id'); ?>">
				<div class="has-danger form-control-feedback err_google_client_id"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Google Client Secret</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="google_client_secret" id="google_client_secret"  class="form-control" placeholder="Google Client Secret" value="<?php echo isset($sm_setting->google_client_secret)?set_value('google_client_secret',$sm_setting->google_client_secret):set_value('google_client_secret'); ?>">
				<div class="has-danger form-control-feedback err_google_client_secret"></div>
		</div>
	</div>
	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm btn-block">SUBMIT</button>
			</div>
		</div>
	</div>
		
	<?php echo form_close(); ?>
	
</div>

