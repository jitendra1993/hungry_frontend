<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Delivery Charges Rates</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Delivery Charges Rates</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/delivery-charges/view')?>" class="btn btn-primary btn-sm" > View</a>
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
		<?php 
	} 
	  
	$attributes = array('class' => 'delivery_charges', 'id' => 'delivery_charges'); 
	echo form_open(!empty($this->uri->segment(4))?'admin/delivery-charges/edit/'.$this->uri->segment(4):'admin/delivery-charges/add/',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($delivery_charges->id)?set_value('name',$delivery_charges->user_hash_id):set_value('id'); ?>">
		
	<div class="form-group row mb-0">
		<label class="col-sm-12 col-md-3 col-form-label">Free delivery above Sub Total Order</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="free_delivery_above_price" id="free_delivery_above_price"  class="form-control" placeholder="Free delivery above Sub Total Order" value="<?php echo isset($store_setting->free_delivery_above_price)?set_value('free_delivery_above_price',$store_setting->free_delivery_above_price):set_value('free_delivery_above_price'); ?>" onkeypress="return isNumber(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Enabled Table Rates?</label>
		<div class="col-sm-12 col-md-5 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input"  id="shipping_enabled" name="shipping_enabled"  value="1"  <?php echo (isset($delivery_charges->shipping_enabled) && $delivery_charges->shipping_enabled==1)?'checked':''; ?>>
				<label class="custom-control-label" for="shipping_enabled"></label>
			</div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Which Rates Enabled?</label>
		<div class="custom-control custom-radio mb-5">
			<input type="radio" value="1"  id ="place" name="delivery_type" class="custom-control-input"<?php echo (isset($delivery_charges->delivery_type) && $delivery_charges->delivery_type==1)?'checked':''; ?>>
			<label class="custom-control-label" for="place" >Place</label>
		</div>
		<div class="custom-control custom-radio mb-5">
			<input type="radio" value="2"  id="postcode" name="delivery_type" class="custom-control-input" <?php echo (isset($delivery_charges->delivery_type) && $delivery_charges->delivery_type==2)?'checked':(!isset($delivery_charges->delivery_type))?'checked':''; ?>>
			<label class="custom-control-label" for="postcode">Postcode </label>
		</div>
	</div>
	
	<?php
	$item_variation = isset($delivery_charges->id)?(array)$delivery_charges->variation:array();
	$item_variation_keys = (isset($item_variation) && count($item_variation)>0)?array_keys((array)$item_variation['distance_from']):array();
	?>
	<div class="form-group">
		<label>Table Rates</label>
		<div class="table-responsive">
			<table class="table table-striped table-bordered append_delivery_charges">
				<thead>
					<tr>
						<th scope="col">Distance</th>
						<th scope="col">Units</th>
						<th scope="col">Price</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<td>
					<input type="text" name="distance_from[<?php echo $uniqid;?>]"   class="form-control small_textbox" placeholder="From" value="<?php echo (isset($item_variation_keys) && count($item_variation_keys)>0 && isset($item_variation))?$item_variation['distance_from'][$item_variation_keys[0]]:''; ?>" onkeypress="return isNumberDecimal(event)">  &nbsp;TO&nbsp;
					<input type="text" name="distance_to[<?php echo $uniqid;?>]"   class="form-control small_textbox" placeholder="To" value="<?php echo (isset($item_variation_keys) && count($item_variation_keys)>0 && isset($item_variation) )?$item_variation['distance_to'][$item_variation_keys[0]]:''; ?>" onkeypress="return isNumberDecimal(event)"> 
					</td>
					
					<td>
						<select class="form-control" name="distance_type[<?php echo $uniqid;?>]">
							<option value="mi" <?php echo (isset($item_variation_keys) && count($item_variation_keys)>0 && isset($item_variation) && $item_variation['distance_type'][$item_variation_keys[0]]=='mi')?'selected':''; ?>>Miles</option> 
							<option value="km" <?php echo (isset($item_variation_keys) && count($item_variation_keys)>0 && isset($item_variation) && $item_variation['distance_type'][$item_variation_keys[0]]=='km')?'selected':''; ?>>Kilometers</option> 
								
						</select>
					</td>
					
					<td>
					<input type="text" name="price[<?php echo $uniqid;?>]"   class="form-control" placeholder="Price" value="<?php echo (isset($item_variation_keys) && count($item_variation_keys)>0 && isset($item_variation))?$item_variation['price'][$item_variation_keys[0]]:''; ?>" onkeypress="return isNumberDecimal(event)">  
					</td>
					
					<td>
					 <a href="javascript:void(0)" class="btn btn-success addDeliveryChargesVariation  btn-sm">+</a>
					</td>
					
				</tr>
				
				<?php
				$i=1;
				if(isset($item_variation) && count($item_variation)>0){
					foreach($item_variation['distance_from'] as $key=>$v)
					{
						if($i==1){
						$i++;
						continue;
						}
						?>
						<tr>
						<td>
						<input type="text" name="distance_from[<?php echo $key;?>]"   class="form-control small_textbox" placeholder="From" value="<?php echo isset($item_variation['distance_from'][$key])?$item_variation['distance_from'][$key]:''; ?>" onkeypress="return isNumberDecimal(event)">  &nbsp;TO&nbsp;
						<input type="text" name="distance_to[<?php echo $key;?>]"   class="form-control small_textbox" placeholder="To" value="<?php echo isset($item_variation['distance_to'][$key])?$item_variation['distance_to'][$key]:''; ?>" onkeypress="return isNumberDecimal(event)"> 
						</td>
						
						<td>
							<select class="form-control" name="distance_type[<?php echo $key;?>]">
								<option value="mi" <?php echo (isset($item_variation) && $item_variation['distance_type'][$key]=='mi')?'selected':''; ?>>Miles</option> 
								<option value="km" <?php echo (isset($item_variation) && $item_variation['distance_type'][$key]=='km')?'selected':''; ?>>Kilometers</option> 
									
							</select>
						</td>
						
						<td>
						<input type="text" name="price[<?php echo $key;?>]"   class="form-control" placeholder="Price" value="<?php echo isset($item_variation['price'][$key])?$item_variation['price'][$key]:''; ?>" onkeypress="return isNumberDecimal(event)">  
						</td>
						
						<td><a href="javascript:void(0)" class="btn btn-danger removeDeliveryChargesVariation  btn-sm">-</a></td>
						
					</tr>
			<?php }
				}	?>
				</tbody>
			</table>
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

