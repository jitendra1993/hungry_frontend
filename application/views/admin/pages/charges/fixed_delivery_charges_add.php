<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4><?=isset($deliveryCharges->id) ? 'Edit':'Add'?>Fixed Delivery Charges Add</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/fixed-delivery-charges/view')?>">Fixed Delivery Charges</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?=isset($deliveryCharges->id) ? 'Edit':'Add'?> Fixed Delivery Charges</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/fixed-delivery-charges/view')?>" class="btn btn-primary btn-sm" > View All</a>
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
		  
	$attributes = array('class' => 'charges-fixed', 'id' => 'charges-fixed'); 
	echo form_open(isset($deliveryCharges->id)?'admin/fixed-delivery-charges/edit/'.$deliveryCharges->id:'admin/fixed-delivery-charges/add',$attributes);?>
	<input type="hidden" name="id" id="id" value="<?php echo isset($deliveryCharges->id)?set_value('id',$deliveryCharges->id):set_value('id'); ?>">
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Place</label>
		<div class="col-sm-12 col-md-9">
			<input type="text" name="place" id="place"  class="form-control" placeholder="Place" value="<?php echo isset($deliveryCharges->place)?set_value('place',html_entity_decode($deliveryCharges->place)):set_value('place'); ?>">
			<div class="has-danger form-control-feedback errPlace"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Price</label>
		<div class="col-sm-12 col-md-9">
			<div class="input-group mb-0">
				<input type="text" name="cost" id="cost"  class="form-control" placeholder="Price" value="<?php echo isset($deliveryCharges->cost)?set_value('cost',html_entity_decode($deliveryCharges->cost)):set_value('cost'); ?>" onkeypress="return isNumberDecimal(event)">
				<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroupPrepend"><?php echo CURRENCY;?></span>
				</div>
			</div>
			<div class="has-danger form-control-feedback err_cost"></div>
		</div>
	</div>
		
	<div class="form-group row">
		<label class="col-sm-12 col-md-3 col-form-label">Satus</label>
		<div class="col-sm-12 col-md-9" >
		<select class="form-control" name="status" id="status" >
			<option value="0" <?php echo (isset($deliveryCharges->status) && $deliveryCharges->status==0)?'selected':''; ?>>Pending</option> 
			<option value="1" <?php echo (isset($deliveryCharges->status) && $deliveryCharges->status==1)?'selected':''; ?>>Published</option> 
		</select>
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
