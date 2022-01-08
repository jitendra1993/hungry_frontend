<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session'));
		if(is_logged_in() &&  is_user_type()!='user'){
			redirect(base_url());
		}
	}
	
	public function getCartForm() {
		$item_id = $this->input->post('item_id');
		$restaurantId = $this->session->userdata('restaurant_id');
		$url = API_URL."/api/restaurant/".$restaurantId."/"."item/".$item_id;
		$response = getCurlWithOutAuthorizationWithOutData($url);
		$status = $response['status'];
		$result = $response['data'];

		if(isset($result) && count($result)>0 && $status==200){ // success
			
			$item_name = !empty($result['name'])?$result['name']:'';
			$description = !empty($result['description'])?$result['description']:'';
			$in_stock = !empty($result['in_stock'])?$result['in_stock']:0;
			$has_meal_deal = !empty($result['meal_deal'])?$result['meal_deal']:0;
			$ingredient = !empty($result['ingredients'])?$result['ingredients']:array();
			$variation = !empty($result['item_variation'])?$result['item_variation']:array();
			$sub_item = !empty($result['addon_items'])?$result['addon_items']:array();
			$has_sub_item = (!empty($result['addon_items']) && count($result['addon_items'])>0 )?1:0;
			$has_item_variation = (!empty($result['item_variation']) && count($result['item_variation'])>0 )?1:0;
			$item_price = !empty($result['item_price'])?round($result['item_price'],2):0;
			$text_price  = $item_price;
										
			?>
			<div class="modal " id="itemModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
				aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $item_name;?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form name="add_to_cart_form" id="add_to_cart_form">
								<input type="hidden" name="item_id" id="item_id" value="<?php echo $item_id?>">
								<input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $restaurantId?>">
								<input type="hidden" name="has_meal_deal" id="has_meal_deal" value="<?php echo $has_meal_deal?>">
								<div class="row">
									<h4 class="col-md-12"><?php echo $item_name;?></h4><br>
									<p class="col-md-12"><?php echo $description;?></p>
								</div>

								<div class="row price">
									<h4 class="col-md-12 heading">Price</h4>
									<hr>
									<div class="col-md-12 row">
										<input type="hidden" name="variation_id" id="variation_id" value="0" />
										<?php
										if($has_item_variation==1 && count($variation)>0 && $has_meal_deal==0){
											$i=1;
											foreach($variation as $priceKey=>$priceValue){
												
												$discount_price = $price=0;
                                                $discount_price = !empty($priceValue['discount_price'])?$priceValue['discount_price']:0;
                                                $main_price = !empty($first_variation['max_price'])?$first_variation['max_price']:0;

                                                if($discount_price>0)
                                                {
                                                    $price =  round($discount_price,2);;
                                                }else{
                                                    $price =  round($main_price,2);
                                                }
											
												if($i==1){
													$text_price  = $price;
												}
												
												?>
												<div class="col-md-3">
													<div class="custom-control custom-radio mb-3">
														<input type="radio" class="custom-control-input popupPriceClass btnClass" value="<?php echo $price;?>" data-id="<?php echo $priceKey;?>" name="item_price" id="customCheck<?php echo $priceKey;?>" <?php echo $i==1?'checked':'';?>>

														<label class="custom-control-label" for="customCheck<?php echo $priceKey;?>"><?php echo $priceValue['size_name'];?>
															<?php echo CURRENCY.' '.number_format($price,2);?>
														</label>
													</div>
												</div>
												<?php	
												$i++;
											}
										}else{ ?>
											<div class="col-md-3">
												<div class="custom-control custom-radio mb-3">
													<input type="radio" class="custom-control-input popupPriceClass btnClass" value="<?php echo $item_price;?>" data-id="0" name="item_price" id="customCheckDefault" checked >

													<label class="custom-control-label" for="customCheckDefault"><?php echo CURRENCY.' '.number_format($item_price,2);?>
													</label>
												</div>
											</div>
											<?php
										}
										?>
									</div>
								</div>

								<?php
								if(isset($ingredient) && count($ingredient)>0 && $has_meal_deal==0) { ?>
									<div class="row ingredient">
										<h4 class="col-md-12 heading">Ingredient</h4>
										<hr>
										<div class="col-md-12 row">
											<?php
											foreach($ingredient as $ingredientKey=>$ingredient){ 
												?>
												<div class="col-md-3">
													<div class="custom-control custom-checkbox mb-3">
														<input type="checkbox" class="custom-control-input popupIngredientClass" value="<?php echo $ingredient['id'];?>" name="ingredient[]" id="ingredientCheck<?php echo $ingredientKey;?>">
														<label class="custom-control-label" for="ingredientCheck<?php echo $ingredientKey;?>"><?php echo $ingredient['name'];?></label>
													</div>
												</div>
												<?php									
											}
											?>
										</div>
									</div>
									<?php 
								}?>

								<div class="row quantity">
									<h4 class="col-md-12 heading">Quantity</h4>
									<hr>
									<div class="col-md-12 row">
										<?php 
										if($in_stock){ ?>
											<div class="col-md-3">
												<div class="quantity">
													<div class="pro-qty">
														<span class="number-qty-decr  qtybtn" type="main_item" data-id="<?php echo $item_id; ?>">-</span>
														<input class="main_item_qty" id="main_item_qty_<?php echo $item_id; ?>" type="text" value="1" min="1" max="10" name="main_item_qty" onkeypress="return isNumber(event)">
														<span class="number-qty-incrs  qtybtn" type="main_item" data-id="<?php echo $item_id; ?>">+</span>
													</div>
												</div>
											</div>
											<?php 
										} ?>
									</div>
								</div>

								<?php
								if($has_sub_item==1 && isset($sub_item) && count($sub_item)>0 && $has_meal_deal==0) { ?>
									<?php
									foreach($sub_item as $subItemKey=>$subItemValue){

										$no_of_selection = $subItemValue['multi_option_value'];
										$selection_type = $subItemValue['selection_type_text'];
										$addon_cat_id = $subItemValue['addon_category_id'];
										$addon_category_name = $subItemValue['addon_category_name'];
										$require_addon = $subItemValue['require_addon'];

										if(($selection_type=='single' && $no_of_selection==1) || ($selection_type=='custom' && $no_of_selection==1)){
											$class='custom-radio';
											$type ='radio';

										} else if(($selection_type=='custom' && $no_of_selection>1) || ($selection_type=='multiple')){
											$class='custom-checkbox';
											$type ='checkbox';
										}
										?>
										<div class="row <?php echo $subItemKey;?>">
											<input type="hidden" name="addon_cat_id[]" class="addon_cat_id" value="<?php echo $addon_cat_id;?>">
											<input type="hidden" name="no_of_selection_<?php echo $addon_cat_id;?>" id="no_of_selection_<?php echo $addon_cat_id;?>" value="<?php echo $no_of_selection;?>">
											<input type="hidden" name="required_<?php echo $addon_cat_id;?>" id="required_<?php echo $addon_cat_id;?>" value="<?php echo $require_addon;?>">
											<h4 class="col-md-12 heading"><?php echo $addon_category_name;?></h4>
											<hr>
											<div class="col-md-12">
												<?php
												$i=1;
												foreach($subItemValue['addon_items'] as $addonItemKey=>$addonItemValue){

													$max_qty = !empty($addonItemValue['max_qty'])?$addonItemValue['max_qty']:1;
													$style = '';
													$readonly='';
													if($max_qty==1){

														$style='style="cursor: not-allowed"';
														$readonly='readonly';
													}
													?>
													<div class="col-md-12">
														<div class="col-md-4">
															<div class="custom-control <?php echo $class;?> mb-3">
																<input <?php echo $i==1?'checked':'';?> type="<?php echo $type;?>" cat-id="<?php echo $addon_cat_id;?>" class="custom-control-input popupAddOnItemClass btnClass toggle_<?php echo $addon_cat_id;?>  no_of_selection_<?php echo $addon_cat_id;?>" value="<?php echo $addonItemValue['id'];?>" name="addOnSubItem[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]" id="<?php echo $subItemKey.'_'.$addonItemValue['id'];?>_check" price="<?php echo !empty($addonItemValue['price'])?$addonItemValue['price']:0;?>">

																<label class="custom-control-label" for="<?php echo $subItemKey.'_'.$addonItemValue['id'];?>_check"><?php echo $addonItemValue['name'];?></label>
															</div>
														</div>


														<div class="col-md-4">
															<div class="row addOnItemQuantity">
																<div class="">
																	<div class="quantity">
																		<div class="pro-qty">
																			<span class=" number-qty-decr  qtybtn" type="addon_item" data-id="<?php echo $addonItemValue['id']; ?>" max_qty="<?php echo $max_qty;?>" <?php echo $style;?>>-</span>
																			
																			<input class="" id="addon_item_qty_<?php echo $addonItemValue['id']; ?>" name="addon_item_qty[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]" type="text" value="1" min="1" max="10" onkeypress="return isNumber(event)" <?php echo $readonly;?>>

																			<span class="number-qty-incrs  qtybtn" type="addon_item" data-id="<?php echo $addonItemValue['id']; ?>" max_qty="<?php echo $max_qty;?>" <?php echo $style;?>>+</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="col-md-4">
															<div class="addOnItemPrice">
																<?php 
																$pp = !empty($addonItemValue['price'])?$addonItemValue['price']:0;
																echo CURRENCY. number_format($pp,2);
																?>
																<input type="hidden" name="addon_item_price[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]" value="<?php echo $pp;?>">
															</div>
														</div>
													</div>
													<?php
													$i++;									
												} ?>
											</div>
										</div>
										<?php 
									}
								}
									
								else if(isset($has_meal_deal) && $has_meal_deal==1) {
									foreach($sub_item as $subItemKey=>$subItemValue){
										
										$no_of_drop_down = $subItemValue['meal_deal_no_of_option'];
										$no_of_selection = $subItemValue['multi_option_value'];
										$selection_type = $subItemValue['selection_type_text'];
										$addon_cat_id = $subItemValue['addon_category_id'];
										$name = $subItemValue['addon_category_name'];
										$default_qty = $subItemValue['addon_category_default_qty_status'];
										$multiple_meal_deal = $subItemValue['addon_category_multiple_meal_deal'];
										$require_addon = $subItemValue['require_addon'];
										$meal_deal_item_qty = !empty($subItemValue['meal_deal_item_qty'])?$subItemValue['meal_deal_item_qty']:[];

										if(($selection_type=='single' && $no_of_selection==1) || ($selection_type=='custom' && $no_of_selection==1)){
											$class='custom-radio';
											$type ='radio';

										}else if(($selection_type=='custom' && $no_of_selection>1) || ($selection_type=='multiple')){
											$class='custom-checkbox';
											$type ='checkbox';
										}
										?>
										<div class="row">
											<hr>
											<div class="col-md-12">
												<?php
												if($no_of_drop_down>0 && $default_qty==0 &&  $multiple_meal_deal==0){ //coverd ?>
													<h4 class="col-md-12 heading"><?php echo $name;?></h4>
													<hr>
													<?php
													for($i=1;$i<=$no_of_drop_down;$i++){?>
														<p>Choice <?php echo $i;?></p>
														<div class="col-md-12 p-0">
															<select class="form-control mealdeal_dropdown" name="mealDealSubItem[<?php echo $addon_cat_id;?>][<?php echo $i;?>]">
																<?php
																foreach($subItemValue['addon_items']as $addonItemKey=>$addonItemValue){
																	if($addonItemKey ==0){
																		$text_price = $text_price+$addonItemValue['price'];
																	}
																	$itemName = ($addonItemValue['price']==0)?$addonItemValue['name']:$addonItemValue['name'].'('.CURRENCY. number_format($addonItemValue['price'],2).')';
																	?>
																	<option value="<?php echo $addonItemValue['id'];?>" data-price="<?php echo $addonItemValue['price']; ?>"><?php echo $itemName;?>
																	</option>
																	<?php
																}?>
															</select>
															<input name="meal_deal_item_qty[<?php echo $addon_cat_id;?>][<?php echo $i;?>]" type="hidden" value="1" min="1" max="10">
															<?php 
															$pp = !empty($addonItemValue['price'])?$addonItemValue['price']:0;
															?>
															<input type="hidden" name="addon_item_price[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]" value="<?php echo $pp;?>">
														</div>
														<?php
													}
												}

												else if($no_of_drop_down==0 && $default_qty>0 &&  $multiple_meal_deal==0){ //coverd  ?>
													<h4 class="col-md-12 heading"><?php echo $name;?></h4>
													<hr>
													<?php

													foreach($subItemValue['addon_items'] as $addonItemKey=>$addonItemValue){ 
														$max_qty = !empty($addonItemValue['max_qty'])?$addonItemValue['max_qty']:1;
														?>
														<div class="col-md-12">
															<div class="col-md-4">
																<div class="custom-control btnClass custom-radio mb-3">
																	<input checked type="radio" cat-id="<?php echo $addon_cat_id;?>"
																		class="custom-control-input" value="<?php echo $addonItemValue['id'];?>"
																		name="mealDealFixedSubItem[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]"
																		id="<?php echo $subItemKey.'_'.$addonItemValue['id'];?>_check">
																	<label class="custom-control-label"
																		for="<?php echo $subItemKey.'_'.$addonItemValue['id'];?>_check"><?php echo $addonItemValue['name'];?></label>
																</div>
															</div>

															<div class="col-md-4">
																<div class="row addOnItemQuantity">
																	<div class="">
																		<div class="quantity">
																			<div class="pro-qty product_qty_hgt">
																				<span class=""type="addon_item" style="cursor: not-allowed">-</span>
																				<input class="number-qty"
																					id="addon_item_qty_<?php echo $addonItemValue['id']; ?>"
																					name="meal_deal_fixed_item_qty[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]"
																					type="text" value="<?php echo $meal_deal_item_qty[$addonItemValue['id']]; ?>" min="1" max="10" onkeypress="return isNumber(event)" readonly>
																				<span class=" " type="addon_item" style="cursor: not-allowed">+</span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="col-md-4">-</div>

														</div>
														<?php
													}
												}
														
												else if($no_of_drop_down==0 && $default_qty==0 &&  $multiple_meal_deal==1){ ?>
													<div class="row <?php echo $subItemKey;?>">
														<input type="hidden" name="addon_cat_id[]" class="addon_cat_id" value="<?php echo $addon_cat_id ;?>">
														<input type="hidden" name="no_of_selection_<?php echo $addon_cat_id;?>" id="no_of_selection_<?php echo $addon_cat_id;?>" value="<?php echo $no_of_selection;?>">
														<h4 class="col-md-12 heading"><?php echo $name;?></h4>
														<hr>
														<div class="col-md-12">
															<?php
															foreach($subItemValue['addon_items'] as $addonItemKey=>$addonItemValue){
																$max_qty = !empty($addonItemValue['max_qty'])?$addonItemValue['max_qty']:1;
																$style = '';
																$readonly='';
																if($max_qty==1){
																	$style='style="cursor: not-allowed"';
																	$readonly='readonly';
																}
																?>
																<div class="col-md-12">
																	<div class="col-md-4">
																		<div class="custom-control <?php echo $class;?> mb-3">
																			<input type="<?php echo $type;?>" cat-id="<?php echo $addon_cat_id;?>"
																				class="custom-control-input popupAddOnItemClass btnClass toggle_<?php echo $addon_cat_id;?> no_of_selection_<?php echo $addon_cat_id;?>" value="<?php echo $addonItemValue['id'];?>"
																				name="addOnSubItem[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]"
																				id="<?php echo $subItemKey.'_'.$addonItemValue['id'];?>_check"
																				price="<?php echo !empty($addonItemValue['price'])?$addonItemValue['price']:0;?>">
																			<label class="custom-control-label"
																				for="<?php echo $subItemKey.'_'.$addonItemValue['id'];?>_check"><?php echo $addonItemValue['name'];?></label>
																		</div>
																	</div>

																	<div class="col-md-4">
																		<div class="row addOnItemQuantity">
																			<div class="product__details__quantity">
																				<div class="quantity">
																					<div class="pro-qty product_qty_hgt">
																						<span class=" number-qty-decr  qtybtn"
																							type="addon_item"
																							data-id="<?php echo $addonItemValue['id']; ?>"
																							max_qty="<?php echo $max_qty;?>"
																							<?php echo $style;?>>-</span>
																						<input class=""
																							id="addon_item_qty_<?php echo $addonItemValue['id']; ?>"
																							name="addon_item_qty[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]"
																							type="text" value="1" min="1" max="10"
																							onkeypress="return isNumber(event)" <?php echo $readonly;?>>

																						<span class="number-qty-incrs  qtybtn"
																							type="addon_item"
																							data-id="<?php echo $addonItemValue['id']; ?>"
																							max_qty="<?php echo $max_qty;?>"
																							<?php echo $style;?>>+</span>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-md-4">
																		<div class="addOnItemPrice">
																			<?php 
																			$pp = !empty($addonItemValue['price'])?$addonItemValue['price']:0;
																			echo CURRENCY. number_format($pp,2);
																			?>
																			<input type="hidden" name="addon_item_price[<?php echo $addon_cat_id;?>][<?php echo $addonItemValue['id'];?>]" type="text" value="<?php echo $pp;?>">
																		</div>
																	</div>
																</div>	
																<?php									
															} ?>
														</div>
													</div>
													<?php
												}
												?>
											</div>
										</div>
										<?php
									}
								}
								?>
								<div class="row special_inst">
									<h4 class="col-md-12 heading">Special Instructions</h4>
									<hr>
									<div class="col-md-12">
										<textarea class="form-control special_inst" name="special_inst" id="special_inst" placeholder="Special Instructions"></textarea>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="text_price" value="<?php echo $text_price; ?>" id="text_price">
							<span class="priceOfItem"><?php echo CURRENCY. number_format($text_price,2);?></span>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary add_cart" data-item-id="<?php echo $item_id; ?>">Add To
								Cart</button>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	
	public function addCart(){

		$user_id='';
		$post = $id = $this->input->post();

		if(!empty($this->session->userdata('user_id'))){
			$user_id = $this->session->userdata('user_id');
			
		} else if(!empty($this->session->userdata('guest_user_id'))){
			$user_id = $this->session->userdata('guest_user_id');
		}
		$cookieId = $_COOKIE['cookieId'];
		$item_id =  $post['item_id'];
		$restaurant_id =  $post['restaurant_id'];
		$item_price =  $post['item_price'];
		$main_item_qty = $post['main_item_qty'];
		$special_inst = htmlspecialchars(strip_tags($post['special_inst']));
		$ingredient = isset($post['ingredient'])?$post['ingredient']:array();
		$variation_id = isset($post['variation_id'])?$post['variation_id']:0;
		$has_meal_deal = isset($post['has_meal_deal'])?$post['has_meal_deal']:0;
		$addon_cat_id = isset($post['addon_cat_id'])?$post['addon_cat_id']:array();
		$addon_item_qty = isset($post['addon_item_qty'])?$post['addon_item_qty']:array();
		$addon_item_price = isset($post['addon_item_price'])?$post['addon_item_price']:array();
		$addOnSubItem = isset($post['addOnSubItem'])?$post['addOnSubItem']:array();
		
		$data['userId'] = $user_id;
		$data['cookieId'] = $cookieId;
		$data['itemId'] = $post['item_id'];
		$data['restaurantId'] = $post['restaurant_id'];
		$data['price'] = $post['item_price'];
		$data['itemQuantity'] = $post['main_item_qty'];
		$data['specialInstruction'] = $special_inst;
		$data['ingredient'] = $ingredient;
		$data['postCode'] =  !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		$data['priceId'] = $variation_id;
		$subItemArr =[];
		$data['addOnItem'] = $subItemArr;

		if($has_meal_deal){

			$modified_arr = array();
			$mealDealSubItemArr = !empty($this->input->post('mealDealSubItem'))?$this->input->post('mealDealSubItem'):array();

			if(isset($mealDealSubItemArr) && count($mealDealSubItemArr)>0){
				
				foreach($mealDealSubItemArr as $SubItemKey=>$SubItemValue){
					$modified_arr[$SubItemKey] = array_count_values($SubItemValue);
				}
				
			}	
			
			if(!empty($this->input->post('meal_deal_fixed_item_qty'))){
				$new_item_arr = $this->input->post('meal_deal_fixed_item_qty')+ $modified_arr;

			}else{
				$new_item_arr =  $modified_arr;
			}
				
			if(isset($addOnSubItem) && count($addOnSubItem)>0){

				foreach($addOnSubItem as $SubItemKey=>$SubItemValue){
					$arr = [];
					$arr['addonCatId'] = $SubItemKey;
					foreach($SubItemValue as $subItem){
						$arr['items'][] = array(
							'addonItemId'=>$subItem,
							'addOnquantity'=>$addon_item_qty[$SubItemKey][$subItem],
							'addOnPrice'=>$addon_item_price[$SubItemKey][$subItem],
						);
					}
					$subItemArr[] = $arr;
				}
			}
			
				
			foreach($new_item_arr as $SubItemKey=>$SubItemValue){
				$arr = [];
				$arr['addonCatId'] = $SubItemKey;
				foreach($SubItemValue as $subItem=>$cnt){
					$pp = 0;
					if(isset($addon_item_price) && count($addon_item_price)>0){
						if(isset($addon_item_price[$SubItemKey])){
							if(isset($addon_item_price[$SubItemKey][$subItem])){
								$pp = $addon_item_price[$SubItemKey][$subItem];
							}
						}
					}

					$arr['items'][] = array(
									'addonItemId'=>$subItem,
									'addOnquantity'=>$cnt,
									'addOnPrice'=>$cnt*$pp,
								);
				}
				$subItemArr[] = $arr;
			}
				
			$data['addOnItem'] = $subItemArr;
			
		}else{
			if(isset($addOnSubItem) && count($addOnSubItem)>0){

				$subItemArr =[];
				foreach($addOnSubItem as $SubItemKey=>$SubItemValue){
					$arr = [];
					$arr['addonCatId'] = $SubItemKey;
					foreach($SubItemValue as $subItem)
					{
						$arr['items'][] = array(
										'addonItemId'=>$subItem,
										'addOnquantity'=>$addon_item_qty[$SubItemKey][$subItem],
										'addOnPrice'=>$addon_item_price[$SubItemKey][$subItem],
									);
					}
					
					$subItemArr[] = $arr;
				}
				$data['addOnItem'] = $subItemArr;
			}
		}
		
		
		$url = API_URL.'/api/restaurant/cart/add';
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];

		if(isset($result) && count($result)>0 && $status==200){ 
			
		$msg = msg['cart_added_msg'];	
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
		die;
	}
	
	public function setOrderType(){
		$user_id='';
		if(is_logged_in()){
			$user_id = $this->session->userdata('user_id');
		}
		
		$id = $this->input->post('chk_type');
		if($id==1){

			$this->session->set_userdata('checkout_type',1);
			$this->session->unset_userdata('address_id');

		}else{
			$this->session->set_userdata('checkout_type',2);
		}
		$this->session->unset_userdata('loyaltyPointUsed');
		$this->session->unset_userdata('promoCode');
		echo $id;
	}

	function carthtml(){
		$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$setting = get_store_detail_by_id($restaurantId);
		echo  $this->load->view('pages/cart', array("merchant_info"=>$setting), true);
	}
	
	public function deleteItem(){
		$user_id='';
		$post = $id = $this->input->post();

		if(!empty($this->session->userdata('user_id'))){
			$user_id = $this->session->userdata('user_id');
			
		} else if(!empty($this->session->userdata('guest_user_id'))){
			$user_id = $this->session->userdata('guest_user_id');
		}

		$cookieId = $_COOKIE['cookieId'];
		$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
		$postCode =  !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
		$loyaltyPointUsed = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';
		$item_id  = $this->input->post('item_id');
		$cart_id  = $this->input->post('cart');
		$type = !empty($this->input->post('type'))?$this->input->post('type'):1;

		$data['userId'] = $user_id;
		$data['cookieId'] = $cookieId;
		$data['restaurantId'] = $restaurantId;
		$data['orderType'] = $checkout_type;
		$data['postCode'] = $postCode;
		$data['promoCode'] = $promoCode;
		$data['loyaltyPointUsed'] = $loyaltyPointUsed;
		$data['uniqueId'] = $item_id;
		$data['cartId'] = $cart_id;
		$data['updateType'] = $type;

		
		if($item_id=='' || $item_id==null){
			unset($data['orderType'],$data['postCode'],$data['promoCode'],$data['loyaltyPointUsed'],$data['uniqueId'],$data['quantity'],$data['updateType']);
			echo json_encode($data);
			$url = API_URL.'/api/restaurant/cart/delete';
		}else{
			$url = API_URL.'/api/restaurant/cart/delete-item';
		}
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		if(isset($result) && count($result)>0 && $status==200){ 
			$status=1;
			$msg= msg['remove_item_success'];
		}else{
			$status=0;
			$msg= $message;
		}
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
	}
	
	public function updateCart(){

		$user_id='';
		$post = $id = $this->input->post();

		if(!empty($this->session->userdata('user_id'))){
			$user_id = $this->session->userdata('user_id');
			
		} else if(!empty($this->session->userdata('guest_user_id'))){
			$user_id = $this->session->userdata('guest_user_id');
		}
		$cookieId = $_COOKIE['cookieId'];
		$restaurantId = !empty($this->session->userdata('restaurant_id'))?$this->session->userdata('restaurant_id'):'';
		$checkout_type = !empty($this->session->userdata('checkout_type'))?$this->session->userdata('checkout_type'):1;
		$postCode =  !empty($this->session->userdata('postcode'))?$this->session->userdata('postcode'):'';
		$promoCode = !empty($this->session->userdata('promoCode'))?$this->session->userdata('promoCode'):'';
		$loyaltyPointUsed = !empty($this->session->userdata('loyaltyPointUsed'))?$this->session->userdata('loyaltyPointUsed'):'';
		$item_id  = $this->input->post('item_id');
		$cart_id  = $this->input->post('cart_id');
		$quantity = $this->input->post('quantity');
		$type = !empty($this->input->post('type'))?$this->input->post('type'):1;

		$data['userId'] = $user_id;
		$data['cookieId'] = $cookieId;
		$data['restaurantId'] = $restaurantId;
		$data['orderType'] = $checkout_type;
		$data['postCode'] = $postCode;
		$data['promoCode'] = $promoCode;
		$data['loyaltyPointUsed'] = $loyaltyPointUsed;
		$data['uniqueId'] = $item_id;
		$data['cartId'] = $cart_id;
		$data['quantity'] = $quantity;
		$data['updateType'] = $type;
		
		$url = API_URL.'/api/restaurant/cart/update';
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];

		if(isset($result) && count($result)>0 && $status==200){ 
			$msg = msg['update_cart_msg'];	
			$status =1;
		}else{
			$msg = $message;	
			$status =0;
		}
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		die;
	}
}