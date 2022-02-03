<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4><?=isset($banner->id) ? 'Edit':'Add'?> Banner</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home sssssssrrrrrr</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/banner/view')?>">Banner</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?=isset($banner->id) ? 'Edit':'Add'?> Banner</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/banner/view')?>" class="btn btn-primary btn-sm" > View All</a>
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
              <?php //echo validation_errors(); ?>
			  <?php echo form_error('name', '<span>', '</span>'); ?>
			<?php echo form_error('image', '<span>', '</span>'); ?>
            </div>
          <?php }
		  
		$attributes = array('class' => 'banner', 'id' => 'banner-master'); 
		  echo form_open_multipart(isset($banner->id)?'admin/banner/edit/'.$banner->id:'admin/banner/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($banner->id)?set_value('name',$banner->id):set_value('id'); ?>">
		<div class="row">
			<div class="col-md-4 col-sm-12">
				<div class="form-group ">
					<label>Name</label>
					<input type="text" name="name" id="name"  class="form-control" placeholder="Banner Name" value="<?php echo isset($banner->name)?set_value('name',html_entity_decode($banner->name)):set_value('name'); ?>">
					<div class="has-danger form-control-feedback errName"></div>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Description</label>
					<input type="text" name="description"  id="description"  class="form-control" placeholder="Description" value="<?php echo isset($banner->description)?set_value('description',html_entity_decode($banner->description)):set_value('description'); ?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Sort Order</label>
					<input type="text" name="sort_order" id="sort_order"  class="form-control" placeholder="Sort Order" value="<?php echo isset($banner->sort_order)?set_value('sort_order',$banner->sort_order):set_value('sort_order'); ?>" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
				</div>
			</div>
		</div>
		
		<div class="row">
		
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Alt Text</label>
					<input type="text" name="alt_text" id="alt_text"  class="form-control" placeholder="Alt Text" value="<?php echo isset($banner->alt_text)?set_value('alt_text',$banner->alt_text):set_value('alt_text'); ?>">
				</div>
			</div>
			
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Status</label>
					<select class="form-control" name="status" id="status">
					<option value="1" <?php echo (isset($banner->status) && $banner->status==1)?'selected':''; ?>>Active</option>
					<option value="0" <?php echo (isset($banner->status) && $banner->status==0)?'selected':''; ?>>Inactive</option>
					</select>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label>Image</label>
					<input type="file" name="image" id="image" class="form-control-file form-control height-auto" accept="image/*">
					<div class="has-danger form-control-feedback errImage"></div>
					<!--<input type="file" name="images[]" multiple accept="image/*"  id="images"  >-->
				</div>
			</div>
			<input type="hidden" value="<?php echo isset($banner->image)?set_value('image',$banner->image):set_value('image'); ?>" name="old_img" id="old_img">
			<input type="hidden" value="0" name="img_status" id="img_status">
			<div class="col-md-4 col-sm-12">
				<div class="form-group image_append">
				 
						  <?php if(isset($banner->image) && !empty($banner->image)) { ?>
						  <span class="pip300 img-thumbnail">
						<img class="imageThumb" width="300" src="<?=base_url('uploads/files').'/'.$banner->image; ?>" title="No image"/>
						<br/><span class="removeExist btn-primary" id="<?php echo $banner->user_hash_id; ?>">Remove</span>
							</span>
						
						  <?php } else { ?>
						  <!-- <img src="<?=$placeholder?>"  width="100" id="blah" />-->
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
