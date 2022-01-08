<?php require_once(APPPATH.'views/admin/includes/admin_metatag.php'); ?>
 <style>
.error{font-size:12px; color:#dd4b39;clear: both;}
.invalid .error{display:block;}
.invalid .form-control{border-color:#f24a00}

  </style>
<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="<?=base_url('admin')?>">
					<img src="<?=base_url()?>assets/admin/vendors/images/deskapp-logo.svg" alt="">
				</a>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="<?=base_url()?>assets/admin/vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Login To DeskApp</h2>
						</div>
						<?php if(validation_errors()){ ?>
						<ul class="list-group">
						<?php echo form_error('username', '<li class="list-group-item list-group-item-danger">', '</li>'); ?>
						<?php echo form_error('password', '<li class="list-group-item list-group-item-danger">', '</li>'); ?>
						</ul>
						<br>
					  <?php } ?>

					  <?php if($this->session->flashdata('msg_success')){ ?>
					  <div class="alert alert-success alert-dismissible"> 
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?=($this->session->flashdata('msg_success'))?> 
					  </div>
					  <?php } if($this->session->flashdata('error_msg')){ ?>
						  <ul class="list-group">
							<li class="list-group-item list-group-item-danger">
							<?php echo $this->session->flashdata('error_msg'); ?>
							</li>
						  </ul><br>
					  <?php } ?>
					    <?php $attributes = array('id' => 'login_admin'); 
							echo form_open('admin/login',$attributes);?>
							<div class="input-group custom">
								<input type="text" name="username" id="username"  class="form-control form-control-lg" placeholder="Username" value="<?php echo set_value('username');?>">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
								<span class="username error col-md-12 row"></span>
							</div>
							<div class="input-group custom" style="margin-bottom:10px">
								<input type="password" id="password" name="password"  class="form-control form-control-lg" placeholder="**********" value="<?php echo set_value('password');?>">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
								<span class="password error col-md-12 row"></span>
							</div>
							<div class="row pb-20">
								<div class="col-6">
									<div class="forgot-password"><a href="<?=base_url('admin/auth/forgot-password')?>">Forgot Password</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
									</div>
								</div>
							</div>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php  include_once(APPPATH.'views/admin/includes/admin_footer.php'); ?>
<script>
      $(function () {
		$('#login_admin').on('submit', function(e){
        var username =  $.trim($('#username').val());
        var password = $.trim($('#password').val());
      
        if(username=='')
        {
            $('#username').parent().addClass('invalid');
            $('#username').focus();
            $('.username').html('Username can\'t be blank.');
            return false;
        }
        else
        {
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(username)) {
				$('#username').parent().addClass('invalid');
				$('#username').focus();
				$('.username').html('Please provide a valid email address');
				return false;
			}else{
				$('#username').parent().removeClass('invalid');
				$('.username').html('');
			}
		}
        
        if(password=='')
        {
            $('#password').parent().addClass('invalid');
            $('#password').focus();
            $('.password').html('Password can\'t be blank.');
            return false;
        }
        else
        {
            $('#password').parent().removeClass('invalid');
            $('.password').html('');
        }
        $('#login_admin').submit();
        
    });
	
      });
    </script>