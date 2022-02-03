<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/order_model');
		$this->load->model('admin/user_model');
		if (!$this->session->userdata('user_id')) {
			redirect('admin'); 
		}
		$this->redirection();
	}

	public function redirection(){
		if(!is_logged_in()) 
		{
			redirect('admin/login'); 
		}
		else if(is_logged_in() && (is_user_type()=='user' || is_user_type()=='driver')) 
		{
			redirect(base_url()); 
		}
	}
	
	public function index(){
		redirect('admin/order/view');
	}
	
	public function orderView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/order/view";
		$config["total_rows"] = $this->order_model->getCountOrder($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
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
	
		$view['page_title']='Table Booking';
		$view['main_content']='admin/pages/order/order_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->order_model->getMasterOrder($filter,$data);
		//$view['driver'] = $this->order_model->getDriver();
		$this->load->view('admin/template_admin',$view);
	}

	public function orderDetailView(){
		
		$id = $this->input->post('id');
		$user_id = $this->input->post('user_id');
		$restaurant_id = $this->input->post('restaurant_id');

		$url = API_URL.'/api/order/detail';
		$data['userId'] = $user_id;
		$data['orderId'] = $id;
		$data['restaurantId'] = $restaurant_id;
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];

		
		
		$type = !empty($this->input->post('type'))?$this->input->post('type'):'popup';
		if($status==200 && $result!=''){
			
			$orderDetail = $result['orderDetail'];
			$userDetail = $result['userDetail'];
			$merchantInfo = $result['merchantInfo'];
			$payment_status = 'Pending';
			$address = '';
			$orderAddress = $orderDetail['address_detail'];
			$order_history = !empty($orderDetail['status_history'])?$orderDetail['status_history']:[];

			$getOrderInvitedDriverDetail = $this->order_model->getOrderInvitedDriverDetail($id);
			
			if($orderDetail['order_type']==2){
				$address = $orderAddress['name'].'<br>'.$orderAddress['addressLine1'].' ,'.$orderAddress['addressLine2'].'<br>'.$orderAddress['pincode'];
			}
			
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

			$payment_type= $orderDetail['payment_type']==1?'Cash':'Online';
			$accept_for =  !empty($orderDetail['admin_delivery_time'])?date('g:i a',strtotime($orderDetail['admin_delivery_time'])):'';
			
			$print = '
			<div class="row">
				<div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<address style="font-size: 12px;font-family: sans-serif;font-style: normal;line-height: 16px;">
								<img src="'.$merchantInfo['mail_logo_url'].'"><br>
								<strong>'.$merchantInfo['merchant_name'].'</strong><br>'.$merchantInfo['address'].'<br>
								<strong>Phone</strong>: '.$merchantInfo['contact_phone'].'<br>
								<strong>Order Type</strong>: '.$orderType.'<br>
								<strong>Payment Type</strong>:'.$payment_type.'<br>
								<strong>Order Number</strong>: '.$orderDetail['order_id'].'<br>
								<strong>Order Date</strong>: '.date('F j, Y, g:i a',$orderDetail['added_date_timestamp']/1000).'<br>
								<strong>Accepted For</strong>: '.$accept_for.'
							</address>
						</div>
					</div>
					<hr>
					<table class="table table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th style="text-align: left;font-size: 12px;font-family: sans-serif;">Product</th>
								<th style="font-size: 12px;font-family: sans-serif;">Qty.</th>
								<th class="text-center"></th>
								<th class="text-center" style="text-align: right;font-size: 12px;font-family: sans-serif;">Total</th>
							</tr>
						</thead>
						<tbody>
							';?>
			
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="col-md-12 form-group p_star">
								<table class="table table-striped table-bordered">
									<tbody>	
										<?php
										if(!empty($order_history)  && count($order_history)>0){ ?>
											<tr>
												<td class="width-50">Order History</td>
												<td class="text-right width-50">
													<table class="table table-striped table-bordered">
														<tbody>	
															<tr>
																<th>Status</th>
																<th>Time</th>
															</tr>
																<?php 
																foreach($order_history as $history){
																	echo '
																	<tr>
																		<td>'.order_status[$history['status']].'</td>
																		<td>'.date('d-m-Y h:i a',$history['added_date']/1000).'</td>
																	</tr>	';
																}
																?>
														</tbody>
													</table>			
												</td>
											</tr>
											<?php
										} ?>

										<?php
										if(!empty($getOrderInvitedDriverDetail) && count($getOrderInvitedDriverDetail)>0){ ?>
											<tr>
												<td class="width-50">Order Invitation</td>
												<td class="text-right width-50">
													<table class="table table-striped table-bordered">
														<tbody>	
															<tr>
																<th>Name</th>
																<th>Mobile</th>
																<th>Status</th>
															</tr>
																<?php 
																foreach($getOrderInvitedDriverDetail as $history){

																	if($history->driver_order_status == 1){
																		$sss = 'Accepted';

																	}else if($history->driver_order_status == 2){
																		$sss = 'Pending';

																	}else if($history->driver_order_status == 3){
																		$sss = 'Rejected';

																	}else if($history->driver_order_status == 4){
																		$sss = 'Deliverd';
																	}
																	
																	echo '
																	<tr>
																		<td>'.$history->name.'</td>
																		<td>'.$history->mobile.'</td>
																		<td>'.$sss.'</td>
																	</tr>	';
																}
																?>
														</tbody>
													</table>			
												</td>
											</tr>
											<?php
										} ?>
										

										<tr>
											<td class="width-50">Customer Name</td>
											<td class="text-right width-50"><?php echo $userDetail['name'];?></td>
										</tr>
										
										<tr>
											<td class="width-50">Customer Email</td>
											<td class="text-right width-50"><?php echo $userDetail['email'];?></td>
										</tr>
										
										<tr>
											<td class="width-50">Customer Mobile</td>
											<td class="text-right width-50"><?php echo $userDetail['mobile'];?></td>
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
											<td class="width-50">Store Address</td>
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
										if($orderDetail['order_type']==2){  ?>
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
										if($orderDetail['payment_type']==2){  ?>
											<tr>
												<td  class="width-50">Payment Id</td>
												<td class="text-right width-50"><?php echo $orderDetail['payment_id'];?></td>
											</tr>
											<tr>
												<td  class="width-50">Remark</td>
												<td class="text-right width-50"><?php echo $orderDetail['payment_remark'];?></td>
											</tr>
											<?php 
										} 
										if($orderDetail['order_type']==2){?>
											<tr>
												<td  class="width-50">Address Detail</td>
												<td class="text-right width-50"><?php echo html_entity_decode($address);?></td>
											</tr>
											<?php 
										} ?>
										<tr>
											<td  class="width-50">Payment Status</td>
											<td class="text-right width-50"><?php echo $payment_status;?></td>
										</tr>
									</tbody>
								</table>
								<div class="item-list">
									<?php
									foreach($result['orderItemDetail'] as $basketKey=>$basketValue){
										$product =$basketValue['master_product'][0];
										$print .= '
										<tr>
											<td class="col-md-9" tyle="text-align: left;font-size: 12px;font-style: normal;font-family: sans-serif;">'.$product['item_name'].'</td>
											<td class="col-md-1" style="text-align: center;font-size: 12px;font-style: normal;font-family: sans-serif;"> '.$basketValue['quantity'].' </td>
											<td class="col-md-1 text-center"></td>
											<td style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;">'.CURRENCY. number_format($basketValue['quantity']*$basketValue['price'],2).'</td>
										</tr>';
										?>
										<div class="row">
											<div class="col-md-9">
												<p class="title-menu"><?php echo $basketValue['quantity'].' x '.$product['item_name']; ?> <?php echo CURRENCY. number_format($basketValue['price'],2); ?></p>
											</div>
											<div class="col-md-3"><?php echo CURRENCY. number_format($basketValue['quantity']*$basketValue['price'],2); ?></div>
										</div>
										<?php 
										if(!empty($basketValue['master_ingredient'])){ 
											$ing = '';
											foreach($basketValue['master_ingredient'] as $value){
												$ing .=implode(',',$value).',';
											}?>
											<div class="row ingredient">
												<div class="col-md-12 addons-added">Ingredient</div>
												<div class="col-md-12 addons-added"><?php echo rtrim($ing,','); ?></div>
											</div>
											<?php 
										}
										$special_instruction =  $basketValue['special_instruction'];
										if(!empty($special_instruction)){ ?>
											<div class="row ingredient">
												<div class="col-md-12 addons-added">Special Instruction</div>
												<div class="col-md-12 addons-added"><?php echo $special_instruction; ?></div>
											</div>
											<?php 
										} 
										if($basketValue['order_addon_item_detail'] && count($basketValue['order_addon_item_detail'])>0 && $basketValue['has_addon']==1){ 
											$addon_cat_name ='';
											$match_cat_name='-';
											foreach($basketValue['order_addon_item_detail'] as $subItem){
												$addon_cat_name = $subItem['addon_category_name'];
												?>
												<div class="row">
													<?php
													if($addon_cat_name!=$match_cat_name){
														$match_cat_name = $subItem['addon_category_name'];
														$print .= '<tr>
														<td class="col-md-9" tyle="text-align: left;font-size: 12px;font-style: normal;font-family: sans-serif;">'.$subItem['addon_category_name'].'</td>
														</tr>';
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
												$print .= '
												<tr>
													<td class="col-md-9" tyle="text-align: left;font-size: 12px;font-style: normal;font-family: sans-serif;">'.$subItem['addon_item_name'].'</td>
													<td class="col-md-1" style="text-align: center;font-size: 12px;font-style: normal;font-family: sans-serif;"> '.$subItem['addon_quantity'].' </td>
													<td class="col-md-1 text-center"></td>
													<td style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;">'.$right.'</td>
												</tr>';
											}
										} 
									}
									?>
								</div>
								<?php
								$print .= '
								<tr>
									<td colspan="3" class="text-right">
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Sub Total:</strong></p>
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Discount:</strong></p>
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Service Charge:</strong></p>';
										if($orderDetail['order_type']==2){ 
												$print .= '<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Delivery Fee:</strong></p>';
										} 													
										if(!empty($orderDetail['promocode_amt']) && $orderDetail['promocode_amt']>0){ 
											$print .= '<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Voucher Discount:</strong></p>'; 
										}
											
										if(!empty($orderDetail['loyalty_point_earn']) && $orderDetail['loyalty_point_earn']>0){
											$print .= '<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Discount:</strong></p>';
										}
											
										if(!empty($orderDetail['loyalty_point_used']) && $orderDetail['loyalty_point_used']>0){
											$print .= '<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Loyalty Point Used:</strong></p>';
										}
											
										if(!empty($orderDetail['loyalty_point_value']) && $orderDetail['loyalty_point_value']>0){ 
											$print .= '<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Loyalty Point Value:</strong></p>';
										}
										$print .= '<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Grand Total:</strong></p>
									</td>
									<td class="text-center">
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY. number_format($orderDetail['sub_total'],2).'</strong></p>
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY .number_format($orderDetail['discount'],2).'</strong></p>
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY .number_format($orderDetail['service_charge'],2).'</strong></p>';

										if($orderDetail['order_type']==2){ 
											$print .= '<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY.' '.number_format($orderDetail['delivery_fee'],2).'</strong></p>';
										}
										if(!empty($orderDetail['promocode_amt']) && $orderDetail['promocode_amt']>0){ 
											$print .= '<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY. number_format($orderDetail['promocode_amt'],2).'</strong></p>'; 
										}
										if(!empty($orderDetail['loyalty_point_earn']) && $orderDetail['loyalty_point_earn']>0){
											$print .= '<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY. number_format($orderDetail['loyalty_point_earn'],2).'</strong></p>'; 
										}
										if(!empty($orderDetail['loyalty_point_used']) && $orderDetail['loyalty_point_used']>0){
											$print .= '<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.number_format($orderDetail['loyalty_point_used'],2).'</strong></p>'; 
										}
										if(!empty($orderDetail['loyalty_point_value']) && $orderDetail['loyalty_point_value']>0){ 
											$print .= '<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY. number_format($orderDetail['loyalty_point_value'],2).'</strong></p>';
										}
										$print .= '<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY .number_format($orderDetail['grand_total'],2).'</strong></p>';
										$print .= '
									</td>
								</tr>
								</tbody>
								</table>
								<hr>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12">
										<address  style="font-size: 12px;font-family: sans-serif;font-style: normal;line-height: 16px;">
											<strong>Customer\'s Detail</strong><br>
											<strong>'.$userDetail['name'].'</strong>';
											if($orderDetail['order_type']==2){
												$print .= '<br>'.$address;
											}
											$print .= '<br>
											<strong>Email</strong>: '.$userDetail['email'].'<br>
											<strong>Phone</strong>: '.$userDetail['mobile'];
											if(!empty($orderDetail['instruction'])){
												$print .= '<br><strong>Comment:<em>'.
												$orderDetail['instruction'].'</em></strong>';
											}
											$payment_type= ($orderDetail['order_type']==2  && $orderDetail['payment_remark']=='Success' && $orderDetail['payment_status']==1)?'Paid':'Unpaid';
											$print .= '<br>
											<strong>Payment staus</strong>:<strong style="font-size:16px;">'.$payment_type.'</strong>
										</address>
									</div>
								</div>
								</div>
								</div>';
						
								echo '
								<div style="display:none">
									<div id="printMe"  style="width: 302px;text-align: center;font-style: normal;border: solid 1px #ccc;padding: 15px;">'.$print.'</div>
								</div>
								<button type="button" class="btn btn-success btn-lg btn-block" id="my-link" onclick="printDiv(\'printMe\')">
									Print<span class="glyphicon glyphicon-chevron-right"></span>
								</button>';
								?>
				
							</div>
							<div class="col-md-12  form-group p_star">
								<button type="button" class="btn-block btn btn-success"  data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<script>
				function printDiv(divName){
					var w = window.open();
					var printOne = document.getElementById(divName).innerHTML;
					w.document.write('<html><head></head><body>' + printOne+'</body></html>');
					
					w.document.close();
					w.focus();
					w.print();
					w.close();
				}
				var type = '<?php echo $type; ?>'
				
				if(type=='direct'){
					document.getElementById('my-link').click();
				}
			</script>
			<?php
		}
	}

	public function newOrderView(){
			
		$this->load->library('pagination');
		$_GET['settled']=1;
		$filter = $_GET;
		$uri = http_build_query($_GET);
	
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/order/new-order";
		$config["total_rows"] = $this->order_model->getCountOrder($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
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
	
		$view['page_title']='New Order';
		$view['main_content']='admin/pages/order/new_order_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->order_model->getMasterOrder($filter,$data);
		//$view['driver'] = $this->order_model->getDriver();
		$this->load->view('admin/template_admin',$view);
	}		

	public function changeorderstatus(){
			
		$order_id = $this->input->post('order_id');
		$new_status = (int)$this->input->post('new_status');
	 	$driver = !empty($this->input->post('driver'))?array_filter($this->input->post('driver')):[];
		$order_remark = htmlspecialchars(strip_tags($this->input->post('order_remark')));
		$delivery_time = (!empty($this->input->post("delivery_time")))?date("H:i", strtotime($this->input->post("delivery_time"))):'00:00:00';
		$msg = 'Order status has been successfully changed.';
		$data = array('status' => $new_status,'admin_order_remark'=>$order_remark,'admin_delivery_time'=>$delivery_time);
		
		if($driver!='' && !empty($driver) && $driver!=0 && count($driver)>0)
		{
			$data['driver_status']=(int)2; //sending invitation for order accapt.  0 default,1 driver assigned,2 sending inivitation

			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			foreach($driver as $driverId){
				$hashUnique = md5(uniqid(rand(), true));
				$driverData = array(
					'hash'=> $hashUnique,
					'driver_user_id' =>$driverId,
					'order_id' =>$order_id,
					'order_status' =>$new_status,
					'admin_delivery_time' =>$delivery_time,
					'driver_order_status' =>(int)2,   //1 accept, 2 pending/default, 3 reject
					'order_assigned' =>(int)0,   //1 yes, 0 no or default
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created,
					'driver_action_timestamp'=>time()*1000,
				);
				$this->order_model->addOrderForDriver($driverData);
			}

		}
		if($new_status==8 || $new_status==9){
			$data['payment_status']= 1;
		}
	
		$orderDetail = changeOrderStatusMail($order_id,$new_status,$order_remark);
		$status_history = json_decode(json_encode($orderDetail['status_history']),true);
		$status_history[] = array('status'=>$new_status,'added_date'=>time()*1000);
		$data['status_history'] = $status_history;
		if($order_id!=''){
			$this->order_model->updateOrder($data,$order_id);
			$response = array(
				'status'=>'1',
				'msg'=>$msg,
				'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Order does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function todayOrderView(){
			
		$this->load->library('pagination');
		$_GET['settled']=1;
		$filter = $_GET;
		$uri = http_build_query($_GET);
	
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/order/today-order";
		$config["total_rows"] = $this->order_model->getCountOrder($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
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
	
		$view['page_title']='New Order';
		$view['main_content']='admin/pages/order/today_order_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->order_model->getMasterOrder($filter,$data);
		//$view['driver'] = $this->order_model->getDriver();
		$this->load->view('admin/template_admin',$view);
	}

	public function todaySalesOrderView(){
			
		$this->load->library('pagination');
		$_GET['settled']=1;
		$_GET['status_not_in']='0,4,5,6,12';
		$filter = $_GET;
		$uri = http_build_query($_GET);
	
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/order/today-sales";
		$config["total_rows"] = $this->order_model->getCountOrder($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
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
		
		$view['page_title']='New Order';
		$view['main_content']='admin/pages/order/today_sales_order_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->order_model->getMasterOrder($filter,$data);
		if ($this->session->userdata('role_master_tbl_id')==1) {
			$view['stores'] = $this->order_model->getStore();
		}else{
			$view['stores'] = [];
		}
		$view['todaySalesPrint'] = $this->order_model->todaySalesPrint($filter);
		$this->load->view('admin/template_admin',$view);
	}

	public function dshboardOrderDetail(){

		$data = $this->order_model->dashboardOrder();
		$response = array(
				'status'=>'1',
				'newOrder'=>$data['newOrder'],
				'todayOrder'=>$data['todayOrder'],
				'monthOrder'=>$data['monthOrder'],
				'todaySales'=>CURRENCY.number_format($data['todaySales'],2),
				'redirect'=>'0'
			);
		
		header('Content-Type: application/json');
		echo json_encode($response);
		
	}

	public function notification(){
		echo $this->order_model->notification();
	}

	public function settlement(){
		$this->order_model->settlement();
			
	}

	public function getDriverAjax(){
		$order_id = $this->input->post('order_id');
		$data = $this->order_model->getDriver($order_id);
		$response = array(
			'status'=>'1',
			'data'=>$data,
			'redirect'=>'0'
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function checkDriverAssignedAjax(){
		$order_id = $this->input->post('order_id');
		$data = $this->order_model->checkDriverAssignedAjax($order_id);
		$response = array(
			'status'=>'1',
			'data'=>$data,
			'redirect'=>'0'
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function assignedDriverDetail(){
		$order_id = $this->input->post('orderid');
		$driver_id = $this->input->post('driver_id');
		$data = $this->order_model->assignedDriverDetail($order_id,$driver_id);
		//print_r($data);
		?>
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Driver Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<input type="hidden" name="order_id" id="remove_driver_order_id" value="<?php echo $order_id;?>">
				<input type="hidden" name="driver_id" id="remove_driver_driver_id" value="<?php echo $driver_id;?>">
				<div class="modal-body">
					<div class="col-md-12 form-group p_star">
						<table class="table table-striped table-bordered">
							<tbody>	
								<tr>
									<td class="width-50">Driver Name</td>
									<td class="text-right width-50"><?php echo $data['name'];?></td>
								</tr>
								
								<tr>
									<td class="width-50">Driver Email</td>
									<td class="text-right width-50"><?php echo $data['email'];?></td>
								</tr>
								
								<tr>
									<td class="width-50">Driver Mobile</td>
									<td class="text-right width-50"><?php echo $data['mobile'];?></td>
								</tr>

								<tr>
									<td class="width-50">Driver Pincode</td>
									<td class="text-right width-50"><?php echo $data['pincode'];?></td>
								</tr>

								<tr>
									<td class="width-50">Driver Address</td>
									<td class="text-right width-50"><?php echo $data['address'];?></td>
								</tr>

								<tr>
									<td class="width-50">Driver status</td>
									<td class="text-right width-50"><?php echo ($data['status']==1)?'Active':'Inactive';?></td>
								</tr>

								<tr>
									<td class="width-50">Driver Online</td>
									<td class="text-right width-50"><?php echo ($data['is_online']==1)?'Online':'Offline';?></td>
								</tr>
								<tr>
									<td class="width-50">Driver Added By</td>
									<td class="text-right width-50"><?php echo ($data['added_by_role']==1)?'Admin':'Seller';?></td>
								</tr>

							</tbody>
						</table>
					</div>
					<div class="col-md-12  form-group p_star">
						<button type="button" class=" btn btn-success col-md-6 remove-driver-confirm">Remove Driver</button>
						<button type="button" class="btn btn btn-danger col-md-5"  data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function removeDriver(){
		$order_id = $this->input->post('order_id');
		$driver_id = $this->input->post('driver_id');
		$data = $this->order_model->removeDriver($order_id,$driver_id);
		$response = array(
			'status'=>'1',
			'data'=>$data,
			'redirect'=>'0'
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	}

}