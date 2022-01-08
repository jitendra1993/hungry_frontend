 <div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Delivery  Charges</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Delivery  Charges</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
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

  
	
	
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Delivery Type</th>
				<th scope="col">Added Date</th>
				<th scope="col">Shipping Enabled</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i= $start+1;
			if(isset($clients) && count($clients)>0){
				foreach($clients as $client){
					$status ='';
					$delivery_type ='';
					
					if(isset($client->u[0])){
						$st = $client->u[0]->shipping_enabled;
						if($st==1)
						{
							$status ='<span class="badge badge-success">Active</span>';
						}else if($st==0)
						{
							$status ='<span class="badge badge-primary">Inactive</span>';
						}
						
						
						$type = $client->u[0]->delivery_type;
						
						if($type==1)
						{
							$delivery_type ='<span class="badge badge-success">Place</span>';
						}else if($type==2)
						{
							$delivery_type ='<span class="badge badge-success">Postcode</span>';
						}
					}
				
					 ?>
					<tr>
						<th scope="row"><?php echo $i; ?></th>
						<td><?=$delivery_type?></td>
						<td><?=date('d-M-y',$client->added_date_timestamp/1000)?></td>
						<td class="status_<?php echo $client->hash; ?>"><?php echo $status;?></td>
						<td>
							<a href="<?=base_url('admin/delivery-charges/edit/'.$client->hash)?>"  class="badge badge-secondary" data-toggle="tooltip" data-placement="top"  title="Edit"><i class="fa fa-pencil"></i></a>
						</td>
					</tr>
					<?php 
					$i++; 
				}
			} else{
				echo '<tr class="text-center"><td colspan=8>No record found</td></tr>';
			} ?>
		</tbody>
	</table>
	 <p><?php echo $links; ?></p>
	</div>
</div>

</div>
