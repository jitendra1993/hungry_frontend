
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="<?=base_url()?>assets/admin/vendors/scripts/core.js"></script>
	<script src="<?=base_url()?>assets/admin/vendors/scripts/script.min.js"></script>
	<script src="<?=base_url()?>assets/admin/vendors/scripts/process.js"></script>
	<script src="<?=base_url()?>assets/admin/vendors/scripts/layout-settings.js"></script>
	<script src="<?=base_url()?>assets/admin/src/plugins/jquery-confirm/jquery-confirm.min.js"></script>
	<script src="<?=base_url()?>assets/admin/vendors/scripts/common.js?v=<?php time();?>"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<style>
	.pip {
		display: inline-block;
		margin: 10px 10px 0 0;
		width: 100px;
	}
	
	.pip300 {
		display: inline-block;
		margin: 10px 10px 0 0;
		width: 300px;
	}
	.pip img {
		height: 100px;
		width: 100%;
	}
	
	.pip img {
       height: 100%;
		width: 100%;
			}
	.remove,.removeExist,.removeLogo,.removeExistLogo,.removeSingleImg,.removeSingleExist,.removeGallery,.removeGalleryExist {
	  display: block;
	  text-align: center;
	  cursor: pointer;
	}
	.small-text{
    font-size: 10px;
}

.font-15 {
    font-size: 15px;
}

.input-group-text {
    background-color: #e9ecef !important;
    border: 1px solid #ced4da!important;
}
span.mtext {
    margin-left: 5px;
}
.hide{display:none}

