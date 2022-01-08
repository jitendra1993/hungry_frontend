<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Table Booking setting</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Table Booking setting</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/tablebooking/view')?>" class="btn btn-primary btn-sm" > View Bookings</a>&nbsp;&nbsp;
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php

 if(isset($error) && !empty($error)){ ?>
	<div class="alert alert-danger" role="alert">
		 <?php echo $error; ?>
	</div>

  <?php }
		  
	   if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
		  <?php //echo validation_errors(); ?>
		  <?php echo form_error('name', '<span>', '</span>'); ?>
		<?php echo form_error('image', '<span>', '</span>'); ?>
		</div>
	  <?php }
	  if($this->session->flashdata('msg_success')){ ?>
	  <div class="alert alert-success alert-dismissible"> 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?=($this->session->flashdata('msg_success'))?> 
	  </div>
	  <?php } 
		  
		$attributes = array('class' => 'table_booking_setting', 'id' => 'table_booking_setting'); 
		echo form_open(isset($tablebooking_setting->user_hash_id)?'admin/tablebooking/setting/edit/'.$tablebooking_setting->user_hash_id:'admin/tablebooking/setting/edit/'.$this->uri->segment(5),$attributes);
		  ?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($tablebooking_setting->user_hash_id)?set_value('name',$tablebooking_setting->user_hash_id):$this->uri->segment(5); ?>">
		
		<div class="title"><h5 class="text-blue h5 mb-20">Enter The Maximum Number Of Tables You Want To Make Available For Your App/Website (Not How Many Guests</h5></div>
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Monday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_monday" id="max_table_monday"  class="form-control" placeholder="Max Table on Monday" value="<?php echo isset($tablebooking_setting->max_table_monday)?set_value('max_table_monday',$tablebooking_setting->max_table_monday):set_value('max_table_monday'); ?>" onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_monday"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Tuesday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_tuesday" id="max_table_tuesday"  class="form-control" placeholder="Max Table on Tuesday" value="<?php echo isset($tablebooking_setting->max_table_tuesday)?set_value('max_table_monday',$tablebooking_setting->max_table_tuesday):set_value('max_table_tuesday'); ?>" onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_tuesday"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Wednesday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_wednesday" id="max_table_wednesday"  class="form-control" placeholder="Max Table on Wednesday" value="<?php echo isset($tablebooking_setting->max_table_wednesday)?set_value('max_table_wednesday',$tablebooking_setting->max_table_wednesday):set_value('max_table_wednesday'); ?>" onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_wednesday"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Thursday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_thursday" id="max_table_thursday"  class="form-control" placeholder="Max Table on Thursday" value="<?php echo isset($tablebooking_setting->max_table_thursday)?set_value('max_table_thursday',$tablebooking_setting->max_table_thursday):set_value('max_table_thursday'); ?>" onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_thursday"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Friday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_friday" id="max_table_friday"  class="form-control" placeholder="Max Table on Friday" value="<?php echo isset($tablebooking_setting->max_table_friday)?set_value('max_table_friday',$tablebooking_setting->max_table_friday):set_value('max_table_friday'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_friday"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Saturday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_saturday" id="max_table_saturday"  class="form-control" placeholder="Max Table on Saturday" value="<?php echo isset($tablebooking_setting->max_table_saturday)?set_value('max_table_saturday',$tablebooking_setting->max_table_saturday):set_value('max_table_saturday'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_saturday"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Sunday</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="max_table_sunday" id="max_table_sunday"  class="form-control" placeholder="Max Table on Sunday" value="<?php echo isset($tablebooking_setting->max_table_sunday)?set_value('max_table_sunday',$tablebooking_setting->max_table_sunday):set_value('max_table_sunday'); ?>"  onkeypress="return isNumber(event)">
					<div class="has-danger form-control-feedback max_table_sunday"></div>
				</div>
			</div>
		
			
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Table Booking Enable?</label>
			<div class="col-sm-12 col-md-9 col-form-label">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="table_booking_status" name="table_booking_status" value="1" <?php echo (isset($tablebooking_setting->table_booking_status) && $tablebooking_setting->table_booking_status==1)?'checked':''; ?>>
					<label class="custom-control-label" for="table_booking_status"></label>
				</div>
			</div>
		</div>
		
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Accept booking same day?</label>
			<div class="col-sm-12 col-md-9 col-form-label">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="accept_booking_sameday" name="accept_booking_sameday" value="1" <?php echo (isset($tablebooking_setting->accept_booking_sameday) && $tablebooking_setting->accept_booking_sameday==1)?'checked':''; ?>>
					<label class="custom-control-label" for="accept_booking_sameday"></label>
				</div>
			</div>
		</div>
			
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Fully booked message</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="fully_booked_msg" id="fully_booked_msg"  class="form-control" placeholder="Fully booked message" value="<?php echo isset($tablebooking_setting->fully_booked_msg)?set_value('fully_booked_msg',$tablebooking_setting->fully_booked_msg):set_value('fully_booked_msg'); ?>">
				<div class="has-danger form-control-feedback fully_booked_msg"></div>
			</div>
		</div>	
			
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Alert Notification?</label>
			<div class="col-sm-12 col-md-9 col-form-label">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="merchant_booking_alert" name="merchant_booking_alert" value="1" <?php echo (isset($tablebooking_setting->merchant_booking_alert) && $tablebooking_setting->merchant_booking_alert==1)?'checked':''; ?>>
					<label class="custom-control-label" for="merchant_booking_alert"></label>
				</div>
			</div>
		</div>	
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Booking Receiver Email</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="booking_receiver_mail" id="booking_receiver_mail"  class="form-control" placeholder="Booking Receiver Email" value="<?php echo isset($tablebooking_setting->booking_receiver_mail)?set_value('booking_receiver_mail',$tablebooking_setting->booking_receiver_mail):set_value('booking_receiver_mail'); ?>">
				<div class="has-danger form-control-feedback booking_receiver_mail"></div>
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

