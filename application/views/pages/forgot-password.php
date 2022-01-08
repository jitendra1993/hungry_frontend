<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>

<div class="content_wrapper">

    <section class="sectionpanel loginRegister">
        <div class="container">
            <div class="row">

                <div class="logRegSection formbox">
                    <div class="ht3 b4 heading-font text-center">Forgot Password</div>
                    <?php  
					if(validation_errors()){ ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <?php 
						echo validation_errors(); 
						?>
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
						
					$attributes = array('class' => 'signup-form', 'id' => 'forgot-password'); 
					echo form_open('auth/forgot-password',$attributes);?>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Email"
                            value="<?php echo set_value('email'); ?>" onblur="validate_email(this.value)">
                        <span class="has-danger err_email"></span>
                    </div>

                    <span class="otpToggle" style="display:none">
                        <div class="form-group mb-15 ">
                            <input type="text" name="otp" class="form-control" id="otp" placeholder="OTP" value=""
                                onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
                            <span class="has-danger err_otp"></span>
                        </div>

                        <div class="form-group mb-15">
                            <input type="password" name="new_password" class="form-control" id="new_password"
                                placeholder="New Password" value="<?php echo set_value('new_password'); ?>">
                            <span class="has-danger err_new_password"></span>
                        </div>

                        <div class="form-group mb-15">
                            <input type="text" name="c_new_password" class="form-control" id="c_new_password"
                                placeholder="Confirm New Password" value="<?php echo set_value('c_new_password'); ?>">
                            <span class="has-danger err_c_new_password"></span>
                        </div>

                        <button type="button"
                            class="btn btn-blue btn-lg submitbtn text-uppercase disable forgotpasswordOTP">Verify
                            OTP</button>
                    </span>

                    <div class="form-group">
                        <input type="button" class="btn btn-blue btn-lg submitbtn disable forgotpassword"
                            value="Forgot" />
                    </div>
                    <?php echo form_close(); ?>
                </div><!-- end of logRegSection -->
            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section><!-- end of loginRegister -->
</div><!-- end of content_wrapper -->