<?php
$grandTotal = trim(str_replace(CURRENCY,'',$this->session->userdata('success_arr')['grandTotal'])); 
$trans_id = $this->session->userdata('order_id');
$billing_postcode = $this->session->userdata('billing_address')['pincode'];;
$customer_phone_number = $this->session->userdata('billing_address')['phoneNumber'];
$email_address = $this->session->userdata('email');
$billing_fullname = $this->session->userdata('billing_address')['name'];
$billing_address = $this->session->userdata('billing_address')['addressLine1'].','.$this->session->userdata('billing_address')['addressLine2'];
?>
<div class="container mt-3 mb-3 link-background bg-white">
	<section class="product_list pt-5">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="hero-cap text-center">
						<h2>Payment Review</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 row">
					<div class="col-lg-3"></div>
					<div class="col-lg-6">
						<?php 
						if($payType=='nochex'){?>
							<div class="order_box">
								<?php 
								$attributes = array('class' => 'payment-review', 'id' => 'payment-review'); 
								echo form_open('https://secure.nochex.com',$attributes);?>
									
									<input type="hidden" name="merchant_id" value="<?php echo $merchant_id;?>">
									<input type="hidden" name="amount" value="<?php echo $grandTotal; ?>">
									<input type="hidden" name="description" value="<?php echo $description;?>">
									<input type="hidden" name="order_id" value="<?php echo $trans_id; ?>">
									<input type="hidden" name="billing_postcode" value="<?php echo $billing_postcode; ?>">
									<input type="hidden" name="customer_phone_number" value="<?php echo $customer_phone_number; ?>">
									<input type="hidden" name="email_address" value="<?=$email_address?>">
									<input type="hidden" name="hide_billing_details " value="true">
									<input type="hidden" name="billing_fullname" value="<?=$billing_fullname?>">
									<input type="hidden" name="billing_address" value="<?=$billing_address?>">
									<input type="hidden" name="success_url" value="<?php echo base_url().'order/success?order_id='.$trans_id;?>&amount=<?=$grandTotal; ?>">                            
									<input type="hidden" name="cancel_url" value="<?php echo base_url().'order/cancel?order_id='.$trans_id;?>">
									<input type="hidden" name="callback_url" value="<?php echo base_url().'order/callback';?>">                            
									<div class="table-responsive m-t-40">
										<table id="myTable" class="table table-bordered table-striped">
											<tbody>
												<tr>
													<td >Sub Total</td>
													<td><?php echo $this->session->userdata('success_arr')['Subtotal']; ?></td>
												</tr>
												<tr>
													<td>Discount</td>
													<td>-<?php echo $this->session->userdata('success_arr')['discount']; ?></td>
												</tr>
												<?php 
												if(isset($this->session->userdata('success_arr')['promoCodeAmnt']) && $this->session->userdata('success_arr')['promoCodeAmnt']!=''){ ?>
													<tr>
														<td>Voucher Discount</td>
														<td>-<?php echo $this->session->userdata('success_arr')['promoCodeAmnt']; ?></td>
													</tr>
													<?php
												}
												if(isset($this->session->userdata('success_arr')['loyaltyPointRedeemValue']) && $this->session->userdata('success_arr')['loyaltyPointRedeemValue']!=''){ ?>
													<tr>
														<td>Loyalty Point Discount</td>
														<td>-<?php echo $this->session->userdata('success_arr')['loyaltyPointRedeemValue']; ?></td>
													</tr>
													<?php
												}
												if($this->session->userdata('success_arr')['orderType']==2){?>
													<tr>
														<td>Delivery Fee</td>
														<td><?php echo $this->session->userdata('success_arr')['deliveryFee']; ?></td>
													</tr>
													<?php 
												} ?>
												<tr>
													<td>Service Charge</td>
													<td><?php echo $this->session->userdata('success_arr')['serviceCharge']; ?></td>
												</tr>
												<tr>
													<td>Total</td>
													<td><?php echo $this->session->userdata('success_arr')['grandTotal']; ?></td>
												</tr>
												<tr>
													<td >Reference Id#</td>
													<td><?php echo $this->session->userdata('order_id'); ?></td>
												</tr>
												<tr>
												<td></td>
												<td><input type="submit" value="Pay" class="btn btn-success"></td>
												</tr>
											</tbody>
										</table>
									</div>
									<?php
								echo form_close(); ?>
							</div>
							<?php
						}
						else if($payType=='rms'){ ?>
							<div class="order_box nochex">
								<div class="table-responsive m-t-40">
									<table id="myTable" class="table table-bordered table-striped">
										<tbody>
										<tr>
											<td >Sub Total</td>
											<td><?php echo $this->session->userdata('success_arr')['Subtotal']; ?></td>
										</tr>
										<tr>
											<td>Discount</td>
											<td><?php echo $this->session->userdata('success_arr')['discount']; ?></td>
										</tr>
										<?php 
										if(isset($this->session->userdata('success_arr')['promoCodeAmnt']) && $this->session->userdata('success_arr')['promoCodeAmnt']!=''){ ?>
											<tr>
												<td>Voucher Discount</td>
												<td>-<?php echo $this->session->userdata('success_arr')['promoCodeAmnt']; ?></td>
											</tr>
											<?php
										}
										if(isset($this->session->userdata('success_arr')['loyaltyPointRedeemValue']) && $this->session->userdata('success_arr')['loyaltyPointRedeemValue']!=''){ ?>
											<tr>
												<td>Loyalty Point Discount</td>
												<td>-<?php echo $this->session->userdata('success_arr')['loyaltyPointRedeemValue']; ?></td>
											</tr>
											<?php
										}
										if($this->session->userdata('success_arr')['orderType']==2){
											?>
											<tr>
												<td>Delivery Fee</td>
												<td><?php echo $this->session->userdata('success_arr')['deliveryFee']; ?></td>
											</tr>
											
											<tr>
												<td>Service Charge</td>
												<td><?php echo $this->session->userdata('success_arr')['serviceCharge']; ?></td>
											</tr>
											<?php 
										} ?>
										<tr>
											<td>Total</td>
											<td><?php echo $this->session->userdata('success_arr')['grandTotal']; ?></td>
										</tr>
										<tr>
											<td >Reference Id#</td>
											<td><?php echo $this->session->userdata('order_id'); ?></td>
										</tr>
										<tr>
										<td></td>
										<td>
											<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
											<script src="<?=base_url()?>assets/frontend/js/dist/rxp-js.js"></script>
											<script>
											RealexHpp.setHppUrl('https://pay.sandbox.realexpayments.com/pay');
											// get the HPP JSON from the server-side SDK
											$(document).ready(function () {
												$.getJSON(site_url+"/order/proxy", function (jsonFromServerSdk) {
													RealexHpp.lightbox.init(
														"payButtonId",
														site_url+"order/successRMS", // merchant url
														jsonFromServerSdk   //form data
													);
													$('body').addClass('loaded');
												});
											});
											</script>
											<input type="submit" class="btn btn-success" id="payButtonId" value="Pay" />
										</td>
										</tr>
									</tbody>
									</table>
								</div>
							</div>
							<?php 
						} ?>
					</div>
					<div class="col-lg-3"></div>
				</div>
			</div>
		</div>
	</section>
</div>
	
