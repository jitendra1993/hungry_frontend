<section class="sliderpanel"></section><!-- end of sliderpanel -->
<div class="home_divider"></div>
<div class="content_wrapper">
    <section class="sectionpanel allrestaurant">
        <div class="container">

            <div class="orderstep">
                <a href="#" class="col-sm-3 sli active"><span class="cnt">1</span> <span>Choose a restaurant</span></a>
                <div class="col-sm-3 sli next ent"><span class="cnt">2</span> <span>Build your Order</span></div>
                <div class="col-sm-3 sli"><span class="cnt">3</span> <span>Checkout</span></div>
                <div class="col-sm-3 sli no"><span class="cnt">4</span> <span>Confirmation</span></div>
            </div><!-- end of orderstep -->

            <div class="row">
                <input type="hidden" value="<?php echo $sub_pincode;?>" name="postocde" id="postocde">
                <input type="hidden" value="<?php echo $this->uri->segment(3);?>" name="start" id="start">
                <?php 
                $dayArr = array('0'=>'Mon','1'=>'Tue','2'=>'Wed','3'=>'Thu','4'=>'Fri','5'=>'Sat','6'=>'Sun');
                $today = date('D',time());
                $timeKey = array_search($today,$dayArr);
                echo '<pre>';
               //print_r($result);
                echo '</pre>';
                ?>
                <div class="col-md-3 col-sm-3 col-xs-12 leftpanel fixedpanel">


                    <div class="weightpanel">
                        <div class="ht3">What do you fancy?</div><!-- end of ht3 -->
                        <ul class="list-unstyled foodlist">
                            <?php 
                            if(isset($result['restaurantCategory']) && !empty($result['restaurantCategory']) && is_array($result['restaurantCategory']) && count($result['restaurantCategory'])>0){
                                foreach($result['restaurantCategory'] as $catKey=>$catValue){ ?>

                                    <li>
                                        <div class="custom-control custom-checkbox mb-5">
                                            <input type="checkbox" class="custom-control-input restaurnat_category" master_cat_id="<?php echo $catValue['category_id']?>" id="categories_<?php echo $catValue['category_id']?>" name="categories"  value="<?php echo $catValue['category_id']?>"  <?php echo (isset($filters) && !empty($filters) && in_array($catValue['category_id'],explode(',',$filters['category'])))?'checked':''; ?>>
                                            <label class="custom-control-label" for="categories_<?php echo $catValue['category_id']?>"><?php echo $catValue['category_name']?><span><?php echo '   '.$catValue['total_restaurant']?></span></label>
                                        </div>

                                    </li>
                                    <?php 
                                }
                                
                            }
                            ?>
                        </ul><!-- end of foodlist -->

                        <div class="ht3">Filters</div>
                        <ul class="list-unstyled foodlist">
                            <?php 
                            if(isset($result['restaurantCategory']) && !empty($result['restaurantCategory']) && is_array($result['restaurantCategory']) && count($result['restaurantCategory'])>0){?>
                                <li>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input restaurnat_open" id="restaurnat_open" name="restaurnat_open"  value="1"  <?php echo (isset($filters) && !empty($filters) && $filters['restaurnat_open']==1)?'checked':''; ?>>
                                        <label class="custom-control-label" for="restaurnat_open">Open Restaurant <span><?php echo '   '.$result['openRestaurant']?></span></label>
                                    </div>
                                </li>

                                <li>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input delivery" id="delivery" name="delivery"  value="1"  <?php echo (isset($filters) && !empty($filters) && $filters['delivery']==1)?'checked':''; ?>>
                                        <label class="custom-control-label" for="delivery">Delivery<span><?php echo '   '.$result['deliveryCnt']?></span></label>
                                    </div>
                                </li>

                                <li>
                                    <div class="custom-control custom-checkbox mb-5">
                                        <input type="checkbox" class="custom-control-input collection" id="collection" name="collection"  value="1"  <?php echo (isset($filters) && !empty($filters) && $filters['collection']==1)?'checked':''; ?>>
                                        <label class="custom-control-label" for="collection">Collections<span><?php echo '   '.$result['collectionCnt']?></span></label>
                                    </div>
                                </li>
                            <?php 
                            }
                            ?>
                        </ul><!-- end of foodlist -->


                    </div><!-- end of weightpanel -->

                </div><!-- end of leftpanel -->


                <div class="col-md-9 col-sm-9 col-xs-12 rightpanel">

                    <div class="ht3"><?php echo $result['totalRecord']; ?> takeaways serving breakfast & lunch to St Lawrence
                    </div><!-- end of ht3 -->

                    <ul class="listStores list-unstyled">
                        <?php 
                        if(isset($result['result']) && !empty($result['result']) && is_array($result['result']) && count($result['result'])>0){

                            // echo '<pre>';
                            // print_r($result['result']);
                            // echo '</pre>';
                            foreach($result['result'] as $resKey=>$resValue){ 
                                    
                                    $todayRestaurantTime = $resValue['store_time'];
                                    $merchant_close_store = $resValue['merchant_close_store'];
                                    $merchant_disabled_ordering = $resValue['merchant_disabled_ordering'];
                                    $pre_order = $resValue['pre_order'];

                                    $is_open = $todayRestaurantTime['is_open'];
                                    $open_time_mrng = date('H:i',strtotime($todayRestaurantTime['open_time_mrng']));
                                    $close_time_mrng = date('H:i',strtotime($todayRestaurantTime['close_time_mrng']));
                                    $open_time_evening = date('H:i',strtotime($todayRestaurantTime['open_time_evening']));
                                    $close_time_evening = date('H:i',strtotime($todayRestaurantTime['close_time_evening']));
                                   
                                    $open_time = date('h:i a',strtotime($open_time_mrng));
                                    $close_time = date('h:i a',strtotime($close_time_evening));
                                    $now = date("h:i");

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
                                    
                                    $title = strtolower(str_replace(' ','-',$resValue['restaurant_name']));
                                    $title = preg_replace('/[^A-Za-z0-9\-]/', '', $title);
                                
                                ?>

                                <li class="col-md-12 col-sm-12 col-xs-12">
                                    <a href="<?php echo base_url('store/'.$title).'?id='.$resValue['setting_id'].'&menu_type=1';?>" class="strip_list">
                                        <div class="ribbon_1">Popular</div>
                                        <div class="store_desc">
                                            <div class="thumb_strip">
                                                <img src="images/thumb_restaurant.jpg" alt="">
                                            </div><!-- end of thumb_strip -->
                                           
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

                                            <h3><?php echo $resValue['restaurant_name'];?></h3>
                                            <div class="type">
                                                <?php echo implode(', ',$resValue['category_name']);?> | <?php echo $resValue['restaurant_city'];?>
                                            </div><!-- end of type -->
                                            <div class="location">
                                                <?php echo $resValue['restaurant_address'];?>
                                            </div><!-- end of location -->
                                            <ul class="list-unstyled">
                                                <?php
                                                if($resValue['service_status']==3){ ?>
                                                    <li>Take away<i class="fa fa-check-circle-o ok"></i></li>
                                                    <li>Delivery<i class="fa fa-check-circle-o ok"></i></li>
                                                    <?php
                                                } else if($resValue['service_status']==1){ ?>
                                                    <li>Take away<i class="fa fa-check-circle-o ok"></i></li>
                                                    <?php
                                                }else if($resValue['service_status']==2){ ?>
                                                    <li>Delivery<i class="fa fa-check-circle-o ok"></i></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <div class="rating">
                                                <i class="fa fa-star voted"></i>
                                                <i class="fa fa-star voted"></i>
                                                <i class="fa fa-star voted"></i>
                                                <i class="fa fa-star voted"></i>
                                                <i class="fa fa-star"></i>
                                            </div><!-- end of rating -->
                                        </div>
                                    </a><!-- end of strip_list -->
                                </li><!-- end of col -->
                                <?php 
                            }
                            
                        }
                        ?>







                    </ul><!-- end of list -->


                   <p><?php echo $links; ?></p>

                </div><!-- end of rightpanel -->

            </div><!-- end of row -->
        </div><!-- end of container -->
    </section><!-- end of sectionpanel -->

</div><!-- end of content_wrapper -->