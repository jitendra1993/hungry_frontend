<div class="container mt-3 mb-3 link-background bg-white">
    <section class="product_list pt-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap text-center">
                        <h2>Order History</h2>
                    </div>
                </div>
            </div>
            <div class="rowdd">
                <div class="col-md-12">
                    <div class="order-list">
                        <div class="row">
                            <div class="table-responsive">
                                <div class="col-md-12 row mb-30">
                                    <form name="filter" id="filter" method="GET"action="<?=base_url('order/history')?>">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <input type="text" name="filter_name"
                                                    value="<?php echo  isset($filters['filter_name'])?$filters['filter_name']:''; ?>"
                                                    placeholder="Search" class="form-control" />
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="filter_from_date"
                                                    value="<?php echo  isset($filters['filter_from_date'])?$filters['filter_from_date']:''; ?>"
                                                    placeholder="From Date" class="form-control datepicker" readonly />
                                            </div>

                                            <div class="col-sm-2">
                                                <input type="text" name="filter_to_date"
                                                    value="<?php echo  isset($filters['filter_to_date'])?$filters['filter_to_date']:''; ?>"
                                                    placeholder="To Date" class="form-control datepicker" readonly />
                                            </div>

                                            <div class="col-sm-3">
                                                <select name="filter_year" id="filter_year" class="form-control">
                                                    <option value="">Select Year</option>
                                                    <?php
														$firstYear = (int)date('Y') - 5;
															
														for($i=date('Y');$i>=$firstYear;$i--)
														{
															$sel = (isset($filters['filter_year']) && $filters['filter_year'] == $i)?'selected':'';
															echo '<option value="'.$i.'"  '.$sel.'>'.$i.'</option>';
														}
														?>
                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <button type="submit" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Order Id</th>
                                            <th scope="col">Placed On</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Payment Type</th>
                                            <th scope="col">Order Type</th>
                                            <th scope="col">Remark</th>
                                            <th scope="col">payment Status</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										if(!empty($orders) && count($orders)>0){
											$i= $start+1;
											foreach($orders as $data){
												$payment_type = $data['payment_type'];
												$payment_id = $data['payment_id'];
												$payment_status = $data['payment_status'];
												$status = $data['status'];
												
												$st ='';
												$pt ='<span class="badge badge-warning">Pending</span>';
												
												if($status==1)
												{
													$st ='<span class="badge badge-primary">Placed</span>';
												}else if($status==2)
												{
													$st ='<span class="badge badge-primary">Placed</span>';
												}else if($status==3)
												{
													$st ='<span class="badge badge-success">Accept</span>';
												}else if($status==4)
												{
													$st ='<span class="badge badge-danger">Rejected</span>';
												}else if($status==5)
												{
													$st ='<span class="badge badge-warning">Pending</span>';
												}else if($status==6)
												{
													$st ='<span class="badge badge-danger">Cancel</span>';
												}else if($status==7)
												{
													$st ='<span class="badge badge-success">Out for delivery</span>';
												}else if($status==8)
												{
													$st ='<span class="badge badge-success">Delivered</span>';
												}else if($status==9)
												{
													$st ='<span class="badge badge-success">Served at Table</span>';
												}
												
												if($payment_status==1)
												{
													$pt ='<span class="badge badge-primary">Success</span>';
												}else if($payment_status==2)
												{
													$pt ='<span class="badge badge-warning">Pending</span>';
												}else if($payment_status==3)
												{
													$pt ='<span class="badge badge-danger">Cancel</span>';
												}else if($payment_status==4)
												{
													$pt ='<span class="badge badge-danger">Decline</span>';
												}
												
												
												if($data['order_type']==1)
												{
													$orderType = 'Collection';
												}else if($data['order_type']==2)
												{
													$orderType = 'Delivery';
												}else if($data['order_type']==3)
												{
													$orderType = 'Dinein';
												}
												?>
												<tr>
													<th scope="row"><?php echo $i;?></th>
													<td><a href="javascript:void(0)" class="order_view"
															data-id="<?php echo $data['order_id']; ?>" restaurant_id="<?php echo $data['restaurant_id']; ?>"><?php echo $data['order_id'];?>
													</td>
													<td><?php echo date('D j, Y, g:i a',$data['added_date_timestamp']/1000); ?></td>
													<td><?php echo CURRENCY. number_format($data['grand_total'],2);?></td>
													<td><?php echo ($data['payment_type']==1)?'Cash':'Online';?></td>
													<td><?php echo $orderType;?></td>
													<td><?php echo $data['payment_remark'];?></td>
													<td><?php echo $pt;?></td>
													<td><?php echo $st;?></td>
												</tr>
                                        		<?php 
												$i++;
											}
										}else{
											echo '<tr><td colspan=8 class="text-center">No record found.</td></tr>';
										}?>
                                    </tbody>
                                </table>
                                <p><?php echo $links; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
