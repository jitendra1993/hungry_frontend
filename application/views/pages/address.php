<section class="sliderpanel">
</section><!-- end of sliderpanel -->
<div class="home_divider"></div>
<div class="container mt-3 mb-3 link-background ">
    <section class="product_list pt-5">
        <div class="container bg-white">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap text-center">
                        <h2>Select address for delivery</h2>
                        <div class="col-md-12 mt-50">
                            <p class="text-center">
                                <a href="javascript:void(0)" id="" class="addUpdateAddress">Click here</a> to add new address
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php 
					if($this->session->flashdata('error_msg')){ ?>
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<?=($this->session->flashdata('error_msg'));?>
						</div>
                    	<?php
					}?>
                    <div class="product_list bg-white">
                        <div class="row">
                            <?php
							if(!empty($address) && is_array($address) && count($address)>0){ 
								foreach($address as $data){
								?>
                            <div class="col-md-4">
                                <div
                                    class="row col-md-12 p-3 pt-0 bg-light border-bottom border-right remove_<?php echo $data['hash'];?>">
                                    <div class="col-md-6 mb-2"><b>Name</b></div>
                                    <div class="col-md-6 mb-2 name_<?php echo $data['hash'];?>"><?php echo $data['name']; ?></div>

                                    <div class="col-md-6 mb-2"><b>Address Type</b></div>
                                    <div class="col-md-6 mb-2 add_type_<?php echo $data['hash'];?>">
                                        <?php echo ($data['addressType']==1)?'Home':'Office'; ?></div>

                                    <div class="col-md-6 mb-2"><b>Phone</b></div>
                                    <div class="col-md-6 mb-2 phone_<?php echo $data['hash'];?>">
                                        <?php echo $data['phoneNumber']; ?></div>

                                    <div class="col-md-6 mb-2"><b>Address Line 1</b></div>
                                    <div class="col-md-6 mb-2 add_line_1_<?php echo $data['hash'];?>">
                                        <?php echo $data['addressLine1']; ?></div>

                                    <div class="col-md-6 mb-2"><b>Address Line 2</b></div>
                                    <div class="col-md-6 mb-2 add_line_2_<?php echo $data['hash'];?>">
                                        <?php echo $data['addressLine2']; ?></div>

                                    <div class="col-md-6 mb-2"><b>Pincode</b></div>
                                    <div class="col-md-6 mb-2 pincode_<?php echo $data['hash'];?>">
                                        <?php echo $data['pincode']; ?></div>

                                    <div class="col-md-6 mb-2"><b>Address Created On</b></div>
                                    <div class="col-md-6 mb-2">
									<?php echo date('F j, Y, g:i a',$data['added_date_timestamp']/1000); ?></div>

                                    <div class="col-md-6 mb-2"><b>Address Updated On</b></div>
                                    <div class="col-md-6 mb-2 ">
									<?php echo date('F j, Y, g:i a',$data['updated_date_timestamp']/1000); ?></div>

                                    <div class="delivery col-md-12 mb-2">
                                        <a href="<?php echo base_url('checkout/'.base64_encode(gzdeflate($data['hash']))); ?>"
                                            class="btn-success btn-sm btn-block col-md-10 text-center">Delivery to this
                                            address</a>
                                    </div>

                                    <div class="col-md-6 mb-2"><a href="javascript:void(0)"
                                            id="<?php echo $data['hash'];?>"
                                            class="btn-warning btn-sm btn-block text-center col-md-6 addUpdateAddress">Edit</a>
                                    </div>
                                    <div class="col-md-6 mb-2"><a href="javascript:void(0)"
                                            id="<?php echo $data['hash'];?>"
                                            class="btn-warning btn-sm btn-block text-center col-md-6 deleteAddress">Delete</a>
                                    </div>

                                </div>
                            </div>
                            <?php
								}
							}
							?>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>