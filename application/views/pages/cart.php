<?php
$currency = CURRENCY;
$setting = $merchant_info['restaurant_setting'];

$service_status = isset($setting['service_status'])?$setting['service_status']:3;
$merchant_disabled_ordering = isset($setting['merchant_disabled_ordering'])?$setting['merchant_disabled_ordering']:0;
$merchant_close_store = isset($setting['merchant_close_store'])?$setting['merchant_close_store']:0;	
$merchant_minimum_order_delivery = !empty($setting['merchant_minimum_order_delivery'])?$setting['merchant_minimum_order_delivery']:0;
$merchant_maximum_order_delivery = !empty($setting['merchant_maximum_order_delivery'])?$setting['merchant_maximum_order_delivery']:0;
$merchant_minimum_order_pickup = !empty($setting['merchant_minimum_order_pickup'])?$setting['merchant_minimum_order_pickup']:0;
$merchant_maximum_order_pickup = !empty($setting['merchant_maximum_order_pickup'])?$setting['merchant_maximum_order_pickup']:0;	

$user_id='';	
if(is_logged_in())
{
	$user_id = $this->session->userdata('user_id');

}else if(!empty($this->session->userdata('guest_user_id'))){
    $user_id = $this->session->userdata('guest_user_id');
}

$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
$postCode = !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
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
$basketResult = array();
if(isset($result) && count($result)>0 && $status==200){ // success
	$basketResult = $result;
}

