<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>

<div class="content_wrapper">

    <section class="sectionpanel loginRegister">
        <div class="container">
            <div class="row">

                <div class="logRegSection formbox">
                    <div class="ht3 b4 heading-font text-center">Change Password</div>
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
                    
					$attributes = array('class' => 'form-signin', 'id' => 'change-password'); 
					echo form_open('user/change-password',$attributes);?>

					<div class="form-group">
						<label>Old password</label>
						<input type="password" name="old_password" class="form-control" id="old_password"  value="<?php echo set_value('old_password'); ?>">
						<span class="has-danger err_old_password"></span>
					</div>

					<div class="form-group">
						<label>New Password</label>
						<input type="password" name="new_password" class="form-control" id="new_password"  value="<?php echo set_value('new_password'); ?>">
						<span class="has-danger err_new_password"></span>
					</div>

					<div class="form-group">
						<label>Confirm New Password</label>
						<input type="text" name="c_new_password" class="form-control" id="c_new_password" value="<?php echo set_value('c_new_password'); ?>">
						<span class="has-danger err_c_new_password"></span>
					</div>

					<div class="form-group">
						<input type="button" name="" class="btn btn-blue btn-lg submitbtn disable change-password" value="Update" />
					</div>
                    <?php echo form_close(); ?>
                </div><!-- end of logRegSection -->
            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section><!-- end of loginRegister -->
</div><!-- end of content_wrapper -->
