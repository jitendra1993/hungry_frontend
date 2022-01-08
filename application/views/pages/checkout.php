<?php
$checkout_type = $this->session->userdata('checkout_type');
$success_arr = array();
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
$used_point = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';
?>
<div class="clearfix"></div>
		
<div class="container mt-3 mb-3 link-background bg-white">
	<section class="product_list pt-5">
		<div class="container">
			<div class="row">
				<?php 
				if($this->session->flashdata('error_msg')){ ?>
					<div class="alert alert-danger alert-dismissible"> 
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?=($this->session->flashdata('error_msg'));?> 
					</div> 
					<?php
				}?>
				<div class="col-xl-12">
					<div class="hero-cap text-center">
						<h2>Payment Option</h2>
					</div>
				</div>
			</div>
			
			<div class="col-md-12">
				<div class="product_list">
					<?php 
					$attributes = array('class' => 'paymentoption', 'id' => 'paymentoption'); 
					echo form_open('order/confirm',$attributes); ?>
					<div class="row">
						<?php 
						if(isset($checkout) && is_array($checkout) && count($checkout)>0){	
							$start = date('Y-m-d H:i:s');
							?>
							<div class="col-lg-8">
								<?php 
								if($checkout_type==1){
								
									$success_arr['orderType'] = 1;
									?>
									<h6 class="">Pickup information</h6>
									<hr/>
									<p><?php echo isset($restaurant_info['address'])?$restaurant_info['address']:'';?></p>
									<?php 
								} 
								if($checkout_type==2){

									$success_arr['orderType'] = 2;
									?>
									<h6 class="">Order Information</h6>
									<hr/>
									<p> TAKEAWAY Restaurant Delivery <?php echo date('F j, Y, g:i a',strtotime(msg['restaurant_delivery_time'],strtotime($start))); ?> to</p>
									<?php 
									if(isset($address) && is_array($address) && count($address)>0 ){ ?>	
										<h6 class="text-center">Order Address</h6>
										<div class="row col-md-12 p-3 pt-0 border-bottom">
											<div class="col-md-6 mb-2"><b>Name</b></div>
											<div class="col-md-6 mb-2 " ><?php echo $address['name']; ?></div>
											
											<div class="col-md-6 mb-2"><b>Address Type</b></div>
											<div class="col-md-6 mb-2 "><?php echo ($address['addressType']==1)?'Home':'Office'; ?></div>
											
											<div class="col-md-6 mb-2"><b>Phone</b></div>
											<div class="col-md-6 mb-2 "><?php echo $address['phoneNumber']; ?></div>
											
											<div class="col-md-6 mb-2"><b>Address Line 1</b></div>
											<div class="col-md-6 mb-2 "><?php echo $address['addressLine1']; ?></div>
											
											<div class="col-md-6 mb-2"><b>Address Line 2</b></div>
											<div class="col-md-6 mb-2 "><?php echo $address['addressLine2']; ?></div>
											
											<div class="col-md-6 mb-2"><b>Postcode</b></div>
											<div class="col-md-6 mb-2 "><?php echo $address['pincode']; ?></div>
										</div>	
										<?php 
									}
									if($delivery_type==1){ ?>
										<br>
										<h6>Select Place to deliver</h6>
										<select name="postcode" id="postcode" class="autoxxx form-control mb-10 setPlaceCheckout">
											<option value="">Select Place</option>
											<?php 
											foreach($place as $single){
												$sel = (!empty($this->session->userdata('postcode')) && $this->session->userdata('postcode')==$single['id'])?'selected':'';
												echo '<option value="'.$single['id'].'" data-name="'.$single['place'].'" '.$sel.'>'.$single['place'].'</option>';
											}
											?>
										</select>
										<span class="error has-danger err_postcode"></span>
										<?php 
									} 
								}
								
								if(isset($loyalty_point) && is_array($loyalty_point) && count($loyalty_point)>0 && $loyalty_point['enable_redeem']==1){
									$loyalty = $loyalty_point;
									?>
									<div class=" payment-options col-md-12">
										<h6>You have <?php echo round($profile['loyalty_point'],2);?> Loyalty Points.</h6>
										<hr/>
										<div class="col-md-12 row">
											<p class="col-md-12 row">Minimum Redeeming Loyalty Points :<b><?php echo $loyalty['min_redeeming_point'];?></b></p>
											<p class="col-md-12 row">Minimum Loyalty Points Used to Redeeming  :<b><?php echo $loyalty['min_point_used'];?></b></p>
											<p class="col-md-12 row">Maximum Loyalty Points Used to Redeeming  :<b><?php echo $loyalty['max_point_used'];?></b></p>
											<p class="col-md-12 row">Loyalty Points Apply Minimum Order    :<b><?php echo CURRENCY. $loyalty['points_apply_order_amt'];?></b></p>
											
											<div class="col-md-6">
												<input type="text" name="used_point" id="used_point" class="form-control" Placeholder="Used? How many?" value="<?php echo $used_point;?>"  onkeypress="return isNumber(event)">
												<span class="error has-danger err_used_point"></span>
												&nbsp;&nbsp;
												<?php 
												if(!empty($used_point)){ 
													$applyPoint = 'none';
													$removePoint = '';
												}else{ 
													$applyPoint = '';
													$removePoint = 'none';
												}
												?>
										
												<button type="button" class="btn btn-success applyPoint" id="applyPoint" style="display:<?php echo $applyPoint;?>">Apply</button>
												<button type="button" class="btn btn-success removePoint" id="removePoint" style="display:<?php echo $removePoint;?>">Remove</button>
											</div>
										</div>
									</div>
									<?php
								}?>
										
								<div class=" payment-options col-md-12">
								
									<h6>Payment Method</h6>
									<hr/>
									<div class="col-md-12 row">
										<input type="hidden" name="delivery_type" id="delivery_type" value="<?php echo $delivery_type;?>">
										<?php
										if(isset($payment_type) && is_array($payment_type) && count($payment_type)>0){
											$e =1;
											foreach($payment_type as $key=>$value){ 
												$checked = ($key=='cash')?'checked':'';
												if($key=='cash'){?>
													<div class="col-md-6">
														<div class="custom-control custom-radio mb-3">
															<input type="radio" class="custom-control-input toggleChanges paymentType" value="1" name="payment_type" id="cod" <?php echo $checked;?>  />
															<label class="custom-control-label" for="cod">Cash</label>
														</div>
														<input type="text" name="order_change" id="order_change" class="form-control" Placeholder="change? For how much?" onkeypress="return isNumberDecimal(event)">
													</div>
													<?php
												}
												if($e==2){ ?>
													<div class="col-md-6">
														<div class="custom-control custom-radio mb-3">
															<input type="radio" class="custom-control-input paymentType" value="2" name="payment_type" id="online"/>
															<label class="custom-control-label" for="online">Online Pay</label>
														</div>
													</div>
													<?php
												}
												$e++;
											}
										} ?>
									</div>
								</div>
								<?php
								if($merchant_enabled_voucher==1){ ?>
									<div class=" payment-options col-md-12">
										<h6>Have you voucher code?</h6>
										<hr/>
										<div class="col-md-12 row">
											<div class="col-md-6">
												<input type="text" name="promocode" id="promocode" class="form-control" Placeholder="Voucher Code" value="<?php echo $promoCode;?>">
												<span class="error has-danger err_promocode"></span>&nbsp;&nbsp;
												<?php 
												if(!empty($promoCode)){ 
													$apply = 'none';
													$remove = '';
												}else{ 
													$apply = '';
													$remove = 'none';
												}
												?>
												<button type="button" class="btn btn-success applyCode" id="apply" style="display:<?php echo $apply;?>">Apply</button>
												<button type="button" class="btn btn-success removeCode" id="edit" style="display:<?php echo $remove;?>">Remove</button>
											</div>
										</div>
									</div>
									<?php 
								} ?>
							</div>

							<div class="col-lg-4 col-md-6 p-0 append_checkout_item">
								<?php echo $checkoutitem;?>
							</div>
							<?php 
						} ?>	
					</div>
					<?php form_close(); ?>
				</div>
			</div>
			
		</div>
	
	</section>
</div>
	
