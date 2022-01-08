<?php 
$method= $this->router->fetch_method();

function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			echo 'found';
			return true;
		}
	}
	echo 'not found';
	return false;
}
?>
<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4><?=isset($product->id) ? 'Edit':'Add'?>  <?php echo $method=='dineProductAdd'?'Dinein Product':'Product';?></h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<?php 
					if($method=='dineProductAdd'){ ?>
						<li class="breadcrumb-item"><a href="<?=base_url('admin/dinein-product/view')?>">Products</a></li>
						<?php 
					}else{ ?>
						<li class="breadcrumb-item"><a href="<?=base_url('admin/product/view')?>">Products</a></li>
						<?php 
					}?>
					<li class="breadcrumb-item active" aria-current="page"><?=isset($product->id) ? 'Edit':'Add'?> Product</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<?php 
			if($method=='dineProductAdd'){ ?>
				<a href="<?=base_url('admin/dinein-product/view')?>" class="btn btn-primary btn-sm" > View All</a>
				<?php 
			}else{ ?>
				<a href="<?=base_url('admin/product/view')?>" class="btn btn-primary btn-sm" > View All</a>
				<?php 
			}?>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php
	$currency = CURRENCY;
	$uniqid = uniqid();
	if(isset($error) && !empty($error)){ ?>
		<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
		<?php 
	}
   if(validation_errors()){ ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<?php //echo validation_errors(); ?>
			<?php echo form_error('item_name', '<span>', '</span><br>'); ?>
			<?php echo form_error('max_price[]', '<span>', '</span><br>'); ?>
			<?php echo form_error('categories[]', '<span>', '</span><br>'); ?>
		</div>
		<?php 
	}
	if($this->session->flashdata('msg_success')){ ?>
		<div class="alert alert-success alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('msg_success'))?> 
		</div>
		<?php 
	}
	if($this->session->flashdata('error_msg')){ ?>
		<div class="alert alert-danger alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('error_msg'));?> 
		</div> 
		<?php
	}
  // echo '<pre>';
  // print_r($product);
  // echo '</pre>';
	$attributes = array('class' => 'add_product', 'id' => 'add_product'); 
	if($method=='dineProductAdd'){
		$addurl ='admin/dinein-product/add';
		$editurl='admin/dinein-product/edit/';
	}else{ 
		$addurl ='admin/product/add';
		$editurl='admin/product/edit/';
	}
			
	echo form_open_multipart(isset($product->id)?$editurl.$product->id:$addurl,$attributes);?>
	 <input type="hidden" name="id" id="id" value="<?php echo isset($product->id)?set_value('name',$product->id):set_value('id'); ?>">
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Product Name</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="item_name" id="item_name"  class="form-control" placeholder="Product Name" value="<?php echo isset($product->item_name)?set_value('item_name',html_entity_decode($product->item_name)):set_value('item_name'); ?>">
			<div class="has-danger form-control-feedback err_item_name"></div>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Description</label>
		<div class="col-sm-12 col-md-9">
		<textarea name="description" id="description" class="form-control description" placeholder="Description"><?php echo isset($product->description)?set_value('description',html_entity_decode($product->description)):set_value('description'); ?></textarea>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Product Price(Only for meal deal)</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group mb-0">
				<input type="text" name="item_price" id="item_price"  class="form-control" placeholder="Product Price" value="<?php echo isset($product->item_price)?set_value('item_price',html_entity_decode($product->item_price)):set_value('item_price'); ?>" onkeypress="return isNumberDecimal(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
			<div class="has-danger form-control-feedback err_item_price"></div>
		</div>
	</div>

	<div class="form-group row">
		<input type="hidden" name="main_cat_ids" id="main_cat_ids" value="<?php echo isset($product->main_cat_ids)?set_value('main_cat_ids',implode(',',(array)$product->main_cat_ids)):set_value('main_cat_ids'); ?>">
		<label class="col-sm-12 col-md-3 col-form-label">Categories</label>
		<div class="col-sm-12 col-md-9 row">
			<?php 
			if(isset($categories) && count($categories)>0){
				
			 $main_cat_ids = isset($product->id)?(array)$product->main_cat_ids:array();
			 foreach($categories as $key=>$category){
				?>
				<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input getSubCategory" master_cat_id="<?php echo $key;?>" id="categories_<?php echo $key;?>" name="categories[<?php echo $key;?>]"  value="1"  <?php echo (isset($product->main_cat_ids) && in_array($key,$main_cat_ids))?'checked':''; ?>>
					<label class="custom-control-label" for="categories_<?php echo $key;?>"><?php echo $category;?></label>
				</div>
				</div>
				<?php
				 
			 }
			}

			?>
			<div class="has-danger form-control-feedback col-md-12 err_categories row"></div>
		</div>
	</div>
	
	<div class="form-group row hide subCatetoggle" <?php echo isset($product->sub_cat_ids)?'style="display:flex"':'"display:none"';?>>
		<input type="hidden" name="sub_cat_ids" id="sub_cat_ids" value="<?php echo isset($product->sub_cat_ids)?set_value('sub_cat_ids',implode(',',(array)$product->sub_cat_ids)):set_value('sub_cat_ids'); ?>">
		<label class="col-sm-12 col-md-3 col-form-label">Sub Categories</label>
		<div class="col-sm-12 col-md-9 row append_sub_category">
			<?php
			if(isset($subcategories) && count($subcategories)>0){
				$sub_cat_ids = isset($product->id)?(array)$product->sub_cat_ids:array();
				foreach($subcategories as $id=> $subcategory){
					?>
					<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
						<div class="custom-control custom-checkbox mb-5">
							<input type="checkbox" class="custom-control-input subcategoryIds"  master_sub_cat_id="<?php echo $id;?>" id="sub_categories_<?php echo $id;?>" name="sub_categories[<?php echo $id;?>]"  value="1" <?php echo (isset($product->sub_cat_ids) && in_array($id,$sub_cat_ids))?'checked':''; ?>>
							<label class="custom-control-label" for="sub_categories_<?php echo $id;?>"><?php echo $subcategory;?></label>
						</div>
					</div>
					<?php
				 
				}
			}
			?>		
			<div class="has-danger form-control-feedback err_sub_categories row"></div>
		</div>
	</div>
	
	<?php 
	if(isset($addonCategoryWithItems) && count($addonCategoryWithItems)>0){
		?>
		<div class="form-group row meal_deal_status">
			<label class="col-sm-12 col-md-3 col-form-label">Meal Deal ?</label>
			<div class="col-sm-12 col-md-9 row">
				<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="meal_deal" name="meal_deal"  value="1" <?php echo (isset($product->meal_deal) && $product->meal_deal==1)?'checked':''; ?>>
					<label class="custom-control-label" for="meal_deal"></label>
				</div>
				</div>
			</div>
		</div> 
		
		<div class="form-group row ">
			<label class="col-sm-12 col-md-3 col-form-label">Two Flavors</label>
			<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input two_flavors"  id="two_flavors" name="two_flavors"  value="1" <?php echo (isset($product->two_flavors) && $product->two_flavors==1)?'checked':''; ?>>
				<label class="custom-control-label" for="two_flavors"></label>
			</div>
			</div>
		</div>
		
		
		<div class="form-group row ">
			<label class="col-sm-12 col-md-3 col-form-label"><?php echo $method=='dineProductAdd'?'Same in Delivery/collection?':'Same in Dinein';?></label>
			<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input same_dinein"  id="same_dinein" name="same_dinein"  value="1" <?php echo (isset($product->same_dinein) && $product->same_dinein==1)?'checked':''; ?>>
				<label class="custom-control-label" for="same_dinein"></label>
			</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-12  col-form-label">AddOn Categories</label>
			<div class="col-md-12 col-sm-12">
			<?php 
			$addon_item_arr = isset($product->id)?(array)$product->addon_item_id:array();
			// echo '<pre>';
			// print_r($addon_item_arr);
			// echo '</pre>';
			//die;
			 foreach($addonCategoryWithItems as $addonCategoryWithItems){
				 
				 $category = $addonCategoryWithItems['category'];
				 $items = $addonCategoryWithItems['items'];
				 $itemCnt = count($items);
			
				 if(isset($items) && count($items)>0){
					 
					$class_hide = ''; 
					$class_toggle = (isset($category['meal_deal']) && $category['meal_deal']==1)?'meal_deal_addon_category':'no_meal_deal_addon_category';
					if(isset($product->id))
					{
						$class_hide = ''; 
					}else{
						
						$class_hide = (isset($category['meal_deal']) && $category['meal_deal']==1)?'hide':'';
					}
					
					if(isset($product->id) && $category['meal_deal']==1 && $product->meal_deal==1)
					{
						$class_hide = '';
					}else if(isset($product->id) && $category['meal_deal']==0 && $product->meal_deal==1){
						$class_hide = 'hide';
					}else if(isset($product->id) && $category['meal_deal']==1 && $product->meal_deal==0){
						$class_hide = 'hide';
					}else if(isset($product->id) && $category['meal_deal']==0 && $product->meal_deal==0){
						$class_hide = '';
					}
					

					$default_qty = $category['default_qty'];

					
					$addon_item_arr_key = array_search($category['id'], array_column($addon_item_arr, 'category_id'));
					
				 ?>
				 <div class="form-group row <?php echo $class_toggle.' '.$class_hide; ?>" row_id="<?php echo  $category['id'];?>">
					<label class="col-sm-12 col-md-3 col-form-label"><?php echo  $category['name'];?></label>
					
					<div class="col-md-9 col-sm-12 row pr-0">
						<div class="col-sm-12 col-md-4 ">
							<select class="multi_option form-control" data-id="<?php echo  $category['id'];?>" name="multi_option[<?php echo  $category['id'];?>]" id="multi_option_<?php echo  $category['id'];?>">
								<option value="1" <?php echo (isset($addon_item_arr) && count($addon_item_arr)>0 && isset($addon_item_arr_key) && $addon_item_arr[$addon_item_arr_key]['multi_option']==1)?'selected':''; ?>>Can Select Only One</option>
								<option value="2" <?php echo (isset($addon_item_arr) && count($addon_item_arr)>0 && isset($addon_item_arr_key) && $addon_item_arr[$addon_item_arr_key]['multi_option']==2)?'selected':''; ?>>Can Select Multiple</option>
								<option value="3" <?php echo (isset($addon_item_arr) && count($addon_item_arr)>0 && isset($addon_item_arr_key) && $addon_item_arr[$addon_item_arr_key]['multi_option']==3)?'selected':''; ?>>Custom</option>
							</select>
						</div>
				
						<div class="col-sm-12 col-md-4 multi_option_value_<?php echo  $category['id'];?> hide" <?php echo (isset($addon_item_arr[$addon_item_arr_key]) && $addon_item_arr[$addon_item_arr_key]['multi_option']=='3')?'style="display:flex"':'"display:none"'; ?>>
							<input class=" form-control" type="text" value="<?php echo (isset($addon_item_arr[$addon_item_arr_key]))?$addon_item_arr[$addon_item_arr_key]['multi_option_value']:''; ?>" name="multi_option_value[<?php echo  $category['id'];?>]" id="multi_option_value_<?php echo  $category['id'];?>" placeholder="Multi Option Value" onkeypress="return isNumber(event)">
						</div>
				
						<div class="col-sm-12 col-md-4 pr-0  hide two_flavors_toggle" <?php echo isset($product->two_flavors)?'style="display:flex"':'"display:none"';?>>
							<select class=" form-control two_flavors_position" data-id="<?php echo  $category['id'];?>" name="two_flavors_position[<?php echo  $category['id'];?>]" id="two_flavors_position_<?php echo  $category['id'];?>">
								<option value="" >Select Position</option>
								<option value="left" <?php echo (isset($addon_item_arr[$addon_item_arr_key]) && $addon_item_arr[$addon_item_arr_key]['two_flavors_position']=='left')?'selected':''; ?>>left</option>
								<option value="right" <?php echo (isset($addon_item_arr[$addon_item_arr_key]) && $addon_item_arr[$addon_item_arr_key]['two_flavors_position']=='right')?'selected':''; ?>>Right</option>
							</select>
						</div>
						
						<div class="col-md-12 col-sm-12 row mt-15 required_check_all">
							<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
								<div class="custom-control custom-checkbox mb-5">
									<input type="checkbox" class="custom-control-input require_addon"  id="require_addon_<?php echo  $category['id'];?>" name="require_addon[<?php echo  $category['id'];?>]"  value="1" <?php echo (isset($addon_item_arr[$addon_item_arr_key]) && $addon_item_arr[$addon_item_arr_key]['require_addon']=='1')?'checked':''; ?>>
									<label class="custom-control-label" for="require_addon_<?php echo  $category['id'];?>">Required?</label>
								</div>
							</div>
							
							<div class="col-sm-12 col-md-5 col-form-label pr-0 pb-0">
								<div class="custom-control custom-checkbox mb-5">
									<input type="checkbox" class="custom-control-input check_all_addon_item" data-id="<?php echo  $category['id'];?>" id="check_all_<?php echo  $category['id'];?>" name="check_all[<?php echo  $category['id'];?>]"  value="1"  <?php echo (isset($addon_item_arr[$addon_item_arr_key]) && count($addon_item_arr[$addon_item_arr_key]['addon_item'])==$itemCnt)?'checked':''; ?>>
									<label class="custom-control-label" for="check_all_<?php echo  $category['id'];?>">Check all/uncheck</label>
								</div>
							</div>
						</div>
						
						<!--get addon product list start-->
						<div class="col-md-12 col-sm-12 row mt-15 addon_product_list">
							<?php 
							foreach( $items as $item){	?>
								<div class="col-sm-12 col-md-12 col-form-label pr-0 pb-0">
									<div class="custom-control custom-checkbox mb-5">
										<input type="checkbox" class="custom-control-input check_all_<?php echo  $category['id'];?>"  id="addon_item_id_<?php echo  $category['id'].'_'.$item['item_id'];?>" name="addon_item_id[<?php echo  $category['id'];?>][<?php echo  $item['item_id'];?>]"  value="1" <?php echo (isset($addon_item_arr[$addon_item_arr_key]) && in_array($item['item_id'],(array)$addon_item_arr[$addon_item_arr_key]['addon_item']))?'checked':''; ?>>
										
										<label class="custom-control-label" for="addon_item_id_<?php echo  $category['id'].'_'.$item['item_id'];?>"><?php echo  $item['item_name'];?> (<?php echo CURRENCY.' '.round($item['item_price'],2);?>)
											<?php
											if(isset($category['meal_deal']) && $category['meal_deal']==1 && isset($category['default_qty']) && $category['default_qty']==1){ ?>

												<input type="text" class="form-control" name="meal_deal_item_qty[<?php echo  $category['id'];?>][<?php echo  $item['item_id'];?>]" id="meal_deal_item_qty_<?php echo  $category['id'].'_'.$item['item_id'];?>"  placeholder="No.of Quantity"  onkeypress="return isNumber(event)" value="<?php echo (isset($addon_item_arr[$addon_item_arr_key]['meal_deal_item_qty'][$item['item_id']]) && !empty($addon_item_arr[$addon_item_arr_key]['meal_deal_item_qty'][$item['item_id']]))?$addon_item_arr[$addon_item_arr_key]['meal_deal_item_qty'][$item['item_id']]:''; ?>">
												<div class="has-danger form-control-feedback err_meal_deal_no_personsss"></div>
												<?php 
											}
											?>
										</label>
									</div>
								</div>
								<?php 
							} ?>
							
							
							<?php
							if(isset($category['meal_deal']) && $category['meal_deal']==1 && isset($category['default_qty']) && $category['default_qty']==0  && $category['multiple_meal_deal']==0){ 
								?>
								<div class="form-group row  meal_deal_addon_category">
									<div class="col-sm-12 col-md-9">
										<input type="text" name="meal_deal_no_option[<?php echo  $category['id'];?>]" id="meal_deal_no_option_<?php echo  $category['id'];?>" class="form-control" onkeypress="return isNumber(event)" value="<?php echo (isset($addon_item_arr[$addon_item_arr_key]) && !empty($addon_item_arr[$addon_item_arr_key]))?$addon_item_arr[$addon_item_arr_key]['meal_deal_no_option']:''; ?>" placeholder="No.of Option" maxlength="1" >
									</div>
								</div>
								<?php 
							} ?>
						</div>
						<!--get addon product list finish-->
					</div>
				</div>
				 <?php
			 }
			 }
			?>
			
			</div>
		</div>
	 <?php 
	 } ?>
	
	<?php 
	if(isset($product->id) && isset($product->meal_deal) && $product->meal_deal==1)
	{
		$class_hide = 'hide';
	}else{
		$class_hide = '';
	}
	  
	if(isset($ingredients) && count($ingredients)>0)
	{
		$ingredientsArr = isset($product->id)?(array)$product->ingredients:array();
		?>
		<div class="form-group row no_meal_deal_addon_category <?php echo $class_hide;?>">
		<label class="col-sm-12 col-md-3 col-form-label">Ingredients</label>
		<div class="col-sm-12 col-md-9 row">
			<?php 
			 foreach($ingredients as $id=>$ingredient){
				?>
				<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="ingredients_<?php echo $id;?>" name="ingredients[<?php echo $id;?>]"  value="1" <?php echo (isset($ingredientsArr) && count($ingredientsArr)>0 && in_array($id,$ingredientsArr))?'checked':''; ?>>
					<label class="custom-control-label" for="ingredients_<?php echo $id;?>"><?php echo $ingredient;?></label>
				</div>
				</div>
				<?php
			}

			?>
		</div>
	</div> 
		<?php 
	} ?>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">VAT/GST (inc)</label>
		<div class="col-sm-12 col-md-9 row">
			<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input"  id="non_taxable" name="non_taxable"  value="1" <?php echo (isset($product->non_taxable) && $product->non_taxable==1)?'checked':''; ?>>
				<label class="custom-control-label" for="non_taxable">Non taxable</label>
			</div>
			</div>
		</div>
	</div> 
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Points Earned</label>
		<div class="col-sm-12 col-md-4">
			<input type="text" name="points_earned" id="points_earned"  class="form-control" placeholder="Points Earned" value="<?php echo isset($product->points_earned)?set_value('points_earned',html_entity_decode($product->points_earned)):set_value('points_earned'); ?>" onkeypress="return isNumber(event)">
		</div>
		
		<div class="col-sm-12 col-md-5 col-form-label pr-0 pb-0">
			<div class="custom-control custom-checkbox mb-5">
				<input type="checkbox" class="custom-control-input"  id="points_disabled" name="points_disabled"  value="1" < <?php echo (isset($product->points_disabled) && $product->points_disabled==1)?'checked':''; ?>>
				<label class="custom-control-label" for="points_disabled"> Disabled Points on this item</label>
			</div>
		</div>
	</div>
	
	<?php
	$item_variation = isset($product->id)?(array)$product->item_variation:array();
	

	?>
	<div class="form-group row no_meal_deal_addon_category <?php echo $class_hide;?>">
			<label class="col-sm-12 col-md-12  col-form-label">Item Variations</label>
			<span class="append_variation">
				<div class="col-md-12 col-sm-12 row">
					<div class="col-sm-12 col-md-3" >
						<select class="form-control" name="size[<?php echo $uniqid;?>]"  id="size">
							<option value="">Select Size</option> 
							<?php 
								foreach($sizes as $id=>$size){
								?>
							<option value="<?php echo $id;?>" <?php echo (isset($item_variation) && count($item_variation)>0  && isset($item_variation[0]['size']) && $item_variation[0]['size']==$id)?'selected':''; ?>><?php echo $size; ?></option> 
								<?php } ?>
						</select>
					</div>
					
					<div class="col-sm-12 col-md-3">
						<input type="text" name="max_price[<?php echo $uniqid;?>]"   class="form-control" placeholder="Max Price" value="<?php echo (isset($item_variation) && count($item_variation)>0 && isset($item_variation[0]['max_price']))?$item_variation[0]['max_price']:''; ?>" onkeypress="return isNumberDecimal(event)">
					</div>
					
					<div class="col-sm-12 col-md-3">
						<input type="text" name="discount_price[<?php echo $uniqid;?>]"  class="form-control" placeholder="Discount Price" value="<?php echo (isset($item_variation) && count($item_variation)>0 && isset($item_variation[0]['discount_price']) )?$item_variation[0]['discount_price']:''; ?>" onkeypress="return isNumberDecimal(event)">
					</div>
					
					<div class="col-sm-12 col-md-2">
						<input type="text" name="quantity[<?php echo $uniqid;?>]"  class="form-control" placeholder="Quantity" value="<?php echo (isset($item_variation) && count($item_variation)>0 && isset($item_variation[0]['quantity']) )?$item_variation[0]['quantity']:''; ?>" onkeypress="return isNumber(event)">
					</div>
					
					<div class="col-sm-12 col-md-1">
						<a href="javascript:void(0)" class="btn btn-success addProductVariation  btn-sm">+</a>
					</div>
				</div>
			<?php
			$i=1;
			if(isset($item_variation) && count($item_variation)>0){

				foreach($item_variation as $key=>$array){
					if($i==1){
						$i++;
						continue;
					}
					
					?>
					<div class="col-md-12 col-sm-12 row mt-15">
						<div class="col-sm-12 col-md-3" >
							<select class="form-control" name="size[<?php echo $key;?>]" >
							<option value="">Select Size</option> 
							<?php 
							foreach($sizes as $id=>$size){
								?>
							<option value="<?php echo $id;?>" <?php echo (isset($item_variation[$key]['size']) && $item_variation[$key]['size']==$id)?'selected':''; ?>><?php echo $size; ?></option> 
							<?php } ?>
							</select>
						</div>
						<div class="col-sm-12 col-md-3">
							<input type="text" name="max_price[<?php echo $key;?>]" value="<?php echo (isset($item_variation) && count($item_variation)>0 && isset($item_variation[$key]['max_price']))?$item_variation[$key]['max_price']:''; ?>" class="form-control" placeholder="Max Price" onkeypress="return isNumberDecimal(event)">
						</div>
						<div class="col-sm-12 col-md-3">
						<input type="text" name="discount_price[<?php echo $key;?>]" value="<?php echo (isset($item_variation) && count($item_variation)>0 && isset($item_variation[$key]['discount_price']) )?$item_variation[$key]['discount_price']:''; ?>" class="form-control" placeholder="Discount Price" onkeypress="return isNumberDecimal(event)">
						</div>
						<div class="col-sm-12 col-md-2">
						<input type="text" name="quantity[<?php echo $key;?>]" value="<?php echo (isset($item_variation) && count($item_variation)>0 && isset($item_variation[$key]['quantity']) )?$item_variation[$key]['quantity']:''; ?> " class="form-control" placeholder="Quantity" onkeypress="return isNumber(event)">
						</div>
						<div class="col-sm-12 col-md-1">
						<a href="javascript:void(0)" class="btn btn-danger removeProductVariation  btn-sm">-</a>
						</div>
					</div>
					<?php
					$i++;
						
					
				}
			}
			?>
			
			</span>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Featured Image</label>
		<div class="col-md-9 col-sm-12 row pr-0">
			<div class="col-sm-12 col-md-12 pr-0">
				<input type="hidden" value="<?php echo isset($product->image)?set_value('image',$product->image):set_value('image'); ?>" name="old_image" id="old_image">
				<input type="hidden" value="0" name="image_status" id="image_status">
				<input type="file" name="image" id="image" class="form-control-file form-control height-auto" accept="image/*" onchange="ValidateSingleFileUpload()">
			</div>
			<div class="col-sm-12 col-md-4">
				<div class="form-group image_append">
					  <?php if(isset($product->image) && !empty($product->image)) { ?>
					  <span class="pip img-thumbnail">
					<img class="imageThumb" width="100" src="<?=base_url('uploads/products').'/'.$product->image; ?>" title="No image"/>
					<br/><span class="removeSingleExist btn-primary" id="<?php echo $product->id; ?>">Remove</span>
						</span>
					  <?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Gallery Image</label>
		<div class="col-md-9 col-sm-12 row pr-0">
			<div class="col-sm-12 col-md-12 pr-0">
				<input type="hidden" value="" name="gallery_img_remove" id="gallery_img_remove">
				<input type="hidden" value="" name="gallery_uploaded_filename" id="gallery_uploaded_filename">
				<input type="file" name="gallery[]" id="gallery" class="form-control-file form-control height-auto multipleupload" multiple accept="image/*">
			</div>
			<div class="col-sm-12 col-md-12">
				<div class="form-group gallery_append">
					<?php if(isset($product->gallery) && !empty($product->gallery)) {
					$gallery_arr = $product->gallery;
					foreach($gallery_arr as $gallery){
					?>
					<span class="pip img-thumbnail">
						<img class="imageThumb" width="100" src="<?=base_url('uploads/products').'/'.$gallery; ?>" title="No image"/>
						<br/><span class="removeGalleryExist btn-primary" id="<?php echo $gallery; ?>">Remove</span>
					</span>
					<?php
					}	
					 } ?>
				</div>
			</div>
		</div>
	</div>
	

	<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
			<div class="col-sm-12 col-md-9" >
			<select class="form-control" name="status" id="status" >
				<option value="0" <?php echo (isset($product->status) && $product->status=='0')?'selected':''; ?>>Pending</option> 
				<option value="1" <?php echo (isset($product->status) && $product->status=='1')?'selected':''; ?>>Published</option> 
			</select>
			</div>
		</div>
			
	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm btn-block disable">SUBMIT</button>
			</div>
		</div>
	</div>
		
	</form>
	
</div>
