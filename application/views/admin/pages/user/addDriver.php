<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Driver Info</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Driver Info</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
		
			<a href="<?=base_url('admin/user/view')?>" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i>View all</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php 
	if(isset($error) && !empty($error)){ ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $error; ?>
		</div>
		<?php 
	}
		  
   if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<?php echo form_error('name', '<span>', '</span><br>'); ?>
			<?php echo form_error('mobile', '<span>', '</span><br>'); ?>
			<?php echo form_error('country', '<span>', '</span><br>'); ?>
			<?php echo form_error('state', '<span>', '</span><br>'); ?>
			<?php echo form_error('city', '<span>', '</span><br>'); ?>
			<?php echo form_error('pincode', '<span>', '</span><br>'); ?>
			<?php echo form_error('address', '<span>', '</span><br>'); ?>
			<?php echo form_error('username', '<span>', '</span><br>'); ?>
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
	
	if($this->session->flashdata('error_msg')){ ?>
		<div class="alert alert-danger alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('error_msg'))?> 
		</div>
		<?php 
	} 
		  
	$attributes = array('class' => 'add_driver', 'id' => 'add_driver'); 
	echo form_open_multipart(isset($driver->hash)?'admin/driver/edit/'.$driver->hash:'admin/driver/add',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($driver->hash)?set_value('name',$driver->hash):set_value('id'); ?>">
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Name</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="name" id="name"  class="form-control" placeholder="Name" value="<?php echo isset($driver->name)?set_value('name',html_entity_decode($driver->name)):set_value('name'); ?>">
			<div class="has-danger form-control-feedback errName"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Mobile</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="mobile" id="mobile"  class="form-control" placeholder="Mobile" value="<?php echo isset($driver->mobile)?set_value('mobile',html_entity_decode($driver->mobile)):set_value('mobile'); ?>"  onkeypress="return isNumber(event)">
			<div class="has-danger form-control-feedback errPhone"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Address</label>
		<div class="col-md-10 col-sm-12 row pr-0">
			
			<div class="col-sm-12 col-md-3 ">
			<input type="hidden" name="country_href" id="country_href" value="<?=base_url('admin/setting/getState')?>">
			<input type="hidden" name="append_state_class" id="append_state_class" value="append_state">
			<select class="custom-select2 form-control country" name="country" id="country">
			<option value="">Country</option>
			<?php
				foreach($getCountry as $k=>$country){
					$sel = (isset($driver->country) && $country->name==$driver->country)?'selected':'';
					echo '<option value="'.$country->name.'^'.$country->id.'" data-id="'.$country->id.'" '.$sel.'>'.$country->name.'</option>';
				}
			?>
			</select>
				<div class="has-danger form-control-feedback errCountry"></div>
			</div>
		
			<div class="col-sm-12 col-md-3">
			<input type="hidden" name="state_href" id="state_href" value="<?=base_url('admin/setting/getCity')?>">
			<input type="hidden" name="append_city_class" id="append_city_class" value="append_city">
			
			<span class="append_state">
			<select class="custom-select2 form-control state" name="state" id="state">
			<option value="">State</option>
			<?php if(isset($getState) && count($getState)>0 && !empty($getState))
				foreach($getState as $k=>$state){
					$sel = ($state['name']==$driver->state)?'selected':'';
					echo '<option value="'.$state['name'].'^'.$state['id'].'" data-id="'.$state['id'].'" '.$sel .'>'.$state['name'].'</option>';
				}
			?>
			</select>
			</span>
				<div class="has-danger form-control-feedback errState"></div>
			</div>
			
			<div class="col-sm-12 col-md-3">
			<span class="append_city">
			<select class="custom-select2 form-control city" name="city" id="city">
			<option value="">City</option>
			<?php if(isset($getCity) && count($getCity)>0 && !empty($getCity))
				foreach($getCity as $k=>$city){
					$sel = ($city['name']==$driver->city)?'selected':'';
					echo '<option value="'.$city['name'].'^'.$city['id'].'" data-id="'.$city['id'].'" '.$sel .'>'.$city['name'].'</option>';
				}
			?>
			</select>
			</span>
				<div class="has-danger form-control-feedback errCity"></div>
			</div>
			
			<div class="col-sm-12 col-md-3 pr-0">
			<input type="text" name="pincode" id="pincode"  class="form-control" placeholder="Pincode" value="<?php echo isset($driver->pincode)?set_value('pincode',html_entity_decode($driver->pincode)):set_value('pincode'); ?>">
				<div class="has-danger form-control-feedback errPincode"></div>
			</div>
		
			<div class="col-sm-12 col-md-12  mt-15 pr-0">
			<textarea id="address" name="address" class=" form-control border-radius-0" placeholder="Enter Address ..."><?php echo isset($driver->address)?set_value('address',html_entity_decode($driver->address)):set_value('address'); ?></textarea>
			<div class="has-danger form-control-feedback errAddress"></div>
		</div>
		
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Username</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="username" id="username"  class="form-control" placeholder="Username(Email)" value="<?php echo isset($driver->email)?set_value('email',html_entity_decode($driver->email)):set_value('email'); ?>">
				<div class="has-danger form-control-feedback errusername"></div>
		</div>
	</div>
	<?php 
	if(empty($driver->hash)){?>
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Password</label>
			<div class="col-sm-12 col-md-10">
				<input type="password" name="password" id="password"  class="form-control" placeholder="Password" value="">
				<div class="has-danger form-control-feedback err_password"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Confirm New Password</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="c_password" id="c_password"  class="form-control" placeholder="Confirm Password" value="">
				<div class="has-danger form-control-feedback err_c_password"></div>
			</div>
		</div>
		<?php 
	} ?>	
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Status</label>
		<div class="col-sm-12 col-md-10">
			<select class="form-control" name="status" id="status">
				<option value="1" <?php echo (isset($driver->Status) && $driver->Status==1)?'seleted':''; ?>>Active</option>
				<option value="0" <?php echo (isset($driver->Status) && $driver->Status==0)?'selected':''; ?>>Inactive</option>
			</select>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Online Status</label>
		<div class="col-sm-12 col-md-10">
			<select class="form-control" name="is_online" id="is_online">
				<option value="1" <?php echo (isset($driver->is_online) && $driver->is_online==1)?'seleted':''; ?>>Online</option>
				<option value="0" <?php echo (isset($driver->is_online) && $driver->is_online==0)?'selected':''; ?>>Offline</option>
			</select>
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

