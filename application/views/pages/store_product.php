<?php 
	$currency = CURRENCY;
	$setting = $merchant_info['restaurant_setting'];
	$restaurant_info = $merchant_info['restaurant_info'];
	$store_category = $merchant_info['store_category'];
	$service_status = isset($setting['service_status'])?$setting['service_status']:3;
	$merchant_close_store = isset($setting['merchant_close_store'])?$setting['merchant_close_store']:0;
	$merchant_close_msg = isset($setting['merchant_close_msg'])?$setting['merchant_close_msg']:msg['store_close'];
	$merchant_disabled_ordering = isset($setting['merchant_disabled_ordering'])?$setting['merchant_disabled_ordering']:0;
	$service_charge = !empty($setting['service_charge'])?CURRENCY.number_format($setting['service_charge'],2):0;	
    $delivery_estimation = isset($setting['delivery_estimation'])?$setting['delivery_estimation']:'45 mins approx';
    $pickup_estimation = isset($setting['pickup_estimation'])?$setting['pickup_estimation']:'45 mins approx';
    $delivery_coverd = isset($setting['merchant_delivery_coverd'])?$setting['merchant_delivery_coverd']:'';
    $distance_type = isset($setting['merchant_distance_type'])?$setting['merchant_distance_type']:'';
    $free_delivery_above_price = !empty($setting['free_delivery_above_price'])?CURRENCY.number_format($setting['free_delivery_above_price'],2):'';
    $merchant_minimum_order_delivery = !empty($setting['merchant_minimum_order_delivery'])?CURRENCY.number_format($setting['merchant_minimum_order_delivery'],2):'';
    $merchant_maximum_order_delivery = !empty($setting['merchant_maximum_order_delivery'])?CURRENCY.number_format($setting['merchant_maximum_order_delivery'],2):'';
    $merchant_minimum_order_pickup = !empty($setting['merchant_minimum_order_pickup'])?CURRENCY.number_format($setting['merchant_minimum_order_pickup'],2):'';
    $merchant_maximum_order_pickup = !empty($setting['merchant_maximum_order_pickup'])?CURRENCY.number_format($setting['merchant_maximum_order_pickup'],2):'';	
    $merchant_delivery_charges = !empty($setting['merchant_delivery_charges'])?CURRENCY.number_format($setting['merchant_delivery_charges'],2):'';	
    $restricted_hour_from = (!empty($setting['restricted_from']) && $setting['restricted_from']!='00:00:00')?$setting['restricted_from']:'21:00:00';
    $restricted_hour_to = (!empty($setting['restricted_to']) && $setting['restricted_to']!='00:00:00')?$setting['restricted_to']:'09:00:00';
   
 
?>

<section class="sliderpanel">
</section>
<div class="home_divider"></div>

