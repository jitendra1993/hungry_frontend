<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Mail Settting</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Mail Settting</li>
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
			<?php echo form_error('admin_received_mail', '<span>', '</span><br>'); ?>
			<?php echo form_error('admin_received_name', '<span>', '</span><br>'); ?>
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
		  
	$attributes = array('class' => 'mail_setting', 'id' => 'mail_setting'); 
	echo form_open_multipart(isset($mail_setting->user_hash_id)?'admin/setting/mail-setting/':'admin/setting/mail-setting',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($mail_setting->user_hash_id)?set_value('name',$mail_setting->user_hash_id):set_value('id'); ?>">
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">From Email</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mail_from_email" id="mail_from_email"  class="form-control" placeholder="Mail From Email" value="<?php echo isset($mail_setting->mail_from_email)?set_value('mail_from_email',$mail_setting->mail_from_email):set_value('mail_from_email'); ?>">
				<div class="has-danger form-control-feedback err_mail_from_email"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">From Name</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mail_from_name" id="mail_from_name"  class="form-control" placeholder="Mail From Name" value="<?php echo isset($mail_setting->mail_from_name)?set_value('mail_from_name',$mail_setting->mail_from_name):set_value('mail_from_name'); ?>">
				<div class="has-danger form-control-feedback err_mail_from_name"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Host</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mail_host" id="mail_host"  class="form-control" placeholder="Mail Host" value="<?php echo isset($mail_setting->mail_host)?set_value('mail_host',$mail_setting->mail_host):set_value('mail_host'); ?>">
				<div class="has-danger form-control-feedback err_mail_host"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Port</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mail_port" id="mail_port"  class="form-control" placeholder="Mail Port" value="<?php echo isset($mail_setting->mail_port)?set_value('mail_port',$mail_setting->mail_port):set_value('mail_port'); ?>" onkeypress="return isNumber(event)">
				<div class="has-danger form-control-feedback err_mail_port"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">User Name(Email)</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mail_username" id="mail_username"  class="form-control" placeholder="Mail User Name" value="<?php echo isset($mail_setting->mail_username)?set_value('mail_username',$mail_setting->mail_username):set_value('mail_username'); ?>">
				<div class="has-danger form-control-feedback err_mail_username"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Mail Password</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mail_password" id="mail_password"  class="form-control" placeholder="Mail Password" value="<?php echo isset($mail_setting->mail_password)?set_value('mail_password',$mail_setting->mail_password):set_value('mail_password'); ?>">
				<div class="has-danger form-control-feedback err_mail_password"></div>
		</div>
	</div>
		
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Admin Received Email</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="admin_received_mail" id="admin_received_mail"  class="form-control" placeholder="Mail From Email" value="<?php echo isset($mail_setting->admin_received_mail)?set_value('admin_received_mail',$mail_setting->admin_received_mail):set_value('admin_received_mail'); ?>">
				<div class="has-danger form-control-feedback err_admin_received_mail"></div>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Admin Received Name</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="admin_received_name" id="admin_received_name"  class="form-control" placeholder="Mail From Name" value="<?php echo isset($mail_setting->admin_received_name)?set_value('admin_received_name',$mail_setting->admin_received_name):set_value('admin_received_name'); ?>">
				<div class="has-danger form-control-feedback err_admin_received_name"></div>
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

