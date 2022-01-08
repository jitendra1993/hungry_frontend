<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Voucher Add/Update</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Voucher</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/voucher/view')?>" class="btn btn-primary btn-sm" >View Voucher</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php
$currency = CURRENCY;
 if(isset($error) && !empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
				 <?php echo $error; ?>
			</div>

          <?php }
		  
		   if(validation_errors()){ ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <?php //echo validation_errors(); ?>
			  <?php echo form_error('voucher_name', '<span>', '</span><br>'); ?>
			  <?php echo form_error('voucher_code', '<span>', '</span><br>'); ?>
			<?php echo form_error('voucher_type', '<span>', '</span><br>'); ?>
			<?php echo form_error('voucher_price', '<span>', '</span><br>'); ?>
			<?php echo form_error('voucher_min_order', '<span>', '</span><br>'); ?>
			<?php echo form_error('valid_from', '<span>', '</span><br>'); ?>
			<?php echo form_error('valid_to', '<span>', '</span><br>'); ?>
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
		  
		$attributes = array('class' => 'voucher_admin', 'id' => 'voucher_admin'); 
		  echo form_open(isset($voucher->id)?'admin/voucher/edit/'.$voucher->id:'admin/voucher/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($voucher->id)?set_value('name',$voucher->id):set_value('id'); ?>">
			
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Voucher Name</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="voucher_name" id="voucher_name"  class="form-control" placeholder="Voucher Name" value="<?php echo isset($voucher->voucher_name)?set_value('voucher_name',html_entity_decode($voucher->voucher_name)):set_value('voucher_name'); ?>" >
					<div class="has-danger form-control-feedback err_voucher_name"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Voucher Code</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="voucher_code" id="voucher_code"  class="form-control" placeholder="Voucher Code" value="<?php echo isset($voucher->voucher_code)?set_value('voucher_code',$voucher->voucher_code):set_value('voucher_code',strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10))); ?>" >
					<div class="has-danger form-control-feedback err_voucher_code"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Voucher Type</label>
				<div class="col-sm-12 col-md-9">
					<select class="form-control" name="voucher_type" id="voucher_type">
						<option value="2" <?php echo (isset($voucher->voucher_type) && $voucher->voucher_type=='2')?'selected':''; ?>>Percent(%)</option> 
						<option value="1"<?php echo (isset($voucher->voucher_type) && $voucher->voucher_type=='1')?'selected':''; ?>>Flat</option> 
					</select>
					<div class="has-danger form-control-feedback err_voucher_type"></div>
				</div>
			</div>
			

			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Voucher Price(for flat)</label>
				<div class="col-sm-12 col-md-9">
					<div class="input-group mb-0">
						<input type="text" name="voucher_price" id="voucher_price"  class="form-control" placeholder="Voucher Price" value="<?php echo isset($voucher->voucher_price)?set_value('voucher_price',$voucher->voucher_price):set_value('voucher_price'); ?>" onkeypress="return isNumberDecimal(event)">
						<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
						</div>
					</div>
					<div class="has-danger form-control-feedback err_voucher_price"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Voucher Applied Above</label>
				<div class="col-sm-12 col-md-9">
					<div class="input-group mb-0">
						<input type="text" name="voucher_min_order" id="voucher_min_order"  class="form-control" placeholder="Orders Above" value="<?php echo isset($voucher->voucher_min_order)?set_value('voucher_min_order',$voucher->voucher_min_order):set_value('voucher_min_order'); ?>" onkeypress="return isNumberDecimal(event)">
						<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
						</div>
					</div>
					<div class="has-danger form-control-feedback err_voucher_min_order"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Maximum Discount(for %)</label>
				<div class="col-sm-12 col-md-9">
					<div class="input-group mb-0">
						<input type="text" name="max_discount" id="max_discount"  class="form-control" placeholder="Maximum Discount" value="<?php echo isset($voucher->max_discount)?set_value('max_discount',$voucher->max_discount):set_value('max_discount'); ?>" onkeypress="return isNumberDecimal(event)">
						<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Valid From</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="valid_from" id="valid_from"  class="form-control datepicker1" placeholder="Valid From" value="<?php echo isset($voucher->valid_from)?set_value('valid_from',date("d-M-y", strtotime($voucher->valid_from))):set_value('valid_from'); ?>" readonly>
					<div class="has-danger form-control-feedback err_valid_from"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Valid To</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="valid_to" id="valid_to"  class="form-control datepicker1" placeholder="Valid From" value="<?php echo isset($voucher->valid_to)?set_value('valid_to',date("d-M-y", strtotime($voucher->valid_to))):set_value('valid_to'); ?>" readonly>
					<div class="has-danger form-control-feedback err_valid_to"></div>
				</div>
			</div>
			
			<div class="form-group row mb-0">
				<label class="col-sm-12 col-md-3 col-form-label">Applicable on</label>
				<div class="col-sm-12 col-md-9 col-form-label">
					<div class="custom-control custom-checkbox mb-5">
						<input type="checkbox" class="custom-control-input"  id="delivery" name="delivery" value="1" <?php echo (isset($voucher->delivery) && $voucher->delivery==1 && isset($voucher->id))?'checked':!isset($voucher->id)?'checked':''; ?>>
						<label class="custom-control-label" for="delivery">Delivery</label>
					</div>
					
					<div class="custom-control custom-checkbox mb-5">
						<input type="checkbox" class="custom-control-input"  id="pickup" name="pickup" value="1" <?php echo (isset($voucher->pickup) && $voucher->pickup==1)?'checked':''; ?>>
						<label class="custom-control-label" for="pickup">Pickup</label>
					</div>
					
					<div class="custom-control custom-checkbox mb-5">
						<input type="checkbox" class="custom-control-input"  id="dinein" name="dinein" value="1" <?php echo (isset($voucher->dinein) && $voucher->dinein==1)?'checked':''; ?>>
						<label class="custom-control-label" for="dinein">Dinein</label>
					</div>
				</div>
			</div>
			
			
			<div class="form-group row mb-0">
				<label class="col-sm-12 col-md-3 col-form-label">Gift Voucher (use once)</label>
				<div class="col-sm-12 col-md-9 col-form-label">
					<div class="custom-control custom-checkbox mb-5">
						<input type="checkbox" class="custom-control-input"  id="used_once" name="used_once" value="1" <?php echo (isset($voucher->used_once) && $voucher->used_once==1)?'checked':''; ?>>
						<label class="custom-control-label" for="used_once"></label>
					</div>
				</div>
			</div>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
				<div class="col-sm-12 col-md-9" >
				<select class="form-control" name="status" id="status" >
					<option value="0" <?php echo (isset($voucher->status) && $voucher->status=='0')?'selected':''; ?>>Pending</option> 
					<option value="1" <?php echo (isset($voucher->status) && $voucher->status=='1')?'selected':''; ?>>Publish</option> 
				</select>
				</div>
			</div>
			
		<div class="row">
			<div class=" col-sm-12">
				<div class="input-group mb-0">
					<button type="submit" class="btn btn-primary btn-sm btn-block disable">SUBMIT</button>
				</div>
			</div>
		</div>
		
	<?php echo form_close(); ?>
	
</div>

