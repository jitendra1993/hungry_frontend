<?php
$user_id = $this->session->userdata('user_id');
$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
$used_point = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';

// echo '<pre>';
// print_r($checkout);
// echo '</pre>';

if(isset($checkout) && is_array($checkout) && count($checkout)>0){ ?>
	<div class="checkout__order">
		<h4>Your Order</h4>
		<div class="checkout__order__products">Products <span>Total</span></div>
		<ul>
			<?php
			foreach($checkout['itemsList'] as $checkoutKey=>$checkoutValue){ ?>
				<li><?php echo $checkoutValue['quantity'].' x '.$checkoutValue['item_name'].' '.CURRENCY. number_format($checkoutValue['price'],2); ?> 
				<span><?php echo CURRENCY. number_format($checkoutValue['quantity']*$checkoutValue['price'],2); ?></span>
				</li>

				<?php 
				if(!empty($checkoutValue['ingredient'])){ ?>
					<li>
						<div class="row cooking_ref sub-item">
						<h6 class="col-md-12 sub-it-head m-0 p-0">Ingredient</h6>
						<p class="col-md-12"><?php echo $checkoutValue['ingredient']; ?></p>
						</div>
					</li>
					<?php 
				} ?>
				
				<?php 
				if($checkoutValue['sub_items'] && count($checkoutValue['sub_items'])>0 && $checkoutValue['has_addon']==1){ 
					$addon_cat_name ='';
					$match_cat_name='-';
					foreach($checkoutValue['sub_items'] as $subItem){
						$addon_cat_name = $subItem['addon_category_name'];
						
						if(!empty($subItem['addon_item_price']) && $subItem['addon_item_price']>0)
						{
							$sub_item_price =  CURRENCY. number_format($subItem['addon_quantity']*$subItem['addon_item_price'],2);
							$ssb = CURRENCY. number_format($subItem['addon_item_price'],2);
						}else{
							$sub_item_price = '-';
							$ssb = '';
						}
								
						
						if($addon_cat_name!=$match_cat_name)
						{
							$match_cat_name = $subItem['addon_category_name'];
							$meal_deal = $checkoutValue['meal_deal'];
							if(!$meal_deal){ ?>
								<li><h6 class="col-md-12 sub-it-head m-0 p-0"><?php echo $subItem['addon_category_name']; ?></h6></li>
								<?php
							}
						}
						?>
						<li><?php echo $subItem['addon_quantity'].' x '.$subItem['addon_item_name'].' '.$ssb; ?> 
						<span>
						<?php echo $sub_item_price ?></span></li>
						<?php	
					}
				} ?>						

				
				<?php
			} ?>
		</ul>
	<input type="hidden" name="sub_total" id="sub_total" value="<?php  echo $checkout['subTotal'];?>">
	<input type="hidden" name="merchant_show_time" id="merchant_show_time" value="<?php  echo $checkout['merchant_show_time'];?>">
	<div class="checkout__order__subtotal">Subtotal <span><?php  echo CURRENCY.$checkout['subTotal'];?></span></div>
	<div class="checkout__order__total">Discount <span><?php  echo CURRENCY. number_format($checkout['discount'],2);?></span></div>
	<?php
	if(!empty($promoCode) && $checkout['promoCodeAmnt']>0){ 
		$success_arr['promoCodeAmnt'] = CURRENCY. number_format($checkout['promoCodeAmnt'],2);
		?>
		<div class="checkout__order__total voucherRemove">Voucher Discount<span><?php  echo '-'.CURRENCY. number_format($checkout['promoCodeAmnt'],2);?></span></div>
		<?php
	}
	?>
	
	<?php
	if(!empty($used_point) && $checkout['loyaltyPointRedeemValue']>0){ 
		$success_arr['loyaltyPointRedeemValue'] = CURRENCY. number_format($checkout['loyaltyPointRedeemValue'],2);
		?>
		<div class="checkout__order__total voucherRemove">Loyalty Point Discount<span><?php  echo '-'.CURRENCY. number_format($checkout['loyaltyPointRedeemValue'],2);?></span></div>
		<?php
	}
	?>
	
	<?php 
	$success_arr['serviceCharge'] = CURRENCY. number_format($checkout['serviceCharge'],2);
	if($checkout_type==2) {
		$success_arr['deliveryFee'] = CURRENCY. number_format($checkout['deliveryFee'],2);
		
		?>
	
		<div class="checkout__order__total">Delivery Fee <span><?php  echo CURRENCY. number_format($checkout['deliveryFee'],2);?></span></div>
	<?php } 
	?>
	<div class="checkout__order__total">Service Charge<span><?php  echo CURRENCY. number_format($checkout['serviceCharge'],2);?></span></div>
	
	
	
	<div class="checkout__order__total">Total <span><?php  echo CURRENCY. number_format($checkout['grandTotal'],2);?></span></div>
	
	
	<div class="row">
		<div class="col-md-12">
			<textarea class="form-control" name="instruction" rows=5 placeholder="Delivery Instruction"></textarea>
		</div>
	</div>
	<?php 
	if($checkout['merchant_show_time']==1){ 
		 $now = date("H:i");

		?>
		<div class="row">
			<div class="col-md-12 mt-20">
				<select name="delivery_time" id="delivery_time" class="form-control">
					<option value=""><?php echo $checkout_type==2?'Delivery Time':'Collection Time';?></option>
					<?php 
					foreach($checkout['merchant_time'] as $value){
						$time = date('H:i',strtotime($value));
						if($time>$now){
							echo '<option vale="'.$value.'">'.$value.'</option>';
						}

					}
					?>
					
				</select>	
				<span class="error has-danger err_delivery_time"></span>
			</div>
		</div>
		<?php
	} ?>
	<span class="total_value">
		<button type="submit" class="disable btn btn-proceed btn-lg btn-block"><?php echo $checkout['orderButtonText'];?></button>
	</span>
	
</div>
	<?php 
	
	$success_arr['orderType'] = $checkout_type;
	$success_arr['Subtotal'] =  CURRENCY.number_format($checkout['subTotal'],2);
	$success_arr['discount'] = CURRENCY. number_format($checkout['discount'],2);
	$success_arr['grandTotal'] = CURRENCY. number_format($checkout['grandTotal'],2);
	$this->session->set_userdata('success_arr',$success_arr);
	
	
} ?>