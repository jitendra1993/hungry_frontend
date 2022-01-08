<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>

<div class="content_wrapper">

    <section class="sectionpanel loginRegister">
        <div class="container">
            <div class="row">

                <div class="logRegSection formbox">
                    <div class="ht3 b4 heading-font text-center">Login</div>
					<?php  
					if(validation_errors() && (!empty(form_error('login_email')) || !empty(form_error('login_password')))){ ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<?php 
							echo !empty(form_error('login_email'))?form_error('login_email', '<span class="row">', '</span>'):''; 
							echo !empty(form_error('login_password'))?form_error('login_password', '<span class="row">', '</span>'):''; 
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
                    
					$attributes = array('class' => 'signup-form', 'id' => 'login'); 
					echo form_open('auth/login',$attributes);?>

					<div class="form-group">
						<label>Email</label>
						<input type="text" name="login_email" class="form-control" id="login_email" placeholder="Email" value="<?php echo set_value('login_email'); ?>">
						<span class="has-danger err_login_email"></span>
					</div>

					<div class="form-group">
						<label>Password</label>
						<input type="password" name="login_password" class="form-control" id="login_password" placeholder="Password" value="<?php echo set_value('login_password'); ?>">
						<span class="has-danger err_login_password"></span>
					</div>

                    <div class="form-group checklist">
                        <label class="pull-right"><a href="<?php echo base_url('auth/forgot-password'); ?>">Forgot password?</a></label>
                    </div>

					<div class="form-group">
						<input type="button" name="" class="btn btn-blue btn-lg submitbtn disable login_click" value="Log in" />
					</div>

					<p class="text-center">Don't have an account? <a href="<?php echo base_url('auth/registration'); ?>">Sign up</a></p>
					<p class="text-center"><small>By logging into your Account you agree to our </small><br />
						<small><a href="<?php echo base_url('terms'); ?>">Terms and Conditions</a>, <a href="<?php echo base_url('policy'); ?>">Privacy Policy</a></small>
					</p>
                    <?php echo form_close(); ?>
                </div><!-- end of logRegSection -->
            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section><!-- end of loginRegister -->
</div><!-- end of content_wrapper -->
