<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4> Ingredient</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page"> Ingredient</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/ingredient/view')?>" class="btn btn-primary btn-sm" >View Ingredients</a>
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
			  <?php echo form_error('name', '<span>', '</span>'); ?>
			<?php echo form_error('image', '<span>', '</span>'); ?>
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
		  
		$attributes = array('class' => 'ingredient', 'id' => 'ingredient_admin'); 
		  echo form_open(isset($ingredient->id)?'admin/ingredient/edit/'.$ingredient->id:'admin/ingredient/add',$attributes);?>
		  <input type="hidden" name="id" id="id" value="<?php echo isset($ingredient->id)?set_value('name',$ingredient->id):set_value('id'); ?>">
		

			<div class="form-group row">
			<label class="col-sm-12 col-md-3 col-form-label">Ingredient Name</label>
			<div class="col-sm-12 col-md-9">
				<input type="text" name="ingredients_name" id="ingredients_name"  class="form-control" placeholder="Ingredient Name" value="<?php echo isset($ingredient->name)?set_value('ingredients_name',$ingredient->name):set_value('ingredients_name'); ?>">
				<div class="has-danger form-control-feedback err_ingredients_name"></div>
			</div>
			</div>
		
			<div class="form-group row">
				<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
				<div class="col-sm-12 col-md-9" >
				<select class="form-control" name="status" id="status" >
					<option value="1" <?php echo (isset($ingredient->status) && $ingredient->status==1)?'selected':''; ?>>Active</option> 
					<option value="0" <?php echo (isset($ingredient->status) && $ingredient->status==0)?'selected':''; ?>>Inactive</option> 
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

