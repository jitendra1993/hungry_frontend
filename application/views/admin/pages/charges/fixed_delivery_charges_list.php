<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Fixed Delivery Charges</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/fixed-delivery-charges/view')?>">Delivery Charges</a></li>
					<li class="breadcrumb-item active" aria-current="page">Fixed Delivery Charges</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/fixed-delivery-charges/add')?>" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i> Add New</a>
		</div>
	</div>
</div>
<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
	<?php 
	if($this->session->flashdata('msg_success')){ ?>
		<div class="alert alert-success alert-dismissible"> 
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?=($this->session->flashdata('msg_success'))?> 
		</div>
		<?php 
	} ?>
	<div class="clearfix mb-20">
		<div class="col-md-12 row">
			<h4 class="text-blue h4 ">Search</h4>
		</div>
		<div class="col-md-12 row mb-30">
			<form name="filter"id="filter" method="GET" action="<?=base_url('admin/fixed-delivery-charges/view')?>">
				<div class="row">
					<div class="col-sm-3">
						<input type="text" name="filter_name" value="<?php echo  isset($filters['filter_name'])?$filters['filter_name']:''; ?>" placeholder="Search"  class="form-control" />
					</div>
		  
					<div class="col-sm-2">
						<input type="text" name="filter_from_date" value="<?php echo  isset($filters['filter_from_date'])?$filters['filter_from_date']:''; ?>" placeholder="From Date"  class="form-control datepicker1 " readonly />
					</div>
		  
					<div class="col-sm-2">
						<input type="text" name="filter_to_date" value="<?php echo  isset($filters['filter_to_date'])?$filters['filter_to_date']:''; ?>" placeholder="To Date"  class="form-control datepicker1 " readonly />
					</div>
				
					<div class="col-sm-3">
						<select name="filter_status" id="filter_status" class="form-control" >
							<option value="">Select Status</option>
							<option value="active" <?=(isset($filters['filter_status']) && $filters['filter_status'] == 'active')?'selected':''?>>Active</option>
							<option value="inactive" <?=(isset($filters['filter_status'])  && $filters['filter_status'] == 'inactive')?'selected':''?>>Inactive</option>
						</select>
					</div>
		 
					<div class="col-sm-2">
						<button type="submit" id="button-filter"  class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</div>
			</form>
		</div>
				  
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Price</th>
						<th scope="col">Added Date</th>
						<th scope="col">Staus</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i= $start+1;
					if(isset($deliveryCharges) && count($deliveryCharges)>0){
						foreach($deliveryCharges as $charges){
							$status = $charges->status == '1'?'<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>';
							 ?>
							<tr>
								<th scope="row"><?php echo $i; ?></th>
								<td><?=$charges->place?></td>
								<td><?=CURRENCY. number_format($charges->cost,2)?></td>
								<td><?=date('d-M-Y',$charges->added_date_timestamp/1000);?></td>
								<td class="status_<?php echo $charges->id; ?>"><?php echo $status;?></td>
								<td>
									<a href="<?=base_url('admin/fixed-delivery-charges/edit/'.$charges->id)?>"  class="badge badge-secondary" data-toggle="tooltip" data-placement="top"  title="Edit"><i class="fa fa-pencil"></i></a>
									<a href="javascript:void(0)" data-href="<?=base_url('admin/Deliverycharge/fixedDelete')?>" data-id="<?=$charges->id?>" class="badge badge-secondary form-delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
									<a href="javascript:void(0)" data-href="<?=base_url('admin/Deliverycharge/chargesStatus')?>" class="badge badge-secondary changestatus" data-status="<?=$charges->status?>"  data-id="<?=$charges->id?>" data-toggle="tooltip" data-placement="top" title="Change Status"><i class="fa fa-toggle-<?=$charges->status == '1'?'on':'off'?> fa-lg"></i></a>
								</td>
							</tr>
							<?php 
							$i++; 
						}
					} else{
						echo '<tr class="text-center"><td colspan=10>No record found</td></tr>';
					} ?>
				</tbody>
			</table>
			<p><?php echo $links; ?></p>
		</div>
	</div>
</div>
