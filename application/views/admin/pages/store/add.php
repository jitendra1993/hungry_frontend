<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Client Info</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Store Info</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
		
			<a href="<?=base_url('admin/store/view')?>" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i>View all</a>
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
			<?php echo form_error('merchant_name', '<span>', '</span><br>'); ?>
			<?php echo form_error('merchant_phone', '<span>', '</span><br>'); ?>
			<?php echo form_error('contact_name', '<span>', '</span><br>'); ?>
			<?php echo form_error('contact_phone', '<span>', '</span><br>'); ?>
			<?php echo form_error('contact_email', '<span>', '</span><br>'); ?>
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
	  $clientmail = (isset($client) && !empty($client->email))?$client->email:'';
	  $clienStatus = (isset($client) && !empty($client->status))?$client->status:1;
	  $client = (isset($client) && !empty($client->info_docs[0]))?$client->info_docs[0]:[];
		  
	$attributes = array('class' => 'add_client', 'id' => 'add_client'); 
	echo form_open_multipart(isset($client->user_hash_id)?'admin/store/edit/'.$client->user_hash_id:'admin/store/add',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($client->user_hash_id)?set_value('name',$client->user_hash_id):set_value('id'); ?>">
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-2 col-form-label">Merchant Name</label>
		<div class="col-sm-12 col-md-10">
			<input type="text" name="merchant_name" id="merchant_name"  class="form-control" placeholder="Merchant Name" value="<?php echo isset($client->merchant_name)?set_value('merchant_name',html_entity_decode($client->merchant_name)):set_value('merchant_name'); ?>">
			<div class="has-danger form-control-feedback errMerchantName"></div>
		</div>
	</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Merchant Phone</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="merchant_phone" id="merchant_phone"  class="form-control" placeholder="Merchant Phone" value="<?php echo isset($client->merchant_phone)?set_value('merchant_phone',html_entity_decode($client->merchant_phone)):set_value('merchant_phone'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback errMerchantPhone"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Contact Name</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="contact_name" id="contact_name"  class="form-control" placeholder="Contact Name" value="<?php echo isset($client->contact_name)?set_value('contact_name',html_entity_decode($client->contact_name)):set_value('contact_name'); ?>">
					<div class="has-danger form-control-feedback errContactName"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Contact Phone</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="contact_phone" id="contact_phone"  class="form-control" placeholder="Contact Phone" value="<?php echo isset($client->contact_phone)?set_value('contact_phone',html_entity_decode($client->contact_phone)):set_value('contact_phone'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback errContactPhone"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Contact Email</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="contact_email" id="contact_email"  class="form-control" placeholder="Contact Email" value="<?php echo isset($client->contact_email)?set_value('contact_email',html_entity_decode($client->contact_email)):set_value('contact_email'); ?>">
					<div class="has-danger form-control-feedback errContactEmail"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Store Category</label>
			<div class="col-sm-12 col-md-10">
				<select name="store_category[]" id="store_category" class="form-control multiple selectpicker " multiple>
					<?php
					foreach($getStoreCategory as $k=>$category){
						$sel = (isset($client->store_category) && in_array($category->id,(array)$client->store_category))?'selected':'';
						echo '<option value="'.$category->id.'" '.$sel.'>'.$category->name.'</option>';
					}
					?>
				</select>
				<div class="has-danger form-control-feedback errStoreCategory"></div>
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
						$sel = (isset($client->country) && $country->name==$client->country)?'selected':'';
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
						$sel = ($state['name']==$client->state)?'selected':'';
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
						$sel = ($city['name']==$client->city)?'selected':'';
						echo '<option value="'.$city['name'].'^'.$city['id'].'" data-id="'.$city['id'].'" '.$sel .'>'.$city['name'].'</option>';
					}
				?>
				</select>
				</span>
					<div class="has-danger form-control-feedback errCity"></div>
				</div>
				
				<div class="col-sm-12 col-md-3 pr-0">
				<input type="text" name="pincode" id="pincode"  class="form-control" placeholder="Pincode" value="<?php echo isset($client->pincode)?set_value('pincode',html_entity_decode($client->pincode)):set_value('pincode'); ?>">
					<div class="has-danger form-control-feedback errPincode"></div>
				</div>
			
				<div class="col-sm-12 col-md-12  mt-15 pr-0">
				<textarea id="address" name="address" class=" form-control border-radius-0" placeholder="Enter Address ..."><?php echo isset($client->address)?set_value('address',html_entity_decode($client->address)):set_value('address'); ?></textarea>
				<div class="has-danger form-control-feedback errAddress"></div>
			</div>
			
			</div>
		</div>
		
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">About Store</label>
			<div class="col-sm-12 col-md-10">
				<textarea id="about" name="about" class=" form-control border-radius-0" placeholder="About Store ..."><?php echo isset($client->about)?set_value('about',html_entity_decode($client->about)):set_value('about'); ?></textarea>
			</div>
		</div>
						
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Logo</label>
			<div class="col-md-10 col-sm-12 row pr-0">
				<div class="col-sm-12 col-md-12 pr-0">
					<input type="hidden" value="<?php echo isset($client->logo)?set_value('logo',$client->logo):set_value('logo'); ?>" name="old_logo" id="old_logo">
					<input type="hidden" value="0" name="logo_status" id="logo_status">
					<input type="file" name="logo" id="logo" class="form-control-file form-control height-auto" accept="image/*" onchange="ValidateLogoUpload()">
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group logo_append">
						  <?php if(isset($client->logo) && !empty($client->logo)) { ?>
						  <span class="pip img-thumbnail">
						<img class="imageThumb" width="300" src="<?=base_url('uploads/client_logo').'/'.$client->logo; ?>" title="No image"/>
						<br/><span class="removeExistLogo btn-primary" id="<?php echo $client->user_hash_id; ?>">Remove</span>
							</span>
						  <?php } ?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Mail logo URL</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="mail_logo_url" id="mail_logo_url"  class="form-control" placeholder="Mail logo URL" value="<?php echo isset($client->mail_logo_url)?set_value('mail_logo_url',$client->mail_logo_url):set_value('mail_logo_url'); ?>">
			</div>
		</div>
		
		<?php 
		$social_media = isset($client->social_media)?$client->social_media:[];
		//print_r($social_media);
		?>
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Facebook url</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="facebook" id="facebook"  class="form-control" placeholder="Facebook url" value="<?php echo isset($social_media->facebook)?set_value('facebook',$social_media->facebook):set_value('facebook'); ?>">
			</div>
		</div>	
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Twitter url</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="twitter" id="twitter"  class="form-control" placeholder="Twitter url" value="<?php echo isset($social_media->twitter)?set_value('twitter',$social_media->twitter):set_value('twitter'); ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Youtube url</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="youtube" id="youtube"  class="form-control" placeholder="Youtube url" value="<?php echo isset($social_media->youtube)?set_value('youtube',$social_media->youtube):set_value('youtube'); ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Instagram url</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="instagram" id="instagram"  class="form-control" placeholder="Instagram url" value="<?php echo isset($social_media->instagram)?set_value('instagram',$social_media->instagram):set_value('instagram'); ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Linkedin url</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="linkedin" id="linkedin"  class="form-control" placeholder="Linkedin url" value="<?php echo isset($social_media->linkedin)?set_value('linkedin',$social_media->linkedin):set_value('linkedin'); ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Username</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="username" id="username"  class="form-control" placeholder="Username(Email)" value="<?php echo isset($clientmail)?set_value('username',html_entity_decode($clientmail)):set_value('username'); ?>">
					<div class="has-danger form-control-feedback errusername"></div>
			</div>
		</div>
		<?php 
		if(empty($client->user_hash_id)){?>
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
					<option value="1" <?php echo (isset($clienStatus) && $clienStatus==1)?'selected':''; ?>>Active</option>
					<option value="0" <?php echo (isset($clienStatus) && $clienStatus==0)?'selected':''; ?>>Inactive</option>
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

