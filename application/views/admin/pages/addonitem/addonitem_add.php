<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Addon Item</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Addon Item</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/addon-item/view')?>" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i> View Addon Items</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php
$currency = CURRENCY;
 if(isset($error) && !empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
				 <?php echo $error; ?>
			</div>

          <?php }
		  
		   if(validation_errors()){ ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <?php //echo validation_errors(); ?>
			  <?php echo form_error('name', '<span>', '</span>'); ?>
			  <?php echo form_error('price', '<span>', '</span>'); ?>
			  <?php echo form_error('addon_categories', '<span>', '</span>'); ?>
            </div>
          <?php }
		    if($this->session->flashdata('msg_success')){ ?>
		  <div class="alert alert-success alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('msg_success'))?> 
		  </div>
		  <?php }
		  if($this->session->flashdata('error_msg')){ ?>
		  <div class="alert alert-danger alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('error_msg'));?> 
		  </div> 
		  <?php
		  }
		  
		$attributes = array('class' => 'addon_item', 'id' => 'addon_item'); 
		  echo form_open_multipart(isset($addonitem->id)?'admin/addon-item/edit/'.$addonitem->id:'admin/addon-item/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($addonitem->id)?set_value('name',$addonitem->id):set_value('id'); ?>">
		
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">AddOn Item</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="name" id="name"  class="form-control" placeholder="AddOn Item" value="<?php echo isset($addonitem->name)?set_value('name',htmlspecialchars_decode($addonitem->name)):set_value('name'); ?>">
				<div class="has-danger form-control-feedback err_name"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Description</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="description" id="description"  class="form-control" placeholder="Description" value="<?php echo isset($addonitem->description)?set_value('description',htmlspecialchars_decode($addonitem->description)):set_value('description'); ?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Sort Order</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="sort_order" id="sort_order"  class="form-control" placeholder="Sort Order" value="<?php echo isset($addonitem->sort_order)?set_value('sort_order',$addonitem->sort_order):set_value('sort_order'); ?>" onkeypress="return isNumber(event)">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Status</label>
			<div class="col-sm-12 col-md-9">
				<select class="form-control" name="status" id="status">
				<option value="1" <?php echo (isset($addonitem->status) && $addonitem->status==1)?'selected':''; ?>>Publish</option>
				<option value="0" <?php echo (isset($addonitem->status) && $addonitem->status==0)?'selected':''; ?>>Pending for Review</option>
				</select>
			</div>
		</div>
		
		
		<div class="form-group row mb-0">
			<label class="col-sm-12 col-md-3 col-form-label">Price</label>
			<div class="col-sm-12 col-md-9">
			<div class="input-group">
				<input type="text" name="price" id="price"  class="form-control" placeholder="Price" value="<?php echo isset($addonitem->price)?set_value('price',$addonitem->price):set_value('price'); ?>" onkeypress="return isNumberDecimal(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo $currency;?></span>
				</div>
			</div>
			<div class="has-danger form-control-feedback err_price"></div>
			</div>
			
		</div>
		
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">AddOn Categories</label>
			<div class="col-sm-12 col-md-9 row">
				<?php 
				
				if(isset($categories) && count($categories)>0){
					$addon_categories = isset($addonitem->id)?(array)$addonitem->addon_categories:array();
					foreach($categories as $key=>$category){
						?>
						<div class="col-sm-12 col-md-4 col-form-label">
							<div class="custom-control custom-checkbox mb-5">
								<input type="checkbox" class="custom-control-input check_all" id="addon_categories_<?php echo $key;?>" name="addon_categories[<?php echo $key;?>]"  value="1" <?php echo (isset($addonitem->addon_categories) && in_array($key,$addon_categories))?'checked':''; ?>>
								<label class="custom-control-label" for="addon_categories_<?php echo $key;?>"><?php echo $category;?></label>
							</div>
						</div>
						<?php
					}
				}
				?>
				<div class="has-danger form-control-feedback err_categories row"></div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-2 col-form-label">Image</label>
			<div class="col-md-10 col-sm-12 row pr-0">
				<div class="col-sm-12 col-md-12 pr-0">
					<input type="hidden" value="<?php echo isset($addonitem->image)?set_value('image',$addonitem->image):set_value('image'); ?>" name="old_image" id="old_image">
					<input type="hidden" value="0" name="image_status" id="image_status">
					<input type="file" name="image" id="image" class="form-control-file form-control height-auto" accept="image/*" onchange="ValidateSingleFileUpload()">
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="form-group image_append">
						  <?php if(isset($addonitem->image) && !empty($addonitem->image)) { ?>
						  <span class="pip img-thumbnail">
						<img class="imageThumb" width="100" src="<?=base_url('uploads/files').'/'.$addonitem->image; ?>" title="No image"/>
						<br/><span class="removeSingleExist btn-primary" id="<?php echo $addonitem->id; ?>">Remove</span>
							</span>
						  <?php } ?>
					</div>
				</div>
			</div>
		</div>
		
		
		
			
			
		<div class="row">
			<div class=" col-sm-12">
				<div class="input-group mb-0">
					<button type="submit" class="btn btn-primary btn-sm btn-block disable">SUBMIT</button>
				</div>
			</div>
		</div>
		
	<?php echo form_close(); ?>
	
</div>

