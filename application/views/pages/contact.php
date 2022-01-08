
<?php 
$store_info = get_admin_store_detail();
// echo '<pre>';
// print_r($store_info);
// echo '</pre>';
?>
<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>

<div class="content_wrapper">

    <section class="sectionpanel loginRegister">
        <div class="container">
            <div class="row">

				<div class="col-lg-8 col-md-7 order-md-last d-flex align-items-stretch">
					<div class="contact-wrap w-100 p-md-5 p-4">
						<h3 class="mb-4">Get in touch</h3>
						<?php 
						
						if(validation_errors()){ ?>
							<div class="alert alert-danger alert-dismissible"> 
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?= validation_errors();;?> 
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
						$attributes = array('class' => 'contactForm', 'id' => 'contactForm'); 
						echo form_open('contact-us',$attributes);?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="label" for="name">Full Name</label>
										<input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo set_value('name'); ?>">
										<span class="error has-danger err_name"></span>
									</div>
								</div>
								
								<div class="col-md-6"> 
									<div class="form-group">
										<label class="label" for="email">Email Address</label>
										<input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
										<span class="error has-danger err_email"></span>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="label" for="subject">Phone Number</label>
										<input type="text" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Phone Number" value="<?php echo set_value('phoneNumber'); ?>" onkeypress="return isNumber(event)">
									</div>
									<span class="error has-danger err_phone"></span>
								</div>
									
								<div class="col-md-12">
									<div class="form-group">
										<label class="label" for="subject">Subject</label>
										<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="<?php echo set_value('subject'); ?>">
										<span class="error has-danger err_subject"></span>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="label" for="#">Message</label>
										<textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message" value="<?php echo set_value('message'); ?>"></textarea>
										<span class="error has-danger err_message"></span>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<input type="submit" value="Send Message" class="btn btn-primary">
										<div class="submitting"></div>
									</div>
								</div>
							</div>
						<?php echo form_close(); ?>
					</div>
				</div>
				<div class="col-lg-4 col-md-5 d-flex align-items-stretch">
					<div class="info-wrap bg-white w-100 p-md-5 p-10">
						<h3>Let's get in touch</h3>
						<p class="mb-4">We're open for any suggestion or just to have a chat</p>
						<div class="dbox w-100 d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="fa fa-map-marker"></span>
							</div>
							<div class="text pl-3">
								<p><span>Address:</span><?=$store_info['restaurant_info']['address']?></p>
							</div>
						</div>
						<div class="dbox w-100 d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="fa fa-phone"></span>
							</div>
							<div class="text pl-3">
								<p><span>Phone:</span> <a href="tel://<?=$store_info['restaurant_info']['contact_phone']?>"><?=$store_info['restaurant_info']['contact_phone']?></a></p>
							</div>
						</div>
						<div class="dbox w-100 d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="fa fa-paper-plane"></span>
							</div>
							<div class="text pl-3">
								<p><span>Email:</span> <a href="mailto:<?=$store_info['restaurant_info']['contact_email']?>"><?=$store_info['restaurant_info']['contact_email']?></a></p>
							</div>
						</div>
						<div class="dbox w-100 d-flex align-items-center">
							<div class="icon d-flex align-items-center justify-content-center">
								<span class="fa fa-globe"></span>
							</div>
							<div class="text pl-3">
								<p><span>Website</span> <a href="<?=$store_info['restaurant_info']['site_url']?>"><?=$store_info['restaurant_info']['site_url']?></a></p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 mt-5">
					<div class="map">
						<iframe  src="https://maps.google.com/maps?q=<?=urlencode($store_info['restaurant_info']['address'])?>&t=&z=16&ie=UTF8&iwloc=&output=embed"  width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
					</div>
				</div>
			


            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section><!-- end of loginRegister -->
</div><!-- end of content_wrapper -->
