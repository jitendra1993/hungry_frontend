<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4><?=isset($category->id) ? 'Edit':'Add'?>  AddOn Categories</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/catalog/addon-category/view')?>">AddOn Category View</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?=isset($category->id) ? 'Edit':'Add'?> AddOn Category</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/catalog/addon-category/view')?>" class="btn btn-primary btn-sm" > View All</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php 
	   if(validation_errors()){ ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <?php //echo validation_errors(); ?>
			  <?php echo form_error('name', '<span>', '</span>'); ?>
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
		  
	$attributes = array('class' => 'addon_cat_master', 'id' => 'addon_cat_master'); 
	 echo form_open(isset($category->id)?'admin/catalog/addon-category/edit/'.$category->id:'admin/catalog/addon-category/add',$attributes);?>
	 <input type="hidden" name="id" id="id" value="<?php echo isset($category->id)?set_value('name',$category->id):set_value('id'); ?>">
		  
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">AddOn Name</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="name" id="name"  class="form-control" placeholder="AddOn Name" value="<?php echo isset($category->name)?set_value('name',html_entity_decode($category->name)):set_value('name'); ?>">
				<div class="has-danger form-control-feedback errName"></div>
			</div>
		</div>
			
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Description</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="description"  id="description"  class="form-control" placeholder="Description" value="<?php echo isset($category->description)?set_value('description',html_entity_decode($category->description)):set_value('description'); ?>">
			</div>
		</div>
			
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Sort Order</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="sort_order" id="sort_order"  class="form-control" placeholder="Sort Order" value="<?php echo isset($category->sort_order)?set_value('sort_order',$category->sort_order):set_value('sort_order'); ?>" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Meal Deal ?</label>
			<div class="col-sm-12 col-md-9">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input meal_deal_addon"  id="meal_deal" name="meal_deal"  value="1" <?php echo (isset($category->meal_deal) && $category->meal_deal==1)?'checked':''; ?>>
					<label class="custom-control-label" for="meal_deal"></label>
				</div>
			</div>
		</div> 
		
		<?php 
		$style = (isset($category->id) && (isset($category->meal_deal) && $category->meal_deal==1))?'':'none';
		?>
		<div class="form-group row multiple_selection_meal_deal" style="display:<?php echo $style;?>">
			<label class="col-sm-12 col-md-3 col-form-label">Multiple Item (Meal Deal) ?</label>
			<div class="col-sm-12 col-md-9">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="multiple_meal_deal" name="multiple_meal_deal"  value="1" <?php echo (isset($category->multiple_meal_deal) && $category->multiple_meal_deal==1)?'checked':''; ?>>
					<label class="custom-control-label" for="multiple_meal_deal"></label>
				</div>
			</div>
		</div> 
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Default Quantity ?</label>
			<div class="col-sm-12 col-md-9">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input"  id="default_qty" name="default_qty"  value="1" <?php echo (isset($category->default_qty) && $category->default_qty==1)?'checked':''; ?>>
					<label class="custom-control-label" for="default_qty"></label>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Status</label>
			<div class="col-sm-12 col-md-9">
				<select class="form-control" name="status" id="status">
				<option value="1" <?php echo (isset($category->status) && $category->status==1)?'selected':''; ?>>Active</option>
				<option value="0" <?php echo (isset($category->status) && $category->status==0)?'selected':''; ?>>Inactive</option>
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
		
	<?php echo form_close();?>
	
</div>
