 <div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Sales Report</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Sales Report</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
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
			<form name="filter"id="filter" method="GET" action="<?=base_url('admin/reports/sales')?>">
				<div class="row">

					<?php
					if ($this->session->userdata('role_master_tbl_id')==1) { ?>
						<div class="col-sm-2">
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
					<div class="col-sm-2">
						<input type="text" name="filter_from_date" value="<?php echo  isset($filters['filter_from_date'])?$filters['filter_from_date']:''; ?>" placeholder="From Date"  class="form-control datepicker1" readonly />
					</div>
					<div class="col-sm-2">
						<input type="text" name="filter_to_date" value="<?php echo  isset($filters['filter_to_date'])?$filters['filter_to_date']:''; ?>" placeholder="To Date"  class="form-control datepicker1" readonly />
					</div>
					<div class="col-sm-2">
						<select name="filter_status" id="filter_status" class="form-control">
							<option value="">Select Status</option>
							<option value="1"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '1')?'selected':''?>>New</option>
							<option value="2"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '2')?'selected':''?>>Read</option>
							<option value="3" <?=(isset($filters['filter_status']) && $filters['filter_status'] == '3')?'selected':''?>>Accepted</option>
							<option value="4"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '4')?'selected':''?>>Rejected</option>
							<option value="5"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '5')?'selected':''?>>Pending</option>
							<option value="6"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '6')?'selected':''?>>Cancelled</option>
							<option value="7"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '7')?'selected':''?>>Out for delivery</option>
							<option value="8"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '8')?'selected':''?>>Delivered</option>
							<option value="9"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '9')?'selected':''?>>Served at Table</option>
							<option value="10"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '10')?'selected':''?>>Started</option>
							<option value="11"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '11')?'selected':''?>>In progress</option>
							<option value="12"<?=(isset($filters['filter_status'])  && $filters['filter_status'] == '12')?'selected':''?>>Failed</option>
						</select>
					</div>
					<div class="col-sm-2">
						<select name="filter_order_type" id="filter_order_type" class="form-control" >
						<option value="">Select Type</option>
						<option value="1" <?=(isset($filters['filter_order_type']) && $filters['filter_order_type'] == '1')?'selected':''?>>Collection</option>
						<option value="2" <?=(isset($filters['filter_order_type'])  && $filters['filter_order_type'] == '2')?'selected':''?>>Delivery</option>
						<option value="3" <?=(isset($filters['filter_order_type'])  && $filters['filter_order_type'] == '3')?'selected':''?>>Dinein</option>
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
						<th scope="col">Order #</th>
						<th scope="col">Merchant</th>
						<th scope="col">Name</th>
						<th scope="col" width="20%">Item</th>
						<th scope="col">Order Type</th>
						<th scope="col">Payment Type</th>
						<th scope="col" width="10%">Total</th>
						<th scope="col">Date</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php 
				
					if(isset($orders) && count($orders)>0){
				
						
						foreach($orders as $order){

							$status ='';
							$st = $order->status;
							
							$product = (array)$order->product;
							$product_name ='';
							foreach($product  as $val){
								$val = (array)$val;
								$product_name .= $val['item_name'].', ';
							}
			
							if($st==0 && $order->payment_type==2){

								if($order->payment_status==2)
								{
									$status ='<span class="badge badge-danger">Payment Pending</span>';
								}else if($order->payment_status==3)
								{
									$status ='<span class="badge badge-danger">Payment Cancelled</span>';
								}else if($order->payment_status==4)
								{
									$status ='<span class="badge badge-danger">Payment Decline</span>';
								}
								
							}else if($st==1)
							{
								$status ='<span class="badge badge-primary">New</span>';

							}else if($st==2)
							{
								$status ='<span class="badge badge-primary">Read</span>';
							}else if($st==3)
							{
								$status ='<span class="badge badge-success">Accepted</span>';
							}else if($st==4)
							{
								$status ='<span class="badge badge-danger">Rejected</span>';
							}else if($st==5)
							{
								$status ='<span class="badge badge-warning">pending</span>';
							}else if($st==6)
							{
								$status ='<span class="badge badge-danger">Cancelled</span>';
							}else if($st==7)
							{
								$status ='<span class="badge badge-primary">Out for delivery</span>';
							}else if($st==8)
							{
								$status ='<span class="badge badge-success">Delivered</span>';
							}else if($st==9)
							{
								$status ='<span class="badge badge-success">Served</span>';
							}
					
							if($order->order_type==1)
							{
								$order_type = 'Pickup';
							}else if($order->order_type==2)
							{
								$order_type = 'Delivery';
							}else if($order->order_type==3)
							{
								$order_type = 'Dinein';
							}
				
							$order_id = $order->order_id;
							$merchant_name = $order->m[0]->merchant_name;
							$name = !empty($order->u[0]->name)?$order->u[0]->name:$order->guest_name.'<br>(Guest)';
							$mobile = !empty($order->u[0]->mobile)?$order->u[0]->mobile:$order->guest_phone.'<br>(Guest)';
							$email = !empty($order->u[0]->email)?$order->u[0]->email:$order->guest_email.'<br>(Guest)';
							$item_name = $product_name;
							$payment_type = ($order->payment_type==1)?'Cash':'Online';
							$grand_total = number_format($order->grand_total,2);;
							$added_date = date('Y-m-d',$order->added_date_timestamp/1000);
				
			 				?>
							<tr>
								<th scope="row"><?php echo $order->order_id; ?></th>
								<td><?=$order->m[0]->merchant_name?></td>
								<td><?=!empty($order->u[0]->name)?$order->u[0]->name:$order->guest_name.'<br>(Guest)'?></td>
								<td><?php echo rtrim($product_name,', '); ?></td>
								<td><?=$order_type;?></td>
								<td><?=($order->payment_type==1)?'Cash':'Online';?></td>
								<td><?=CURRENCY.''.number_format($order->grand_total,2);?></td>
								<td><?=date('D j, Y, g:i a',$order->added_date_timestamp/1000)?></td>
								<td class="status_<?php echo $order->order_id; ?>" ><?php echo $status;?></td>
							</tr>
				 			<?php  
						}
				 	} else{
						echo '<tr class="text-center"><td colspan=15>No record found</td></tr>';
					}
					
					$serialize_filters = base64_encode(json_encode($filters));
					?>
				</tbody>
			</table>
		 	<p><?php echo $links; ?></p>
		 	<div class="col-md-6 col-sm-12 text-right">
			 	<?php
				 if(isset($orders) && count($orders)>0){ ?>
				<form method="post" action="<?php echo base_url(); ?>admin/reports/export" target="_blank">
					<input type="hidden" name='filter' id="filter" value="<?php echo $serialize_filters;?>">
					<input type="hidden" name='file_name' id="file_name" value="sale_report">
					<input type='submit' value='Export' name='Export' id="export" class="btn btn-primary exportcsv">
				</form>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
