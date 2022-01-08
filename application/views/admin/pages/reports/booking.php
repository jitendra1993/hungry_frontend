<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Booking Summary Report</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Booking Summary Report</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
		</div>
	</div>
</div>

<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
	<div class="clearfix mb-20">
		<div class="col-md-12 row">
			<h4 class="text-blue h4 ">Search</h4></div>
			<div class="col-md-12 row mb-30">
				<form name="filter"id="filter" method="GET" action="<?=base_url('admin/reports/booking')?>" class="row col-md-12" style="display:block">
					<div class="row">
					<?php
					if ($this->session->userdata('role_master_tbl_id')==1) { ?>
						<div class="col-sm-3">
							<select name="merchant_id" id="merchant_id" class="form-control">
								<option value="">Select Store</option>
								<?php 
								if(isset($stores) && count($stores)>0){
									foreach($stores as $singleStore){
										$sel = (isset($filters['merchant_id'])  && $filters['merchant_id'] == $singleStore['user_hash_id'])?'selected':'';
										echo '<option value="'.$singleStore['user_hash_id'].'"'.$sel.'>'.$singleStore['merchant_name'].'</option>';
									}
								}
								?>
							</select>
						</div>
						<?php
					} ?>
						<div class="col-sm-5 col-md-3">
							<input type="text" name="filter_from_date" value="<?php echo  isset($filters['filter_from_date'])?$filters['filter_from_date']:''; ?>" placeholder="From Date"  class="form-control datepicker1" readonly />
						</div>
					
						<div class="col-sm-5 col-md-3">
							<input type="text" name="filter_to_date" value="<?php echo  isset($filters['filter_to_date'])?$filters['filter_to_date']:''; ?>" placeholder="To Date"  class="form-control datepicker1" readonly />
						</div>
						<div class="col-sm-2 col-md-2">
							<button type="submit" id="button-filter"  class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
						</div>
					</div>
				</form>
				<p class="text-center col-md-12 mt-30">
					<?php 
					if(!isset($filters['filter_from_date']) && !isset($filters['filter_to_date'])){
						echo 'As of '.date('d-M-Y');
					}
					else if(!empty($filters['filter_from_date']) && empty($filters['filter_to_date'])){

						echo 'As of '.date('d-M-Y',strtotime($filters['filter_from_date']));
					}
					else if(empty($filters['filter_from_date']) && !empty($filters['filter_to_date'])){

						echo 'As of '.date('d-M-Y',strtotime($filters['filter_to_date']));
					}
					else if(!empty($filters['filter_from_date']) && !empty($filters['filter_to_date'])){

						echo 'As of '.date('d-M-Y',strtotime($filters['filter_from_date'])).' to '.date('d-M-Y',strtotime($filters['filter_to_date']));
					}
					?>
				</p>
			</div>
		
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th scope="col" width="33%">Merchant Name</th>
							<th scope="col" width="33%">Total Approved</th>
							<th scope="col" width="33%">Total Cancelled</th>
							<th scope="col" width="33%">Total Pending</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(isset($orders) && count($orders)>0){
							foreach($orders as $order){
							
								$merchant = $order['merchant_name'];
								$approved = $order['approved'];
								$cancel = $order['cancel'];
								$pending = $order['pending'];
								?>
								<tr>
									<td><?=$merchant;?></td>
									<th scope="row"><?php echo $approved; ?></th>
									<td><?=$cancel;?></td>
									<td><?=$pending;?></td>
								</tr>
								<?php  
							}
						} else{
							echo '<tr class="text-center"><td colspan=5>No record found</td></tr>';
						}
						$serialize_filters = base64_encode(json_encode($filters));
						?>
					</tbody>
				</table>
				<p><?php echo $links; ?></p>
				<div class="col-md-6 col-sm-12 text-right">
					<?php
					if(isset($orders) && count($orders)>0){ ?>
						<form method="post" action="<?php echo base_url(); ?>admin/reports/exportBookingSummary" target="_blank">
							<input type="hidden" name='filter' id="filter" value="<?php echo $serialize_filters;?>">
							<input type='submit' value='Export' name='Export' id="export" class="btn btn-primary exportcsv">
						</form>
						<?php
					}?>
				</div>
			</div>
		</div>

	</div>
</div>
