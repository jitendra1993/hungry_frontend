<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Store setting</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Store setting</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/setting/setting-view')?>" class="btn btn-primary btn-sm" > View</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php
	$currency = CURRENCY;
	 if(isset($error) && !empty($error)){ ?>
		<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
		<?php 
	}
	  
	if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
		  <?php echo validation_errors(); ?>
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
	  
	$attributes = array('class' => 'merchant_setting', 'id' => 'merchant_setting'); 
	echo form_open_multipart('admin/setting/edit/'.$store_setting->user_hash_id,$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($store_setting->user_hash_id)?set_value('name',$store_setting->user_hash_id):set_value('id'); ?>">
	<div class="title"><h5 class="text-blue h5 mb-20"> Merchant setting</h5></div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Delivery Estimation</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="delivery_estimation" id="delivery_estimation"  class="form-control" placeholder="Delivery Estimation" value="<?php echo isset($store_setting->delivery_estimation)?set_value('delivery_estimation',html_entity_decode($store_setting->delivery_estimation)):set_value('delivery_estimation'); ?>">
			<div class="has-danger form-control-feedback err_delivery_estimation"></div>
		</div>
	</div>
				
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Pickup Estimation</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="pickup_estimation" id="pickup_estimation"  class="form-control" placeholder="Pickup Estimation" value="<?php echo isset($store_setting->pickup_estimation)?set_value('pickup_estimation',html_entity_decode($store_setting->pickup_estimation)):set_value('pickup_estimation'); ?>">
			<div class="has-danger form-control-feedback err_pickup_estimation"></div>
		</div>
	</div>
			
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Delivery Distance Covered</label>
		<div class="col-sm-12 col-md-6">
			<input type="text" name="merchant_delivery_coverd" id="merchant_delivery_coverd"  class="form-control" placeholder="Delivery Distance Covered" value="<?php echo isset($store_setting->merchant_delivery_coverd)?set_value('merchant_delivery_coverd',html_entity_decode($store_setting->merchant_delivery_coverd)):set_value('merchant_delivery_coverd'); ?>" onkeypress="return isNumber(event)" maxlength="3">
			<div class="has-danger form-control-feedback err_merchant_delivery_coverd"></div>
		</div>
		<div class="col-sm-12 col-md-3" >
		<select class="form-control" name="merchant_distance_type" id="merchant_distance_type" >
			<option value="mi" <?php echo (isset($store_setting->merchant_distance_type) && $store_setting->merchant_distance_type=='mi')?'selected':''; ?>>Miles</option> 
			<option value="km" <?php echo (isset($store_setting->merchant_distance_type) && $store_setting->merchant_distance_type=='km')?'selected':''; ?>>Kilometers</option> 
		</select>
		</div>
	</div>
			
			
	<!--<div class="title"><h5 class="text-blue h5 mb-20">Food Item Options</h5></div>
			
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">If item is not available, Than</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-radio mb-5">
				<input type="radio" value="1" id="food_option_not_available1" name="food_option_not_available" class="custom-control-input"<?php echo (isset($store_setting->food_option_not_available) && $store_setting->food_option_not_available==1)?'checked':''; ?>>
				<label class="custom-control-label" for="food_option_not_available1" >Hide</label>
			</div>
			
			<div class="custom-control custom-radio mb-5">
				<input type="radio" value="2" id="food_option_not_available2" name="food_option_not_available" class="custom-control-input" <?php echo (isset($store_setting->food_option_not_available) && $store_setting->food_option_not_available==2)?'checked':(!isset($store_setting->food_option_not_available))?'checked':''; ?>>
				<label class="custom-control-label" for="food_option_not_available2">Disabled</label>
			</div>
			 
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="disabled_food_gallery" name="disabled_food_gallery" value="1"  <?php echo (isset($store_setting->disabled_food_gallery) && $store_setting->disabled_food_gallery==1)?'checked':''; ?>>
				<label class="custom-control-label" for="disabled_food_gallery">Disabled food gallery</label>
			</div>
		</div>
	</div>-->
				
				
	<div class="title"><h5 class="text-blue h5 mb-20">Receipt Options</h5></div>	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">VAT/GST Number</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="merchant_tax_number" id="merchant_tax_number"  class="form-control" placeholder="VAT/GST Number" value="<?php echo isset($store_setting->merchant_tax_number)?set_value('merchant_tax_number',html_entity_decode($store_setting->merchant_tax_number)):set_value('merchant_tax_number'); ?>">
		</div>
	</div>
		
	<?php if($userRole==1){?>			
	<div class="title"><h5 class="text-blue h5 mb-20">Free Delivery Options</h5></div>	
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Free delivery above Sub Total Order</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="free_delivery_above_price" id="free_delivery_above_price"  class="form-control" placeholder="Free delivery above Sub Total Order" value="<?php echo isset($store_setting->free_delivery_above_price)?set_value('free_delivery_above_price',html_entity_decode($store_setting->free_delivery_above_price)):set_value('free_delivery_above_price'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>	
	<?php } ?>
				
			
	<div class="title"><h5 class="text-blue h5 mb-20">Restaurant Open/Close/Other</h5></div>	
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Close Store?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="merchant_close_store" name="merchant_close_store" value="1" <?php echo (isset($store_setting->merchant_close_store) && $store_setting->merchant_close_store==1)?'checked':''; ?>>
				<label class="custom-control-label" for="merchant_close_store"></label>
			</div>
		</div>
	</div>

	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Show Merchant Current Time?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="merchant_show_time" name="merchant_show_time" value="1" <?php echo (isset($store_setting->merchant_show_time) && $store_setting->merchant_show_time==1)?'checked':''; ?>>
				<label class="custom-control-label" for="merchant_show_time"></label>
			</div>
		</div>
	</div>

	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Disabled Ordering?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="merchant_disabled_ordering" name="merchant_disabled_ordering" value="1" <?php echo (isset($store_setting->merchant_disabled_ordering) && $store_setting->merchant_disabled_ordering==1)?'checked':''; ?>>
				<label class="custom-control-label" for="merchant_disabled_ordering"></label>
			</div>
		</div>
	</div>

	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Used Admin Driver?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="used_admin_driver" name="used_admin_driver" value="1" <?php echo (isset($store_setting->used_admin_driver) && $store_setting->used_admin_driver==1)?'checked':''; ?>>
				<label class="custom-control-label" for="used_admin_driver"></label>
			</div>
		</div>
	</div>
				
	<div class="form-group row ">
		<label class="col-sm-12 col-md-3 col-form-label">Close Message</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="merchant_close_msg" id="merchant_close_msg"  class="form-control" placeholder="Close Message" value="<?php echo isset($store_setting->merchant_close_msg)?set_value('merchant_close_msg',html_entity_decode($store_setting->merchant_close_msg)):set_value('merchant_close_msg'); ?>">
		</div>
	</div>

	<?php if($userRole==1){?>			
	<div class="title"><h5 class="text-blue h5 mb-20">Marketing message for online menu only</h5></div>
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Enabled Voucher?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="merchant_enabled_voucher" name="merchant_enabled_voucher" value="1" <?php echo (isset($store_setting->merchant_enabled_voucher) && $store_setting->merchant_enabled_voucher==1)?'checked':''; ?>>
				<label class="custom-control-label" for="merchant_enabled_voucher"></label>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php if($userRole==2){?>				
	<div class="title"><h5 class="text-blue h5 mb-20">Minimum/ Maximum Order Price</h5></div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Pick Up or Delivery?</label>
		<div class="col-sm-12 col-md-9">
			<select class="form-control" name="service_status" id="service_status">
			<option value="3" <?php echo (isset($store_setting->service_status) && $store_setting->service_status=='3')?'selected':''; ?>>Collection and Delivery</option> 
			<option value="1" <?php echo (isset($store_setting->service_status) && $store_setting->service_status=='1')?'selected':''; ?>>Collection</option> 
			<option value="2" <?php echo (isset($store_setting->service_status) && $store_setting->service_status=='2')?'selected':''; ?>>Delivery</option> 
			
		</select>
		</div>
	</div>
	
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Minimum Order Delivery</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_minimum_order_delivery" id="merchant_minimum_order_delivery"  class="form-control" placeholder="Minimum Order Delivery" value="<?php echo isset($store_setting->merchant_minimum_order_delivery)?set_value('merchant_minimum_order_delivery',html_entity_decode($store_setting->merchant_minimum_order_delivery)):set_value('merchant_minimum_order_delivery'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Maximum Order Delivery</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_maximum_order_delivery" id="merchant_maximum_order_delivery"  class="form-control" placeholder="Maximum Order Delivery" value="<?php echo isset($store_setting->merchant_maximum_order_delivery)?set_value('merchant_maximum_order_delivery',html_entity_decode($store_setting->merchant_maximum_order_delivery)):set_value('merchant_maximum_order_delivery'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Minimum Order Pickup</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_minimum_order_pickup" id="merchant_minimum_order_pickup"  class="form-control" placeholder="Minimum Order Pickup" value="<?php echo isset($store_setting->merchant_minimum_order_pickup)?set_value('merchant_minimum_order_pickup',html_entity_decode($store_setting->merchant_minimum_order_pickup)):set_value('merchant_minimum_order_pickup'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Maximum Order Pickup</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_maximum_order_pickup" id="merchant_maximum_order_pickup"  class="form-control" placeholder="Maximum Order Pickup" value="<?php echo isset($store_setting->merchant_maximum_order_pickup)?set_value('merchant_maximum_order_pickup',html_entity_decode($store_setting->merchant_maximum_order_pickup)):set_value('merchant_maximum_order_pickup'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Minimum Order Dine</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_minimum_order_dinein" id="merchant_minimum_order_dinein"  class="form-control" placeholder="Minimum Order Dine" value="<?php echo isset($store_setting->merchant_minimum_order_dinein)?set_value('merchant_minimum_order_dinein',$store_setting->merchant_minimum_order_dinein):set_value('merchant_minimum_order_dinein'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Maximum Order Dine</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_maximum_order_dinein" id="merchant_maximum_order_dinein"  class="form-control" placeholder="Maximum Order Dine" value="<?php echo isset($store_setting->merchant_maximum_order_dinein)?set_value('merchant_maximum_order_dinein',html_entity_decode($store_setting->merchant_maximum_order_dinein)):set_value('merchant_maximum_order_dinein'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if($userRole==2){?>				
	<div class="title"><h5 class="text-blue h5 mb-20">Packaging Charge</h5></div>
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Packaging Charge</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="merchant_packaging_charge" id="merchant_packaging_charge"  class="form-control" placeholder="Packaging Charge" value="<?php echo isset($store_setting->merchant_packaging_charge)?set_value('merchant_packaging_charge',html_entity_decode($store_setting->merchant_packaging_charge)):set_value('merchant_packaging_charge'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>

	<?php }
	 if($userRole==1){?>	
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Service Charge</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="service_charge" id="service_charge"  class="form-control" placeholder="Service Charge" value="<?php echo isset($store_setting->service_charge)?set_value('service_charge',html_entity_decode($store_setting->service_charge)):set_value('service_charge'); ?>">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
				
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Dinein Service Charge</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="dinein_service_charge" id="dinein_service_charge"  class="form-control" placeholder="Service Charge" value="<?php echo isset($store_setting->dinein_service_charge)?set_value('dinein_service_charge',html_entity_decode($store_setting->dinein_service_charge)):set_value('dinein_service_charge'); ?>">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>		
	<?php 
	 if($userRole==2){?>		
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Dinein Enable?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="dinein_enable" value="1" name="dinein_enable" <?php echo (isset($store_setting->dinein_enable) && $store_setting->dinein_enable==1)?'checked':''; ?>>
				<label class="custom-control-label" for="dinein_enable"></label>
			</div>
		</div>
	</div>

	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Dinein Open Table Enable?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="dinein_open_table_enable" value="1" name="dinein_open_table_enable" <?php echo (isset($store_setting->dinein_open_table_enable) && $store_setting->dinein_open_table_enable==1)?'checked':''; ?>>
				<label class="custom-control-label" for="dinein_open_table_enable"></label>
			</div>
		</div>
	</div>
	
	<?php } if($userRole==1){ ?>	
	<div class="title"><h5 class="text-blue h5 mb-20">Minimum Delivery Charge</h5></div>	
		<div class="form-group row mb-0">
			<p class="mb-15 text-muted font-15 col-md-12">Enter your minimum delivery charge (if any). You can use 'Delivery Charge Rates' tables as well</p>
			<label class="col-sm-12 col-md-3 col-form-label">Minimum Delivery Charge</label>
			
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<input type="text" name="merchant_delivery_charges" id="merchant_delivery_charges"  class="form-control" placeholder="Minimum Delivery Charge" value="<?php echo isset($store_setting->merchant_delivery_charges)?set_value('merchant_delivery_charges',html_entity_decode($store_setting->merchant_delivery_charges)):set_value('merchant_delivery_charges'); ?>" onkeypress="return isNumber(event)">
					<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>	
				
				
	<div class="title"><h5 class="text-blue h5 mb-20">Tips</h5></div>		
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Enabled?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="merchant_enabled_tip" name="merchant_enabled_tip" value="1" <?php echo (isset($store_setting->merchant_enabled_tip) && $store_setting->merchant_enabled_tip==1)?'checked':''; ?>>
				<label class="custom-control-label" for="merchant_enabled_tip"></label>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Default Tip</label>
		<div class="col-sm-12 col-md-9">
			<select name="merchant_tip_perchant" id="merchant_tip_perchant" class="form-control">
			<option value="" >none</option>
			<option value="10" <?php echo (isset($store_setting->merchant_tip_perchant) && $store_setting->merchant_tip_perchant=='10')?'selected':''; ?>>10%</option>
			<option value="15" <?php echo (isset($store_setting->merchant_tip_perchant) && $store_setting->merchant_tip_perchant=='15')?'selected':''; ?>>15%</option>
			<option value="20" <?php echo (isset($store_setting->merchant_tip_perchant) && $store_setting->merchant_tip_perchant=='20')?'selected':''; ?>>20%</option>
			<option value="25" <?php echo (isset($store_setting->merchant_tip_perchant) && $store_setting->merchant_tip_perchant=='25')?'selected':''; ?>>25%</option>
			</select>
		</div>
	</div>
	<?php if($userRole==1){?>	
	<div class="title"><h5 class="text-blue h5 mb-20">Restricted Hours</h5></div>
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Restricted Hours</label>
		<div class="col-sm-12 col-md-5">
			<input type="text" name="restricted_from" id="restricted_from"  class="form-control timepicker" placeholder="From" value="<?php echo (isset($store_setting->restricted_from) && $store_setting->restricted_from!='00:00:00')?date("g:i a", strtotime($store_setting->restricted_from)):'';?>" readonly>
		</div>
		<div class="col-sm-12 col-md-4" >
			<input type="text" name="restricted_to" id="restricted_to"  class="form-control timepicker" placeholder="To" value="<?php echo (isset($store_setting->restricted_to) && $store_setting->restricted_to!='00:00:00')?date("g:i a", strtotime($store_setting->restricted_to)):'';?>" readonly>
		</div>
	</div>		
	<?php } ?>

	<?php if($userRole==2){?>	
	<div class="title"><h5 class="text-blue h5 mb-20">Store Hours</h5></div>
	<div class="form-group">
		<label>Store days(s) Open:</label>
		<p class="mb-15 text-muted font-15 ">If none of the days have been selected then restuarant will be set to open all the time</p>
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Day</th>
						<th scope="col">Monrning In</th>
						<th scope="col">Monrning Out</th>
						<th scope="col">Evening In</th>
						<th scope="col">Evening Out</th>
					</tr>
				</thead>
				<tbody>
				<?php 
			if(isset($store_setting->store_time) && count($store_setting->store_time)>0){
				$i=0;
				foreach($store_setting->store_time as  $time)
				{
					?>
					<tr>
					<td>
						<div class="custom-control custom-checkbox mb-5">
							<input type="checkbox" class="custom-control-input" id="day1<?php echo $i;?>" value="1" name="day[<?php echo $i?>]" <?php echo (isset($time['is_open']) && $time['is_open']==1)?'checked':''; ?>>
							<label class="custom-control-label" for="day1<?php echo $i?>"></label>
						</div>
					</td>
					<td>
						<?php echo $time['store_day'];?>
					</td>
					<td>
						<input type="text" name="open_time_mrng[<?php echo $i?>]" id="<?php echo $i.'_mrng_in';?>"  class="form-control timepicker" placeholder="<?php echo $time['store_day'];?> Morning In" value="<?php echo (isset($time['open_time_mrng']) && $time['open_time_mrng']!='00:00:00')?date("g:i a", strtotime($time['open_time_mrng'])):'';?>" readonly>
					</td>
					<td>
						<input type="text" name="close_time_mrng[<?php echo $i?>]" id="<?php echo $i.'_mrng_out';?>"  class="form-control timepicker" placeholder="<?php echo $time['store_day'];?> Morning Out" value="<?php echo (isset($time['close_time_mrng']) && $time['close_time_mrng']!='00:00:00')?date("g:i a", strtotime($time['close_time_mrng'])):'';?>" readonly>
					</td>
					<td>
						<input type="text" name="open_time_evening[<?php echo $i?>]" id="<?php echo $i.'_even_in';?>"  class="form-control timepicker" placeholder="<?php echo $time['store_day'];?> Evening In" value="<?php echo (isset($time['open_time_evening']) && $time['open_time_evening']!='00:00:00')?date("g:i a", strtotime($time['open_time_evening'])):'';?>" readonly>
					</td>
					<td>
						<input type="text" name="close_time_evening[<?php echo $i?>]" id="<?php echo $i.'_even_out';?>"  class="form-control timepicker" placeholder="<?php echo $time['store_day'];?> Evening Out" value="<?php echo (isset($time['close_time_evening']) && $time['close_time_evening']!='00:00:00')?date("g:i a", strtotime($time['close_time_evening'])):'';?>" readonly>
					</td>
					</tr>
		
					<?php
					$i++;
				}
			}else{
				$day = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
				for($i=1;$i<=7;$i++){
					?>
					<tr>
					<td>
						<div class="custom-control custom-checkbox mb-5">
							<input type="checkbox" class="custom-control-input" id="day1<?php echo $i;?>" value="1" name="day[<?php echo $i?>]">
							<label class="custom-control-label" for="day1<?php echo $i?>"></label>
						</div>
					</td>
					<td>
						<?php echo $day[$i-1];?>
					</td>
					<td>
						<input type="text" name="open_time_mrng[<?php echo $i?>]" id="<?php echo $i.'_mrng_in';?>"  class="form-control timepicker" placeholder="<?php echo $day[$i-1];?> Morning In"  readonly>
					</td>
					<td>
						<input type="text" name="close_time_mrng[<?php echo $i?>]" id="<?php echo $i.'_mrng_out';?>"  class="form-control timepicker" placeholder="<?php echo $day[$i-1];?> Morning Out"  readonly>
					</td>
					<td>
						<input type="text" name="open_time_evening[<?php echo $i?>]" id="<?php echo $i.'_even_in';?>"  class="form-control timepicker" placeholder="<?php echo $day[$i-1];?> Evening In" readonly>
					</td>
					<td>
						<input type="text" name="close_time_evening[<?php echo $i?>]" id="<?php echo $i.'_even_out';?>"  class="form-control timepicker" placeholder="<?php echo $day[$i-1];?> Evening Out"  readonly>
					</td>
					</tr>
		
					<?php
				}
				
			} ?>
			
				</tbody>
			</table>
		</div>
	</div>
							
			
	<div class="title"><h5 class="text-blue h5 mb-20">Pre-orders/ Holidays</h5></div>		
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Enabled?</label>
		<div class="col-sm-12 col-md-9 col-form-label">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input" id="pre_order" value="1" name="pre_order" <?php echo (isset($store_setting->pre_order) && $store_setting->pre_order==1)?'checked':''; ?>>
				<label class="custom-control-label" for="pre_order"></label>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm btn-block">SUBMIT</button>
			</div>
		</div>
	</div>
			
	<?php echo form_close(); ?>
	
</div>

