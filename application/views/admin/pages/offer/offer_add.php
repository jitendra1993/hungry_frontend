<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Offers</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Offer</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/offer/view')?>" class="btn btn-primary btn-sm" >View Offers</a>
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
			  <?php echo form_error('discount_type', '<span>', '</span>'); ?>
			<?php echo form_error('discount_price', '<span>', '</span>'); ?>
			<?php echo form_error('min_order', '<span>', '</span>'); ?>
			<?php echo form_error('valid_from', '<span>', '</span>'); ?>
			<?php echo form_error('valid_to', '<span>', '</span>'); ?>
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
		  
		$attributes = array('class' => 'offer_admin', 'id' => 'offer_admin'); 
		  echo form_open(isset($offer->id)?'admin/offer/edit/'.$offer->id:'admin/offer/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($offer->id)?set_value('name',$offer->id):set_value('id'); ?>">
			
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Offer Type</label>
				<div class="col-sm-12 col-md-9">
					<select class="form-control" name="discount_type" id="discount_type">
						<option value="2">Percent(%)</option> 
						<option value="1">Flat</option> 
					</select>
					<div class="has-danger form-control-feedback err_discount_type"></div>
				</div>
			</div>
			

			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Offer Price</label>
				<div class="col-sm-12 col-md-9">
					<div class="input-group mb-0">
						<input type="text" name="discount_price" id="discount_price"  class="form-control" placeholder="Offer Price" value="<?php echo isset($offer->discount_price)?set_value('discount_price',$offer->discount_price):set_value('discount_price'); ?>" onkeypress="return isNumber(event)">
						<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
						</div>
					</div>
					<div class="has-danger form-control-feedback err_discount_price"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Orders Above</label>
				<div class="col-sm-12 col-md-9">
					<div class="input-group mb-0">
						<input type="text" name="min_order" id="min_order"  class="form-control" placeholder="Orders Above" value="<?php echo isset($offer->min_order)?set_value('min_order',$offer->min_order):set_value('min_order'); ?>" onkeypress="return isNumber(event)">
						<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
						</div>
					</div>
					<div class="has-danger form-control-feedback err_min_order"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Maximum Discount</label>
				<div class="col-sm-12 col-md-9">
					<div class="input-group mb-0">
						<input type="text" name="max_discount" id="max_discount"  class="form-control" placeholder="Maximum Discount" value="<?php echo isset($offer->max_discount)?set_value('max_discount',$offer->max_discount):set_value('max_discount'); ?>" onkeypress="return isNumberDecimal(event)">
						<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Valid From</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="valid_from" id="valid_from"  class="form-control datepicker1" placeholder="Valid From" value="<?php echo isset($offer->valid_from)?set_value('valid_from',date("d-M-y", strtotime($offer->valid_from))):set_value('valid_from'); ?>" readonly>
					<div class="has-danger form-control-feedback err_valid_from"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Valid To</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="valid_to" id="valid_to"  class="form-control datepicker1" placeholder="Valid From" value="<?php echo isset($offer->valid_to)?set_value('valid_to',date("d-M-y", strtotime($offer->valid_to))):set_value('valid_to'); ?>" readonly>
					<div class="has-danger form-control-feedback err_valid_to"></div>
				</div>
			</div>
			
			<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Applicable on</label>
			<div class="col-sm-12 col-md-9 col-form-label">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="delivery" name="delivery" value="1" <?php echo (isset($offer->delivery) && $offer->delivery==1 && isset($offer->id))?'checked':!isset($offer->id)?'checked':''; ?>>
					<label class="custom-control-label" for="delivery">Delivery</label>
				</div>
				
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="pickup" name="pickup" value="1" <?php echo (isset($offer->pickup) && $offer->pickup==1)?'checked':''; ?>>
					<label class="custom-control-label" for="pickup">Pickup</label>
				</div>
				
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="dinein" name="dinein" value="1" <?php echo (isset($offer->dinein) && $offer->dinein==1)?'checked':''; ?>>
					<label class="custom-control-label" for="dinein">Dinein</label>
				</div>
			</div>
		</div>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
				<div class="col-sm-12 col-md-9" >
				<select class="form-control" name="status" id="status" >
					<option value="0" <?php echo (isset($offer->status) && $offer->status=='0')?'selected':''; ?>>Pending</option> 
					<option value="1" <?php echo (isset($offer->status) && $offer->status=='1')?'selected':''; ?>>Publish</option> 
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

