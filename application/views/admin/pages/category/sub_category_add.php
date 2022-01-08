<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4><?=isset($category->id) ? 'Edit':'Add'?>  Sub Categories</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/catalog/sub-category/view')?>">Sub Category</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?=isset($category->id) ? 'Edit':'Add'?> Sub Category</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/catalog/sub-category/view')?>" class="btn btn-primary btn-sm" > View All</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php if(isset($error) && !empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
				 <?php echo $error; ?>
			</div>

          <?php }
		  
		   if(validation_errors()){ ?>
            <div class="alert alert-danger" role="alert">
              <?php echo validation_errors(); ?>
            </div>
          <?php }
		  
		$attributes = array('class' => 'cate', 'id' => 'sub-cat-master'); 
		  echo form_open_multipart(isset($subcategory->id)?'admin/catalog/sub-category/edit/'.$subcategory->id:'admin/catalog/sub-category/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($subcategory->id)?set_value('name',$subcategory->id):set_value('id'); ?>">
		<div class="row">
			<div class="col-md-4 col-sm-12">
				<div class="form-group ">
					<label>Master Category</label>
					<select name="categoryId" id="categoryId" class="categoryId form-control">
						<option value="" selected="">--select--</option>
						<?php foreach($categories as $main_mascat){ ?>
						<option value="<?=$main_mascat->id;?>" <?=(isset($subcategory->category_id) && $subcategory->category_id == $main_mascat->id)?'selected':''?> ><?=$main_mascat->name;?></option>
					<?php } ?>
				  </select>
					<div class="has-danger form-control-feedback errcategoryId"></div>
				</div>
			</div>
			
			<div class="col-md-4 col-sm-12">
				<div class="form-group ">
					<label>Name</label>
					<input type="text" name="name" id="name"  class="form-control" placeholder="Category Name" value="<?php echo isset($subcategory->name)?set_value('name',$subcategory->name):set_value('name'); ?>">
					<div class="has-danger form-control-feedback errName"></div>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Description</label>
					<input type="text" name="description"  id="description"  class="form-control" placeholder="Description" value="<?php echo isset($subcategory->description)?set_value('description',$subcategory->description):set_value('description'); ?>">
				</div>
			</div>
			
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Sort Order</label>
					<input type="text" name="sort_order" id="sort_order"  class="form-control" placeholder="Sort Order" value="<?php echo isset($subcategory->sort_order)?set_value('sort_order',$subcategory->sort_order):set_value('sort_order'); ?>" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label>Status</label>
					<select class="form-control" name="status" id="status">
					<option value="1" <?php echo (isset($subcategory->status) && $subcategory->status==1)?'selected':''; ?>>Active</option>
					<option value="0" <?php echo (isset($subcategory->status) && $subcategory->status==0)?'selected':''; ?>>Inactive</option>
					</select>
				</div>
			</div>
			
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Image</label>
					<input type="file" name="image" id="image" class="form-control-file form-control height-auto" accept="image/*" onchange="return ValidateFileUpload()" >
				</div>
			</div>
			<input type="hidden" value="<?php echo isset($subcategory->image)?set_value('image',$subcategory->image):set_value('image'); ?>" name="old_img" id="old_img">
			<input type="hidden" value="0" name="img_status" id="img_status">
			<div class="col-md-2">
				<div class="form-group image_append">
				 
						  <?php if(isset($subcategory->image) && !empty($subcategory->image)) { ?>
						  <span class="pip img-thumbnail">
						<img class="imageThumb" width="100" src="<?=base_url('uploads/files').'/'.$subcategory->image; ?>" title="No image"/>
						<br/><span class="removeExist btn-primary" id="<?php echo $subcategory->id; ?>">Remove</span>
							</span>
						
						  <?php } else { ?>
						   <img src="<?=$placeholder?>"  width="100" id="blah" />
						  <?php } ?>
                       
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
		
	</form>
	
</div>
