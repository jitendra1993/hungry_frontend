<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\AddressType;
use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\HostedPaymentConfig;
use GlobalPayments\Api\Entities\HostedPaymentData;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\Services\HostedService;

class Order extends CI_Controller {
	
	public  $merchantId ;
	public $accountId;
	public $sharedSecret;
	public $serviceUrl;
	public $description;
	public $payType;
		
	public function __construct() {
		parent::__construct();
		if(is_logged_in() &&  is_user_type()!='user'){
			redirect(base_url());
		}
		if(!is_logged_in()){
			redirect(base_url());
		}

		$admin_info = get_admin_store_detail();
		$payment_option = $admin_info['payment_option'];
		foreach($payment_option as $singlevalue){

			if($singlevalue['payment_type']=='nochex' && $singlevalue['disable']==0){
				$this->merchantId = $singlevalue['merchant_id'];
				$this->accountId =  (isset($singlevalue['account_id']) && !empty($singlevalue['account_id']))? $singlevalue['account_id']:'';
				$this->sharedSecret =  (isset($singlevalue['secret']) && !empty($singlevalue['secret']))? $singlevalue['secret']:'';
				$this->description =  (isset($singlevalue['description']) && !empty($singlevalue['description']))? $singlevalue['description']:'';
				$this->payType = 'nochex';

			}else if($singlevalue['payment_type']=='rms' && $singlevalue['disable']==0){
				$this->merchantId = $singlevalue['merchant_id'];
				$this->accountId =  (isset($singlevalue['account_id']) && !empty($singlevalue['account_id']))? $singlevalue['account_id']:'';
				$this->sharedSecret =  (isset($singlevalue['secret']) && !empty($singlevalue['secret']))? $singlevalue['secret']:'';
				$this->description =  (isset($singlevalue['description']) && !empty($singlevalue['description']))? $singlevalue['description']:'';
				$this->payType = 'rms';
			}

		}
		$this->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";
	}
	
	public function index(){
		redirect(base_url());
	}
	
	public function proxy(){
		$user_id  = $this->session->userdata('user_id');	
		$full_name  = $this->session->userdata('full_name');	
		$email  = $this->session->userdata('email');	
		$phone  = $this->session->userdata('phone');
		
		
		$secret = 'secret';
		// configure client, request and HPP settings
		$config = new GpEcomConfig();
		$config->merchantId = $this->merchantId;
		$config->accountId = $this->accountId;
		$config->sharedSecret = $this->sharedSecret;
		$config->serviceUrl = $this->serviceUrl;

		$config->hostedPaymentConfig = new HostedPaymentConfig();
		$config->hostedPaymentConfig->version = HppVersion::VERSION_2;
		$service = new HostedService($config);
		
		//Add 3D Secure 2 Mandatory and Recommended Fields
		$hostedPaymentData = new HostedPaymentData();
		$hostedPaymentData->customerEmail = $email;
		$hostedPaymentData->customerPhoneMobile = "44|".$phone;
		$hostedPaymentData->addressesMatch = true;

		$billingAddress = new Address();
		$billingAddress->streetAddress1 = "Flat 123";
		$billingAddress->streetAddress2 = "House 456";
		$billingAddress->streetAddress3 = "Unit 4";
		$billingAddress->city = "Halifax";
		$billingAddress->postalCode = "W5 9HR";
		$billingAddress->country = "826";
		
		try {
			
			$grandTotal = trim(str_replace(CURRENCY,'',$this->session->userdata('success_arr')['grandTotal'])); 
			
		   $hppJson = $service->charge($grandTotal)
			  ->withCurrency("EUR")
			  ->withHostedPaymentData($hostedPaymentData)
			  ->withAddress($billingAddress, AddressType::BILLING)
			 // ->withAddress($shippingAddress, AddressType::SHIPPING)
			  ->serialize();      
		   // TODO: pass the HPP JSON to the client-side    
		} catch (ApiException $e) {
		   // TODO: Add your error handling here
		}
		
		$res = json_decode($hppJson,true);
		$response = array();
		$data['MERCHANT_ID'] = $res['MERCHANT_ID'];
		$data['ACCOUNT'] = $res['ACCOUNT'];
		$data['AMOUNT'] = $res['AMOUNT'];
		$data['CURRENCY'] = $res['CURRENCY'];
		$data['AUTO_SETTLE_FLAG'] = $res['AUTO_SETTLE_FLAG'];
		$data['PM_METHODS'] = 'cards';

		foreach ($data as $key => $value) {
			if (!$value) {
				continue;
			}
			$response[$key] = $value;
		}

		//$response["ORDER_ID"] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 22);
		$response["ORDER_ID"] = $this->session->userdata('order_id');
		$response["TIMESTAMP"] = (new DateTime())->format("YmdHis");
		$response["SHA1HASH"] = $this->generateHash($response, $secret);

		$jsonResponse = json_encode($response);

		error_log('sending: ' . $jsonResponse);
		echo $jsonResponse;
	}

