<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Admin Profile</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Admin Profile</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php
	   if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
		  <?php echo validation_errors(); ?>
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
		$attributes = array('class' => 'admin_profile', 'id' => 'admin_profile'); 
		  echo form_open('admin/profile',$attributes);?>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Name</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="name" id="name"  class="form-control" placeholder="Name" value="<?php echo isset($admin->name)?set_value('name',$admin->name):set_value('name'); ?>">
					<div class="has-danger form-control-feedback err_name"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Email</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="email" id="email"  class="form-control" placeholder="Email" value="<?php echo isset($admin->email)?set_value('email',$admin->email):set_value('email'); ?>">
					<div class="has-danger form-control-feedback err_email"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Mobile</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="mobile" id="mobile"  onkeypress="return isNumber(event)" class="form-control" placeholder="Mobile" value="<?php echo isset($admin->mobile)?set_value('mobile',$admin->mobile):set_value('mobile'); ?>">
					<div class="has-danger form-control-feedback err_mobile"></div>
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

