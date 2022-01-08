<?php
	$store_info = get_admin_store_detail();
    
	$img ='';
	if(!empty($store_info['restaurant_info']['logo']))
	{
		$path = BASEPATH.'../uploads/files/'.$store_info['restaurant_info']['logo']; 
		if(is_file($path))
		{
			$img = base_url('uploads/files').'/'.$store_info['restaurant_info']['logo']; 
		}
	}
	$social_media = $store_info['restaurant_info']['social_media'];
?>
<div id="wrapper">

    <header id="header">
        <div class="container">
            <div class="left_nav">
                <div class="logo"><a href="<?php echo base_url();?>"><span class="logoht">Hungry <small>to</small>
                            <span>Eat</span></span></a></div>
                <div class="tagline_holder"><em class="tagline">
                        <span class="split">Aberdeen takeaway </span>
                        <span class="split">the easy way.</span>
                    </em></div>
            </div><!-- end of right_nav --> ̰

            <div class="right_nav pull-right">
                <div class="nav-info">
                    <ul class="nav navbar-nav pull-right">
                        <li><a href="javascript:void(0)"><i class="fa fa-envelope fa-fw"></i> <?php echo !empty($store_info['restaurant_info']['contact_email'])?$store_info['restaurant_info']['contact_email']:'';?></a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-phone fa-fw"></i> <?php echo !empty($store_info['restaurant_info']['contact_phone'])?$store_info['restaurant_info']['contact_phone']:'';?></a></li>

                        <li><a href="<?php echo !empty($social_media['facebook'])?$social_media['facebook']:'#';?>"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="<?php echo !empty($social_media['youtube'])?$social_media['youtube']:'#';?>"><i class="fa fa-youtube"></i></a></li>
                        <li><a href="<?php echo !empty($social_media['twitter'])?$social_media['twitter']:'#';?>"><i class="fa fa-twitter"></i></a></li>
                    </ul>
                </div><!-- end of nav-info -->
                <div class="nav-menu">
                    <ul class="nav navbar-nav">
                        <?php
					    if(!is_logged_in()){
					        ?>
                            <li><a href="<?php echo base_url('auth/login');?>"><i class="fa fa-sign-in fa-fw"></i> Log In</a></li>
                            <li><a href="<?php echo base_url('auth/registration');?>"><i class="fa fa-plus fa-fw"></i> Sign Up</a></li>
                            <li><a href="<?=base_url()?>contact-us"><i class="fa fa-send fa-fw"></i> Contact Us</a></li>
                            <?php 
                        }else{?> 
                            <li class="nav-item dropdown">
						        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">My Account</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?=base_url()?>order/history">Order History</a>
                                    <a class="dropdown-item" href="<?=base_url()?>user/profile">Profile</a>
                                    <a class="dropdown-item" href="<?=base_url()?>user/change-password">Change Password</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?=base_url()?>auth/logout">Logout</a>
                                </div>
					        </li>
					        <?php 
				        } ?>
                    </ul>
                </div><!-- end of nav-info -->
            </div><!-- end of right_nav -->


        </div><!-- end of container -->
    </header><!-- end of header -->