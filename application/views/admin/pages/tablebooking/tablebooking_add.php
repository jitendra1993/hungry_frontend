<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Table Booking</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="="<?=base_url('admin/dashboard')?>"">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Table Booking</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/tablebooking/setting/view')?>" class="btn btn-primary btn-sm" > Setting</a>&nbsp;&nbsp;
			<a href="<?=base_url('admin/tablebooking/view')?>" class="btn btn-primary btn-sm" >View Booking</a>
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
		  if($this->session->flashdata('error_msg')){ ?>
		  <div class="alert alert-danger alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('error_msg'));?> 
		  </div> 
		  <?php
		  }
		  
		$attributes = array('class' => 'add_table_booking', 'id' => 'add_table_booking'); 
		  echo form_open(isset($tablebooking->id)?'admin/tablebooking/edit/'.$tablebooking->id:'admin/tablebooking/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($tablebooking->id)?set_value('name',$tablebooking->id):set_value('id'); ?>">
		
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Number Of Guests</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="number_guest" id="number_guest"  class="form-control" placeholder="Number Of Guests" value="<?php echo isset($tablebooking->number_guest)?set_value('number_guest',$tablebooking->number_guest):set_value('number_guest'); ?>" onkeypress="return isNumber(event)">
				<div class="has-danger form-control-feedback err_number_guest"></div>
			</div>
		</div>
			
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Date/Time Of Booking</label>
			
			<div class="col-sm-12 col-md-5">
				<input type="text" name="booking_date" id="booking_date"  class="form-control datepickercurrent" placeholder="Date Of Booking" value="<?php echo isset($tablebooking->booking_date)?set_value('booking_date',date("d-M-y", strtotime($tablebooking->booking_date))):set_value('booking_date'); ?>" readonly>
				<div class="has-danger form-control-feedback err_booking_date"></div>
			</div>
			
			<div class="col-sm-12 col-md-4">
				<input type="text" name="booking_time" id="booking_time"  class="form-control time-picker" placeholder="Time" value="<?php echo isset($tablebooking->booking_time)?set_value('booking_time',$tablebooking->booking_time):set_value('booking_time'); ?>" readonly>
				<div class="has-danger form-control-feedback err_booking_time"></div>
			</div>
			
		</div>
		
		<div class="title"><h5 class="text-blue h5 mb-20">Contact Information</h5></div>
		
			<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Name</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="name" id="name"  class="form-control" placeholder="Name" value="<?php echo isset($tablebooking->name)?set_value('name',$tablebooking->name):set_value('name'); ?>">
				<div class="has-danger form-control-feedback err_name"></div>
			</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Email</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="email" id="email"  class="form-control" placeholder="Email" value="<?php echo isset($tablebooking->email)?set_value('email',$tablebooking->email):set_value('email'); ?>">
					<div class="has-danger form-control-feedback err_email"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Mobile</label>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="mobile" id="mobile"  class="form-control" placeholder="Mobile" value="<?php echo isset($tablebooking->mobile)?set_value('mobile',$tablebooking->mobile):set_value('mobile'); ?>" onkeypress="return isNumber(event)" minlength="7" maxlength="15">
					<div class="has-danger form-control-feedback err_mobile"></div>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Your Instructions</label>
				<div class="col-sm-12 col-md-9">
				<textarea name="booking_notes" id="booking_notes" class="form-control"  placeholder="Your Instructions" rows=2 style="height: auto;"><?php echo isset($tablebooking->booking_notes)?set_value('booking_notes',$tablebooking->booking_notes):set_value('booking_notes'); ?></textarea>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Message to client</label>
				<div class="col-sm-12 col-md-9">
				<textarea name="email_message" id="email_message" class="form-control"  placeholder="Message to client" rows=2 style="height: auto;"><?php echo isset($tablebooking->email_message)?set_value('email_message',$tablebooking->email_message):set_value('email_message'); ?></textarea>
				</div>
			</div>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
				<div class="col-sm-12 col-md-9" >
				<select class="form-control" name="status" id="status" >
					<option value="0" <?php echo (isset($tablebooking->status) && $tablebooking->status==0)?'selected':''; ?>>Pending</option> 
					<option value="1" <?php echo (isset($tablebooking->status) && $tablebooking->status==1)?'selected':''; ?>>Approved</option> 
					<option value="3" <?php echo (isset($tablebooking->status) && $tablebooking->status==3)?'selected':''; ?>>Denied</option> 
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

