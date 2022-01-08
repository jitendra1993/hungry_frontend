<?php $username = $this->session->userdata('name'); ?>
<body>
	<audio id="sound"></audio>
	<style>
		.msg_error_success {
			position: fixed;
			z-index: 99999999;
			right: 1%;
			top: 30%;
		}
	</style>
	
	<div class="msg_error_success" style="display:none">
		<div class="alert alert-success alert-dismissible suss"> 
			<button type="button" class="close ">×</button>
			<span class="fixed_success"></span>
		</div>
	</div>

	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
			<div class="header-search">
				
					
			</div>
		</div>
		<div class="header-right" >
			<?php
			if ($this->session->userdata('role_master_tbl_id')==2) { ?>
				<div class="btn btn-primary " style="position: absolute;left: 40%;padding: 22px;">
					<a href="javascript:void(0)" class="settle" style="color: #000;margin-top: 10px;background-color: white;padding: 10px;border-radius: 30px;">Settle all orders</a>
				</div>
				<?php
			}?>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="<?=base_url()?>assets/admin/vendors/images/photo1.jpg" alt="">
						</span>
						<span class="user-name"><?=ucfirst($username);?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="<?=base_url('admin/profile')?>"><i class="dw dw-user1"></i> Profile</a>
						<?php
						if ($this->session->userdata('role_master_tbl_id')==3) { ?>
						<a class="dropdown-item" href="<?=base_url('admin/setting')?>"><i class="dw dw-settings2"></i> Setting</a>
						<?php } ?>
						<a class="dropdown-item" href="<?=base_url('admin/logout')?>"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>
		</div>
	</div>