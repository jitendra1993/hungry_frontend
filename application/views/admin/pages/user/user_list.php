<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>User List</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">User List</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
			<a href="<?=base_url('admin/driver/add')?>" class="btn btn-primary btn-sm" > Add Driver</a>&nbsp;&nbsp;
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
	 	<div class="col-md-12 row"><h4 class="text-blue h4 ">Search</h4></div>
		<div class="col-md-12 row mb-30">
			<form name="filter"id="filter" method="GET" action="<?=base_url('admin/user/view')?>">
				<div class="row">
					<div class="col-sm-2">
						<input type="text" name="filter_name" value="<?php echo  isset($filters['filter_name'])?$filters['filter_name']:''; ?>" placeholder="Search"  class="form-control" />
					</div>
	  
					<div class="col-sm-2">
						<input type="text" name="filter_from_date" value="<?php echo  isset($filters['filter_from_date'])?$filters['filter_from_date']:''; ?>" placeholder="From Date"  class="form-control datepicker1" readonly />
					</div>
	  
					<div class="col-sm-2">
						<input type="text" name="filter_to_date" value="<?php echo  isset($filters['filter_to_date'])?$filters['filter_to_date']:''; ?>" placeholder="To Date"  class="form-control datepicker1" readonly />
					</div>
					<?php
					if ($this->session->userdata('role_master_tbl_id')==1) { ?>
						<div class="col-sm-2">
							<select name="filter_user_type" id="filter_user_type" class="form-control" >
								<option value="">Select User Type</option>
								<?php 
								foreach(role_master as $key=>$value){
									$sel = (isset($filters['filter_user_type']) && $filters['filter_user_type'] == $key)?'selected':'';
									echo '<option value="'.$key.'"'.$sel.'>'.$value.'</option>';
								}	
								?>
							</select>
						</div> 
						<?php
					} ?>
					
			
					<div class="col-sm-2">
						<select name="filter_status" id="filter_status" class="form-control" >
						<option value="">Select Status</option>
						<option value="active" <?=(isset($filters['filter_status']) && $filters['filter_status'] == 'active')?'selected':''?>>Active</option>
						<option value="inactive" <?=(isset($filters['filter_status'])  && $filters['filter_status'] == 'inactive')?'selected':''?>>In-Active</option>
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
						<th scope="col">Email</th>
						<th scope="col">Mobile</th>
						<th scope="col">Type</th>
						<th scope="col">Date</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if(isset($users) && count($users)>0){
						// echo '<pre>';
						// print_r($users);
						// echo '</pre>';
						$i = 1+$start;
						foreach($users as $user){
							$status ='';
							$role_master_tbl_id = $user->role_master_tbl_id;
							$st = $user->status;
							if($st==1)
							{
								$status ='<span class="badge badge-success">Active</span>';
							}else if($st==0)
							{
								$status ='<span class="badge badge-danger">Inactive</span>';
							}
							$role = '';
							if($role_master_tbl_id==1)
							{
								$role ='<span class="badge badge-success">SuperAdmin</span>';
							}else if($role_master_tbl_id==2)
							{
								$role ='<span class="badge badge-success">Seller</span>';
							}else if($role_master_tbl_id==3)
							{
								$role ='<span class="badge badge-success">Driver</span>';
							}
							else if($role_master_tbl_id==4)
							{
								$role ='<span class="badge badge-success">User</span>';
							}
				
							?>
							<tr>
								<th scope="row"><?php echo $i; ?></th>
								<td><?=$user->name?></td>
								<td><?=$user->email?></td>
								<td><?=$user->mobile?></td>
								<td><?=$role?></td>
								<td><?=date('D j, Y, g:i a',$user->added_date_timestamp/1000)?></td>
								<td class="status_<?php echo $user->hash; ?>" ><?php echo $status;?></td>
								<td>
									<?php
									if($user->role_master_tbl_id==3 ){ 
										if(($this->session->userdata('role_master_tbl_id')==2 &&  $this->session->userdata('user_id')==$user->added_by_id) || $this->session->userdata('role_master_tbl_id')==1){ ?>
											<a href="<?=base_url('admin/driver/edit/'.$user->hash)?>"  class="badge badge-secondary" data-toggle="tooltip" data-placement="top"  title="Edit"><i class="fa fa-pencil"></i></a>
											<?php
										}
									}
									if(($this->session->userdata('role_master_tbl_id')==2 &&  $this->session->userdata('user_id')==$user->added_by_id) || $this->session->userdata('role_master_tbl_id')==1){ ?>
										<a href="javascript:void(0)" data-href="<?=base_url('admin/user/changeuserstatus')?>" class="badge badge-secondary changestatus" data-status="<?=$user->status?>"  data-id="<?=$user->hash?>" data-toggle="tooltip" data-placement="top" title="Change Status"><i class="fa fa-toggle-<?=$user->status == 1?'on':'off'?> fa-lg"></i></a>
										<?php
									}
									?>
									<a href="javascript:void(0)" user-id="<?=$user->hash?>" class="badge badge-secondary view-user-detail" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
								</td>
							</tr>
							<?php  
							$i++;
						}
					} else{
						echo '<tr class="text-center"><td colspan=12>No record found</td></tr>';
					} ?>
				</tbody>
			</table>
			<p><?php echo $links; ?></p>
		</div>
	</div>
</div>


<div class="modal fade" id="viewOrderDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <span class="appendOrderDetail"></span>
</div>