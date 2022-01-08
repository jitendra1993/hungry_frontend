<?php
$postcode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
$r1  = (base_url().'area/'.$postcode);
?>
<div class="container mt-3 mb-3 link-background ">
	<section class="product_list pt-5">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="hero-cap text-center"><h2> Cancellation</h2></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 row">
					<div class="col-lg-3"></div>
					<div class="col-lg-6">
						<div class="order_box">
							<h4> Your order has been canceled.</h4>
							<a  href="<?php echo $r1; ?>" class="btn btn-primary btn-sm btn-block">Continue to homepage</a>
						</div>
					</div>
					<div class="col-lg-3"></div>
				</div>
			</div>
		</div>
	</section>
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