?>
<div class="checkboxsection col-md-3 col-sm-3 col-xs-12 fixedpanel">
    <div class="panel">
        <a class="allergy" href="javascript:void(0)">
            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Do you have an allergy or other dietary requirement?
        </a>
        <?php
        if(isset($basketResult) && count($basketResult)>0){
            $this->session->set_userdata('cartId',$basketResult['cartId']);
            if($merchant_disabled_ordering==0 || $merchant_close_store==0){ 
                $attributes = array('class' => 'checkout', 'id' => 'checkout'); 
                echo form_open('checkout',$attributes); 
            } ?>   
            <input type="hidden" name="cart" id="cart" value="<?php echo $basketResult['cartId']; ?>">
            <div class="row">
                <div class="col-md-12 row">
                    <?php
                    if($service_status==1 || $service_status==3){?>
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" class="custom-control-input chk_type" value="1" name="order_type" id="order_type_1"  <?php echo (!empty($this->session->userdata('checkout_type')) && $this->session->userdata('checkout_type')==1)?'checked':'';?>>
                                <label class="custom-control-label" for="order_type_1">Collection <i class="fa fa-shopping-bag" aria-hidden="true"></i></label>
                            </div>
                        </div>
                        <?php 
                    } 
                    if($service_status==2 || $service_status==3){
                        ?>
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" class="custom-control-input chk_type" value="2" name="order_type" id="order_type_2" <?php echo (!empty($this->session->userdata('checkout_type')) && $this->session->userdata('checkout_type')==2)?'checked':'';?> >
                                <label class="custom-control-label" for="order_type_2">Delivery <i class="fa fa-motorcycle" aria-hidden="true"></i></label>
                            </div>
                        </div>
                        <?php 
                    } ?>
                </div>
            </div>

            <div class="panel panel-heading">Your Basket
                <a href="javascript:void(0)" data-id="" data-cart="<?php echo $basketResult['cartId']; ?>" class="delete_item_cart"><i class="fa fa-trash-o pull-right"></i>
                </a>
            </div>
            <div class="panel-body">
                <ul class="list-unstyled checklist">
                    <?php
                    $show_cart=1;
                    $checkout_button_status = 1;
	                foreach($basketResult['itemsList'] as $basketKey=>$basketValue){ 
                        
                        $restricted_category = $basketValue['restricted_category'];
                        $restricted_with_time = $basketValue['restricted_with_time'];
                        if($show_cart==1){
                            $show_cart =  $basketValue['show_cart'];
        
                        }else if($show_cart==0){

                            $checkout_button_status = 0;
        
                        }
                        ?>
                        <li><a class="remov fa fa-minus-circle fa-fw"></a>
                            <p class="tem"><?php echo $basketValue['quantity'].' x '.$basketValue['item_name']; ?></p> 
                            <span class="pull-right"><?php echo CURRENCY.number_format($basketValue['quantity']*$basketValue['price'],2); ?></span>
                            <div class="clear"></div>

                            <div class="quantity">
                                <div class="pro-qty-cart product_qty_hgt">
                                    <?php $cc = ($basketValue['quantity']==1)?'delete_item_cart':'update_item_cart';?>
                                    <span class="number-qty-decr  qtybtn <?php echo $cc;?>" type="main_item"  data-id="<?php echo $basketValue['unique_id']; ?>" data-cart="<?php echo $basketResult['cartId']; ?>">-</span>
                                    
                                    <input class="number-qty" id="main_item_qty_<?php echo $basketValue['unique_id']; ?>" type="text" value="<?php echo $basketValue['quantity']; ?>" min="1" max="10" name="main_item_qty" onkeypress="return isNumber(event)">
                                    
                                    <span class="number-qty-incrs  qtybtn update_item_cart" type="main_item"  data-id="<?php echo $basketValue['unique_id']; ?>" data-cart="<?php echo $basketResult['cartId']; ?>">+</span>
                                </div>
                            </div>

                            <?php 
                            if(!empty($basketValue['ingredient'])){ ?>
                                <div class="row ingredient">
                                    <div class="col-md-12 addons-added">Ingredient</div>
                                    <div class="col-md-12 addons-added"><?php echo $basketValue['ingredient']; ?></div>
                                </div>
                                <?php 
                            }  
                            if(!empty($basketValue['special_instruction'])){ ?>
                                <div class="row ingredient">
                                    <div class="col-md-12 addons-added">Special Instruction</div>
                                    <div class="col-md-12 addons-added"><?php echo $basketValue['special_instruction']; ?></div>
                                </div>
                                <?php 
                            }
                            if($basketValue['sub_items'] && count($basketValue['sub_items'])>0 && $basketValue['has_addon']==1){ 
                                $addon_cat_name ='';
                                $match_cat_name='-';
                                echo '<ol class="list-unstyled subli">';
                                foreach($basketValue['sub_items'] as $subItem){
                                    $addon_cat_name = $subItem['addon_category_name'];

                                    if($addon_cat_name!=$match_cat_name){

                                        $match_cat_name = $subItem['addon_category_name'];
                                        $meal_deal = $basketValue['meal_deal'];
                                        if(!$meal_deal){
                                            ?>
                                            <div class="col-md-12 addons-added sub-it-head"><?php echo $subItem['addon_category_name']; ?></div>
                                            <?php
                                        }
                                    }

                                    if(!empty($subItem['addon_item_price']) && $subItem['addon_item_price']>0){

                                        $left = CURRENCY.$subItem['addon_item_price'];
                                        $right =  CURRENCY. number_format($subItem['addon_quantity']*$subItem['addon_item_price'],2);
                                    }else{
                                        $left = $right= '-';
                                    }
                                    ?>
                                    <li class="uli"><?php echo $subItem['addon_quantity'].' x '.$subItem['addon_item_name'];?><span class="pull-right"><?php echo $right; ?></span></li>
                                    <div class="col-md-3 quantity">
                                        <?php 
                                        if($subItem['addon_item_price']>0){ ?>
                                            <div class="pro-qty-cart product_qty_hgt">
                                                <?php $cc = ($subItem['addon_quantity']==1)?'delete_addon_item_cart':'update_addon_item_cart';?>
                                                <span class=" number-qty-decr  qtybtn <?php echo $cc; ?>" type="addon_item"  data-id="<?php echo $subItem['addon_unique_id']; ?>" data-cart="<?php echo $basketResult['cartId']; ?>">-</span></li>
                                                
                                                <input class=" number-qty" id="addon_item_qty_<?php echo $subItem['addon_unique_id']; ?>" type="text" value="<?php echo $subItem['addon_quantity']; ?>" min="1" max="10" name="addon_item_qty" onkeypress="return isNumber(event)"></li>
                                                
                                                <span class=" number-qty-incrs  qtybtn update_addon_item_cart" type="addon_item"  data-id="<?php echo $subItem['addon_unique_id']; ?>" data-cart="<?php echo $basketResult['cartId']; ?>">+</span></li>
                                            </div>
                                            <?php 
                                        } ?>
                                    </div>
                                    <?php
                                }
                                echo '</ol>';
                            }
                            ?>
                        </li>
                      
                        <?php
                    }
                    ?>
                    
                </ul>
                <div class="clear"></div>
                <div class="clear"></div>
                <ul class="list-unstyled totalbox">
                    <li>Subtotal <span class="pull-right"><?php  echo CURRENCY.number_format($basketResult['subTotal'],2);?></span></li>
                    <li>Service Charge <span class="pull-right">-<?php  echo CURRENCY. number_format($basketResult['serviceCharge'],2);?></span></li>
                    <li>Discount <span class="pull-right">-<?php  echo CURRENCY. number_format($basketResult['discount'],2);?></span></li>
                   
                    <?php 
                    if($checkout_type==2) { ?>
                        <li>Delivery Fee <span class="pull-right">-<?php  echo CURRENCY. number_format($basketResult['deliveryFee'],2);?></span></li>
                        <?php
                    }
                    ?>
                </ul>
                <h5 class="total">Total <span class="pull-right"><?php  echo CURRENCY.$basketResult['grandTotal'];?></span></h5>
                <div class="clear"></div>

                
            </div>
            <hr class="my-2">
            <?php 
            if(isset($basketResult['loyaltyPointEarn']) && $basketResult['loyaltyPointEarn']>0){ ?>
                <p class="has-danger text-center allergy mimium_value">You will earn <b>`<?php echo $basketResult['loyaltyPointEarn'];?>`</b> Loyalty Points to redeem on your next order.</p>
                <?php 
                $this->session->set_userdata('earn_loyalty_point',$basketResult['loyaltyPointEarn']);
            }
            if($basketResult['grandTotal']<$merchant_minimum_order_delivery && $checkout_type==2){ ?>
                <p class="has-danger text-center allergy mimium_value">Cart value should be minimum <?php echo CURRENCY.number_format($merchant_minimum_order_delivery,2);?></p>
                <button type="button" class="btn btn-yel btn-md chechout bold-font" disabled><span>Checkout</span></button>
                <?php 

            } else if($basketResult['grandTotal']<$merchant_minimum_order_pickup && $checkout_type==1){ ?>
                <p class="has-danger text-center allergy mimium_value">Cart value should be minimum <?php echo CURRENCY.number_format($merchant_minimum_order_pickup,2);?></p>
                <button type="button" class="btn btn-yel btn-md chechout bold-font" disabled><span>Checkout</span></button>
                <?php

            }else if($merchant_disabled_ordering==0 && $merchant_close_store==0 && $checkout_button_status==1){ ?>
                <button type="button" class="btn btn-yel btn-md chechout bold-font" id="quick_checkout_button">
                <span>Checkout</span>
                </button>
                <?php 

            }else if($merchant_close_store==1 || $merchant_disabled_ordering==1){ ?>
                <button type="button" class="btn btn-yel btn-md chechout bold-font" disabled><span><?php echo msg['store_close'];?></span></button>
                <?php
            } 
            if($checkout_button_status==0){ ?>
                <p class="has-danger text-center"><?php echo msg['alcohol']?></p>
                <?php 
            }
           
            if($merchant_disabled_ordering==0 || $merchant_close_store==0){ 
                echo form_close(); 
            }
        } else{ ?>
            <div class="row">
                <div class="col-md-6"><h3><span>Your Basket</span></h3></div>
                <div class="empty_cart">Your cart is empty</div>
            </div>
            <?php
        } ?>

    </div>
</div>
	