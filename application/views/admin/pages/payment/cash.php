<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> CASH</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> CASH</li>
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
  
	$attributes = array('class' => 'cash', 'id' => 'cash'); 
	echo form_open_multipart('admin/payment/cash/',$attributes);?>
	<input type="hidden" name="type" id="cash" value="cash">
	<div class="form-group row">

		<label class="col-sm-12 col-md-3 col-form-label">Disabled Cash on Del/Col</label>
		<div class="col-sm-12 col-md-9 row">
			<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="disabled_cod" name="disabled_cod"  value="1"  <?php echo (isset($cash_info->disable) && $cash_info->disable==1)?'checked':''; ?>>
				<label class="custom-control-label" for="disabled_cod"></label>
			</div>
			</div>
			
		</div>
	</div>
		
	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm disable">SUBMIT</button>
			</div>
		</div>
	</div>
		
	</form>
	
</div>