.small_textbox{width:45%;display:inline}
</style> 
<?php
$menu1 = $this->uri->segment(2);
$menu = $this->uri->segment(3);
$action = $this->uri->segment(4);
if(($menu=='category' && ($action=='add' || $action=='edit')) || ($menu=='sub-category' && ($action=='add' || $action=='edit')) || ($menu1=='store-category' && ($menu=='add' || $menu=='edit'))){ ?>
	<script>
		function ValidateFileUpload() {
			var fuData = document.getElementById('image');
			var FileUploadPath = fuData.value;

			if (FileUploadPath == '') {
				alert("Please upload an image");

			} else {
				var Extension = FileUploadPath.substring(
						FileUploadPath.lastIndexOf('.') + 1).toLowerCase();


				if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {
					if (fuData.files && fuData.files[0]) {
						var reader = new FileReader();
						reader.onload = function(e) {
							//$('#blah').attr('src', e.target.result);
							var file = e.target;
						var app = "<span class=\"pip img-thumbnail\">" +
						"<img class=\"imageThumb\" width=\"100\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
						"<br/><span class=\"remove btn-primary\">Remove</span>" +
						"</span>";
						$('.image_append').html(app);
						}
						reader.readAsDataURL(fuData.files[0]);
					}
					
				} 
				else {
					alert("Image only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
				}
			}
		}

		$(function () {
			
			$(document).on('click','.remove',function(){
				$(this).parent(".pip").remove();
				$('#image').val('')
				$('#img_status').val(1)
			});
			
			$(".removeExist").click(function(){
				$(this).parent(".pip").remove();
				$('#img_status').val('1')
			});
			
			$('#cat-master').on('submit', function(e){
				var name =  $.trim($('#name').val());
				if(name=='')
				{
					$('#name').addClass('form-control-danger');
					$('#name').focus();
					$('.errName').html('Category name can\'t be blank.');
					return false;
				}
				else
				{
					$('#name').addClass('form-control-success');
					$('#name').removeClass('form-control-danger');
					$('.errName').html('');
				}
				
				$('#cat-master').submit();
			
			});
			
			
			$('#sub-cat-master').on('submit', function(e){
				var categoryId =  $.trim($('#categoryId').val());
				var name =  $.trim($('#name').val());
				
				if(categoryId=='')
				{
					$('#categoryId').addClass('form-control-danger');
					$('#categoryId').focus();
					$('.errcategoryId').html('Master category name can\'t be blank.');
					return false;
				}
				else
				{
					$('#categoryId').addClass('form-control-success');
					$('#categoryId').removeClass('form-control-danger');
					$('.errcategoryId').html('');
				}
				
				if(name=='')
				{
					$('#name').addClass('form-control-danger');
					$('#name').focus();
					$('.errName').html('Sub category name can\'t be blank.');
					return false;
				}
				else
				{
					$('#name').addClass('form-control-success');
					$('#name').removeClass('form-control-danger');
					$('.errName').html('');
				}
				
				$('#sub-cat-master').submit();
			
			});
		});
	</script>

	<?php 
} elseif($menu1=='banner'){ ?>
	<script>
		$(document).ready(function() {
			if (window.File && window.FileList && window.FileReader) {

				$("#image").on("change", function(e) {
					var files = e.target.files,
					filesLength = files.length;
					for (var i = 0; i < filesLength; i++) {
						var f = files[i];
						var file1 = $("#image").get(0).files[i].name;
						var Extension = file1.substring(file1.lastIndexOf('.') + 1).toLowerCase(); 
						if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {
							var fileReader = new FileReader();
							fileReader.onload = (function(e) {
								var file = e.target;
								var app = "<span class=\"pip300 img-thumbnail\">" +
								"<img class=\"imageThumb\" width=\"300\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
								"<br/><span class=\"remove btn-primary\">Remove</span>" +
								"</span>";
								$('.image_append').html(app);
							});
							fileReader.readAsDataURL(f);
						} else {
							alert("Image only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
							$("#image").val('');
						}
					}
				});
			} else {
				alert("Your browser doesn't support to File API")
			}
			
			$(document).on('click','.remove',function(){
				$(this).parent(".pip300").remove();
				$('#image').val('')
				$('#img_status').val(1)
			});
			
			$(".removeExist").click(function(){
				$("#image").val('');
				$(this).parent(".pip300").remove();
				$('#img_status').val('1')
			});
			
			$('#banner-master').on('submit', function(e){
				var name =  $.trim($('#name').val());
				var image =  $.trim($('#image').val());
				var img_status =  $.trim($('#img_status').val());
				var old_img =  $.trim($('#old_img').val());
				var id =  $.trim($('#id').val());
				if(name=='')
				{
					$('#name').addClass('form-control-danger');
					$('#name').focus();
					$('.errName').html('Banner name can\'t be blank.');
					return false;
				}
				else
				{
					$('#name').addClass('form-control-success');
					$('#name').removeClass('form-control-danger');
					$('.errName').html('');
				}
				
				if(id=='')
				{
					if(image=='')
					{
						$('#image').addClass('form-control-danger');
						$('#image').focus();
						$('.errImage').html('Banner image can\'t be blank.');
						return false;
					}
					else
					{
						$('#image').addClass('form-control-success');
						$('#image').removeClass('form-control-danger');
						$('.errImage').html('');
					}
				}else{
					if((old_img=='' || img_status==1) && image=='')
					{
						$('#image').addClass('form-control-danger');
						$('#image').focus();
						$('.errImage').html('Banner image can\'t be blank.');
						return false;
					}
					else
					{
						$('#image').addClass('form-control-success');
						$('#image').removeClass('form-control-danger');
						$('.errImage').html('');
					}
				}
				$('#banner-master').submit();
		
			});
		});
	</script>
	<?php 
} 
if(is_logged_in() && ($this->session->userdata('role_master_tbl_id')==2 || $this->session->userdata('role_master_tbl_id')==1)){?>
	<script>
		function playSound(filename){
			  var mp3Source = '<source src="' + filename + '.mp3" type="audio/mpeg">';
			  var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3">';
			  document.getElementById("sound").innerHTML='<audio controls autoplay>'+mp3Source+'</audio>';
		}

		function load_unseen_notification(){
			$.ajax({
				url: site_url+"admin/order/notification",
				'dataType': 'html',
				success: function (data) {
					if(data > 0)
					{
						var sound = '<?=base_url()?>assets/admin/vendors/sound/ding-dong';
						var url = site_url+'admin/order/new-order?filter_status=1';
						$('.msg_error_success').show()
						$('.fixed_success').html('You have received '+data+' new order <a href="'+url+'">click here</a> to view.')
						playSound(sound);
					}else{
						$('.msg_error_success').hide()
					}
					
				}
			})
		}
		
		function dshboardOrderDetail(){
			$.ajax({
				url: site_url+"admin/order/dshboardOrderDetail",
				'dataType': 'json',
				success: function (data) {
					if(data.status==1){
						$('.monthOrder').html(data.monthOrder)
						$('.newOrder').html(data.newOrder)
						$('.todayOrder').html(data.todayOrder)
						$('.todaysales').html(data.todaySales)
					}
					
				}
			})
		}
		setInterval(function(){
			load_unseen_notification();
			dshboardOrderDetail();
		}, 5000);
	</script>
	<?php 
} ?>
</body>
</html>
