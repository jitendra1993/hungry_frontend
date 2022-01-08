<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Loyalty Points Settings</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Loyalty Points Settings</li>
				</ol>
			</nav>
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
              <?php echo validation_errors(); ?>
			 
            </div>
          <?php }
		  if($this->session->flashdata('msg_success')){ ?>
		  <div class="alert alert-success alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('msg_success'))?> 
		  </div>
		  <?php } 
		  
		$attributes = array('class' => 'point_setting', 'id' => 'point_setting'); 
		  echo form_open_multipart('admin/points-settings',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($setting->id)?set_value('name',$setting->id):set_value('id'); ?>">
		
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Enabled Points System</label>
			<div class="col-sm-12 col-md-9 col-form-label">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="enable_pts" name="enable_pts"  value="1" <?php echo (isset($setting->enable_pts) && $setting->enable_pts==1)?'checked':''; ?>>
					<label class="custom-control-label" for="enable_pts"></label>
				</div>
			</div>
		</div>
			
		<div class="title"><h5 class="text-blue h5 mb-20"> Earning Points Settings</h5></div>
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Based points earnings</label>
			<div class="col-sm-12 col-md-9">
				<select class="form-control" name="points_based_earn" id="points_based_earn">
				<option value="2" <?php echo (isset($setting->points_based_earn) && $setting->points_based_earn=='2')?'selected':''; ?>>Order Sub total</option> 
				<option value="1" <?php echo (isset($setting->points_based_earn) && $setting->points_based_earn=='1')?'selected':''; ?>>Food item</option> 
				
				
			</select>
			</div>
		</div>
		

		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Earning Point</label>
			<div class="col-sm-12 col-md-3">
				<input type="text" name="pts_earning" id="pts_earning"  class="form-control" placeholder="Earning Point" value="<?php echo isset($setting->pts_earning)?set_value('pts_earning',html_entity_decode($setting->pts_earning)):set_value('pts_earning'); ?>" onkeypress="return isNumberDecimal(event)">
			</div>
			
			<label class="col-sm-12 col-md-1 col-form-label">Every</label>
			<div class="col-sm-12 col-md-5">
				<input type="text" name="every_spent" id="every_spent"  class="form-control" placeholder="Every" value="<?php echo isset($setting->every_spent)?set_value('every_spent',html_entity_decode($setting->every_spent)):set_value('every_spent'); ?>" onkeypress="return isNumberDecimal(event)">
			</div>
			
		</div>
			
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Earning Point Value</label>
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<input type="text" name="earning_points_value" id="earning_points_value"  class="form-control" placeholder="Earning Point Value" value="<?php echo isset($setting->earning_points_value)?set_value('earning_points_value',html_entity_decode($setting->earning_points_value)):set_value('earning_points_value'); ?>" onkeypress="return isNumberDecimal(event)">
					<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Earn points above order</label>
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<input type="text" name="earn_above_amount" id="earn_above_amount"  class="form-control" placeholder="Earn points above order" value="<?php echo isset($setting->earn_above_amount)?set_value('earn_above_amount',html_entity_decode($setting->earn_above_amount)):set_value('earn_above_amount'); ?>" onkeypress="return isNumberDecimal(event)">
					<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="title"><h5 class="text-blue h5 mb-20">Redeeming Points Settings</h5></div>
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Enabled Redeeming</label>
			<div class="col-sm-12 col-md-9 col-form-label">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="enable_redeem" name="enable_redeem"  value="1" <?php echo (isset($setting->enable_redeem) && $setting->enable_redeem==1)?'checked':''; ?>>
					<label class="custom-control-label" for="enable_redeem"></label>
				</div>
			</div>
		</div>
		
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Mimimum Redeeming Point</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="min_redeeming_point" id="min_redeeming_point"  class="form-control" placeholder="Mimimum Redeeming Point" value="<?php echo isset($setting->min_redeeming_point)?set_value('min_redeeming_point',html_entity_decode($setting->min_redeeming_point)):set_value('min_redeeming_point'); ?>" onkeypress="return isNumber(event)">
			</div>
		</div>
		
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Redeem points above orders</label>
			<div class="col-sm-12 col-md-9">
				<div class="input-group">
					<input type="text" name="points_apply_order_amt" id="points_apply_order_amt"  class="form-control" placeholder="Redeem points above orders" value="<?php echo isset($setting->points_apply_order_amt)?set_value('points_apply_order_amt',html_entity_decode($setting->points_apply_order_amt)):set_value('points_apply_order_amt'); ?>" onkeypress="return isNumber(event)">
					<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Minimum points can be used</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="min_point_used" id="min_point_used"  class="form-control" placeholder="Minimum points can be used" value="<?php echo isset($setting->min_point_used)?set_value('min_point_used',html_entity_decode($setting->min_point_used)):set_value('min_point_used'); ?>" onkeypress="return isNumber(event)">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Maximum points can be used</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="max_point_used" id="max_point_used"  class="form-control" placeholder="Maximum points can be used" value="<?php echo isset($setting->max_point_used)?set_value('max_point_used',html_entity_decode($setting->max_point_used)):set_value('max_point_used'); ?>" onkeypress="return isNumber(event)">
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

