<div class="left-side-bar">
	<div class="brand-logo">
		<a href="<?=base_url()?>admin">
			<img src="<?=base_url()?>assets/admin/vendors/images/deskapp-logo.svg" alt="" class="dark-logo">
			<img src="<?=base_url()?>assets/admin/vendors/images/deskapp-logo-white.svg" alt="" class="light-logo">
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close"><i class="ion-close-round"></i></div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li>
					<a href="<?=base_url('admin/dashboard')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='dashboard')?'active':''?>">
						<span class="micon dw dw-calendar1"></span><span class="mtext">Dashboard</span>
					</a>
				</li>
				<?php
					if ($this->session->userdata('role_master_tbl_id')==1) { ?>
						<li><a href="<?=base_url('admin/store-category/view')?>" class="dropdown-toggle no-arrow  <?=($this->uri->segment(2)==='store-category')?'active':''?>"><i class="fa fa-list-alt"></i><span class="mtext">Stores Category</span></a></li>
							
						<li><a href="<?=base_url('admin/store/view')?>" class="dropdown-toggle no-arrow  <?=($this->uri->segment(2)==='store')?'active':''?>"><i class="fa fa-list-alt"></i><span class="mtext">View Stores</span></a></li>
						
						<li>
							<a href="<?=base_url('admin/banner/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='banner')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Banners</span>
							</a>
						</li>
						<?php 
					} 
				
					if ($this->session->userdata('role_master_tbl_id')==1 || $this->session->userdata('role_master_tbl_id')==2) { ?>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<i class="fa fa-list-alt"></i><span class="mtext">Setting</span>
							</a>
							<ul class="submenu">
								<?php
								if ($this->session->userdata('role_master_tbl_id')==2) { ?>
									<li><a href="<?=base_url('admin/setting/store-info')?>" class="<?=($this->uri->segment(3)==='store-info')?'active':''?>">Store Info</a></li>
									<?php 
								} ?>
								<li><a href="<?=base_url('admin/setting/setting-view')?>" class="<?=($this->uri->segment(3)==='setting-view')?'active':''?>">Store Setting</a></li>
								<?php
								if ($this->session->userdata('role_master_tbl_id')==1) { ?>
									<li><a href="<?=base_url('admin/setting/mail-setting')?>" class="<?=($this->uri->segment(3)==='mail-setting')?'active':''?>">Mail Setting</a></li>
									<li><a href="<?=base_url('admin/setting/sm-setting')?>" class="<?=($this->uri->segment(3)==='sm-setting')?'active':''?>">SM Setting</a></li>
									<?php 
								} ?>
							</ul>
						</li>
						
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
								<i class="fa fa-list-alt"></i><span class="mtext">Catalog</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=base_url('admin/catalog/category/view')?>" class="<?=($this->uri->segment(3)==='category')?'active':''?>">Categories</a></li>
								<li><a href="<?=base_url('admin/catalog/sub-category/view')?>" class="<?=($this->uri->segment(3)==='sub-category')?'active':''?>">Sub Categories</a></li>
								<li><a href="<?=base_url('admin/catalog/addon-category/view')?>" class="<?=($this->uri->segment(3)==='addon-category')?'active':''?>">AddOn Category</a></li>
							</ul>
						</li>
						
						<li>
							<a href="<?=base_url('admin/size/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='size')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Size/Type</span>
							</a>
						</li>
						
						<li>
							<a href="<?=base_url('admin/ingredient/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='ingredient')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Ingredients</span>
							</a>
						</li>
						
						<li>
							<a href="<?=base_url('admin/addon-item/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='addon-item')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Addon Item</span>
							</a>
						</li>
							
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
							<i class="fa fa-list-alt"></i><span class="mtext">Products</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=base_url('admin/product/view')?>" class="<?=($this->uri->segment(2)==='product')?'active':''?>">Products</a></li>
								<li><a href="<?=base_url('admin/dinein-product/view')?>" class="<?=($this->uri->segment(2)==='dinein-product')?'active':''?>">Dinein Products</a></li>
							</ul>
						</li>
					
						<li>
							<a href="<?=base_url('admin/tablebooking/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='tablebooking')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Table Booking</span>
							</a>
						</li>
						<?php 
					} 
				?>
					
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
						<i class="fa fa-list-alt"></i><span class="mtext">Orders</span>
					</a>
					<ul class="submenu">
						
						<li><a href="<?=base_url('admin/order/new-order?filter_status=1')?>" class="<?=($this->uri->segment(3)==='new-order')?'active':''?>">New Order</a></li>
						<li><a href="<?=base_url('admin/order/today-order')?>" class="<?=($this->uri->segment(3)==='today-order')?'active':''?>">Today's Order</a></li>
						<li><a href="<?=base_url('admin/order/today-sales')?>" class="<?=($this->uri->segment(3)==='today-sales')?'active':''?>">Today's sales</a></li>
						<li><a href="<?=base_url('admin/order/view')?>" class="<?=($this->uri->segment(3)==='view' && $this->uri->segment(2)==='order')?'active':''?>">All Orders</</a></li>
					</ul>
				</li>
				
				<li>
					<a href="<?=base_url('admin/auth/change-password')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(3)==='change-password')?'active':''?>">
						<i class="fa fa-list-alt"></i><span class="mtext">Change Password</span>
					</a>
				</li>
					
				<?php
					if ($this->session->userdata('role_master_tbl_id')==1) { ?>
						<li>
							<a href="<?=base_url('admin/user/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='user')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">All User</span>
							</a>
						</li>

						<li>
							<a href="<?=base_url('admin/offer/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='offer')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Offers</span>
							</a>
						</li>
					
						<li>
							<a href="<?=base_url('admin/voucher/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='voucher')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Vouchers</span>
							</a>
						</li>

						<li>
							<a href="<?=base_url('admin/points-settings')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='points-settings')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">Loyalty Points</span>
							</a>
						</li>
							
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
							<i class="fa fa-list-alt"></i><span class="mtext">Delivery Charges Rates</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=base_url('admin/delivery-charges/view')?>" class="<?=($this->uri->segment(2)==='delivery-charges')?'active':''?>">Delivery Charges Rates</a></li>
								<li><a href="<?=base_url('admin/fixed-delivery-charges/view')?>" class="<?=($this->uri->segment(2)==='fixed-delivery-charges')?'active':''?>">Fixed Delivery Charges</a></li>
							</ul>
						</li>
						
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
							<i class="fa fa-list-alt"></i><span class="mtext">Payment Gateway</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=base_url('admin/payment/cash')?>" class="<?=($this->uri->segment(3)==='cash')?'active':''?>">Cash</a></li>
								<li><a href="<?=base_url('admin/payment/nochex')?>" class="<?=($this->uri->segment(3)==='nochex')?'active':''?>">Nochex</a></li>
								<li><a href="<?=base_url('admin/payment/rms')?>" class="<?=($this->uri->segment(3)==='rms')?'active':''?>">RMS(Global Pay)</a></li>
							</ul>
						</li>
						
						<li>
							<a href="<?=base_url('admin/sms')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='sms')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">SMS Gateway</span>
							</a>
						</li>
						
						<li>
							<a href="<?=base_url('admin/seo/view')?>" class="dropdown-toggle no-arrow <?=($this->uri->segment(2)==='seo')?'active':''?>">
								<i class="fa fa-list-alt"></i><span class="mtext">SEO</span>
							</a>
						</li>
						<?php 
					} 
				?>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle">
					<i class="fa fa-list-alt"></i><span class="mtext">Reports</span>
					</a>
					<ul class="submenu">
						<li><a href="<?=base_url('admin/reports/sales')?>" class="<?=($this->uri->segment(3)==='sales')?'active':''?>">Sales Report</a></li>
						<li><a href="<?=base_url('admin/reports/sales-summary')?>" class="<?=($this->uri->segment(3)==='sales-summary')?'active':''?>">Sales Summary Report</a></li>
						<li><a href="<?=base_url('admin/reports/booking')?>" class="<?=($this->uri->segment(3)==='booking')?'active':''?>">Booking Summary Report</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="mobile-menu-overlay"></div>