<div class="content_wrapper">

    <section class="sectionpanel menusectionpanel">
        <div class="container">

            <div class="orderstep">
                <a href="#" class="col-sm-3 sli active"><span class="cnt">1</span> <span>Choose a restaurant</span></a>
                <div class="col-sm-3 sli next ent"><span class="cnt">2</span> <span>Build your Order</span></div>
                <div class="col-sm-3 sli"><span class="cnt">3</span> <span>Checkout</span></div>
                <div class="col-sm-3 sli no"><span class="cnt">4</span> <span>Confirmation</span></div>
            </div><!-- end of orderstep -->


            <div class="row">
                <?php
                $todayRestaurantTime = $setting['store_time'];
                $pre_order = $setting['pre_order'];

                $is_open = $todayRestaurantTime['is_open'];
                $open_time_mrng = date('H:i',strtotime($todayRestaurantTime['open_time_mrng']));
                $close_time_mrng = date('H:i',strtotime($todayRestaurantTime['close_time_mrng']));
                $open_time_evening = date('H:i',strtotime($todayRestaurantTime['open_time_evening']));
                $close_time_evening = date('H:i',strtotime($todayRestaurantTime['close_time_evening']));
            
                $open_time = date('h:i a',strtotime($open_time_mrng));
                $close_time = date('h:i a',strtotime($close_time_evening));
                $now = date("H:i");

                $open = 0;
                $pre = 0;
                $close=0;
                

                if($merchant_close_store==0 && $merchant_disabled_ordering==0){ 
                    if( $is_open==1 &&  ( ( $now > $open_time_mrng  && $now <  $close_time_mrng ) || ( $now > $open_time_evening  && $now <  $close_time_evening ) ) ){
                        $open = 1;
                      
    
                    } 
                    else if($is_open==1 && ( ( $now < $open_time_mrng  && $now <  $close_time_mrng ) || ( $now < $open_time_evening  && $now <  $close_time_evening )) && $pre_order==1){
                        $pre = 1;
                    }else{
                        $close=1;
                    }
                }
    
                
                ?>
                <ul class="takeawayinfo list-unstyled">
                    <li class="col-md-12 col-sm-12 col-xs-12">
                        <div class="strip_list">
                            <div class="ribbon_1">Popular</div>
                            <div class="store_desc col-md-7 col-sm-7 col-xs-12">
                                <div class="thumb_strip">
                                    <img src="images/thumb_restaurant.jpg" alt="">
                                </div>
                                <?php 
                                if($open==1){ ?>
                                    <div class="sign_holder">
                                        <em class="opening_sign open"><span class="title">Open</span>
                                        <span class="boundary"><span class="split_part part_1">Closes </span>
                                        <span class="split_part part_2"><?php echo $close_time;?></span></span>
                                        </em>
                                    </div>
                                    <?php

                                }else  if($close==1){ ?>
                                    <div class="sign_holder">
                                    <em class="opening_sign offline"><span class="title">Closed</span>
                                        <span class="boundary"><span class="split_part part_1">Open </span>
                                        <span class="split_part part_2"><?php echo $open_time;?></span></span>
                                        </em>
                                    </div>
                                    <?php

                                } else if($pre==1){ ?>
                                    <div class="sign_holder">
                                        <em class="opening_sign open"><span class="title">Pre Order</span>
                                        <span class="boundary"><span class="split_part part_1">Open </span>
                                        <span class="split_part part_2"><?php echo $open_time;?></span></span>
                                        </em>
                                    </div>
                                    <?php

                                } else if($merchant_close_store==1 || $merchant_disabled_ordering==1){ ?>
                                    <div class="sign_holder">
                                    <em class="opening_sign offline"><span class="title">Closed</span>
                                        <span class="boundary"><span class="split_part part_1"><?php echo msg['store_close'];?></span>
                                        </em>
                                    </div>
                                    <?php

                                }
                                ?>
                                <h3><?php echo $restaurant_info['merchant_name'];?></h3>
                                <div class="type">
                                <?php echo implode(', ',$store_category);?>  | <?php echo $restaurant_info['city'];?>
                                </div>
                                <div class="location">
                                <?php echo $restaurant_info['address'];?>
                                </div><!-- end of location -->
                                <ul class="list-unstyled">
                                    <?php
                                    if($setting['service_status']==3){ ?>
                                        <li>Take away<i class="fa fa-check-circle-o ok"></i></li>
                                        <li>Delivery<i class="fa fa-check-circle-o ok"></i></li>
                                        <?php
                                    } else if($setting['service_status']==1){ ?>
                                        <li>Take away<i class="fa fa-check-circle-o ok"></i></li>
                                        <?php
                                    }else if($setting['service_status']==2){ ?>
                                        <li>Delivery<i class="fa fa-check-circle-o ok"></i></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <span class="stars">3</span>
                            </div><!-- end of col -->
                            <div class="store_desc col-md-5 col-sm-5 col-xs-12">
                                <a href="info.html" class="btn btn-default btn-lg btninfo"><i
                                        class="fa fa-info-circle"></i> Info &amp; Map</a>
                            </div><!-- end of col -->
                        </div><!-- end of strip_list -->
                    </li><!-- end of col -->
                </ul>


                <div class="leftpanel col-md-3 col-sm-3 col-xs-12 fixedpanel">

                    <div class="weightpanel">
                        <div class="ht3">Menu (Order Online)</div><!-- end of ht3 -->
                        <ul class="list-unstyled foodlist">
                            <?php 
                            if(isset($result) && count($result)>0){
                                foreach($result as $key=>$value){ ?>
                                    <li>
                                        <a class="nav-link" href="#cat_<?php echo $value['category_id'];?>"><?php echo $value['category_name'];?> </a>
                                    </li>
                                    <?php 
                                }
                            } ?>
                        </ul>
                    </div>
                </div>

                <div class="menuviewpanel col-md-6 col-sm-6 col-xs-12">

                    <div class="panel">
                        <?php 
                        
                        if(isset($result) && count($result)>0){
			                if($merchant_close_store==1){ 
				                ?>
				                <div class=" mt-2 link-background close_store" ><?php echo $merchant_close_msg;?></div>
				                <?php
				            }else{
                                foreach($result as $key=>$value){ 
                                    
                                    $show_cart=1;
                                    $restricted_category = $value['restricted_category'];
                                    $restricted_with_time = $value['restricted_with_time'];
                                   
                                    if($restricted_with_time==1){
                                        if (time() < strtotime($restricted_hour_from) && time() > strtotime($restricted_hour_to)) {
                                        
                                        }else{
                                            $show_cart=0;	
                                            
                                        }
                                    }
                                    ?>

                                    <div class="panel-heading" panel-id="cate_<?php echo $value['category_id'];?>">
                                        <h2 class="banner_heading_small"><?php echo str_replace('amp;','',html_entity_decode($value['category_name']));?></h2>
                                        <p><?php echo str_replace('amp;','',html_entity_decode($value['category_description']));?></p>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-unstyled itemlist">
                                            <?php
                                            foreach($value['items'] as $itemKey=>$item){
                                                $price = $item['item_price'];
                                                
                                                $class_toggle = ($restricted_category==1)?'restricted_popup':'add-menu-item';
                                               
                                                ?>
                                                <li class="lilist" id="item_<?php echo $item['id'];?>">
                                                    <h5 class="h-item"><?php echo str_replace('amp;','',html_entity_decode($item['item_name']));?></h5>
                                                    <p><?php echo str_replace('amp;','',html_entity_decode($item['description']));?></p>
                                                    <?php 
                                                    if($item['in_stock'] && $show_cart==1){ ?>
                                                        <a  href="javascript:void(0)"  data-id="<?php echo $item['id'];?>" class="pull-right pribox <?php echo $class_toggle; ?> false">
                                                            <strong class="price"><?php echo $currency.number_format($price,2);?> </strong>
                                                            <i class="plusicon"></i>
                                                        </a>   
                                                        <?php

                                                    } else if($item['in_stock'] && $show_cart==0){ ?>
                                                       <a href="javascript:void(0)" class="pull-right pribox">
                                                            <strong class="price"><?php echo $currency.number_format($price,2);?> </strong>
                                                            <div>You can not add this product in your cart.</div>
                                                        </a>  
                                                        <?php
                                                    }else{ ?>
                                                        <a href="javascript:void(0)" class="pull-right pribox">
                                                            <strong class="price"><?php echo $currency.number_format($price,2);?> </strong>
                                                            <div>Out of stock</div>
                                                        </a> 
                                                        <?php 
                                                    }
                                                    ?>
                                                </li>
                                                <?php
                                            } ?>
                                        </ul>
                                    </div>
                                    <?php
                                }
                            }
                        } ?>

                    </div>
                </div>

                <span class="append_cart_item">      
                <?php 
                if(isset($basket)){
                   echo $basket;
                } ?>
                </span>

            </div><!-- end of row -->
        </div><!-- end ot container -->
    </section>

</div><!-- end of content_wrapper -->

<div id="takeaway_popup"></div>

<div class="modal" id="allergypopup" tabindex="-1" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Allergy Advice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       Compliance with food labelling regulations, customers are advised to contact the restaurant directly if any food may cause an allergic reaction prior to ordering If you would like to know the list of ingredients in a particular dish from our menu, please contact the restaurant directly
      </div>
     
    </div>
  </div>
</div>