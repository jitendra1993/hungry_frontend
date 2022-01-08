<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> SMS Setting</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">SMS</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php
$currency = CURRENCY;
$uniqid = uniqid();
  
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
  
	$attributes = array('class' => 'sms', 'id' => 'sms'); 
	echo form_open_multipart('admin/sms/',$attributes);?>
	 <input type="hidden" name="type" id="rms" value="rms">
	<div class="form-group row">

		<label class="col-sm-12 col-md-3 col-form-label">Disabled SMS</label>
		<div class="col-sm-12 col-md-9 row">
			<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="disabled" name="disabled"  value="1"  <?php echo (isset($sms_info->disable) && $sms_info->disable==1)?'checked':''; ?>>
				<label class="custom-control-label" for="disabled"></label>
			</div>
			</div>
			
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">SMS URL</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="sms_url" id="sms_url"  class="form-control" placeholder="SMS URL" value="<?php echo isset($sms_info->sms_url)?set_value('sms_url',$sms_info->sms_url):set_value('sms_url'); ?>" required>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">SMS Id</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="sms_id" id="sms_id"  class="form-control" placeholder="SMS Id" value="<?php echo isset($sms_info->sms_id)?set_value('sms_id',$sms_info->sms_id):set_value('sms_id'); ?>" required>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">SMS Password</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="sms_password" id="sms_password"  class="form-control" placeholder="SMS Password" value="<?php echo isset($sms_info->sms_password)?set_value('sms_password',$sms_info->sms_password):set_value('sms_password'); ?>" required>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">SMS Sender</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="sms_sender" id="sms_sender"  class="form-control" placeholder="SMS Sender" value="<?php echo isset($sms_info->sms_sender)?set_value('sms_sender',$sms_info->sms_sender):set_value('sms_sender'); ?>" required>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">SMS Template</label>
		<div class="col-sm-12 col-md-9">
		<input type="text" name="sms_template" id="sms_template"  class="form-control" placeholder="SMS Template" value="<?php echo isset($sms_info->sms_template)?set_value('sms_template',$sms_info->sms_template):set_value('sms_template'); ?>" required>
		</div>
	</div>

	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm btn-block disable">SUBMIT</button>
			</div>
		</div>
	</div>
	</form>
	
</div>
