<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>SEO Page Add/Update</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> SEO Page</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/seo/view')?>" class="btn btn-primary btn-sm" >View SEO Page</a>
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
		<div class="alert alert-danger alert-dismissible" role="alert">
			<?php echo form_error('page_name', '<span>', '</span>'); ?>
			<?php echo form_error('title', '<span>', '</span>'); ?>
			<?php echo form_error('keywords', '<span>', '</span>'); ?>
			<?php echo form_error('description', '<span>', '</span>'); ?>
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
		  
	$attributes = array('class' => 'seo_admin', 'id' => 'seo_admin'); 
	echo form_open(isset($seo->id)?'admin/seo/edit/'.$seo->id:'admin/seo/add',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($seo->id)?set_value('name',$seo->id):set_value('id'); ?>">
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Page Name</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="page_name" id="page_name"  class="form-control" placeholder="Page Name" value="<?php echo isset($seo->page_name)?set_value('page_name',html_entity_decode($seo->page_name)):set_value('page_name'); ?>" >
			<div class="has-danger form-control-feedback err_page_name"></div>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Title</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="title" id="title"  class="form-control" placeholder="Title" value="<?php echo isset($seo->title)?set_value('title',html_entity_decode($seo->title)):set_value('title'); ?>" >
			<div class="has-danger form-control-feedback err_title"></div>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Keywords</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="keywords" id="keywords"  class="form-control" placeholder="Keywords" value="<?php echo isset($seo->keywords)?set_value('keywords',html_entity_decode($seo->keywords)):set_value('keywords'); ?>" >
			<div class="has-danger form-control-feedback err_keywords"></div>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Description</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="description" id="description"  class="form-control" placeholder="Description" value="<?php echo isset($seo->description)?set_value('description',html_entity_decode($seo->description)):set_value('description'); ?>" >
			<div class="has-danger form-control-feedback err_description"></div>
		</div>
	</div>
	
	
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
		<div class="col-sm-12 col-md-9" >
			<select class="form-control" name="status" id="status" >
				<option value="1" <?php echo (isset($seo->status) && $seo->status=='1')?'selected':''; ?>>Publish</option> 
				<option value="0" <?php echo (isset($seo->status) && $seo->status=='0')?'selected':''; ?>>Inactive</option> 
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
		
	<?php echo form_close(); ?>
	
</div>

