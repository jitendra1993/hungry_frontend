
<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>

<div class="content_wrapper">
    <section class="sectionpanel loginRegister">
        <div class="container">
            <div class="row">

                <div class="logRegSection formbox">
                    <div class="ht3 b4 heading-font text-center">Create account</div>
					<span class="errorMsg"></span>
					<?php  
					if(validation_errors()){ ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<?php 
							echo !empty(form_error('full_name'))?form_error('full_name', '<span class="row">', '</span>'):''; 
							echo !empty(form_error('email'))?form_error('email', '<span class="row">', '</span>'):''; 
							echo !empty(form_error('phone'))?form_error('phone', '<span class="row">', '</span>'):''; 
							echo !empty(form_error('password'))?form_error('password', '<span class="row">', '</span>'):''; 
							echo !empty(form_error('cpassword'))?form_error('cpassword', '<span class="row">', '</span>'):''; 
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
					}
                    
					$attributes = array('class' => 'registration_form signup-form', 'id' => 'registration'); 
					echo form_open('auth/registration',$attributes);?>

					<div class="form-group">
						<label>Name</label>
						<input type="text" name="full_name" class="form-control" id="full_name" placeholder="Full Name" value="<?php echo set_value('full_name'); ?>">
						<span class="error has-danger err_full_name"></span>
					</div>

					<div class="form-group">
						<label>Email</label>
						<input type="text" name="email" class="form-control" id="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
						<span class="error has-danger err_email"></span>
					</div>

					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" name="phone" class="form-control" id="phone" placeholder="Mobile" value="<?php echo set_value('phone'); ?>" onkeypress="return isNumber(event)">
						<span class="error has-danger err_phone"></span>
					</div>

					<div class="form-group">
						<label>Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password" value="<?php echo set_value('password'); ?>">
						<span class="error has-danger err_password"></span>
					</div>

					<div class="form-group">
						<label>Confirm Password</label>
						<input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="Confirm Password" value="<?php echo set_value('cpassword'); ?>">
						<span class="error has-danger err_cpassword"></span>
					</div>

					<div class="form-group registration_toggle">
						<input type="button" name="" class="btn btn-blue btn-lg submitbtn disable registration_button" value="Sign up" />
					</div>


					<div class="registration_otp_toggle" style="display:none">
						<h5 class="card-title text-center">Verify Email</h5>
						<p class="card-title text-center"><?php echo msg['registration_otp_msg']?></p>
						<span class="errorMsg"></span>
						<span class="successMsg"></span>
						<div class="form-group mb-15 " >
							<input type="text" name="registration_otp" class="form-control" id="registration_otp" placeholder="OTP" value="" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')" >
						   <span class="has-danger err_otp"></span>
						   <span class="resendVerifyOtp"><a href="javascript:void(0)" class="resenotpOnreg">Resen OTP</a></span>
						</div>
						<button type="button" class="btn btn-lg btn-primary btn-block text-uppercase disable submitRegOTP" >Verify OTP</button>
					</div>

					<p class="text-center">Already have an account? <a href="<?=base_url('auth/login')?>">Log in</a></p>
					<p class="text-center"><small>By creating an Account you agree to our 
						<a href="<?=base_url('auth/terms')?>">Terms &amp;Conditions</a>, 
						<a href="<?=base_url('auth/policy')?>"> Policy</a> being sent marketing communications. You can change your preferences in Contact Preferences</small>
					</p>
                    <?php echo form_close(); ?>	
                </div>
            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section><!-- end of loginRegister -->
</div><!-- end of content_wrapper -->

