<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session'));
		if(is_logged_in() &&  is_user_type()!='user'){
			redirect(base_url());
		}
	}
	
		
	public function index(){
		redirect(base_url('checkout'));
	}
	
	public function checkout($id = ''){

		$restaurantId = $this->session->userdata('restaurant_id');
		$checkout_type = $this->session->userdata('checkout_type');
		$postcode = $this->session->userdata('postcode');
		$this->session->set_userdata('redirect','checkout');

		$merchant_info = get_store_detail_by_id($restaurantId);
		$setting = $merchant_info['restaurant_setting'];
		$restaurant_info = $merchant_info['restaurant_info'];
		$merchant_time = $merchant_info['merchant_time'];
		$merchant_show_time = $setting['merchant_show_time'];

		$admin_info = get_admin_store_detail();
		$adminsetting = $admin_info['deliverySetting'];
		
		$deliverySetting = !empty($admin_info['deliverySetting'])?$admin_info['deliverySetting']:'';
		if(empty($setting)){

			redirect(base_url());
		}

		$todayRestaurantTime = $setting['store_time'];
		$pre_order = $setting['pre_order'];

		$is_open = $todayRestaurantTime['is_open'];
		$open_time_mrng = date('H:i',strtotime($todayRestaurantTime['open_time_mrng']));
		$close_time_mrng = date('H:i',strtotime($todayRestaurantTime['close_time_mrng']));
		$open_time_evening = date('H:i',strtotime($todayRestaurantTime['open_time_evening']));
		$close_time_evening = date('H:i',strtotime($todayRestaurantTime['close_time_evening']));
		$merchant_close_store = isset($setting['merchant_close_store'])?$setting['merchant_close_store']:0;
		$merchant_disabled_ordering = isset($setting['merchant_disabled_ordering'])?$setting['merchant_disabled_ordering']:0;

		$open_time = date('h:i a',strtotime($open_time_mrng));
		$close_time = date('h:i a',strtotime($close_time_evening));
		$now = date("H:i");

		$open = 0;
		$pre = 0;
		$close=0;
		$delivery_type = 2; // 2 for postcode
		$place = []; // 1 for place
		$orderButtonText = 'PLACE PRE ORDER';

		$view['checkout'] = array();	
		$view['msg'] ='';
		$view['address'] ='';
		$view['page_title']="Checkout";
		$view['main_content']='pages/checkout';
		$view['delivery_type']=2; // 2for postcode,1 for place based
		$view['place']=[];
		$view['open']=$open;
		$view['pre']=$pre;
		$view['close']=$close;
		$view['restaurant_info']= $restaurant_info;
		
		if(isset($deliverySetting) && !empty($deliverySetting) && is_array($deliverySetting)){
			$delivery_type = $deliverySetting['delivery_type'];
			$view['delivery_type'] = $delivery_type;
			$view['place'] = $deliverySetting['place'];
		}
	
	

		if($merchant_close_store==0 && $merchant_disabled_ordering==0){ 
			if( $is_open==1 &&  ( ( $now > $open_time_mrng  && $now <  $close_time_mrng ) || ( $now > $open_time_evening  && $now <  $close_time_evening ) ) ){
				$open = 1;
				$orderButtonText = 'PLACE ORDER';

			} 
			else if($is_open==1 && ( ( $now < $open_time_mrng  && $now <  $close_time_mrng ) || ( $now < $open_time_evening  && $now <  $close_time_evening )) && $pre_order==1){
				$pre = 1;
				$orderButtonText = 'PLACE PRE ORDER';

			}else{
				$close=1;
				$orderButtonText = msg['store_close'];
			}
		}else if($merchant_close_store==1 || $merchant_disabled_ordering==1){ 
			redirect(base_url('area/'.$postcode));
			exit;
		} 

		if($close==1){
			redirect(base_url('area/'.$postcode));
			exit;
		}
		
		
		if(!is_logged_in() &&  is_user_type()!='user'){
			redirect(base_url('auth'));

		}
		
		else if(empty($id) && $checkout_type==2){

			redirect(base_url('user/address'));
		}

		$postcode = '';
		if(!empty($id)){

			$address = $this->getAddressId($id);
			$address = (isset($address) && is_array($address) && count($address)>0)?$this->getAddressId($id):'';
			$view['address'] = $address;
			
			if($delivery_type==2){
 				$postcode = isset($address['pincode'])?$address['pincode']:'invalid';
				if($postcode!='invalid'){
					$this->postcodeVerification($postcode);
					$this->session->set_userdata('postcode', $postcode);
					setcookie('can_deliverd', 1, time() + (1 * 365 * 24 * 60 * 60), "/"); 
					setcookie('address_postcode', $postcode, time() + (1 * 365 * 24 * 60 * 60), "/"); 
				}
			}else if($delivery_type==1){
				$postcode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
			}
			$this->session->set_userdata('billing_address',$address);	
		}

		$user_id = $this->session->userdata('user_id');
		$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
		$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
		$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
		$loyaltyPointUsed = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';


		$url = API_URL.'/api/user/profile/'.$user_id;
		$responseProfile = getCurlWithAuthorizationWithOutData($url);
		$profileStatus = $responseProfile['status'];
		$profileResult = $responseProfile['data'];

		if(isset($profileResult) && count($profileResult)>0){ // success
			$view['profile']= $profileResult;
		}

		$url = API_URL.'/api/restaurant/cart/detail';
		$data['userId'] = $user_id;
		$data['cookieId'] = $cookieId;
		$data['restaurantId'] = $restaurantId;
		$data['orderType'] = $checkout_type;
		$data['postCode'] = $postCode;
		$data['promoCode'] = $promoCode;
		$data['loyaltyPointUsed'] = $loyaltyPointUsed;
			
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		if(isset($result) && count($result)>0 && $status==200){ // success
		
			$view['checkout'] = $checkout = $result;
			$result['orderButtonText'] = $orderButtonText;
			$result['merchant_time'] = $merchant_time;
			$result['merchant_show_time'] = $merchant_show_time;

			$merchant_minimum_order_delivery = isset($setting['merchant_minimum_order_delivery'])?number_format($setting['merchant_minimum_order_delivery'],2):0;
			$merchant_maximum_order_delivery = isset($setting['merchant_maximum_order_delivery'])?number_format($setting['merchant_maximum_order_delivery'],2):0;
			$merchant_minimum_order_pickup = isset($setting['merchant_minimum_order_pickup'])?number_format($setting['merchant_minimum_order_pickup'],2):0;
			$merchant_maximum_order_pickup = isset($setting['merchant_maximum_order_pickup'])?number_format($setting['merchant_maximum_order_pickup'],2):0;

			if(($checkout['grandTotal']<$merchant_minimum_order_pickup  && $checkout_type==1) || ($checkout['grandTotal']<$merchant_minimum_order_delivery && $checkout_type==2)){ 
				redirect(base_url('area/'.$postCode));
			}
		}else{
			redirect(base_url('area/'.$postCode));
		}
		
		$payment_option = $admin_info['payment_option'];
		$loyalty_point = $admin_info['loyaltyPoint'];
		$merchant_enabled_voucher = $admin_info['restaurant_setting']['merchant_enabled_voucher'];
		
		$view['payment_type'] = $payment_option;	
		$view['orderButtonText'] = $orderButtonText;	
		$view['loyalty_point'] = $loyalty_point;	
		$view['merchant_enabled_voucher'] = $merchant_enabled_voucher;	
		$view['checkoutitem'] = $this->load->view('pages/checkoutitem', array("checkout"=>$result), true);
		$this->load->view('template',$view);
		
	}

	public function getAddressId($address_id){

		$user_id = $this->session->userdata('user_id');
		$address_id = gzinflate(base64_decode($address_id));;
		$url = API_URL.'/api/user/address/'.$address_id ;
		$responseAddress = getCurlWithAuthorizationWithOutData($url);
		
		$addressStatus = $responseAddress['status'];
		$addressResult = $responseAddress['data'];
		$addressMessage = $responseAddress['message'];
		if(isset($addressResult) && count($addressResult)>0 && $addressStatus==200){ // success
			$this->session->set_userdata('address_id',$address_id);
			return $addressResult;
			
		}elseif(isset($addressResult) && count($addressResult)==0 && $addressStatus==1){
			return msg['no_address'];
		}
		
	}
	
	public function postcodeVerification($postcode){
		$restaurantId = $this->session->userdata('restaurant_id');
		$url = API_URL.'/api/common/postcode-verification?postcode='.$postcode.'&restaurant_id='.$restaurantId;
		$response = getCurlWithOutAuthorizationWithOutData($url);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		
		if($status==200){ // success
			return 1;
		}else{
			$this->session->set_flashdata('error_msg', $message);
			redirect(base_url('user/address'));
			exit;
		}
	}

	public function applyPoint(){
		$used_point = $this->input->post('used_point');
		$sub_total = $this->input->post('sub_total');
		$user_id = $this->session->userdata('user_id');
		
		$admin_info = get_admin_store_detail();
		$adminsetting = $admin_info['deliverySetting'];
		$loyalty_point = $admin_info['loyaltyPoint'];

		if(isset($loyalty_point) && is_array($loyalty_point) && count($loyalty_point)>0){
			$loyalty = $loyalty_point;
			
			$url = API_URL.'/api/user/profile/'.$user_id;
			$responseProfile = getCurlWithAuthorizationWithOutData($url);
			$profileStatus = $responseProfile['status'];
			$profileResult = $responseProfile['data'];

			if(isset($profileResult) && count($profileResult)>0){ // success
				$user_loyalty_point = !empty($profileResult['loyalty_point'])?$profileResult['loyalty_point']:0;
				$min_redeeming_point = $loyalty['min_redeeming_point'];
				$min_point_used = $loyalty['min_point_used'];
				$max_point_used = $loyalty['max_point_used'];
				$points_apply_order_amt = $loyalty['points_apply_order_amt'];

				if($user_loyalty_point>=$min_redeeming_point){

					if($used_point<=$user_loyalty_point){

						if($used_point>=$min_point_used && $used_point<=$max_point_used ){
							if($sub_total>=$points_apply_order_amt){
								$this->session->set_userdata('loyaltyPointUsed',$used_point);
								$status = 1;
								$msg = 'Success';
								$used_point = $used_point;
							}else{
								$status = 0;
								$msg = 'Sub total should be minimum '.CURRENCY. number_format($points_apply_order_amt,2);
								$used_point = 0;
							}
						}else{
							$status = 0;
							$msg = 'Please enter loyalty point to redeem between  '.$min_point_used.' to '.$max_point_used;
							$used_point = 0;
						}
					}else{
						$status = 0;
						$msg = 'Loyalty point should be less then the '.$user_loyalty_point;
						$used_point = 0;
					}
				}else{
					$status = 0;
					$msg = 'Loyalty point should be minimum '.$min_redeeming_point.' to redeem.';
					$used_point = 0;
				}

			}
		}else{
			$status = 0;
			$msg = 'Something went wrong';
			$used_point =0;
		}
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'used_point'=>$used_point
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		die;
	}

	public function refreshCheckout(){
		
		$restaurantId = $this->session->userdata('restaurant_id');
		$merchant_info = get_store_detail_by_id($restaurantId);
		$setting = $merchant_info['restaurant_setting'];
		$todayRestaurantTime = $setting['store_time'];
		$pre_order = $setting['pre_order'];

		$is_open = $todayRestaurantTime['is_open'];
		$open_time_mrng = date('H:i',strtotime($todayRestaurantTime['open_time_mrng']));
		$close_time_mrng = date('H:i',strtotime($todayRestaurantTime['close_time_mrng']));
		$open_time_evening = date('H:i',strtotime($todayRestaurantTime['open_time_evening']));
		$close_time_evening = date('H:i',strtotime($todayRestaurantTime['close_time_evening']));
		$merchant_close_store = isset($setting['merchant_close_store'])?$setting['merchant_close_store']:0;
		$merchant_disabled_ordering = isset($setting['merchant_disabled_ordering'])?$setting['merchant_disabled_ordering']:0;

		$open_time = date('h:i a',strtotime($open_time_mrng));
		$close_time = date('h:i a',strtotime($close_time_evening));
		$now = date("H:i");

		$open = 0;
		$pre = 0;
		$close=0;
		$orderButtonText = 'PLACE PRE ORDER';

		if($merchant_close_store==0 && $merchant_disabled_ordering==0){ 
			if( $is_open==1 &&  ( ( $now > $open_time_mrng  && $now <  $close_time_mrng ) || ( $now > $open_time_evening  && $now <  $close_time_evening ) ) ){
				$open = 1;
				$orderButtonText = 'PLACE ORDER';

			} 
			else if($is_open==1 && ( ( $now < $open_time_mrng  && $now <  $close_time_mrng ) || ( $now < $open_time_evening  && $now <  $close_time_evening )) && $pre_order==1){
				$pre = 1;
				$orderButtonText = 'PLACE PRE ORDER';

			}else{
				$close=1;
				$orderButtonText = msg['store_close'];
			}
		}


		$user_id = $this->session->userdata('user_id');
		$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
		$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
		$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
		$loyaltyPointUsed = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';

		$url = API_URL.'/api/restaurant/cart/detail';
		$data['userId'] = $user_id;
		$data['cookieId'] = $cookieId;
		$data['restaurantId'] = $restaurantId;
		$data['orderType'] = $checkout_type;
		$data['postCode'] = $postCode;
		$data['promoCode'] = $promoCode;
		$data['loyaltyPointUsed'] = $loyaltyPointUsed;
			
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		if(isset($result) && count($result)>0 && $status==200){ // success
		
			$view['checkout'] = $checkout = $result;
			$result['orderButtonText'] = $orderButtonText;
		}
		echo $this->load->view('pages/checkoutitem', array("checkout"=>$result), true);
	}
	
	public function applypromocode(){

		$user_id = $this->session->userdata('user_id');
		$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
		$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
		$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		$promoCode = $this->input->post('promocode');
		$loyaltyPointUsed = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';

		$url = API_URL.'/api/restaurant/cart/detail';
		$data['userId'] = $user_id;
		$data['cookieId'] = $cookieId;
		$data['restaurantId'] = $restaurantId;
		$data['orderType'] = $checkout_type;
		$data['postCode'] = $postCode;
		$data['promoCode'] = $promoCode;
		$data['loyaltyPointUsed'] = $loyaltyPointUsed;
			
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		
		if(isset($result) && count($result)>0 && $status==200){  // success
			
			$promoCodeMsg= $result['promoCodeMsg'];
			$promoCodeAmt= $result['promoCodeAmnt'];
			if($promoCodeAmt>0){
				$this->session->set_userdata('promoCode',$promoCode);
			}
			$status= $result['promoCodeApplied'];
		}else{
			$promoCodeMsg = 'Something went wrong';	
			$promoCodeAmt= 0;
			$status =0;
		}

		$response = array(
			'status'=>$status,
			'msg'=>$promoCodeMsg,
			'promoCodeAmt'=>$promoCodeAmt
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		die;
	}
	
	public function removepromocode(){
		$this->session->unset_userdata('promoCode');
		$status =1;
		$response = array(
			'status'=>$status,
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		die;
	}
	
	public function removePoint(){
		$this->session->unset_userdata('used_point');
		$status =1;
		$response = array(
			'status'=>$status,
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		die;
	}
	

}
