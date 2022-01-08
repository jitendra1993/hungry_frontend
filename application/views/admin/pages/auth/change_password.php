<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Change Password</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Change Password</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php
	   if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
		  <?php //echo validation_errors(); ?>
		  <?php echo form_error('current_password', '<span>', '</span>'); 
				echo form_error('new_password', '<span>', '</span>');
				echo form_error('c_new_password', '<span>', '</span>'); 
		?>
		</div>
	  <?php }
	  if($this->session->flashdata('msg_success')){ ?>
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
	  }
		$attributes = array('class' => 'change-password', 'id' => 'change-password'); 
		  echo form_open('admin/auth/change-password',$attributes);?>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Current Password</label>
				<div class="col-sm-12 col-md-9">
					<input type="password" name="current_password" id="current_password"  class="form-control" placeholder="Current Password" value="">
					<div class="has-danger form-control-feedback err_current_password"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">New Password</label>
				<div class="col-sm-12 col-md-9">
					<input type="password" name="new_password" id="new_password"  class="form-control" placeholder="New Password" value="">
					<div class="has-danger form-control-feedback err_new_password"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Confirm New Password</label>
				<div class="col-sm-12 col-md-9">
					<input type="password" name="c_new_password" id="c_new_password"  class="form-control" placeholder="Confirm Password" value="">
					<div class="has-danger form-control-feedback err_c_new_password"></div>
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

