
<?php
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

$checkout_type = 'Collection';

$a = $this->session->userdata('checkout_type');
if($a==2){
	$checkout_type = 'Delivery';
}else if($a==1){
	$checkout_type = 'Collection';
}else if($a==3){
	$checkout_type = 'Dinein';
}

?>
<div class="rootx">
    <div>
		<div class="ant-collapse ant-collapse-icon-position-left">

		</div>
		<div class="clearfix"></div>
		
		<div class="container mt-3 mb-3 link-background ">
		
		
			<section class="product_list pt-5">
				<div class="container">
					
				  <div class="row">
                    <div class="col-xl-12">
                        <div class="hero-cap text-center">
                            <img src="<?=base_url()?>/assets/frontend/img/success.png" width="150">
                            <h2>Success</h2>
                        </div>
                    </div>
                </div>
					<div class="row">
						<div class="col-md-12 row">
							<div class="col-lg-4"></div>
          <div class="col-lg-4">
		  <div class="order_box">
              <h3>Thank You For Your Order!</h3>
            <p class="leadtext-center">Your order has been placed and is being processed.<br>
				Your order id is : <b><?php echo $this->session->userdata('order_id');?></b> 
				and your order type <?php echo strtoupper(($this->session->userdata('payment_type')=='2')?'Online':'Cash'); ?>.
			</p>
              <ul class="list list_2">
                <li>
                  <a href="#">Order Status #
                    <span>Success</span>
                  </a>
                </li>
				
				<li>
                  <a href="#">Sub Total - 
                    <span><?php echo $this->session->userdata('success_arr')['Subtotal']; ?></span>
                  </a>
                </li>  
				<?php 
				if($this->session->userdata('success_arr')['orderType']==2){?>
				<li>
				  <a href="#">Delivery Fee - 
					<span><?php echo $this->session->userdata('success_arr')['deliveryFee']; ?></span>
				  </a>
				</li>
				
				<?php }
				
				?>	
				<li>
				  <a href="#">Service Charge - 
					<span><?php echo $this->session->userdata('success_arr')['serviceCharge']; ?></span>
				  </a>
				</li>
				<li>
					<a href="#">Discount - 
						<span><?php echo $this->session->userdata('success_arr')['discount']; ?></span>
					</a>
				</li>
				
				<li>
					<a href="#">Order Type  - 
						<span><?php echo $checkout_type; ?></span>
					</a>
				</li>
				
				<li>
					<a href="#">Payment Mode  - 
						<span><?php echo strtoupper(($this->session->userdata('payment_type')=='2')?'Online':'Cash'); ?></span>
					</a>
				</li>
				
                <li>
                  <a href="#">Total - 
                    <span><?php echo $this->session->userdata('success_arr')['grandTotal']; ?></span>
                  </a>
                </li>
              </ul>
			  <?php
			  
			  $postcode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
			  $r1  = (base_url().'area/'.$postcode);
			  
			 
				if($checkout_type==2 || $checkout_type==1){
					$r1  = redirect(base_url().'area/'.$postcode);
				}else if($checkout_type==3){
					$r1 = base_url().'dinein';
				}
				
			  ?>
			  <a  href="<?php echo $r1; ?>" class="btn btn-primary btn-sm btn-block">Continue to homepage</a>
            </div>

		  </div>
		  <div class="col-lg-4"></div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<?php

if(!empty($this->session->userdata('user_id'))){
$user_id = $this->session->userdata('user_id');
}else{
	$user_id = $this->session->userdata('guest_user_id');
}
$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
$detail = array(
	"userId" => $user_id,
	"cookieId" => $_COOKIE['cookieId'],
	"restaurantId" => $restaurantId,
	"cartId" => $this->session->userdata('cartId')
);
$url = API_URL.'/api/restaurant/cart/delete';
$response = postCurlWithAuthorization($url,$detail);
		
$this->session->unset_userdata('restaurant_id');
$this->session->unset_userdata('cartId');
$this->session->unset_userdata('checkout_type');
$this->session->unset_userdata('earn_loyalty_point');
$this->session->unset_userdata('address_id');
$this->session->unset_userdata('billing_address');
$this->session->unset_userdata('redirect');
$this->session->unset_userdata('order_id');
$this->session->unset_userdata('user_loyalty_point');
$this->session->unset_userdata('success_arr');
$this->session->unset_userdata('loyaltyPointUsed');
$this->session->unset_userdata('promoCode');
$this->session->unset_userdata('payment_type');
$this->session->unset_userdata('table_number');
$this->session->unset_userdata('dinein_name');
$this->session->unset_userdata('dinein_email');
$this->session->unset_userdata('dinein_phone');

?>