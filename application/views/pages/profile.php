<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>

<div class="content_wrapper">

    <section class="sectionpanel loginRegister">
        <div class="container">
            <div class="row">

                <div class=" formbox col-md-6 bg-white">
                    <div class="ht3 b4 heading-font text-center">Profile Details</div>
					<?php  
					if(validation_errors()){ ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<?php 
							echo !empty(form_error('name'))?form_error('name', '<span class="row">', '</span>'):''; 
							echo !empty(form_error('email'))?form_error('email', '<span class="row">', '</span>'):''; 
							?>
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
							<?=($this->session->flashdata('error_msg'));?> 
						</div> 
						<?php
					}?>
					<div class="profile row">
						<div class="col-md-6 mb-2"><b>Name</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['name']; ?></div>
						<div class="col-md-6 mb-2"><b>Email</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['email']; ?></div>
					
						<div class="col-md-6 mb-2"><b>Phone Number</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['mobile']; ?></div>
						
						<div class="col-md-6 mb-2"><b>Status</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['status']==0?'Inactive':'Active'; ?></div>

						<div class="col-md-6 mb-2"><b>Mail Status</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['mail_status']==0?'Not verify':'Verified'; ?></div>

						<div class="col-md-6 mb-2"><b>Mobile Status</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['mobile_status']==0?'Not verify':'Verified'; ?></div>
					
						<div class="col-md-6 mb-2"><b>Role</b></div>
						<div class="col-md-6 mb-2"><?php echo $profile['role_master_tbl_id']==3?'Driver':'User'; ?></div>
						
						<div class="col-md-6 mb-2"><b>Profile Created On</b></div>
						<div class="col-md-6 mb-2"><?php echo date('F j, Y, g:i a',$profile['added_date_timestamp']/1000); ?></div>
						
						<div class="col-md-6 mb-2"><b>Profile Updated On</b></div>
						<div class="col-md-6 mb-2"><?php echo date('F j, Y, g:i a',$profile['updated_date_timestamp']/1000); ?></div>
						
					</div>

					<div class="billing_details edit-profile " style="display:none">
						<div class="col-lg-12">
							<?php 
							$attributes = array('class' => 'row contact_form', 'id' => 'updateProfile'); 
							echo form_open('user/profile',$attributes);?>

							<div class="form-group">
								<label>Name</label>
								<input type="text" class="form-control" id="name" name="name"  value="<?php echo isset($profile['name'])?set_value('name',$profile['name']):''; ?>"/>
								<span class="error has-danger err_name"></span>
							</div>

							<div class="form-group">
								<label>Email</label>
								<input type="text" class="form-control" id="email" name="email" value="<?php echo isset($profile['email'])?set_value('email',$profile['email']):''; ?>" onblur="javascript:return validate_email(this.value)" />
								<span class="error has-danger err_email"></span>
							</div>

							<div class="form-group">
								<label>Mobile</label>
								<input type="text" class="form-control" id="phoneNumber" name="phoneNumber"  onkeyup="this.value = this.value.replace(/[^0-9]/g, '')" value="<?php echo isset($profile['mobile'])?set_value('mobile',$profile['mobile']):''; ?>" onblur="javascript:return validate_mobile1(this.value)"  />
								<span class="error has-danger err_phone"></span>
							</div>

							<div class="form-group">
								<input type="button" name="" class="btn btn-blue btn-lg submitbtn disable updateProfile" value="Update" />
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
					<div class="col-md-12 row"><button type="button" class="btn btn-blue btn-lg submitbtn changeScreen">Edit Profile</button></div>
                </div>
				
				<div class=" formbox col-md-6 bg-white">
                    <div class="ht3 b4 heading-font text-center">Address Details</div>
					
					<?php 
					// echo '<pre>';
					// print_r($address);
					// echo '</pre>';
					if(!empty($address) && is_array($address) && count($address)>0){ 

						foreach($address as $data){
							?>
							<div class="row col-md-12 p-3 pt-0 border-bottom remove_<?php echo $data['hash'];?>">
								<div class="col-md-6 mb-2"><b>Name</b></div>
								<div class="col-md-6 mb-2 name_<?php echo $data['hash'];?>" ><?php echo $data['name']; ?></div>
							
								<div class="col-md-6 mb-2"><b>Address Type</b></div>
								<div class="col-md-6 mb-2 add_type_<?php echo $data['hash'];?>"><?php echo ($data['addressType']==1)?'Home':'Office'; ?></div>
								
								<div class="col-md-6 mb-2"><b>Phone</b></div>
								<div class="col-md-6 mb-2 phone_<?php echo $data['hash'];?>"><?php echo $data['phoneNumber']; ?></div>
								
								<div class="col-md-6 mb-2"><b>Address Line 1</b></div>
								<div class="col-md-6 mb-2 add_line_1_<?php echo $data['hash'];?>"><?php echo $data['addressLine1']; ?></div>
								
								<div class="col-md-6 mb-2"><b>Address Line 2</b></div>
								<div class="col-md-6 mb-2 add_line_2_<?php echo $data['hash'];?>"><?php echo $data['addressLine2']; ?></div>
								
								<div class="col-md-6 mb-2"><b>Pincode</b></div>
								<div class="col-md-6 mb-2 pincode_<?php echo $data['hash'];?>"><?php echo $data['pincode']; ?></div>
								
								<div class="col-md-6 mb-2"><b>Address Created On</b></div>
								<div class="col-md-6 mb-2"><?php echo date('F j, Y, g:i a',$data['added_date_timestamp']/1000); ?></div>
							
								<div class="col-md-6 mb-2"><b>Address Updated On</b></div>
								<div class="col-md-6 mb-2 "><?php echo date('F j, Y, g:i a',$data['updated_date_timestamp']/1000); ?></div>
						
								<div class="col-md-6 mb-2"><a href="javascript:void(0)"id="<?php echo $data['hash'];?>" class="btn-warning btn-sm btn-block text-center col-md-6 addUpdateAddress">Edit</a></div>
								<div class="col-md-6 mb-2"><a href="javascript:void(0)"id="<?php echo $data['hash'];?>" class="btn-warning btn-sm btn-block text-center col-md-6 deleteAddress">Delete</a></div>
							
							</div>
							<?php
						}
						echo '<br><p> <a href="javascript:void(0)" id="" class="addUpdateAddress">click here</a> to add new address</p>'; ?>
						<?php 
					}else{ 
						echo '<p>No Address found <a href="javascript:void(0)" id="" class="addUpdateAddress">click here</a> to add address</p>';
					} ?>

					
                </div>


            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section><!-- end of loginRegister -->
</div><!-- end of content_wrapper -->
