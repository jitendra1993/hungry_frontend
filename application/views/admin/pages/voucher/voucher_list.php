 <div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Vouchers</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Voucher</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/voucher/add')?>" class="btn btn-primary btn-sm" ><i class="fa fa-plus"></i> Add Voucher</a>
		</div>
	</div>
</div>

<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
<?php if($this->session->flashdata('msg_success')){ ?>
  <div class="alert alert-success alert-dismissible"> 
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<?=($this->session->flashdata('msg_success'))?> 
  </div>
  <?php } ?>
<div class="clearfix mb-20">
	 <div class="col-md-12 row">
			<h4 class="text-blue h4 ">Search</h4>
		</div>
	<div class="col-md-12 row mb-30">
		<form name="filter"id="filter" method="GET" action="<?=base_url('admin/voucher/view')?>">
		<div class="row">
		
		 <div class="col-sm-2">
			   <input type="text" name="filter_name" value="<?php echo  isset($filters['filter_name'])?$filters['filter_name']:''; ?>" placeholder="Search"  class="form-control " />
		  </div>
		  
		  <div class="col-sm-2">
			   <input type="text" name="filter_from_date" value="<?php echo  isset($filters['filter_from_date'])?$filters['filter_from_date']:''; ?>" placeholder="From Date"  class="form-control datepicker1" readonly />
		  </div>
	  
		  <div class="col-sm-2">
		 
			  <input type="text" name="filter_to_date" value="<?php echo  isset($filters['filter_to_date'])?$filters['filter_to_date']:''; ?>" placeholder="To Date"  class="form-control datepicker1" readonly />
		  </div>
			
		 <div class="col-sm-3">
			  <select name="filter_status" id="filter_status" class="form-control" >
			  <option value="">Select Status</option>
			  <option value="pending" <?=(isset($filters['filter_status']) && $filters['filter_status'] == 'pending')?'selected':''?>>Pending</option>
			  <option value="publish" <?=(isset($filters['filter_status'])  && $filters['filter_status'] == 'publish')?'selected':''?>>Publish</option>
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
					<th scope="col">Store Name</th>
					<th scope="col">Mobile</th>
					<th scope="col">Name</th>
					<th scope="col">Price</th>
					<th scope="col">Order Above</th>
					<th scope="col">Valid Date</th>
					<th scope="col">Applicable</th>
					<th scope="col">Status</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
			 <?php 
			 $currency = CURRENCY;
				$i= $start+1;
			if(isset($voucher) && count($voucher)>0)
			{
			 foreach($voucher as $singleVoucher){
				
				$status ='';
				$st = $singleVoucher->status;
				if($st==1)
				{
					$status ='<span class="badge badge-success">Active</span>';
				}else if($st==0)
				{
					$status ='<span class="badge badge-danger">Inactive</span>';
				}
				
				$voucher_type= ($singleVoucher->voucher_type==1)?'Flat':'Percent';
				
				$applicable='All';
				if(($singleVoucher->delivery==1 || $singleVoucher->pickup==1 || $singleVoucher->dinein==1) && ($singleVoucher->delivery!=1 || $singleVoucher->pickup!=1 || $singleVoucher->dinein!=1) )
				{
					$applicable = '';
					if($singleVoucher->delivery==1)
					{
						$applicable .= 'Delivery';
					}
					if($singleVoucher->pickup==1)
					{
						$applicable .= ', Pickup';
					}
					if($singleVoucher->dinein==1)
					{
						$applicable .= ', Dinein';
					}
				}
				$applicable = ltrim($applicable,',');
				 ?>
				<tr>
					<th scope="row"><?php echo $i; ?></th>
					<td><?=$singleVoucher->m[0]->merchant_name?></td>
					<td><?=$singleVoucher->m[0]->merchant_phone?></td>
					<td><?=$singleVoucher->voucher_name?></td>
					<td><?=$voucher_type.'@'.$singleVoucher->voucher_price.' '.$currency?></td>
					<td><?=$singleVoucher->voucher_min_order.' '.$currency?></td>
					<td><?=date('d-M-Y',strtotime($singleVoucher->valid_from)).'/'.date('d-M-Y',strtotime($singleVoucher->valid_to))?></td>
					<td><?=$applicable?></td>
					<td class="status_<?php echo $singleVoucher->id; ?>"><?php echo $status;?></td>
					<td>
						<?php
						if ($this->session->userdata('role_master_tbl_id')==1 ){ ?>
							<a href="<?=base_url('admin/voucher/edit/'.$singleVoucher->id)?>"  class="badge badge-secondary" data-toggle="tooltip" data-placement="top"  title="Edit"><i class="fa fa-pencil"></i></a>
					
							<a href="javascript:void(0)" data-href="<?=base_url('admin/voucher/voucherDelete')?>" data-id="<?=$singleVoucher->id?>" class="badge badge-secondary form-delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
						
							<a href="javascript:void(0)" data-href="<?=base_url('admin/voucher/voucherStatus')?>" class="badge badge-secondary changestatus" data-status="<?=$singleVoucher->status?>"  data-id="<?=$singleVoucher->id?>" data-toggle="tooltip" data-placement="top" title="Change Status"><i class="fa fa-toggle-<?=$singleVoucher->status == '1'?'on':'off'?> fa-lg"></i></a>
							<?php
						} ?>
					</td>
				</tr>
				 <?php $i++; }
				 } else{
			echo '<tr class="text-center"><td colspan=12>No record found</td></tr>';
			
		} ?>
			</tbody>
		</table>
		 <p><?php echo $links; ?></p>
	</div>
</div>

</div>
