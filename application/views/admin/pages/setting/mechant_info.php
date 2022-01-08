<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Store Info</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard');?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Store Info</li>
				</ol>
			</nav>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php if(isset($error) && !empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
				 <?php echo $error; ?>
			</div>

          <?php }
		  
		   if(validation_errors()){ ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <?php //echo validation_errors(); ?>
			  <?php echo form_error('merchant_name', '<span>', '</span>'); ?>
			<?php echo form_error('merchant_phone', '<span>', '</span>'); ?>
			<?php echo form_error('contact_name', '<span>', '</span>'); ?>
			<?php echo form_error('contact_phone', '<span>', '</span>'); ?>
			<?php echo form_error('contact_email', '<span>', '</span>'); ?>
			<?php echo form_error('country', '<span>', '</span>'); ?>
			<?php echo form_error('state', '<span>', '</span>'); ?>
			<?php echo form_error('city', '<span>', '</span>'); ?>
			<?php echo form_error('pincode', '<span>', '</span>'); ?>
			<?php echo form_error('address', '<span>', '</span>'); ?>
            </div>
          <?php }
		  if($this->session->flashdata('msg_success')){ ?>
		  <div class="alert alert-success alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('msg_success'))?> 
		  </div>
		  <?php } 
		  
		  // echo '<pre>';
		  // print_r($merchant_info);
		  // echo '</pre>';
		  
		$attributes = array('class' => 'merchant_info', 'id' => 'merchant_info'); 
		  echo form_open_multipart(isset($merchant_info->user_hash_id)?'admin/setting/store-info/':'admin/setting/store-info',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($merchant_info->user_hash_id)?set_value('name',$merchant_info->user_hash_id):set_value('id'); ?>">
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Merchant Name</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="merchant_name" id="merchant_name"  class="form-control" placeholder="Merchant Name" value="<?php echo isset($merchant_info->merchant_name)?set_value('merchant_name',html_entity_decode($merchant_info->merchant_name)):set_value('merchant_name'); ?>">
					<div class="has-danger form-control-feedback errMerchantName"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Merchant Phone</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="merchant_phone" id="merchant_phone"  class="form-control" placeholder="Merchant Phone" value="<?php echo isset($merchant_info->merchant_phone)?set_value('merchant_phone',html_entity_decode($merchant_info->merchant_phone)):set_value('merchant_phone'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback errMerchantPhone"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Contact Name</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="contact_name" id="contact_name"  class="form-control" placeholder="Contact Name" value="<?php echo isset($merchant_info->contact_name)?set_value('contact_name',html_entity_decode($merchant_info->contact_name)):set_value('contact_name'); ?>">
					<div class="has-danger form-control-feedback errContactName"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Contact Phone</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="contact_phone" id="contact_phone"  class="form-control" placeholder="Contact Phone" value="<?php echo isset($merchant_info->contact_phone)?set_value('contact_phone',html_entity_decode($merchant_info->contact_phone)):set_value('contact_phone'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback errContactPhone"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Contact Email</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="contact_email" id="contact_email"  class="form-control" placeholder="Contact Email" value="<?php echo isset($merchant_info->contact_email)?set_value('contact_email',html_entity_decode($merchant_info->contact_email)):set_value('contact_email'); ?>">
					<div class="has-danger form-control-feedback errContactEmail"></div>
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
						$sel = ($country->name==$merchant_info->country)?'selected':'';
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
						$sel = ($state['name']==$merchant_info->state)?'selected':'';
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
						$sel = ($city['name']==$merchant_info->city)?'selected':'';
						echo '<option value="'.$city['name'].'^'.$city['id'].'" data-id="'.$city['id'].'" '.$sel .'>'.$city['name'].'</option>';
					}
				?>
				</select>
				</span>
					<div class="has-danger form-control-feedback errCity"></div>
				</div>
				
				<div class="col-sm-12 col-md-3 pr-0">
				<input type="text" name="pincode" id="pincode"  class="form-control" placeholder="Pincode" value="<?php echo isset($merchant_info->pincode)?set_value('pincode',html_entity_decode($merchant_info->pincode)):set_value('pincode'); ?>">
					<div class="has-danger form-control-feedback errPincode"></div>
				</div>
			
				<div class="col-sm-12 col-md-12  mt-15 pr-0">
				<textarea id="address" name="address" class=" form-control border-radius-0" placeholder="Enter Address ..."><?php echo isset($merchant_info->address)?set_value('address',html_entity_decode($merchant_info->address)):set_value('address'); ?></textarea>
				<div class="has-danger form-control-feedback errAddress"></div>
			</div>
			
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">About Store</label>
			<div class="col-sm-12 col-md-10">
				<textarea id="about" name="about" class=" form-control border-radius-0" placeholder="About Store ..."><?php echo isset($merchant_info->about)?set_value('about',html_entity_decode($merchant_info->about)):set_value('about'); ?></textarea>
			</div>
		</div>
						
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Featured image</label>
			<div class="col-md-10 col-sm-12 row pr-0">
				<div class="col-sm-12 col-md-12 pr-0">
					<input type="hidden" value="<?php echo isset($merchant_info->logo)?set_value('logo',$merchant_info->logo):set_value('logo'); ?>" name="old_logo" id="old_logo">
					<input type="hidden" value="0" name="logo_status" id="logo_status">
					<input type="file" name="logo" id="logo" class="form-control-file form-control height-auto" accept="image/*" onchange="ValidateLogoUpload()">
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group logo_append">
						  <?php if(isset($merchant_info->logo) && !empty($merchant_info->logo)) { ?>
						  <span class="pip img-thumbnail">
						<img class="imageThumb" width="300" src="<?=base_url('uploads/client_logo').'/'.$merchant_info->logo; ?>" title="No image"/>
						<br/><span class="removeExistLogo btn-primary" id="<?php echo $merchant_info->user_hash_id; ?>">Remove</span>
							</span>
						  <?php } ?>
					</div>
				</div>
			</div>
		</div>
		
	
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Mail logo URL</label>
			<div class="col-sm-12 col-md-10">
				<input type="text" name="mail_logo_url" id="mail_logo_url"  class="form-control" placeholder="Mail logo URL" value="<?php echo isset($merchant_info->mail_logo_url)?set_value('mail_logo_url',$merchant_info->mail_logo_url):set_value('mail_logo_url'); ?>">
			</div>
		</div>
		
		<?php 
		$social_media = isset($merchant_info->social_media)?$merchant_info->social_media:[];
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
			
		<div class="row">
			<div class=" col-sm-12">
				<div class="input-group mb-0">
					<button type="submit" class="btn btn-primary btn-sm btn-block">SUBMIT</button>
				</div>
			</div>
		</div>
		
	<?php echo form_close(); ?>
	
</div>

