<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title"><h4>Today's Sales Order List</h4></div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Today's Sales Order List</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<script>
				function printDiv2(divName){
					
					var contents = document.getElementById(divName).innerHTML;
					var frame1 = document.createElement('iframe');
					frame1.name = "frame1";
					frame1.style.position = "absolute";
					frame1.style.top = "-1000000px";
					document.body.appendChild(frame1);
					var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
					frameDoc.document.open();
					frameDoc.document.write('<html><head>');
					frameDoc.document.write('</head><body>');
					frameDoc.document.write(contents);
					frameDoc.document.write('</body></html>');
					frameDoc.document.close();
					setTimeout(function () {
						window.frames["frame1"].focus();
						window.frames["frame1"].print();
						document.body.removeChild(frame1);
					}, 500);
					return false;
				}
			</script>
			<?php
				$print = ' 
				<div class="row">
					<div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<address style="font-size: 12px;font-family: sans-serif;font-style: normal;line-height: 16px;">
									<br><strong>Merchant Name: '.$todaySalesPrint['store_info']['merchant_name'].'</strong>
									<br><strong>Address:</strong>'.$todaySalesPrint['store_info']['address'].'
									<br><strong>Phone</strong>: '.$todaySalesPrint['store_info']['contact_phone'].'
								</address>
							</div>
						</div>
						<hr>
						<table class="table table-hover" style="width: 100%;">
							<thead>
								<tr>
									<th style="text-align: left;font-size: 12px;font-family: sans-serif;">Type</th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center" style="text-align: right;font-size: 12px;font-family: sans-serif;">Qty.</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="3" class="text-right">
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Online Paid:</strong></p>
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Cash:</strong></p>
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Collection :</strong></p>
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Delivery  :</strong></p>
										<p style="font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>Sales Amount  :</strong></p>
									</td>
									
									<td class="text-center">
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.$todaySalesPrint['online'].'</strong></p>
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.$todaySalesPrint['cash'].'</strong></p>
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.$todaySalesPrint['collection'].'</strong></p>
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.$todaySalesPrint['delivery'].'</strong></p>
										<p style="text-align: right;font-size: 12px;font-style: normal;font-family: sans-serif;"><strong>'.CURRENCY .number_format($todaySalesPrint['totalSales'],2).'</strong></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>';
				echo '
				<div style="display:none">
					<div id="printMe2"  style="width: 302px;text-align: center;font-style: normal;border: solid 1px #ccc;padding: 15px;">'.$print.'</div>
				</div>';
			?>
			<a href="javascript:void(0)"  onclick="printDiv2('printMe2')" class="printTodaySales" title="Print"><span class="badge badge-success">Print Today's Sales</span></a>
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
		<?php
		if ($this->session->userdata('role_master_tbl_id')==1) { ?>
			<div class="col-md-12 row">
				<h4 class="text-blue h4 ">Search</h4>
			</div>
			<div class="col-md-12 row mb-30">
				<form name="filter" id="filter" method="GET" action="<?=base_url('admin/order/today-sales')?>">
					<div class="row">
						<div class="col-sm-9">
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
					
						<div class="col-sm-3">
							<button type="submit" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i>Filter</button>
						</div>
					</div>
				</form>
			</div>
			<?php
		} ?>

		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th scope="col">Order #</th>
						<th scope="col">Merchant</th>
						<th scope="col">Name</th>
						<th scope="col">Order Type</th>
						<th scope="col">Payment Type</th>
						<th scope="col">Total</th>
						<th scope="col">Platform</th>
						<th scope="col">Date</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(isset($orders) && count($orders)>0){
						// echo '<pre>';
						// print_r($orders);
						// echo '</pre>';
						foreach($orders as $order){
						
							$status ='';
							$st = $order->status;
						
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
							?>
							<tr>
								<th scope="row"><?php echo $order->order_id; ?></th>
								<td><?=$order->m[0]->merchant_name?></td>
								<td><?=!empty($order->u[0]->name)?$order->u[0]->name:$order->guest_name.'<br>(Guest)'?></td>
								<td><?=$order_type;?></td>
								<td><?=($order->payment_type==1)?'Cash':'Online';?></td>
								<td><?=CURRENCY.' '.number_format($order->grand_total,2);?></td>
								<td><?=$order->platform?></td>
								<td><?=date('D j, Y, g:i a',$order->added_date_timestamp/1000)?></td>
								<td class="status_<?php echo $order->order_id; ?>" ><?php echo $status;?></td>
								<td>
									<a href="javascript:void(0)"  class="printNewOrder"  data-id="<?=$order->order_id?>"  user-id="<?=$order->user_id?>" restaurant-id="<?=$order->restaurant_id?>"  data-toggle="tooltip" data-placement="top" title="Print"><span class="badge badge-success">Print</span></a> |
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


<!-- Order detail view-->
<div class="modal fade" id="viewOrderDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <span class="appendOrderDetail"></span>
</div>