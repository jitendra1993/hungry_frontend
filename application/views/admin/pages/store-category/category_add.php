<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4><?=isset($category->id) ? 'Edit':'Add'?>  Store Categories</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/store-category/view')?>">Category</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?=isset($category->id) ? 'Edit':'Add'?> Store Category</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/store-category/view')?>" class="btn btn-primary btn-sm" > View All</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
	<?php 
	if(isset($error) && !empty($error)){ ?>
		<div class="alert alert-danger" role="alert">
			 <?php echo $error; ?>
		</div>
          <?php 
	}	  
   if(validation_errors()){ ?>
		<div class="alert alert-danger" role="alert">
			<?php echo validation_errors(); ?>
		</div>
		<?php 
	}
		  
	$attributes = array('class' => 'cate', 'id' => 'cat-master'); 
	echo form_open_multipart(isset($category->id)?'admin/store-category/edit/'.$category->id:'admin/store-category/add',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($category->id)?set_value('name',$category->id):set_value('id'); ?>">
	<div class="row">
		<div class="col-md-4 col-sm-12">
			<div class="form-group ">
				<label>Name</label>
				<input type="text" name="name" id="name"  class="form-control" placeholder="Category Name" value="<?php echo isset($category->name)?set_value('name',html_entity_decode($category->name)):set_value('name'); ?>">
				<div class="has-danger form-control-feedback errName"></div>
			</div>
		</div>
		
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label>Description</label>
				<input type="text" name="description"  id="description"  class="form-control" placeholder="Description" value="<?php echo isset($category->description)?set_value('description',html_entity_decode($category->description)):set_value('description'); ?>">
			</div>
		</div>
		
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label>Sort Order</label>
				<input type="text" name="sort_order" id="sort_order"  class="form-control" placeholder="Sort Order" value="<?php echo isset($category->sort_order)?set_value('sort_order',$category->sort_order):set_value('sort_order'); ?>" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
			</div>
		</div>
	</div>
		
	<div class="row">
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label>Status</label>
				<select class="form-control" name="status" id="status">
					<option value="1" <?php echo (isset($category->status) && $category->status==1)?'selected':''; ?>>Active</option>
					<option value="0" <?php echo (isset($category->status) && $category->status==0)?'selected':''; ?>>Inactive</option>
				</select>
			</div>
		</div>
			
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label>Image</label>
				<input type="file" name="image" id="image" class="form-control-file form-control height-auto" accept="image/*" onchange="return ValidateFileUpload()" >
				</div>
			</div>
		
			<input type="hidden" value="<?php echo isset($category->image)?set_value('image',$category->image):set_value('image'); ?>" name="old_img" id="old_img">
			<input type="hidden" value="0" name="img_status" id="img_status">
			<div class="col-md-4 col-sm-12">
				<div class="form-group image_append">
					<?php 
					if(isset($category->image) && !empty($category->image)) { ?>
						<span class="pip img-thumbnail">
						<img class="imageThumb" width="100" src="<?=base_url('uploads/files').'/'.$category->image; ?>" title="No image"/>
						<br/>
						<span class="removeExist btn-primary" id="<?php echo $category->id; ?>">Remove</span></span>
						  <?php 
					}else{ ?>
						<img src="<?=$placeholder?>"  width="100" id="blah" />
						<?php 
					} ?>   
				</div>
			</div>
		</div>
		
	<div class="row">
		<div class=" col-sm-12">
			<div class="input-group mb-0">
				<button type="submit" class="btn btn-primary btn-sm btn-block">SUBMIT</button>
			</div>
		</div>
	</div>
	<?php echo form_close();?>
	
</div>
