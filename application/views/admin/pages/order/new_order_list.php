<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title"><h4>New Order List</h4></div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">New Order List</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right"></div>
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
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th scope="col">Order #</th>
						<th scope="col">Merchant</th>
						<th scope="col">Name</th>
						<th scope="col">Order Type</th>
						<th scope="col">Total</th>
						<th scope="col">Date</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(isset($orders) && count($orders)>0){
						foreach($orders as $order){
						
							$status ='';
							$st = $order->status;
						
							if($st==0 && $order->payment_type==2)
							{
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
							?>
							<tr>
								<th scope="row"><?php echo $order->order_id; ?></th>
								<td><?=$order->m[0]->merchant_name?></td>
								<td><?=!empty($order->u[0]->name)?$order->u[0]->name:$order->guest_name.'<br>(Guest)'?></td>
								<td><?=$order_type;?></td>
								<td><?=CURRENCY.' '.number_format($order->grand_total,2);?></td>
								<td><?=date('D j, Y, g:i a',$order->added_date_timestamp/1000)?></td>
								<td class="status_<?php echo $order->order_id; ?>" ><?php echo $status;?></td>
								<td>
								<?php 
								if($order->settled==0 && $this->session->userdata('role_master_tbl_id')==2){ ?>
									<a href="javascript:void(0)" class=" change-order-status" current-status="3"  data-id="<?=$order->order_id?>" data-toggle="tooltip" user-id="<?=$order->user_id?>" data-order-type="<?php echo $order->order_type;?>" data-placement="top" title="Accept"><span class="badge badge-primary">Accept</span></a> |
									
									<a href="javascript:void(0)" class=" newOrderReject" data-status="4"  data-id="<?=$order->order_id?>" data-toggle="tooltip" data-placement="top" user-id="<?=$order->user_id?>" title="Reject"><span class="badge badge-danger">Reject</span></a> |
									<?php 
								} ?>
									<a href="javascript:void(0)" data-id="<?=$order->order_id?>" user-id="<?=$order->user_id?>" restaurant-id="<?=$order->restaurant_id?>"  class="badge badge-secondary view-order" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
								</td>
							</tr>
							<?php 
						}
					} else{
						echo '<tr class="text-center"><td colspan=15>No record found</td></tr>';
					} ?>
				</tbody>
			</table>
			<p><?php echo $links; ?></p>
		</div>
	</div>

	</div>

	<div class="modal" id="changeStatusModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Accepted for</h5>
					<button type="button" class="close align-center" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="form-box">
						<input type='hidden' name='order_id' id="order_id" value=''>
						<input type='hidden' name='user_id' id="user_id" value=''>
						<input type='hidden' name='order_type' id="order_type" value=''>
						<input type='hidden' name='popup_on' id="popup_on" value='new_order'>

						<div class="col-md-12 form-group">
							<select name="new_status" id="new_status" class="form-control">
								<option value="3" selected>Accept</option>
							</select>
							<span class="error has-danger new_status_error"></span>
						</div>
						
						<div class="col-md-12 form-group toggleTime">
							<input type='text' name='delivery_time' id="delivery_time" value="<?php echo date('h:m A',time()); ?>" class="form-control time-picker">
							<span class="error has-danger delivery_time_error"></span>
						</div>

						<div class="col-md-12 form-group">
							<select name="driver[]" name="driver" id="driver" class="form-control driverDropdown  selectpicker" multiple style="display:none">
								<option value="" disabled>Select Driver</option>
							</select>
						</div>

						<div class="col-md-12 form-group">
							<textarea name="order_remark" id="order_remark" class='form-control' placeholder="Remark" rows=2 style="height:60px"></textarea>
						</div>
						<br>
						<div class="search-icon">
							<button type="button" class="btn btn-success submitStatus">Submit</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<!-- Order detail view-->
<div class="modal fade" id="viewOrderDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <span class="appendOrderDetail"></span>
</div>
