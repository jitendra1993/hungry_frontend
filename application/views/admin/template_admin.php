<?php 
require_once(APPPATH.'views/admin/includes/admin_metatag.php');
require_once(APPPATH.'views/admin/includes/admin_header.php');
include_once(APPPATH.'views/admin/includes/admin_sidebar.php');
?>
<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<?php  $this->load->view($main_content); ; ?>  
		</div>
		<div class="footer-wrap pd-20 mb-20 card-box">
		S-epos
		</div>
	</div>
</div>
<?php  include_once(APPPATH.'views/admin/includes/admin_footer.php'); ?>
 