	public function confirm(){

		if(!is_logged_in())
		{
			redirect(base_url('auth'));
		}
		if($this->input->method(TRUE) == 'POST'){
			
			$payment_type = htmlspecialchars(strip_tags($this->input->post('payment_type')));
			$order_change = htmlspecialchars(strip_tags($this->input->post('order_change')));
			$instruction = htmlspecialchars(strip_tags($this->input->post('instruction')));
			$postcode = !empty($this->input->post('postcode'))?$this->input->post('postcode'):'';
			$delivery_type = htmlspecialchars(strip_tags($this->input->post('delivery_type')));
			$delivery_time = !empty($this->input->post('delivery_time'))?date("H:i", strtotime($this->input->post('delivery_time'))):''; 
			
			if($delivery_type==1){
				$this->session->set_userdata('postcode', $postcode);
			}
			
			$user_id = $this->session->userdata('user_id');
			$checkout_type = $this->session->userdata('checkout_type'); //1 collection 2, delivery, 3 dinein
			$cart_id = !empty($this->session->userdata('cartId'))?$this->session->userdata('cartId'):'';
			$address_id = !empty($this->session->userdata('address_id'))?$this->session->userdata('address_id'):'';
			$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
			$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
			$used_point = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):0;
			$restaurant_id = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';

			$url = API_URL.'/api/order/create';
			$data['userId'] = $user_id;
			$data['cartId'] = $cart_id;
			$data['restaurantId'] = $restaurant_id;
			$data['addressId'] = $address_id;
			$data['orderType'] = $checkout_type;
			$data['paymentType'] = $payment_type;
			$data['orderChange'] = $order_change;
			$data['instruction'] = $instruction;
			$data['platform'] ='Web';
			$data['postCode'] =  !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
			$data['promoCode'] = $promoCode;
			$data['loyaltyPointUsed'] = $used_point;
			$data['deliveryTime'] = $delivery_time;
			$response = postCurlWithAuthorization($url,$data);
			$status = $response['status'];
			$result = $response['data'];
			if($status==200){
				$order_id = $result['order_id'];
				$this->session->set_userdata('order_id',$order_id );				
				$this->session->set_userdata('payment_type',$payment_type );		
					
			}else{
				$msg = 'Something went wrong';	
				$this->session->set_flashdata('error_msg', $msg);
				redirect(base_url('checkout'));
			}
		
			if($payment_type==1){
				redirect(base_url('order/success?id='.$order_id));	
				exit;
			}else{
				redirect(base_url('order/payment-review'));
			}

		}else{
			redirect(base_url());
		}
	}	

	public function success(){
		
		if(!is_logged_in()){
			redirect(base_url('auth'));
		}
		
		if(!empty($this->session->userdata('user_id'))){
			if(!$this->session->userdata('order_id')){
				$postcode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
				redirect(base_url().'area/'.$postcode);
				exit;
			}
		}
	
		if(!empty($this->session->userdata('user_id'))){
		$user_id = $this->session->userdata('user_id');
		}else{
			$user_id = $this->session->userdata('guest_user_id');
		}
		$payment_type = $this->session->userdata('payment_type');
		
		if($payment_type==2){
			$transaction_id= '';
			$trans_id = $this->input->get('trans_id');
			$transaction_id = $this->input->get('transaction_id');
			
			if(isset($trans_id) && !empty($trans_id)){
				$transaction_id = $trans_id;

			}else if(isset($transaction_id) && !empty($transaction_id)){
				$transaction_id = $transaction_id;
			}
			$order_id = $this->session->userdata('order_id');
			$restaurant_id = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
			$url = API_URL.'/api/order/update-payment-status';
			$data['orderId'] = $order_id;
			$data['status'] = 1;
			$data['paymentId'] = $transaction_id;
			$data['userId'] = $user_id;
			$data['restaurantId'] = $restaurant_id;
			$response = postCurlWithOutAuthorizationJson($url,$data);
			$status = $response['status'];
			$result = $response['data'];
			$message = $response['message'];
		}
		
		$view['msg'] ='';
		$view['page_title']="Success";
		$view['main_content']='pages/success';
		$this->load->view('template',$view);
		
	}	

	public function paymentReview(){
		$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		if(empty($this->merchantId)){
			redirect(base_url('area/'.$postCode));
			exit;
		} 
		$user_id = $this->session->userdata('user_id');		
		$cookieId = $_COOKIE['cookieId'];
		$checkout_type = $this->session->userdata('checkout_type');
			
		$full_name  = $this->session->userdata('full_name');	
		$email  = $this->session->userdata('email');	
		$phone  = $this->session->userdata('phone');	
		
		$view['msg'] ='';
		$view['merchant_id']= $this->merchantId;
		$view['account_id']= $this->accountId;
		$view['secret']= $this->sharedSecret;
		$view['description']= $this->description;
		$view['payType']= $this->payType;
		$view['page_title']="Payment Review";
		$view['main_content']='pages/payment-review';
		$this->load->view('template',$view);
		
	}
	
	public function decline(){
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):2;
		if($checkout_type==2){
			if(empty($this->session->userdata('user_id')) && empty($this->session->userdata('guest_user_id'))){
				redirect(base_url('auth'));
			}
			$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
			if(!$this->session->userdata('order_id')){
				redirect(base_url('area/'.$postCode));
				exit;
			} 
		
		}else if($checkout_type==3){
			if(!$this->session->userdata('order_id')){
				redirect(base_url().'dinein');
				exit;
			} 
		}
		$transaction_id= '';
		$trans_id = $this->input->get('trans_id');
		$transaction_id = $this->input->get('transaction_id');
		
		if(isset($trans_id) && !empty($trans_id)){
			$transaction_id = $trans_id;

		}else if(isset($transaction_id) && !empty($transaction_id)){
			$transaction_id = $transaction_id;
		}
		$user_id  = $this->session->userdata('user_id');	
		$order_id = $this->session->userdata('order_id');
		$restaurant_id = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$url = API_URL.'/api/order/update-payment-status';

		$data['orderId'] = $order_id;
		$data['status'] = 4;
		$data['paymentId'] = $transaction_id;
		$data['userId'] = $user_id;
		$data['restaurantId'] = $restaurant_id;
		$response = postCurlWithOutAuthorizationJson($url,$data);
		
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		if($status==200){
			$view['msg'] ='';
			$view['page_title']="Order Cancel";
			$view['main_content']='pages/cancel';
			$this->load->view('template',$view);
		}else{
			echo $message;
			exit;
		}
	}
	
	public function cancel(){
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):2;
		
		if($checkout_type==2){
			if(!is_logged_in()){
				redirect(base_url('auth'));
			}
			$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
			if(!$this->session->userdata('order_id')){
				redirect(base_url('area/'.$postCode));
				exit;
			} 
		
		}else if($checkout_type==3){
			if(!$this->session->userdata('order_id')){
				redirect(base_url().'dinein');
				exit;
			} 
		}
		
		$user_id='';
		if(!empty($this->session->userdata('user_id'))){
			$user_id = $this->session->userdata('user_id');
			
		}else if(!empty($this->session->userdata('guest_user_id'))){
			$user_id = $this->session->userdata('guest_user_id');
		}
		$order_id = $this->session->userdata('order_id');
		$restaurant_id = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$url = API_URL.'/api/order/update-payment-status';
		
		$data['orderId'] = $order_id;
		$data['status'] = 3;
		$data['paymentId'] = "";
		$data['userId'] = $user_id;
		$data['restaurantId'] = $restaurant_id;
		$response = postCurlWithOutAuthorizationJson($url,$data);
		
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		if($status==200){
			$view['msg'] ='';
			$view['page_title']="Order Cancel";
			$view['main_content']='pages/cancel';
			$this->load->view('template',$view);
		}else{
			echo $message;
			exit;
		}
	}

	public function orderHistory(){

		if(!is_logged_in()){
			redirect(base_url('auth'));
		}
			
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$view['orders'] = array();	
		$view['msg'] ='';
		$config = array();
		
		$rowperpage = ROW_PER_PAGE;
		$seg = 3;
		$rowno = ($this->uri->segment($seg))? $this->uri->segment($seg) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
		
		$user_id = $this->session->userdata('user_id');
		
		
		$post['userId'] =$user_id;
		$post['start'] = $rowno;
		$post['last'] = $rowperpage;
		$post['textSearch'] = '' ;
		$post['startDate'] = '' ;
		$post['endDate'] = '' ;
		$post['year'] = '' ;
		
		if(!empty($filter) && count($filter)>0)
		{
			$post['textSearch'] = $filter['filter_name'] ;
			$post['startDate'] = $filter['filter_from_date'] ;
			$post['endDate'] = $filter['filter_to_date'] ;
			$post['year'] = $filter['filter_year'] ;
		}
		$url = API_URL.'/api/order/history?'.http_build_query($post);
		$response = getCurlWithAuthorizationWithOutData($url);
		$status = $response['status'];
		$result = $response['data'];
		if($status==200 &&  $result!=''){
			$view['orders']  = $result['result'];
			$config["total_rows"] = $result['totalRecord'];
		}
	
		$config["base_url"] = base_url() . "/order/history";
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = $seg;;
		$config['use_page_numbers'] = TRUE;
		if(!empty($_GET))
		{
			$config['suffix'] = '?'.http_build_query($_GET, '', "&");
		}
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		
		$view['page_title']="Takaway - My-orders";
		$view['main_content']='pages/order-list';
		$this->load->view('template',$view);	
	}

	public function successRMS(){
		
		$config = new GpEcomConfig();
		$config->merchantId = $this->merchantId;
		$config->accountId = $this->accountId;
		$config->sharedSecret = $this->sharedSecret;
		$config->serviceUrl = $this->serviceUrl;

		$service = new HostedService($config);
		$responseJson = $_POST['hppResponse'];
		$parsedResponse = $service->parseResponse($responseJson, true);
		$responseValues = $parsedResponse->responseValues;
		
		$MESSAGE = $responseValues['MESSAGE'];
		$RESULT = $responseValues['RESULT'];
		$AMOUNT = $responseValues['AMOUNT'];
		$SHA1HASH = $responseValues['SHA1HASH'];
		$ORDER_ID = $responseValues['ORDER_ID'];
		$PASREF = $responseValues['PASREF'];
		$pas_uuid = $responseValues['pas_uuid'];
		$BATCHID = $responseValues['BATCHID'];
		$transaction_id = $PASREF;
		
		if (!strstr($MESSAGE, "Authorised")) {  
			$url1 = base_url('order/decline?trans_id='.$PASREF);
		}else{
			
			$url1 = base_url('order/success?trans_id='.$PASREF);
		}
		?>
		<script type="text/javascript">
			window.location="<?php echo $url1; ?>";
		</script>
		<?php	
	}

	public function orderView(){
		
		$id = $this->input->post('id');
		$restaurantId = $this->input->post('restaurant_id');
		$user_id = $this->session->userdata('user_id');
		$url = API_URL.'/api/order/detail';
		$data['userId'] = $user_id;
		$data['orderId'] = $id;
		$data['restaurantId'] = $restaurantId;
		$response = postCurlWithAuthorization($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];

		if($status==200 && $result!=''){
			
			$orderDetail = $result['orderDetail'];
			$userDetail = $result['userDetail'];
			$merchantInfo = $result['merchantInfo'];
			$payment_status = 'Pending';

			if($orderDetail['order_type']==1)
			{
				$orderType = 'Collection';
			}else if($orderDetail['order_type']==2)
			{
				$orderType = 'Delivery';
			}else if($orderDetail['order_type']==3)
			{
				$orderType = 'Dinein';
			}

			if($orderDetail['payment_type']==2){
				if($orderDetail['payment_status']==1 && $orderDetail['payment_id']!=''){
					$payment_status = 'Success';
	
				}else if($orderDetail['payment_status']==2)
				{
					$payment_status = 'Pending';
				}else if($orderDetail['payment_status']==3)
				{
					$payment_status = 'Cancel';
	
				}else if($orderDetail['payment_status']==4)
				{
					$payment_status = 'Decline';
				}
			} 
			
			?>
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				  	<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  	<span aria-hidden="true">&times;</span>
						</button>
				  	</div>
					<div class="modal-body row">
						<div class="col-md-12 form-group p_star">
							<table class="table table-striped table-bordered">
							<tbody>	
									<tr>
										<td class="width-50">Customer Name</td>
										<td class="text-right width-50"><?php echo $userDetail['name'];?></td>
									</tr>
									
									<?php 
									if($orderDetail['order_type']==3){ ?>
										<tr>
											<td class="width-50">Table Number</td>
											<td class="text-right width-50"><?php echo $orderDetail['table_number'];?></td>
										</tr>
										
										<tr>
											<td class="width-50">Number of Person</td>
											<td class="text-right width-50"><?php echo $orderDetail['no_of_person'];?></td>
										</tr>	
										<?php
									}
									?>
									<tr>
										<td class="width-50">Restaurant</td>
										<td class="text-right width-50"><?php echo $merchantInfo['merchant_name'];?></td>
									</tr>
									
									<tr>
										<td class="width-50">Takeaway Address</td>
										<td class="text-right width-50"><?php echo $merchantInfo['address'];?></td>
									</tr>
										
									<tr>
										<td  class="width-50">Order Type</td>
										<td class="text-right width-50"><?php echo $orderType;?></td>
									</tr>
									
									<tr>
										<td  class="width-50">Payment Type</td>
										<td class="text-right width-50"><?php echo ($orderDetail['payment_type']==1)?'Cash':'Online';?></td>
									</tr>
									
									<tr>
										<td  class="width-50">Order #</td>
										<td class="text-right width-50"><?php echo $orderDetail['order_id'];?></td>
									</tr>
									
									<tr>
										<td  class="width-50">Order Date</td>
										<td class="text-right width-50"><?php echo date('F j, Y, g:i a',$orderDetail['added_date_timestamp']/1000);?></td>
									</tr>
									
									<tr>
										<td  class="width-50">Customer Tel</td>
										<td class="text-right width-50"><?php echo $userDetail['mobile'];?></td>
									</tr>
									<?php 
									if(!empty($orderDetail['order_change'])){ ?>	
										<tr>
											<td  class="width-50">Order Change</td>
											<td class="text-right width-50"><?php echo CURRENCY.$orderDetail['order_change'];?></td>
										</tr>
										<?php 
									} 
									if(!empty($orderDetail['instruction'])){ ?>
										<tr>
											<td  class="width-50">Order Instruction</td>
											<td class="text-right width-50"><?php echo $orderDetail['instruction'];?></td>
										</tr>
										<?php 
									} ?>
									<tr>
										<td  class="width-50">Sub Total</td>
										<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['sub_total'],2);?></td>
									</tr>
									
									<tr>
										<td  class="width-50">Discount</td>
										<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['discount'],2);?></td>
									</tr>
									<?php 
									if(!empty($orderDetail['promocode_amt']) && $orderDetail['promocode_amt']>0){ ?>
										<tr>
											<td  class="width-50">Voucher Discount</td>
											<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['promocode_amt'],2);?></td>
										</tr>
										<?php 
									} 
									if(!empty($orderDetail['loyalty_point_earn']) && $orderDetail['loyalty_point_earn']>0){ ?>
										<tr>
											<td  class="width-50">Loyalty Point Earn</td>
											<td class="text-right width-50"><?php echo number_format($orderDetail['loyalty_point_earn'],2);?></td>
										</tr>
										<?php 
									} 
									if(!empty($orderDetail['loyalty_point_used']) && $orderDetail['loyalty_point_used']>0){ ?>
										<tr>
											<td  class="width-50">Loyalty Point Used</td>
											<td class="text-right width-50"><?php echo number_format($orderDetail['loyalty_point_used'],2);?></td>
										</tr>
										<?php 
									} 
									if(!empty($orderDetail['loyalty_point_value']) && $orderDetail['loyalty_point_value']>0){ ?>
										<tr>
											<td  class="width-50">Loyalty Point Value</td>
											<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['loyalty_point_value'],2);?></td>
										</tr>
										<?php 
									} ?>
									
									<tr>
										<td  class="width-50">Service Charge</td>
										<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['service_charge'],2);?></td>
									</tr>
									<?php
									if($orderDetail['order_type']==2){ ?>
										<tr>
											<td  class="width-50">Delivery Fee</td>
											<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['delivery_fee'],2);?></td>
										</tr>
										<?php 
									} ?>
									<tr>
										<td  class="width-50">Grand Total</td>
										<td class="text-right width-50"><?php echo CURRENCY. number_format($orderDetail['grand_total'],2);?></td>
									</tr>
									<?php 
									if($orderDetail['payment_type']==2){ ?>
										<tr>
											<td  class="width-50">Payment Id</td>
											<td class="text-right width-50"><?php echo $orderDetail['payment_id'];?></td>
										</tr>
										<tr>
											<td  class="width-50">Remark</td>
											<td class="text-right width-50"><?php echo $orderDetail['payment_remark'];?></td>
										</tr>

										
										<?php 
									} ?>
									<tr>
										<td  class="width-50">Payment Status</td>
										<td class="text-right width-50"><?php echo $payment_status;?></td>
									</tr>
									<tr>
										<td  class="width-50">Delivery Time</td>
										<td class="text-right width-50"><?php echo date('h:m a',strtotime($orderDetail['delivery_time']));?></td>
									</tr>
							</tbody>
							</table>
							
							<div class="item-list">
								<?php
								foreach($result['orderItemDetail'] as $basketKey=>$val){
									$basketValue =$val['master_product'][0];
									?>
									<div class="row">
										<div class="col-md-9">
											<p class="title-menu"><?php echo $val['quantity'].' x '.$basketValue['item_name']; ?> <?php echo CURRENCY. number_format($val['price'],2); ?></p>
										</div>
										<div class="col-md-3"><?php echo CURRENCY. number_format($val['quantity']*$val['price'],2); ?></div>
									</div>
									<?php 
									if(!empty($val['master_ingredient'])){ 

										$ing = '';
										foreach($val['master_ingredient'] as $value){
											$ing .=implode(',',$value).',';
										}
										
										?>
										<div class="row ingredient">
											<div class="col-md-12 addons-added">Ingredient</div>
											<div class="col-md-12 addons-added"><?php echo rtrim($ing,','); ?></div>
										</div>
										<?php 
									} 
									$special_instruction =  $val['special_instruction'];
									if(!empty($special_instruction)){ ?>
										<div class="row ingredient">
											<div class="col-md-12 addons-added">Special Instruction</div>
											<div class="col-md-12 addons-added"><?php echo $special_instruction; ?></div>
										</div>
										<?php 
									} 
									if($val['order_addon_item_detail'] && count($val['order_addon_item_detail'])>0 && $val['has_addon']==1){ 
										$addon_cat_name ='';
										$match_cat_name='-';
										foreach($val['order_addon_item_detail'] as $subItem){
											//print_r( $subItem);
											$addon_cat_name = $subItem['addon_category_name'];
											?>
											<div class="row">
												<?php
												if($addon_cat_name!=$match_cat_name){
													$match_cat_name = $subItem['addon_category_name'];
													?>
													<div class="col-md-12 addons-added sub-it-head"><?php echo $subItem['addon_category_name']; ?></div>
													<?php
												}
												if(!empty($subItem['addon_price']) && $subItem['addon_price']>0){
													$left = CURRENCY.$subItem['addon_price'];
													$right =  CURRENCY. number_format($subItem['addon_quantity']*$subItem['addon_price'],2);
												}else{
													$left = $right= '-';
												}
												?>
												<i class="fa fa-angle-right" aria-hidden="true"></i>
												<div class="col-md-8 addons-added"><?php echo $subItem['addon_quantity'].' '.$subItem['addon_item_name'].' '. $left; ?></div>
												<p class="menu-price col-md-3 sub-tm-price"><?php echo $right; ?></p>
											</div>
											<?php	
										}
									} 
								} ?>
							</div>
						</div>
						<div class="col-md-12  form-group p_star">
						<button type="button" class="btn-block btn btn-success" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function http_post($server, $port, $url, $vars) { 
		$urlencoded = ""; 
		foreach ($vars as $Index => $Value) // loop round variables and encode them to be used in query
		$urlencoded .= urlencode($Index ) . "=" . urlencode($Value) . "&"; 
		$urlencoded = substr($urlencoded,0,-1);   // returns portion of string, everything but last character

		$headers = "POST $url HTTP/1.0\r\n";  // headers to be sent to the server
		$headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$headers .= "Host: www.nochex.com\r\n";
		$headers .= "Content-Length: ". strlen($urlencoded) . "\r\n\r\n";  // length of the string
			
		$fp = fsockopen($server, $port, $errno, $errstr, 20);  // returns file pointer
		if (!$fp) return "ERROR: fsockopen failed.\r\nError no: $errno - $errstr";  
		fputs($fp, $headers);
		fputs($fp, $urlencoded);  
		$ret = ""; 
		while (!feof($fp)) $ret .= fgets($fp, 1024); // while it’s not the end of the file it will loop 
		fclose($fp);  // closes the connection
		return $ret; // array 
	}

	public function callback()	{
		//$response = http_post("ssl://www.nochex.com", 443, "/apcnet/apc.aspx", $_POST); 
		// HTTP  
		$response = $this->http_post("www.nochex.com", 80, "/apcnet/apc.aspx", $_POST); 
		$debug = "IP -> " . $_SERVER['REMOTE_ADDR'] ."\r\n\r\nPOST DATA:\r\n"; 
		foreach($_POST as $Index => $Value) 
		$debug .= "$Index -> $Value\r\n"; 
		$debug .= "\r\nRESPONSE:\r\n$response"; 
  
		if (!strstr($response, "AUTHORISED")) {  // searches response to see if AUTHORISED is present if it isn’t a failure message is displayed
			$msg = "APC was not AUTHORISED.\r\n\r\n$debug";  // displays debug message
		} 
		else { 
			$msg = "APC was AUTHORISED.".json_encode($_POST) ; // if AUTHORISED was found in the response then it was successful
			// whatever else you want to do 
			$transaction_id= '';
			$trans_id = $_POST['trans_id'];
			$transaction_id = $_POST['transaction_id'];
			if(isset($trans_id) && !empty($trans_id))
			{
				$transaction_id = $trans_id;
			}else if(isset($transaction_id) && !empty($transaction_id)){
				$transaction_id = $transaction_id;
			}
	
			$restaurant_id = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
			$url = API_URL.'/api/order/update-payment-status';
			$data['orderId'] = $_POST['order_id'];
			$data['status'] = 1;
			$data['paymentId'] = $transaction_id;
			$data['userId'] = $user_id;
			$data['restaurantId'] = $restaurant_id;
			$response = postCurlWithOutAuthorizationJson($url,$data);
			$this->session->unset_userdata('success_arr');
			$this->session->unset_userdata('order_id');
			$this->session->unset_userdata('cart_id');
			$this->session->unset_userdata('redirect');
			$this->session->unset_userdata('address_id');
			$this->session->unset_userdata('payment_type');
			$this->session->unset_userdata('billing_address');
			$this->session->unset_userdata('checkout_type');
			
		} 
		mail('jitendraagrawal1993@gmail.com', "APC Debug", $msg); 
		
	}
	
	public function generateHash($data, $secret){
		$toHash = [];
		$timeStamp           = !isset($data['TIMESTAMP']) ? "" : $data['TIMESTAMP'];
		$merchantId          = !isset($data['MERCHANT_ID']) ? "" : $data['MERCHANT_ID'];
		$orderId             = !isset($data['ORDER_ID']) ? "" : $data['ORDER_ID'];
		$amount              = !isset($data['AMOUNT']) ? "" : $data['AMOUNT'];
		$currency            = !isset($data['CURRENCY']) ? "" : $data['CURRENCY'];
		$payerReference      = !isset($data['PAYER_REF']) ? "" : $data['PAYER_REF'];
		$paymentReference    = !isset($data['PMT_REF']) ? "" : $data['PMT_REF'];
		$hppSelectStoredCard = !isset($data['HPP_SELECT_STORED_CARD']) ? "" : $data['HPP_SELECT_STORED_CARD'];
		$payRefORStoredCard  = empty($hppSelectStoredCard) ?  $payerReference : $hppSelectStoredCard;

		if (isset($data['CARD_STORAGE_ENABLE']) && $data['CARD_STORAGE_ENABLE'] === '1') {
			$toHash = [
				$timeStamp,
				$merchantId,
				$orderId,
				$amount,
				$currency,
				$payerReference,
				$paymentReference,
			];
		} elseif ($payRefORStoredCard && empty($paymentReference)) {
			$toHash = [
				$timeStamp,
				$merchantId,
				$orderId,
				$amount,
				$currency,
				$payRefORStoredCard,
				""
			];
		} elseif ($payRefORStoredCard && !empty($paymentReference)) {
			$toHash = [
				$timeStamp,
				$merchantId,
				$orderId,
				$amount,
				$currency,
				$payRefORStoredCard,
				$paymentReference,
			];
		} else {
			$toHash = [
				$timeStamp,
				$merchantId,
				$orderId,
				$amount,
				$currency,
			];
		}

		return sha1(sha1(implode('.', $toHash)) . '.' . $secret);
	}






	public function sendtokitchen()
	{
		
		
		$table_number = !empty($this->session->userdata('table_number' ))?$this->session->userdata('table_number' ):'';	
		$name = !empty($this->session->userdata('dinein_name' ))?$this->session->userdata('dinein_name' ):'';	
		$email = !empty($this->session->userdata('dinein_email' ))?$this->session->userdata('dinein_email' ):'';	
		$phone = !empty($this->session->userdata('dinein_phone' ))?$this->session->userdata('dinein_phone' ):0;	
		// if(!empty($table_number)){
			// redirect(base_url('dinein'));
		// }

			
		if(!empty($this->session->userdata('user_id'))){
			$user_id = $this->session->userdata('user_id');
		}else{
			$user_id = $this->session->userdata('guest_user_id');
		}
			
		$cart_id = !empty($this->session->userdata('cart_id'))?$this->session->userdata('cart_id'):'';
		$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
			
		$url = API_URL.'/cart/send-to-kitchen';
		$data['table_number'] = $table_number;
		$data['name'] = $name;
		$data['email'] = $email;
		$data['phone'] = $phone;
		$data['userId'] = $user_id;
		$data['cartId'] = $cart_id;
		$data['orderType'] = 3;
		$data['cookieId'] = $cookieId;
		$data['platform'] ='Web';
		
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		
		if($status==1){
			$msg = 'Your order has been successfully sent to kitchen';		
			$status =1;				
		}else{
			$msg = 'Something went wrong';	
			$status =0;
		}
		
			
			
		$response = array(
		'status'=>$status,
		'msg'=>$msg,
		'redirect'=>'0'
		);
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}
	public function dineinconfirm(){
		
		
		if(empty($this->session->userdata('user_id')) && empty($this->session->userdata('guest_user_id')))
		{
			redirect(base_url('dinein-get-started'));
		}
		
		if(empty($this->session->userdata('table_number' ))){
			redirect(base_url('dinein-get-started'));
		}
		
				
		$table_number = !empty($this->session->userdata('table_number' ))?$this->session->userdata('table_number' ):'';	
		$name = !empty($this->session->userdata('dinein_name' ))?$this->session->userdata('dinein_name' ):'';	
		$email = !empty($this->session->userdata('dinein_email' ))?$this->session->userdata('dinein_email' ):'';	
		$phone = !empty($this->session->userdata('dinein_phone' ))?$this->session->userdata('dinein_phone' ):0;	
		
		$payment_type = htmlspecialchars(strip_tags($this->input->get('payment_type', TRUE)));
		$this->session->set_userdata('payment_type',$payment_type );
	
		$order_change = 0;
				
		if(!empty($this->session->userdata('user_id'))){
		$user_id = $this->session->userdata('user_id');
		}else{
			$user_id = $this->session->userdata('guest_user_id');
		}
		
		
		$url = API_URL.'/cart/kitchen-detail';
		$data['tableNumber'] = $table_number ;
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status2 = $response['status'];
		$result2 = $response['result'];
		if(isset($result2) && count($result2)>0 && $status2==1){ // success
			$order_id = $result2['orderId'];
			$this->session->set_userdata('order_id',$order_id );
		}else{
			redirect(base_url('dinein'));
		}
			
		$checkout_type = $this->session->userdata('checkout_type'); //1 collection 2, delivery, 3 dinein
		$cart_id = !empty($this->session->userdata('cart_id'))?$this->session->userdata('cart_id'):'';
		$order_id = !empty($this->session->userdata('order_id'))?$this->session->userdata('order_id'):'';
		$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
		
		$url = API_URL.'/cart/dinein-place-order';
		$data['tableNumber'] = $table_number;
		$data['name'] = $name;
		$data['email'] = $email;
		$data['phone'] = $phone;
		$data['instruction'] = '';
		$data['paymentType'] = $payment_type;
		$data['orderChange'] = $order_change;
		$data['userId'] = $user_id;
		$data['orderType'] = $checkout_type;
		$data['cookieId'] = $cookieId;
		$data['cartId'] = $cart_id;
		$data['platform'] ='Web';
		
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['result'];
		
		if($status==1){
			
			$order_id = $result['order_id'];
			$this->session->set_userdata('order_id',$order_id );				
			$this->session->set_userdata('payment_type',$payment_type );				
		}else{
			$msg = 'Something went wrong';	
			$this->session->set_flashdata('error_msg', $msg);
			redirect(base_url('/dinein-checkout'));
		}
		
		if($payment_type==1)
		{
			$this->session->userdata('order_id');
			redirect(base_url('order/successdine?id='.$order_id));
			exit;
		}else{
			redirect(base_url('order/paymentreviewdinein'));
		}
			
		
		
	}	
	
	public function successdine()
	{
		if(!$this->session->userdata('order_id')){
			redirect(base_url().'dinein');
			exit;
		}
		
		if(!empty($this->session->userdata('user_id'))){
		$user_id = $this->session->userdata('user_id');
		}else{
			$user_id = $this->session->userdata('guest_user_id');
		}
					
		$payment_type = $this->session->userdata('payment_type');
		
		if($payment_type==2)
		{
			$transaction_id= '';
			$trans_id = $this->input->get('trans_id');
			$transaction_id = $this->input->get('transaction_id');
			
			if(isset($trans_id) && !empty($trans_id))
			{
				$transaction_id = $trans_id;
			}else if(isset($transaction_id) && !empty($transaction_id)){
				$transaction_id = $transaction_id;
			}

			$order_id = $this->session->userdata('order_id');
			$url = API_URL.'/cart/update-payment-status';
			
			$data['orderId'] = $order_id;
			$data['status'] = 1;
			$data['payment_id'] = $transaction_id;
			$data['user_id'] = $user_id;
			
			$response = postCurlWithOutAuthorizationJson($url,$data);
			$status = $response['status'];
			$result = $response['result'];
			$message = $response['message'];
		}
		$user_loyalty_point = !empty($this->session->userdata('user_loyalty_point'))?$this->session->userdata('user_loyalty_point'):0;
		$earn_loyalty_point = !empty($this->session->userdata('earn_loyalty_point'))?$this->session->userdata('earn_loyalty_point'):0;
		$used_point = !empty($this->session->userdata('used_point'))?$this->session->userdata('used_point'):0;
		
		$sum = ($user_loyalty_point+$earn_loyalty_point-$used_point);
		$this->session->set_userdata('user_loyalty_point',$sum);
		
		$view['msg'] ='';
		$view['page_title']="Success";
		$this->load->view('dinein/success',$view);
		
	}	

	public function paymentreviewdinein()
	{

		if(!$this->session->userdata('order_id') || empty($this->merchantId)){
			redirect(base_url('dinein'));
			exit;
		} 
		$table_number = !empty($this->session->userdata('table_number' ))?$this->session->userdata('table_number' ):'';	
		$url = API_URL.'/cart/kitchen-detail';
		$data['tableNumber'] = $table_number ;
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status2 = $response['status'];
		$result2 = $response['result'];
		if(isset($result2) && count($result2)>0 && $status2==1){ // success
			$order_id = $result2['orderId'];
			$this->session->set_userdata('order_id',$order_id );
		}else{
			redirect(base_url('dinein'));
		}
		
		$view['msg'] ='';
		$view['merchant_id']= $this->merchantId;
		$view['account_id']= $this->accountId;
		$view['secret']= $this->sharedSecret;
		$view['description']= $this->description;
		$view['payType']= $this->payType;
		$view['page_title']="Payment Review";
		$this->load->view('dinein/paymentreview',$view);
	}
	

	
	
	
	

}
