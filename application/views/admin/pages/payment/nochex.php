<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Nochex Setting</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Nochex</li>
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
  
	$attributes = array('class' => 'nochex', 'id' => 'nochex'); 
	echo form_open_multipart('admin/payment/nochex/',$attributes);?>
	 <input type="hidden" name="type" id="nochex" value="nochex">
	<div class="form-group row">

		<label class="col-sm-12 col-md-3 col-form-label">Disabled Nochex</label>
		<div class="col-sm-12 col-md-9 row">
			<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="disabled_cod" name="disabled_cod"  value="1"  <?php echo (isset($nochex_info->disable) && $nochex_info->disable==1)?'checked':''; ?>>
				<label class="custom-control-label" for="disabled_cod"></label>
			</div>
			</div>
			
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Merchant Id</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="merchant_id" id="merchant_id"  class="form-control" placeholder="Merchant Id" value="<?php echo isset($nochex_info->merchant_id)?set_value('merchant_id',$nochex_info->merchant_id):set_value('merchant_id'); ?>">
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Description</label>
		<div class="col-sm-12 col-md-9">
		<input type="text" name="description" id="description"  class="form-control" placeholder="Description" value="<?php echo isset($nochex_info->description)?set_value('description',$nochex_info->description):set_value('description'); ?>">
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
