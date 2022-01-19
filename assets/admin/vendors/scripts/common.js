function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isNumberDecimal(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31&& (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function validate_email(uname){
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var mail_format = regex.test(uname);
	
	if(!mail_format){
		$('#email').addClass('form-control-danger');
		$('#email').focus();
		$('.err_email').html('Please provide a valid email');
		//$('.disable').prop('disabled', true);
		return false;
	}
	else {
		$('#email').removeClass('form-control-danger');
		$('#email').addClass('form-control-success');
		$('.err_email').html('');
		//$('.disable').prop('disabled', false);
	} 
}
 
function validate_mobile(phone){
	
	if((phone.length!= 11 && country=='UK') || (phone.length!= 10 && country=='IN')){
		$('#mobile').addClass('form-control-danger');
		$('#mobile').focus();
		$('.err_mobile').html("Mobile number should be "+mobile_length+" digits.");
		return false;
	}else {
		$('#mobile').removeClass('form-control-danger');
		$('#mobile').addClass('form-control-success');
		$('.err_mobile').html('');
		}
}

function ValidateLogoUpload() {
	var fuData = document.getElementById('logo');
	var FileUploadPath = fuData.value;

	if (FileUploadPath == '') {
		alert("Please upload an logo");

	} else {
		var Extension = FileUploadPath.substring(
				FileUploadPath.lastIndexOf('.') + 1).toLowerCase();


		if (Extension == "png" || Extension == "jpeg" || Extension == "jpg") {
			if (fuData.files && fuData.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					//$('#blah').attr('src', e.target.result);
					var file = e.target;
			  var app = "<span class=\"pip img-thumbnail\">" +
				"<img class=\"imageThumb\" width=\"100\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
				"<br/><span class=\"removeLogo btn-primary\">Remove</span>" +
				"</span>";
				$('.logo_append').html(app);
				}
				reader.readAsDataURL(fuData.files[0]);
			}
			
		} 
		else {
			alert("Image only allows file types of  PNG, JPG, JPEG. ");
			$('#logo').val('')
		}
	}
}

function ValidateSingleFileUpload() {
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
				"<br/><span class=\"removeSingleImg btn-primary\">Remove</span>" +
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


$('.description').wysihtml5({
	"font-styles": true, //Font styling, e.g. h1, h2, etc.
	"emphasis": true, //Italics, bold, etc.
	"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers.
	"html": true, //Button which allows you to edit the generated HTML.
	"link": true, //Button to insert a link.
	"image": false, //Button to insert an image.
	"color": true ,//Button to change color of font
	events: {
	   load: function () {
		   $('.wysihtml5').addClass('nicehide');
	   }
   }
});

$(function () {
	var const_country = country
	
	$('.select222').select2()
	var currentDate = new Date();
	$('.timepicker').timepicker({
		timeFormat: 'h:mm p',
		interval: 30,
		minTime: '0',
		maxTime: '11:30pm',
		defaultTime: '',
		startTime: '00:00',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});
	
	$('.timepicker2').timepicker({
		timeFormat: 'h:mm p',
		interval: 15,
		minTime: '0',
		maxTime: '11:30pm',
		defaultTime: '',
		startTime: '00:00',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});
	
	$( ".datepicker1" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat:'d-M-y'
    });
	
	$( ".datepickercurrent" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  minDate: currentDate,
	  dateFormat:'d-M-y',
	  yearRange: "-90:+1"
    });
	
	$( document ).on( "click", ".check_all", function() {
		if($(this).is(':checked')){			
			$(".all_checked").prop( "checked", true );
		} else {
			$(".all_checked").prop( "checked", false );
		}
	});
	
	$( document ).on( "click", ".two_flavors", function() {
		if($(this).is(':checked')){			
			$('.two_flavors_toggle').css('display','flex')
			  $('.two_flavors_toggle select').prop('selectedIndex',0);
		} else {
			$('.two_flavors_toggle').css('display','none')
			  $('.two_flavors_toggle select').prop('selectedIndex',0);
		}
	});
	
	$(".multi_option").on('change', function() {
		var id = $(this).attr('data-id');
		var value = $('option:selected', this).val();
		var two_flavors_position = $('option:selected','#two_flavors_position_'+id).val();
		if(two_flavors_position=='' && value==3)
		{
			$('.multi_option_value_'+id).css('display','flex')
			$('.multi_option_value_'+id).val('')
		}else if(two_flavors_position!=''){
			$('#multi_option_'+id).prop('selectedIndex',0);
			$('.multi_option_value_'+id).css('display','none')
			$('.multi_option_value_'+id).val('')
		}else{
			$('.multi_option_value_'+id).css('display','none')
			$('.multi_option_value_'+id).val('')
			
		}
	});	
	
	$(".two_flavors_position").on('change', function() {
		var id = $(this).attr('data-id');
		var value = $('option:selected', this).val();
		
		if(value!='')
		{
			$('#multi_option_'+id).prop('selectedIndex',0);
			$('.multi_option_value_'+id).css('display','none')
			$('.multi_option_value_'+id).val('')
		}else{
			
		}
	});	
	
	$( document ).on( "click", ".check_all_addon_item", function() {
		
		var id = $(this).attr( "data-id");
		if($(this).is(':checked')){			
			$(".check_all_"+id).prop( "checked", true );
		} else {
			$(".check_all_"+id).prop( "checked", false );
		}
	});
	
	if (window.File && window.FileList && window.FileReader) {
		$(".multipleupload").on("change", function(e) {
			var files = e.target.files,
			filesLength = files.length;
			$('#gallery_uploaded_filename').val('');
			$('.galcnt').remove();
			for (let i = 0; i < filesLength; i++) 
			{
				var f = files[i];
				var file1 = $("#gallery").get(0).files[i].name;
				//alert(file1)
				var Extension = file1.substring(file1.lastIndexOf('.') + 1).toLowerCase(); 
				if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") 
				{
					var fileReader = new FileReader();
					fileReader.onload = (function(e) {
					  var file = e.target;
					  var app='';
					  app = "<span class=\"pip img-thumbnail galcnt\">" +
						"<img class=\"imageThumb\" width=\"100\" src=\"" + e.target.result + "\" title=\"" + file1 + "\"/>" +
						"<br/><span class=\"removeGallery btn-primary\" id="+(i)+">Remove</span>" +
						"</span>";
					  //$(app).insertAfter("#images");
						$('.gallery_append').append(app);
					});
					fileReader.readAsDataURL(f);
					showMsg = true
					
					var gallery_uploaded_filename = $('#gallery_uploaded_filename').val();
					var array = gallery_uploaded_filename.split(",");
					var index = array.indexOf(file1);
					if(index== -1)
					{
						array.push(file1);
					}
					uploaded_filename = array.join(',');
					 while(uploaded_filename.charAt(0) == ',')
					{
					 uploaded_filename = uploaded_filename.substring(1);
					}
					$('#gallery_uploaded_filename').val(uploaded_filename);
				} else {
					alert("Image only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
					$("#gallery").val('');
				}
			}
		});
	} else {
		alert("Your browser doesn't support to File API")
	}
	
	$(".removeExistLogo").click(function(){
		$("#logo").val('');
		$(this).parent(".pip").remove();
		$('#logo_status').val('1')
    });
	
	$(".removeSingleExist").click(function(){
		$("#image").val('');
		$(this).parent(".pip").remove();
		$('#image_status').val('1')
    });
	
	$(document).on('change','.country', function () {
		var url = $('#country_href').val();
		var append_state_class = $('#append_state_class').val();
		var append_city_class = $('#append_city_class').val();
		var id = $('option:selected', this).attr('data-id');
		
		$('.'+append_state_class).html('<select class="custom-select2 form-control" name="state" id="state"><option value="">State</option></select>');
		$('.'+append_city_class).html('<select class="custom-select2 form-control" name="city" id="city"><option value="">City</option></select>');
		
		$('.loading-image').show();
		$.ajax({
			'type': "POST",
			'url': url,
			'data': { id: id},
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				$('.'+append_state_class).html(data)
				$('#state').select2();
			}
		});
					
	});
		
	$(document).on('change','.state', function () {
		var url = $('#state_href').val();
		var append_city_class = $('#append_city_class').val();
		var id = $('option:selected', this).attr('data-id');
		
		$('.'+append_city_class).html('<select class="custom-select2 form-control" name="city" id="city"><option value="">City</option></select>');
		
		$('.loading-image').show();
		$.ajax({
			'type': "POST",
			'url': url,
			'data': { id: id},
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				$('.'+append_city_class).html(data)
				$('#city').select2();
			}
		});
					
	});
	
	$('#add_client').on('submit', function(e){
		var merchant_name =  $.trim($('#merchant_name').val());
		var merchant_phone =  $.trim($('#merchant_phone').val());
		var contact_name =  $.trim($('#contact_name').val());
		var contact_phone =  $.trim($('#contact_phone').val());
		var contact_email =  $.trim($('#contact_email').val());
		var store_category =  $.trim($('#store_category').val());
		var country =  $.trim($('#country').val());
		var state =  $.trim($('#state').val());
		var city =  $.trim($('#city').val());
		var pincode =  $.trim($('#pincode').val());
		var address =  $.trim($('#address').val());
		var username =  $.trim($('#username').val());
		var password =  $.trim($('#password').val());
		var c_password =  $.trim($('#c_password').val());	
		var id =  $.trim($('#id').val());	
		
		if(merchant_name=='')
		{
			$('#merchant_name').addClass('form-control-danger');
			$('#merchant_name').focus();
			$('.errMerchantName').html('Merchant name can\'t be blank.');
			return false;
		}else{
			$('#merchant_name').addClass('form-control-success');
			$('#merchant_name').removeClass('form-control-danger');
			$('.errMerchantName').html('');
		}
			
		if(merchant_phone=='')
		{
			$('#merchant_phone').addClass('form-control-danger');
			$('#merchant_phone').focus();
			$('.errMerchantPhone').html('Merchant phone can\'t be blank.');
			return false;
		}else{
			$('#merchant_phone').addClass('form-control-success');
			$('#merchant_phone').removeClass('form-control-danger');
			$('.errMerchantPhone').html('');
		}
			
		if(contact_name=='')
		{
			$('#contact_name').addClass('form-control-danger');
			$('#contact_name').focus();
			$('.errContactName').html('Contact name can\'t be blank.');
			return false;
		}else{
			$('#contact_name').addClass('form-control-success');
			$('#contact_name').removeClass('form-control-danger');
			$('.errContactName').html('');
		}
			
		if(contact_phone=='')
		{
			$('#contact_phone').addClass('form-control-danger');
			$('#contact_phone').focus();
			$('.errContactPhone').html('Contact phone can\'t be blank.');
			return false;
		}else{
			$('#contact_phone').addClass('form-control-success');
			$('#contact_phone').removeClass('form-control-danger');
			$('.errContactPhone').html('');
		}
			
		if(contact_email=='')
		{
			$('#contact_email').addClass('form-control-danger');
			$('#contact_email').focus();
			$('.errContactEmail').html('Contact email can\'t be blank.');
			return false;
		}else{
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(contact_email)) 
			{
				$('#contact_email').addClass('form-control-danger');
				$('#contact_email').focus();
				$('.errContactEmail').html('Please provide a valid email address');
				return false;
			}
			else
			{
				$('#contact_email').addClass('form-control-success');
				$('#contact_email').removeClass('form-control-danger');
				$('.errContactEmail').html('');
			}
		}
		
		if(store_category=='')
		{
			$('#store_category').addClass('form-control-danger');
			$('#store_category').focus();
			$('.errStoreCategory').html('Store category can\'t be blank.');
			return false;
		}else{
			$('#store_category').addClass('form-control-success');
			$('#store_category').removeClass('form-control-danger');
			$('.errStoreCategory').html('');
		}
			
		if(country=='')
		{
			$("#country").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#country').focus();
			$('.errCountry').html('Country can\'t be blank.');
			return false;
		}else{
			$('#country').addClass('form-control-success');
			$("#country").next("span.select2-container").find("span.select2-selection--single").css('border-color',''); 
			$('.errCountry').html('');
		}
			
		if(state=='')
		{
			$("#state").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#state').focus();
			$('.errState').html('State can\'t be blank.');
			return false;
		}else{
			$('#state').addClass('form-control-success');
			$("#state").next("span.select2-container").find("span.select2-selection--single").css('border-color',''); 
			$('.errState').html('');
		}
			
		if(city=='')
		{
			$("#city").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#city').focus();
			$('.errCity').html('City can\'t be blank.');
			return false;
		}else{
			$('#city').addClass('form-control-success');
			$("#city").next("span.select2-container").find("span.select2-selection--single").css('border-color','');
			$('.errCity').html('');
		}
			
		if(pincode=='')
		{
			$('#pincode').addClass('form-control-danger');
			$('#pincode').focus();
			$('.errPincode').html('Pincode can\'t be blank.');
			return false;
		}else{
			$('#pincode').addClass('form-control-success');
			$('#pincode').removeClass('form-control-danger');
			$('.errPincode').html('');
		}
			
		if(address=='')
		{
			$('#address').addClass('form-control-danger');
			$('#address').focus();
			$('.errAddress').html('Address can\'t be blank.');
			return false;
		}else{
			$('#address').addClass('form-control-success');
			$('#address').removeClass('form-control-danger');
			$('.errAddress').html('');
		}
		
		if(username=='')
		{
			$('#username').addClass('form-control-danger');
			$('#username').focus();
			$('.errusername').html('Username can\'t be blank.');
			return false;
		}else{
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(username)) 
			{
				$('#username').addClass('form-control-danger');
				$('#username').focus();
				$('.errusername').html('Please provide a valid email address');
				return false;
			}
			else
			{
				$('#username').addClass('form-control-success');
				$('#username').removeClass('form-control-danger');
				$('.errusername').html('');
			}
		}
		
		if(id=='' || id<=0 ){
			if(password=='')
			{
				$('#password').addClass('form-control-danger');
				$('#password').focus();
				$('.err_password').html('Password can\'t be blank.');
				return false;
			}else{
				$('#password').addClass('form-control-success');
				$('#password').removeClass('form-control-danger');
				$('.err_password').html('');
			}
			
			if(c_password=='')
			{
				$('#c_password').addClass('form-control-danger');
				$('#c_password').focus();
				$('.err_c_password').html('Confirm password can\'t be blank.');
				return false;
			}else{
				
				
				if (password!=c_password) 
				{
					$('#c_password').addClass('form-control-danger');
					$('#c_password').focus();
					$('.err_c_password').html('Password and confirm does not match');
					return false;
				}
				else
				{
					$('#c_password').addClass('form-control-success');
					$('#c_password').removeClass('form-control-danger');
					$('.err_c_password').html('');
				}
			}
		}
		
		$('#add_client').submit();
        
	});
	
	$('#merchant_info').on('submit', function(e){
		var merchant_name =  $.trim($('#merchant_name').val());
		var merchant_phone =  $.trim($('#merchant_phone').val());
		var contact_name =  $.trim($('#contact_name').val());
		var contact_phone =  $.trim($('#contact_phone').val());
		var contact_email =  $.trim($('#contact_email').val());
		var store_category =  $.trim($('#store_category').val());
		var country =  $.trim($('#country').val());
		var state =  $.trim($('#state').val());
		var city =  $.trim($('#city').val());
		var pincode =  $.trim($('#pincode').val());
		var address =  $.trim($('#address').val());
		
			
		if(merchant_name=='')
		{
			$('#merchant_name').addClass('form-control-danger');
			$('#merchant_name').focus();
			$('.errMerchantName').html('Merchant name can\'t be blank.');
			return false;
		}else{
			$('#merchant_name').addClass('form-control-success');
			$('#merchant_name').removeClass('form-control-danger');
			$('.errMerchantName').html('');
		}
			
		if(merchant_phone=='')
		{
			$('#merchant_phone').addClass('form-control-danger');
			$('#merchant_phone').focus();
			$('.errMerchantPhone').html('Merchant phone can\'t be blank.');
			return false;
		}else{
			$('#merchant_phone').addClass('form-control-success');
			$('#merchant_phone').removeClass('form-control-danger');
			$('.errMerchantPhone').html('');
		}
			
		if(contact_name=='')
		{
			$('#contact_name').addClass('form-control-danger');
			$('#contact_name').focus();
			$('.errContactName').html('Contact name can\'t be blank.');
			return false;
		}else{
			$('#contact_name').addClass('form-control-success');
			$('#contact_name').removeClass('form-control-danger');
			$('.errContactName').html('');
		}
			
		if(contact_phone=='')
		{
			$('#contact_phone').addClass('form-control-danger');
			$('#contact_phone').focus();
			$('.errContactPhone').html('Contact phone can\'t be blank.');
			return false;
		}else{
			$('#contact_phone').addClass('form-control-success');
			$('#contact_phone').removeClass('form-control-danger');
			$('.errContactPhone').html('');
		}
			
		if(contact_email=='')
		{
			$('#contact_email').addClass('form-control-danger');
			$('#contact_email').focus();
			$('.errContactEmail').html('Contact email can\'t be blank.');
			return false;
		}else{
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(contact_email)) 
			{
				$('#contact_email').addClass('form-control-danger');
				$('#contact_email').focus();
				$('.errContactEmail').html('Please provide a valid email address');
				return false;
			}
			else
			{
				$('#contact_email').addClass('form-control-success');
				$('#contact_email').removeClass('form-control-danger');
				$('.errContactEmail').html('');
			}
		}
		
		if(store_category=='')
		{
			$('#store_category').addClass('form-control-danger');
			$('#store_category').focus();
			$('.errStoreCategory').html('Store category can\'t be blank.');
			return false;
		}else{
			$('#store_category').addClass('form-control-success');
			$('#store_category').removeClass('form-control-danger');
			$('.errStoreCategory').html('');
		}
		
		if(country=='')
		{
			$("#country").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#country').focus();
			$('.errCountry').html('Country can\'t be blank.');
			return false;
		}else{
			$('#country').addClass('form-control-success');
			$("#country").next("span.select2-container").find("span.select2-selection--single").css('border-color',''); 
			$('.errCountry').html('');
		}
			
		if(state=='')
		{
			$("#state").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#state').focus();
			$('.errState').html('State can\'t be blank.');
			return false;
		}else{
			$('#state').addClass('form-control-success');
			$("#state").next("span.select2-container").find("span.select2-selection--single").css('border-color',''); 
			$('.errState').html('');
		}
			
		if(city=='')
		{
			$("#city").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#city').focus();
			$('.errCity').html('City can\'t be blank.');
			return false;
		}else{
			$('#city').addClass('form-control-success');
			$("#city").next("span.select2-container").find("span.select2-selection--single").css('border-color','');
			$('.errCity').html('');
		}
			
		if(pincode=='')
		{
			$('#pincode').addClass('form-control-danger');
			$('#pincode').focus();
			$('.errPincode').html('Pincode can\'t be blank.');
			return false;
		}else{
			$('#pincode').addClass('form-control-success');
			$('#pincode').removeClass('form-control-danger');
			$('.errPincode').html('');
		}
			
		if(address=='')
		{
			$('#address').addClass('form-control-danger');
			$('#address').focus();
			$('.errAddress').html('Address can\'t be blank.');
			return false;
		}else{
			$('#address').addClass('form-control-success');
			$('#address').removeClass('form-control-danger');
			$('.errAddress').html('');
		}
		
		$('#merchant_info').submit();
        
	});
	
	$('.form-delete').on('click', function () {
        var url = $(this).attr('data-href');
        var id = $(this).attr('data-id');
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure to delete!',
            buttons: {
                confirm: function () {
					$('.loading-image').show();
                    $.ajax({
                        'type': "POST",
                        'url': url,
                        'data': { id: id },
                        'dataType': 'json',
                        success: function (data) {
							$('.loading-image').hide();
                            $.alert(data.msg);
                            if (data.redirect) {
								setTimeout(function(){
								   window.location.reload(1);
								}, 2500);
                                
                            }
                        }
                    });
                },
                cancel: function () {
                }
            }
        });
    });
		
	$('.changestatus').on('click', function () {
		var self = this;
		var url = $(this).attr('data-href');
		var id = $(this).attr('data-id');
		var status = $(this).attr('data-status');
		$.confirm({
			title: 'Confirm!',
			content: 'Are you sure to change the status!',
			buttons: {
				confirm: function () {
					$('.loading-image').show();
					$.ajax({
						'type': "POST",
						'url': url,
						'data': { id: id, status: status },
						'dataType': 'json',
						success: function (data) {
							$('.loading-image').hide();
							 if (status == 1) {
								$(self).html('<i class="fa fa-toggle-off fa-lg"></i>');
								$(self).attr('data-status', 0)
								$('.status_'+id).html('<span class="badge badge-danger">Inactive</span>')
							}
							if (status == 0) {
								$(self).html('<i class="fa fa-toggle-on fa-lg"></i>');
								$(self).attr('data-status', 1)
								$('.status_'+id).html('<span class="badge badge-success">Active</span>')
			
							}
							$.alert(data.msg);
						}
					});
				},
				cancel: function () {
				}
			}
		});
	});
	
	$('#change-password').on('submit', function(e){
		var current_password =  $.trim($('#current_password').val());
		var new_password =  $.trim($('#new_password').val());
		var c_new_password =  $.trim($('#c_new_password').val());

		if(current_password=='')
		{
			$('#current_password').addClass('form-control-danger');
			$('#current_password').focus();
			$('.err_current_password').html('Current password can\'t be blank.');
			return false;
		}else{
			$('#current_password').addClass('form-control-success');
			$('#current_password').removeClass('form-control-danger');
			$('.err_current_password').html('');
		}
		
		if(new_password=='')
		{
			$('#new_password').addClass('form-control-danger');
			$('#new_password').focus();
			$('.err_new_password').html('New password can\'t be blank.');
			return false;
		}else{
			$('#new_password').addClass('form-control-success');
			$('#new_password').removeClass('form-control-danger');
			$('.err_new_password').html('');
		}
		
		if(c_new_password=='')
		{
			$('#c_new_password').addClass('form-control-danger');
			$('#c_new_password').focus();
			$('.err_c_new_password').html('Confirm password can\'t be blank.');
			return false;
		}else{
			
			
			if (new_password!=c_new_password) 
			{
				$('#c_new_password').addClass('form-control-danger');
				$('#c_new_password').focus();
				$('.err_c_new_password').html('Password and confirm does not match');
				return false;
			}
			else
			{
				$('#c_new_password').addClass('form-control-success');
				$('#c_new_password').removeClass('form-control-danger');
				$('.err_c_new_password').html('');
			}
		}
	
		$('#change-password').submit();
	
	});

	$('#forgot_password_admin').on('submit', function(e){
		
		var username =  $.trim($('#username').val());
		if(username=='')
		{
			$('#username').addClass('form-control-danger');
			$('#username').focus();
			$('.errContactEmail').html('Email can\'t be blank.');
			return false;
		}else{
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(username)) 
			{
				$('#username').addClass('form-control-danger');
				$('#username').focus();
				$('.errContactEmail').html('Please provide a valid email address');
				return false;
			}
			else
			{
				$('#username').addClass('form-control-success');
				$('#username').removeClass('form-control-danger');
				$('.errContactEmail').html('');
			}
		}
		
		$('.disable').prop('disabled', true);
			
		$('#forgot_password_admin').submit();
	
	});
	
	$('#reset_password').on('submit', function(e){
		var password =  $.trim($('#password').val());
		var cpassword =  $.trim($('#cpassword').val());

		if(password=='')
		{
			$('#password').addClass('form-control-danger');
			$('#password').focus();
			$('.errpassword').html('New password can\'t be blank.');
			return false;
		}else{
			$('#password').addClass('form-control-success');
			$('#password').removeClass('form-control-danger');
			$('.errpassword').html('');
		}
		
		if(cpassword=='')
		{
			$('#cpassword').addClass('form-control-danger');
			$('#cpassword').focus();
			$('.errcpassword').html('Confirm new password can\'t be blank.');
			return false;
		}else{
			if (password!=cpassword) 
			{
				$('#cpassword').addClass('form-control-danger');
				$('#cpassword').focus();
				$('.errcpassword').html('Password and confirm does not match');
				return false;
			}
			else
			{
				$('#cpassword').addClass('form-control-success');
				$('#cpassword').removeClass('form-control-danger');
				$('.errcpassword').html('');
			}
		}
		$('.disable').prop('disabled', true);
		$('#reset_password').submit();
	
	});
	
	$( document ).on( "click", ".meal_deal_addon", function() {
		if($(this).is(':checked')){			
			$('.multiple_selection_meal_deal').css('display','flex')
		} else {
			$('.multiple_selection_meal_deal').css('display','none')
		}
	});
	
	$('#offer_admin').on('submit', function(e){
		
		var discount_type =  $.trim($('#discount_type').val());
		var discount_price =  $.trim($('#discount_price').val());
		var min_order =  $.trim($('#min_order').val());
		var valid_from =  $.trim($('#valid_from').val());
		var valid_to =  $.trim($('#valid_to').val());
		
		if(discount_type=='')
		{
			$('#discount_type').addClass('form-control-danger');
			$('#discount_type').focus();
			$('.err_discount_type').html('Offer Type can\'t be blank.');
			return false;
		}else{
			$('#discount_type').addClass('form-control-success');
			$('#discount_type').removeClass('form-control-danger');
			$('.err_discount_type').html('');
		}
		
		if(discount_price=='')
		{
			$('#discount_price').addClass('form-control-danger');
			$('#discount_price').focus();
			$('.err_discount_price').html('Offer Price can\'t be blank.');
			return false;
		}else{
			$('#discount_price').addClass('form-control-success');
			$('#discount_price').removeClass('form-control-danger');
			$('.err_discount_price').html('');
		}
		
		if(min_order=='')
		{
			$('#min_order').addClass('form-control-danger');
			$('#min_order').focus();
			$('.err_discount_price').html('Orders Above can\'t be blank.');
			return false;
		}else{
			$('#min_order').addClass('form-control-success');
			$('#min_order').removeClass('form-control-danger');
			$('.err_discount_price').html('');
		}
		
		if(valid_from=='')
		{
			$('#valid_from').addClass('form-control-danger');
			$('#valid_from').focus();
			$('.err_valid_from').html('Valid From can\'t be blank.');
			return false;
		}else{
			$('#valid_from').addClass('form-control-success');
			$('#valid_from').removeClass('form-control-danger');
			$('.err_valid_from').html('');
		}
		
		if(valid_to=='')
		{
			$('#valid_to').addClass('form-control-danger');
			$('#valid_to').focus();
			$('.err_valid_to').html('Valid To can\'t be blank.');
			return false;
		}else{
			$('#valid_to').addClass('form-control-success');
			$('#valid_to').removeClass('form-control-danger');
			$('.err_valid_to').html('');
		}
		
		var x = new Date(valid_from);
		var y = new Date(valid_to);
		if(x>y)
		{
			$('#valid_to').addClass('form-control-danger');
			$('#valid_to').focus();
			$('.err_valid_to').html('Valid from should be less than or equal to valid to date.');
			return false;
		}else{
			$('#valid_to').addClass('form-control-success');
			$('#valid_to').removeClass('form-control-danger');
			$('.err_valid_to').html('');
		}
		
		$('.disable').prop('disabled', true);
			
		$('#offer_admin').submit();
	
	});
	
	$('#size_admin').on('submit', function(e){
		
		var size_name =  $.trim($('#size_name').val());
		
		if(size_name=='')
		{
			$('#size_name').addClass('form-control-danger');
			$('#size_name').focus();
			$('.err_size_name').html('Size name can\'t be blank.');
			return false;
		}else{
			$('#size_name').addClass('form-control-success');
			$('#size_name').removeClass('form-control-danger');
			$('.err_size_name').html('');
		}
		
	
		$('.disable').prop('disabled', true);
			
		$('#size_admin').submit();
	
	});
	
	$( document ).on( "click", ".removeGallery", function() {
		var gallery_uploaded_filename = $('#gallery_uploaded_filename').val();
		var index = $(this).attr("id");
		var array = gallery_uploaded_filename.split(",");
		array.splice(index, 1);
		remove_id = array.join(',');
		while(remove_id.charAt(0) == ',')
		{
		 remove_id = remove_id.substring(1);
		}
		$('#gallery_uploaded_filename').val(remove_id);	
		$(this).parent(".pip").remove();
	});
	
	$('#addon_cat_master').on('submit', function(e){
		
		var name =  $.trim($('#name').val());
		
		if(name=='')
		{
			$('#name').addClass('form-control-danger');
			$('#name').focus();
			$('.errName').html('AddOn category name can\'t be blank.');
			return false;
		}else{
			$('#name').addClass('form-control-success');
			$('#name').removeClass('form-control-danger');
			$('.errName').html('');
		}
		
	
		$('.disable').prop('disabled', true);
			
		$('#addon_cat_master').submit();
	
	});
	
	$('#addon_item').on('submit', function(e){
		
		var name =  $.trim($('#name').val());
		var price =  $.trim($('#price').val());
		
		if(name=='')
		{
			$('#name').addClass('form-control-danger');
			$('#name').focus();
			$('.err_name').html('AddOn item name can\'t be blank.');
			return false;
		}else{
			$('#name').addClass('form-control-success');
			$('#name').removeClass('form-control-danger');
			$('.err_name').html('');
		}
		
		// if(price=='')
		// {
			// $('#price').addClass('form-control-danger');
			// $('#price').focus();
			// $('.err_price').html('Price can\'t be blank.');
			// return false;
		// }else{
			// $('#price').addClass('form-control-success');
			// $('#price').removeClass('form-control-danger');
			// $('.err_price').html('');
		// }
		
		var checkboxes = document.getElementsByClassName('check_all'); // puts all your checkboxes in a variable
		var okay = false;
		for(var i=0;i<checkboxes.length;i++)
		{
			if(checkboxes[i].checked)
			{
				okay = true;
				break;
			}
		}
		 if(okay){
			$('.check_all').parent().removeClass('form-control-danger');
			$('.err_categories').html('');
		 }else{
			$('.check_all').parent().addClass('form-control-danger');
			$('.err_categories').html('Select atlease one category.');
			return false;
		 }
	
		$('.disable').prop('disabled', true);
			
		$('#addon_item').submit();
	
	});
	
	$(".removeGalleryExist").click(function(){
		var gallery_img_remove = $('#gallery_img_remove').val();
		var id = $(this).attr("id");
		var array = gallery_img_remove.split(",");
		var index = array.indexOf(id);
		if(index== -1)
		{
			array.push(id);
		}
		remove_id = array.join(',');
		while(remove_id.charAt(0) == ',')
		{
		 remove_id = remove_id.substring(1);
		}
		
		$('#gallery_img_remove').val(remove_id);
		$(this).parent(".pip").remove();
		//$('#logo_status').val('1')
    });
	
	$(document).on('click','.removeLogo',function(){
		$(this).parent(".pip").remove();
		$('#logo').val('')
		$('#logo_status').val(1)
	});
	
	$(document).on('click','.removeSingleImg',function(){
		$(this).parent(".pip").remove();
		$('#image').val('')
		$('#image_status').val(1)
	});
  
	$( document ).on( "click", ".getSubCategory", function() {
		var id = $(this).attr( "master_cat_id");
		var main_cat_ids = $('#main_cat_ids').val();
		var sub_cat_ids = $('#sub_cat_ids').val();
		var array = main_cat_ids.split(",");
		var index = array.indexOf(id);
			
		if($(this).is(':checked')){
			if(index== -1)
			{
				array.push(id);
			}
			
			
		} else {
			if (index > -1) {
				array.splice(index, 1);
			}
		}
		cat_id = array.join(',');
		while(cat_id.charAt(0) == ',')
		{
		 cat_id = cat_id.substring(1);
		}
		$('#main_cat_ids').val(cat_id);
		
		$('.loading-image').show();
		url = site_url+'/admin/product/getSubCategories';
		$.ajax({
			'type': "POST",
			'url': url,
			'data': { cat_id: cat_id,sub_cat_ids:sub_cat_ids},
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				$('.subCatetoggle').css('display','flex')
				$('.append_sub_category').html(data)
			}
		});
					
		
	});
	
	$( document ).on( "click", ".subcategoryIds", function() {
		var id = $(this).attr( "master_sub_cat_id");
		var sub_cat_ids = $('#sub_cat_ids').val();
		var array = sub_cat_ids.split(",");
		var index = array.indexOf(id);
			
		if($(this).is(':checked')){
			if(index== -1)
			{
				array.push(id);
			}
			
			
		} else {
			if (index > -1) {
				array.splice(index, 1);
			}
		}
		cat_id = array.join(',');
		while(cat_id.charAt(0) == ',')
		{
		 cat_id = cat_id.substring(1);
		}
		$('#sub_cat_ids').val(cat_id);
	});
	
	$('.changeStockstatus').on('click', function () {
		var self = this;
		var url = $(this).attr('data-href');
		var id = $(this).attr('data-id');
		var status = $(this).attr('data-status');
		$('.loading-image').show();
		$.ajax({
			'type': "POST",
			'url': url,
			'data': { id: id, status: status },
			'dataType': 'json',
			success: function (data) {
				$('.loading-image').hide();
				if (status == 1) {
					$(self).attr('data-status', 0)
				}
				else if (status == 0) {
					$(self).attr('data-status', 1)

				}
							
				$.alert(data.msg);
			}
		});
	});
	
	$('#add_product').on('submit', function(e){
		
		var item_name =  $.trim($('#item_name').val());
		
		if(item_name=='')
		{
			$('#item_name').addClass('form-control-danger');
			$('#item_name').focus();
			$('.err_item_name').html('Item name can\'t be blank.');
			return false;
		}else{
			$('#item_name').addClass('form-control-success');
			$('#item_name').removeClass('form-control-danger');
			$('.err_item_name').html('');
		}
		
	
		
		var checkboxes = document.getElementsByClassName('getSubCategory'); // puts all your checkboxes in a variable
		var okay = false;
		for(var i=0;i<checkboxes.length;i++)
		{
			if(checkboxes[i].checked)
			{
				okay = true;
				break;
			}
		}
		 if(okay){
			$('.getSubCategory').parent().removeClass('form-control-danger');
			$('.err_categories').html('');
		 }else{
			$('.getSubCategory').parent().addClass('form-control-danger');
			$('.err_categories').html('Select at lease one category.');
			return false;
		 }
		 
		
		if($('#meal_deal').is(':checked')){	
			
			// var meal_deal_no_person =  $.trim($('#meal_deal_no_person').val());
			// if(meal_deal_no_person=='')
			// {
				// $('#meal_deal_no_person').addClass('form-control-danger');
				// $('#meal_deal_no_person').focus();
				// $('.err_meal_deal_no_person').html('No of person can\'t be blank.');
				// return false;
			// }else{
				// $('#meal_deal_no_person').addClass('form-control-success');
				// $('#meal_deal_no_person').removeClass('form-control-danger');
				// $('.err_meal_deal_no_person').html('');
			// }
		} 
		$('.disable').prop('disabled', true);
			
		$('#add_product').submit();
	
	});
	
	$('.addProductVariation').on('click', function () {
		var size = $('#size').html()
		let r = Math.random().toString(36).substring(7);
		var append = '<div class="col-md-12 col-sm-12 row mt-15"><div class="col-sm-12 col-md-3" ><select class="form-control" name="size['+r+']" >'+size+'</select></div><div class="col-sm-12 col-md-3"><input type="text" name="max_price['+r+']" class="form-control" placeholder="Max Price" onkeypress="return isNumberDecimal(event)"></div><div class="col-sm-12 col-md-3"><input type="text" name="discount_price['+r+']" class="form-control" placeholder="Discount Price" onkeypress="return isNumberDecimal(event)"></div><div class="col-sm-12 col-md-2"><input type="text" name="quantity['+r+']" class="form-control" placeholder="Quantity" onkeypress="return isNumber(event)"></div><div class="col-sm-12 col-md-1"><a href="javascript:void(0)" class="btn btn-danger removeProductVariation  btn-sm">-</a></div></div>';
		$('.append_variation').append(append);
	});
	
	$('.addDeliveryChargesVariation').on('click', function () {
		let r = Math.random().toString(36).substring(7);
		var append = '<tr><td><input type="text" name="distance_from['+r+']"   class="form-control small_textbox" placeholder="From" onkeypress="return isNumberDecimal(event)">  &nbsp;TO&nbsp;<input type="text" name="distance_to['+r+']"   class="form-control small_textbox" placeholder="To" onkeypress="return isNumberDecimal(event)"> </td><td><select class="form-control" name="distance_type['+r+']"><option value="mi">Miles</option> <option value="km" >Kilometers</option></select></td><td><input type="text" name="price['+r+']"  class="form-control" placeholder="Price"  onkeypress="return isNumberDecimal(event)">  </td><td><a href="javascript:void(0)" class="btn btn-danger removeDeliveryChargesVariation  btn-sm">-</a></td></tr>';
		$('.append_delivery_charges').append(append);
	});
	
	$(document).on('click','.removeProductVariation', function () {
		$(this).parent().parent().remove();
	});
	
	$(document).on('click','.removeDeliveryChargesVariation', function () {
		$(this).parent().parent().remove();
	});
	
	$( document ).on( "click", "#meal_deal", function() {
		if($(this).is(':checked')){			
			$('.no_meal_deal_addon_category').css('display','none')
			$('.meal_deal_addon_category').css('display','flex')
		} else {
			
			$('.no_meal_deal_addon_category').css('display','flex')
			$('.meal_deal_addon_category').css('display','none')
		}
	});

	$('#ingredient_admin').on('submit', function(e){
		
		var ingredients_name =  $.trim($('#ingredients_name').val());
		
		if(ingredients_name=='')
		{
			$('#ingredients_name').addClass('form-control-danger');
			$('#ingredients_name').focus();
			$('.err_ingredients_name').html('Ingredient name can\'t be blank.');
			return false;
		}else{
			$('#ingredients_name').addClass('form-control-success');
			$('#ingredients_name').removeClass('form-control-danger');
			$('.err_ingredients_name').html('');
		}
		
	
		$('.disable').prop('disabled', true);
			
		$('#ingredient_admin').submit();
	
	});

	$( document ).on( "click", ".view-order", function() {
		var id = $(this).attr( "data-id");
		var user_id = $(this).attr( "user-id");
		var restaurant_id = $(this).attr( "restaurant-id");
		$('.loading-image').show();
		$.ajax({
			url: site_url+"admin/order/orderDetailView",
			method:"POST",
			'data': { id: id,user_id:user_id,restaurant_id:restaurant_id},
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				//alert(data)
				$('.appendOrderDetail').html(data);
				$('#viewOrderDetail').modal('show');
			}
		})
	});

	$('.change-order-status').on('click', function () {
		$('#driver option').removeAttr('disabled');
		$("#driver option[value='']").attr('disabled', 'disabled');
		$(".selectpicker").selectpicker('refresh');
		$('.driverDropdown').css("display", "none")
		var id = $(this).attr('data-id');
		var user_id = $(this).attr('user-id');
		var order_type = $(this).attr('data-order-type');
		var status = $(this).attr('current-status');
		$('#order_id').val(id)
		$('#user_id').val(user_id)
		$('#order_type').val(order_type)

		if(status!=1 && status!=0){
			$('#new_status').val(status)
		}
		if(order_type==2){
			$('.driverDropdown').removeAttr("style")

			if(status==3 || status==11 || status==1){
				$('.driverDropdown').removeAttr("style")
				$('.toggleTime').removeAttr("style")
			}else{
				$('.driverDropdown').css("display", "none")
				$('.toggleTime').css("display", "none")
			}
		} else if(order_type==1){

			if(status==3 || status==11 || status==1){
				$('.driverDropdown').css("display", "none")
				$('.toggleTime').removeAttr("style")
			}else{
				$('.driverDropdown').css("display", "none")
				$('.toggleTime').css("display", "none")
			}
		}
		//$('.loading-image').show();
		var url = site_url+'admin/order/checkOrderInvitaion' 
		 $.ajax({
			'type': "POST",
			'url': url,
			'data': { order_id: id},
			'dataType': 'json',
			success: function (data) {
				$('.loading-image').hide();
				var user = data.data
				user.forEach((entry) => {
					//entry = JSON.stringify(entry)
					var driver_user_id = entry['driver_user_id']
					$("#driver option[value="+driver_user_id+"]").attr('disabled', 'disabled');
				})
				$(".selectpicker").selectpicker('refresh');
				
				//$('.status_'+order_id).html(status)
			}
		 });

		$('#changeStatusModal').modal('show')
	
	});

	$('#new_status').on('change', function () {

		var status = $('option:selected', this).val();
		var order_type = $('#order_type').val();
		if(order_type==2){
			$('.driverDropdown').removeAttr("style")

			if(status==3 || status==11 || status==1){
				$('.driverDropdown').removeAttr("style")
				$('.toggleTime').removeAttr("style")
			}else{
				$('.driverDropdown').css("display", "none")
				$('.toggleTime').css("display", "none")
			}
		} else if(order_type==1){
			$('.driverDropdown').css("display", "none")
			if(status==3 || status==11 || status==1){
				$('.toggleTime').removeAttr("style")
			}else{
				$('.toggleTime').css("display", "none")
			}
		}
	
	});

	$('.newOrderReject').on('click', function () {
		var self = this;
		var order_id = $(this).attr('data-id');
		var new_status = $(this).attr('data-status');
		var order_remark = '';
		var user1 = $(this).attr('user-id');
		var delivery_time = 0;
		var status ='';
		status ='<span class="badge badge-danger">Rejected</span>';
		var url = site_url+'admin/order/changeorderstatus' 
		$.confirm({
			title: 'Confirm!',
			content: 'Are you sure to change the status!',
			buttons: {
				confirm: function () {
					$('.loading-image').show();
					$.ajax({
						'type': "POST",
						'url': url,
						'data': { order_id: order_id, new_status: new_status,order_remark:order_remark ,user:user1,delivery_time:delivery_time},
						'dataType': 'json',
						success: function (data) {
							$('.loading-image').hide();
							$('.status_'+order_id).html(status)
							window.location.href = site_url+'admin/dashboard';
							$.alert(data.msg);
						}
					});
				},
				cancel: function () {
				}
			}
		});
	
	});

	$( document ).on( "click", ".openTimePopup", function() {
		$('.driverDropdown').css("display", "none")
		var id = $(this).attr('data-id');
		var user_id = $(this).attr('user-id');
		var order_type = $(this).attr('data-order-type');
		$('#time_order_id').val(id)
		$('#time_user_id').val(user_id)
		$('#time_order_type').val(order_type)
		if(order_type==2){
			$('.driverDropdown').removeAttr("style")
		}
		$('#acceptOrderTime').modal('show')
	});

	$('.submitAcceptOrderTime').on('click', function () {
		var self = this;
		var order_id = $('#time_order_id').val();
		var user1 = $('#time_user_id').val();
		var time_order_type = $('#time_order_type').val();
		var delivery_time = $('#delivery_time').val();
		var driver = (time_order_type==2)?$('#driver').val():'';
		var new_status = 3;
		var order_remark = '';
		
		if(delivery_time=='')
		{
			$('#delivery_time').addClass('form-control-danger');
			$('#delivery_time').focus();
			$('.delivery_time_error').html('Accepted for can\'t be blank.');
			return false;
		}else{
			$('#delivery_time').addClass('form-control-success');
			$('#delivery_time').removeClass('form-control-danger');
			$('.delivery_time_error').html('');
		}

		var status ='<span class="badge badge-success">Accepted</span>';
		var url = site_url+'admin/order/changeorderstatus' 
		$('.loading-image').show();
		$.ajax({
			'type': "POST",
			'url': url,
			'data': { order_id: order_id, new_status: new_status,order_remark:order_remark ,user:user1,delivery_time:delivery_time,driver:driver},
			'dataType': 'json',
			success: function (data) {
				$('.loading-image').hide();
				$('.status_'+order_id).html(status)
				if(new_status==3)
				{
					var id = order_id;
					var user_id = user1;
					$('.loading-image').show();
					$.ajax({
						url: site_url+"admin/order/orderDetailView",
						method:"POST",
						'data': { id: id,user_id:user_id,'type':'direct'},
						'dataType': 'html',
						success: function (data) {
							$('.loading-image').hide();
							$('.appendOrderDetail').html(data);
							window.location.href = site_url+'admin/dashboard';
						}
					})
				}else{
					window.location.href = site_url+'admin/dashboard';
					$.alert(data.msg);
					
				}
			}
		})
	});

	$('.submitStatus').on('click', function () {

		var self = this;
		var order_id = $('#order_id').val();
		var user1 = $('#user_id').val();
		var time_order_type = $('#order_type').val();
		var delivery_time = $('#delivery_time').val();
		var driver = (time_order_type==2)?$('#driver').val():[];
		var new_status = $('#new_status').val();
		var order_remark = $('#order_remark').val();
	
		if(new_status=='')
		{
			$('#new_status').addClass('form-control-danger');
			$('#new_status').focus();
			$('.new_status_error').html('New status can\'t be blank.');
			return false;
		}else{
			$('#new_status').addClass('form-control-success');
			$('#new_status').removeClass('form-control-danger');
			$('.new_status_error').html('');
		}

		if(new_status!=3 && new_status!=11 && new_status!=1){
			delivery_time = 0;
			driver =[];
		}

		var status ='';
		if(new_status==1)
		{
			status ='<span class="badge badge-primary">New</span>';
		}if(new_status==2)
		{
			status ='<span class="badge badge-primary">Read</span>';
		}else if(new_status==3)
		{
			status ='<span class="badge badge-success">Accepted</span>';
		}else if(new_status==4)
		{
			status ='<span class="badge badge-danger">Rejected</span>';
		}else if(new_status==5)
		{
			status ='<span class="badge badge-warning">pending</span>';
		}else if(new_status==6)
		{
			status ='<span class="badge badge-danger">Cancelled</span>';
		}else if(new_status==7)
		{
			status ='<span class="badge badge-primary">Out for delivery</span>';
		}else if(new_status==8)
		{
			status ='<span class="badge badge-success">Delivered</span>';
		}
				
		var url = site_url+'admin/order/changeorderstatus' 
		$.confirm({
			title: 'Confirm!',
			content: 'Are you sure to change the status!',
			buttons: {
				confirm: function () {
					$('.loading-image').show();
					$.ajax({
						'type': "POST",
						'url': url,
						'data': { order_id: order_id, new_status: new_status,order_remark:order_remark ,user:user1,delivery_time:delivery_time,driver:driver},
						'dataType': 'json',
						success: function (data) {
							$('.loading-image').hide();
							$('#changeStatusModal').modal('hide')
							$('#order_id_status_'+order_id).attr('current-status', new_status)
							$('.status_'+order_id).html(status)
							$.alert(data.msg);
						}
					});
				},
				cancel: function () {
				}
			}
		});
	
	});

	$( document ).on( "click", ".printNewOrder", function() {
		var id = $(this).attr( "data-id");
		var user_id = $(this).attr( "user-id");
		var restaurant_id = $(this).attr( "restaurant-id");
		$('.loading-image').show();
		$.ajax({
			url: site_url+"admin/order/orderDetailView",
			method:"POST",
			'data': { id: id,user_id:user_id,restaurant_id:restaurant_id,'type':'direct'},
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				$('.appendOrderDetail').html(data);
			}
		})
	});

	$( document ).on( "click", ".settle", function() {
		$('.loading-image').show();
		$.ajax({
			url: site_url+"admin/order/settlement",
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				if(data > 0)
				{
					$('.msg_error_success').show()
					$('.fixed_success').html('All order has been settled.')
				}else{
					$('.msg_error_success').hide()
				}
			}
		})
	});

	$('#add_driver').on('submit', function(e){
		var name =  $.trim($('#name').val());
		var phone =  $.trim($('#mobile').val());
		var username =  $.trim($('#username').val());
		var password =  $.trim($('#password').val());
		var c_password =  $.trim($('#c_password').val());	
		var id =  $.trim($('#id').val());	
		var country =  $.trim($('#country').val());
		var state =  $.trim($('#state').val());
		var city =  $.trim($('#city').val());
		var pincode =  $.trim($('#pincode').val());
		var address =  $.trim($('#address').val());	
		var driver_delivery_coverd =  $.trim($('#driver_delivery_coverd').val());	
		var driver_distance_type =  $.trim($('#driver_distance_type').val());	
		if(name=='')
		{
			$('#name').addClass('form-control-danger');
			$('#name').focus();
			$('.errName').html('Driver name can\'t be blank.');
			return false;
		}else{
			$('#name').addClass('form-control-success');
			$('#name').removeClass('form-control-danger');
			$('.errName').html('');
		}
			
		if(phone=='')
		{
			$('#mobile').addClass('form-control-danger');
			$('#mobile').focus();
			$('.errPhone').html('mobile number can\'t be blank.');
			return false;
		}else{
			if((phone.length!= 11 && const_country=='UK') || (phone.length!= 10 && const_country=='IN')){
					$('#mobile').addClass('form-control-danger');
					$('#mobile').focus();
					$('.errPhone').html("Mobile number should be "+mobile_length+" digits.");
					return false;
			}else {
					$('#mobile').removeClass('form-control-danger');
					$('#mobile').addClass('form-control-success');
					$('.errPhone').html('');
				}
		}
		
		if(country=='')
		{
			$("#country").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#country').focus();
			$('.errCountry').html('Country can\'t be blank.');
			return false;
		}else{
			$('#country').addClass('form-control-success');
			$("#country").next("span.select2-container").find("span.select2-selection--single").css('border-color',''); 
			$('.errCountry').html('');
		}
			
		if(state=='')
		{
			$("#state").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#state').focus();
			$('.errState').html('State can\'t be blank.');
			return false;
		}else{
			$('#state').addClass('form-control-success');
			$("#state").next("span.select2-container").find("span.select2-selection--single").css('border-color',''); 
			$('.errState').html('');
		}
			
		if(city=='')
		{
			$("#city").next("span.select2-container").find("span.select2-selection--single").css('border-color','#dc3545'); 
			$('#city').focus();
			$('.errCity').html('City can\'t be blank.');
			return false;
		}else{
			$('#city').addClass('form-control-success');
			$("#city").next("span.select2-container").find("span.select2-selection--single").css('border-color','');
			$('.errCity').html('');
		}
			
		if(pincode=='')
		{
			$('#pincode').addClass('form-control-danger');
			$('#pincode').focus();
			$('.errPincode').html('Pincode can\'t be blank.');
			return false;
		}else{
			$('#pincode').addClass('form-control-success');
			$('#pincode').removeClass('form-control-danger');
			$('.errPincode').html('');
		}
			
		if(address=='')
		{
			$('#address').addClass('form-control-danger');
			$('#address').focus();
			$('.errAddress').html('Address can\'t be blank.');
			return false;
		}else{
			$('#address').addClass('form-control-success');
			$('#address').removeClass('form-control-danger');
			$('.errAddress').html('');
		}
		
		if(driver_delivery_coverd=='')
		{
			$('#driver_delivery_coverd').addClass('form-control-danger');
			$('#driver_delivery_coverd').focus();
			$('.err_driver_delivery_coverd').html('Driver delivery coverd can\'t be blank.');
			return false;
		}else{
			$('#driver_delivery_coverd').addClass('form-control-success');
			$('#driver_delivery_coverd').removeClass('form-control-danger');
			$('.err_driver_delivery_coverd').html('');
		}


		if(username=='')
		{
			$('#username').addClass('form-control-danger');
			$('#username').focus();
			$('.errusername').html('Username can\'t be blank.');
			return false;
		}else{
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(username)) 
			{
				$('#username').addClass('form-control-danger');
				$('#username').focus();
				$('.errusername').html('Please provide a valid email address');
				return false;
			}
			else
			{
				$('#username').addClass('form-control-success');
				$('#username').removeClass('form-control-danger');
				$('.errusername').html('');
			}
		}
		
		if(id=='' || id<=0 ){
			if(password=='')
			{
				$('#password').addClass('form-control-danger');
				$('#password').focus();
				$('.err_password').html('Password can\'t be blank.');
				return false;
			}else{
				$('#password').addClass('form-control-success');
				$('#password').removeClass('form-control-danger');
				$('.err_password').html('');
			}
			
			if(c_password=='')
			{
				$('#c_password').addClass('form-control-danger');
				$('#c_password').focus();
				$('.err_c_password').html('Confirm password can\'t be blank.');
				return false;
			}else{
				
				
				if (password!=c_password) 
				{
					$('#c_password').addClass('form-control-danger');
					$('#c_password').focus();
					$('.err_c_password').html('Password and confirm does not match');
					return false;
				}
				else
				{
					$('#c_password').addClass('form-control-success');
					$('#c_password').removeClass('form-control-danger');
					$('.err_c_password').html('');
				}
			}
		}
		
		$('#add_driver').submit();
        
	});

	$( document ).on( "click", ".view-user-detail", function() {
		var user_id = $(this).attr( "user-id");
		$('.loading-image').show();
		$.ajax({
			url: site_url+"admin/user/userDetailView",
			method:"POST",
			'data': {user_id:user_id},
			'dataType': 'html',
			success: function (data) {
				$('.loading-image').hide();
				//alert(data)
				$('.appendOrderDetail').html(data);
				$('#viewOrderDetail').modal('show');
			}
		})
	});

});


