<?php
// echo '<pre>';
// print_r($store_info);
// echo '</pre>';
?>
<footer id="footer" class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                <div class="h4">Order takeaway food online in Aberdeen with HungrytoEat</div>

                <ul class="list-inline followus">
                    <li><a target="_blank" href="<?php echo !empty($social_media['facebook'])?$social_media['facebook']:'#';?>"><i class="fa fa-facebook"></i></a></li>
                    <li><a target="_blank" href="<?php echo !empty($social_media['youtube'])?$social_media['youtube']:'#';?>"><i class="fa fa-youtube"></i></a></li>
                    <li><a target="_blank" href="<?php echo !empty($social_media['twitter'])?$social_media['twitter']:'#';?>"><i class="fa fa-twitter"></i></a></li>
                    <li><a target="_blank" href="<?php echo !empty($social_media['instagram'])?$social_media['instagram']:'#';?>"><i class="fa fa-instagram"></i></a></li>
                    <li><a target="_blank" href="<?php echo !empty($social_media['linkedin'])?$social_media['linkedin']:'#';?>"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div><!-- end of col -->
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="ht4">HungrytoEat Information</div>
                <ul class="list-unstyled ftnav">
                    <li><a href="<?php echo base_url();?>">Home</a></li>
                    <li><a href="#">How It Works</a></li>
                    <li><a href="<?php echo base_url('about-us');?>">About Us</a></li>
                    <li><a href="<?php echo base_url('contact-us');?>">Contact Us</a></li>
                    
            </div><!-- end of col -->
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="ht4">Takeaway Cuisine</div>
                <ul class="list-unstyled ftnav">
                    <li><a href="#">Browse all takeaway types</a></li>
                    <li><a href="#">Chinese</a></li>
                    <li><a href="#">Chip Shop</a></li>
                    <li><a href="#">Indian</a></li>
                    <li><a href="#">Kebabs</a></li>
                    <li><a href="#">Pizza</a></li>
                    <li><a href="#">Portuguese</a></li>
                    <li><a href="#">Thai</a></li>
            </div><!-- end of col -->
        </div><!-- end of row -->
        <div class="clear"></div><!-- end of clear -->
    </div><!-- end of container -->
    <div class="footer_bottom">
        <div class="container">
            <div class="col-sm-6 col-xs-12 pull-right text-right">
                <ul class="list-inline">
                    <li><a href="<?php echo base_url('terms');?>">Terms & Condition</a></li>
                    <li><a href="<?php echo base_url('privacy');?>">Privacy </a></li>
                    <li><a href="<?php echo base_url('policy');?>">Policy</a></li>
                    <li><a href="#">How do we use cookies?</a></li>
                </ul>
            </div><!-- end of col -->
            <div class="col-sm-6 col-xs-12">
                <div class="copyright"> &copy; Copyright <script>document.write(new Date().getFullYear());</script>
                    Hungry to Eat. All Rights Reserved | <a href="#" target="_blank">App Web</a></div>
            </div><!-- end of col -->
        </div><!-- end of container -->
    </div><!-- end of footer_bottom -->
</footer><!-- end of footer -->


</div><!-- end of wrapper -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="<?=base_url()?>assets/frontend/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/frontend/js/custome.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/frontend/js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/frontend/js/main.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/frontend/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/frontend/js/jquery.timepicker.min.js"></script>
<div class="modal" id="addresspopup"  tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add/Edit Address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?php 
            $attributes = array('class' => 'row address', 'id' => 'address'); 
            echo form_open('user/profile',$attributes);
            ?>
            <input type="hidden" id="address_id" name="address_id" value="">
            <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" id="address_name" name="address_name"
                    placeholder="Full Name" value="" />
                <span class="error has-danger address_name_error"></span>
            </div>

            <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" id="address_phoneNumber" name="address_phoneNumber"
                    placeholder="Phone Number" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')" value="" />
                <span class="has-danger error_address_phoneNumber error"></span>
            </div>

            <div class="col-md-12 form-group p_star">
                <input type="text" class="form-control" id="address_pincode" name="address_pincode"
                    placeholder="Postcode" value="" />
                <span class="has-danger error pincode_add_error"></span>
            </div>

            <div class="col-md-12 form-group p_star">
                <textarea class="form-control" id="address_addressLine1" name="address_addressLine1"
                    placeholder="Address Line 1" value=""></textarea>
                <span class="has-danger error address1_add_error"></span>
            </div>

            <div class="col-md-12 form-group p_star">
                <textarea class="form-control" id="address_addressLine2" name="address_addressLine2"
                    placeholder="Address Line 2" value=""></textarea>
                <span class="has-danger error address2_add_error"></span>
            </div>

            <div class="col-md-12 form-group">
                <select name="address_type" id="address_type" class="form-control">
                    <option value="1" selected>Home</option>
                    <option value="2">Office</option>
                </select>
                <span class="error has-danger address_type_error"></span>
            </div>


            <div class="col-md-12  form-group p_star">
                <button type="button" class="btn-block btn btn-success ajaxAddress disable">Submit</button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
  </div>
</div>

<div class="modal" id="restrictedModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Age Restricted</h5>
      </div>
      <div class="modal-body">
        <div class="text-center">The sale of the item is restricted to customers of at least 18 years old and will be subject to a valid ID being provided.</div>
			<div class="search-icon text-center">
                <input type="hidden" value="" name="restricted_popup_item_id" id="restricted_popup_item_id">
				<button type="button" class="btn btn-success restrictedCookieAccept">Yes</button>
				<button type="button" class="btn btn-primary closeRestrictedPopup" >Close</button>
			</div>
      </div>
    </div>
  </div>
</div>
<?php
 echo $class = $this->router->fetch_class();
 echo $method = $this->router->fetch_method();
?>

<script>
$(function () {    
   
    var classname = '<?php echo $class;?>'
    var method = '<?php echo $method;?>'
  
    if(classname=='Restaurant' && method=='list'){
        $( document ).on( "change", 'input[type="checkbox"]', function() {
            var category_arr = []
            $("input:checkbox[name=categories]:checked").each(function() {
                category_arr.push($(this).val())
            });
            var category_str = category_arr.join()
            var restaurnat_open = 0;
            var delivery = 0;
            var collection = 0;
            var order = 0;

            if($("input:checkbox[name=restaurnat_open]").is(":checked")){
                restaurnat_open =1;
            }

            if($("input:checkbox[name=delivery]").is(":checked")){
                delivery =1;
            }

            if($("input:checkbox[name=collection]").is(":checked")){
                collection =1;
            }

            var pageno = $('#start').val()==0?'':$('#start').val()
            var postocde = $('#postocde').val()

            var url = `${site_url}area/${postocde}?category=${category_str}&restaurnat_open=${restaurnat_open}&delivery=${delivery}&collection=${collection}&order=${order}`;
            window.location=url
        });
    }
});
</script>
<div class="modal" id="viewOrderDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <span class="appendOrderDetail"></span>
</div>

</body>

</html>