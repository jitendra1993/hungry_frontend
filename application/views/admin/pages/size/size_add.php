<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Size/Type</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Size/Type</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/size/view')?>" class="btn btn-primary btn-sm" >View Size/Type</a>
		</div>
	</div>
</div>

<div class="pd-20 card-box mb-30">
<?php
 if(isset($error) && !empty($error)){ ?>
            <div class="alert alert-danger" role="alert">
				 <?php echo $error; ?>
			</div>

          <?php }
		  
		   if(validation_errors()){ ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <?php //echo validation_errors(); ?>
			  <?php echo form_error('size_name', '<span>', '</span>'); ?>
			
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
		  
		$attributes = array('class' => 'size', 'id' => 'size_admin'); 
		  echo form_open(isset($size->id)?'admin/size/edit/'.$size->id:'admin/size/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($size->id)?set_value('name',$size->id):set_value('id'); ?>">
		

			<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Size Name</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="size_name" id="size_name"  class="form-control" placeholder="Size Name" value="<?php echo isset($size->name)?set_value('size_name',$size->name):set_value('size_name'); ?>">
				<div class="has-danger form-control-feedback err_size_name"></div>
			</div>
			</div>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
				<div class="col-sm-12 col-md-9" >
				<select class="form-control" name="status" id="status" >
					<option value="1" <?php echo (isset($size->status) && $size->status==1)?'selected':''; ?>>Active</option> 
					<option value="0" <?php echo (isset($size->status) && $size->status==0)?'selected':''; ?>>Inactive</option> 
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

