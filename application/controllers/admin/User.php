<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/user_model');
		$this->load->model('admin/store_model');
		$this->load->model('admin_model');
		if (!$this->session->userdata('user_id')) {
			redirect('admin'); 
		}
		$this->redirection();
	}

	public function redirection(){
		if(!is_logged_in()) 
		{
			redirect('admin/login'); 
		}
		else if(is_logged_in() && (is_user_type()=='user' || is_user_type()=='driver')) 
		{
			redirect(base_url()); 
		}
	}
	
	public function index(){
		redirect('admin/user/view');
	}
	
	public function userView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/user/view";
		$config["total_rows"] = $this->user_model->getCountUser($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
		$config['use_page_numbers'] = TRUE;
		if(!empty($_GET))
		{
			$config['suffix'] = '?'.http_build_query($_GET, '', "&");
		}
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
	
		$view['page_title']='Table Booking';
		$view['main_content']='admin/pages/user/user_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['users'] = $this->user_model->getMasterUser($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function changeuserstatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'User successfully de-activated.':'User successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->user_model->updateUser($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			changeUserStatusMail($id,$status==1?0:1);
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'User does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function driverAdd($id=''){
		
		$view['getState'] = array();
		$view['getCity'] = array();
		$view['page_title']='Add Driver';
		$view['main_content']='admin/pages/user/addDriver';
		$user_id = $this->session->userdata('user_id');
		$driver = $this->user_model->getUserById($id);
		$view['driver'] = $driver;
		if(isset($driver) && !empty($driver->country_id) && $driver->country_id){
			$condition = array('country_id'=>$driver->country_id);
			$view['getState'] = $this->store_model->getState($condition);
		}
		if(isset($driver) && !empty($driver->state_id) && $driver->state_id){
			$condition = array('state_id'=>$driver->state_id);
			$view['getCity'] = $this->store_model->getCity($condition);
		}
		$condition = array();
		$getCountry = $this->store_model->getCountry($condition);
		$view['getCountry'] = $getCountry;
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$id = $this->input->post('id');
			$this->form_validation->set_rules('name', 'Driver Name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('mobile', 'Driver mobile', 'trim|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('pincode', 'Pincode', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|valid_email');
			if(empty($id)){
				$this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[3]');
				$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|min_length[3]|matches[password]');
			}
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$password = $this->input->post('password');
				$username = $this->input->post('username');	
				if(empty($id)){
					$check_status = $this->admin_model->checkMailExist($username);
				}else{
					$user_tbl_id = $id;
					$check_status = $this->store_model->checkMailExistOnUpdt($username,$user_tbl_id);
				}
				if($check_status){
					$this->session->set_flashdata('error_msg', 'Email already exist.');
					$this->load->view('admin/template_admin',$view);
				}else{
					$country = explode('^',htmlspecialchars(strip_tags($this->input->post('country'))));
					$state = explode('^',htmlspecialchars(strip_tags($this->input->post('state'))));
					$city = explode('^',htmlspecialchars(strip_tags($this->input->post('city'))));
					$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
					$hashUnique = md5(uniqid(rand(), true));

					$user_master = array(
						'hash'=> $hashUnique,
						'name' =>htmlspecialchars(strip_tags($this->input->post("name"))),
						'email' =>htmlspecialchars(strip_tags($this->input->post("username"))),
						'password' =>password_hash($password,PASSWORD_DEFAULT),
						'mobile' =>$this->input->post("mobile"),
						'role_master_tbl_id' =>(int)3,
						'country' => $country[0],
						'country_id' => (int)$country[1],
						'state' => $state[0],
						'state_id' => (int)$state[1],
						'city' => $city[0],
						'city_id' => (int)$city[1],
						'address' => htmlspecialchars(strip_tags($this->input->post('address'))),
						'pincode' => htmlspecialchars(strip_tags($this->input->post('pincode'))),
						'cordinate'=>array('latitude'=>'','longitude'=>''),
						'status' =>(int)$this->input->post("status"),
						'is_online' =>(int)$this->input->post("is_online"),
						"mail_status"  =>1,
						"mobile_status"  => 1,
						"added_by_role"  => (int)$this->session->userdata('role_master_tbl_id'),
						"added_by_id"  => $this->session->userdata('user_id'),
						"is_free"  => (int)1,
						'added_date'=> date('d-m-Y H:i:s'),
						'updated_date'=>date('d-m-Y H:i:s'),
						'added_date_timestamp'=>time()*1000,
						'updated_date_timestamp'=>time()*1000,
						'added_date_iso'=>$date_created,
						'updated_date_iso'=>$date_created,
						"verification_token" => 111111,
						"verification_token_time" => (time()*1000)+6000
					);
					
					if(!empty($id)){
						unset($user_master['added_date'],$user_master['added_date_timestamp'],$user_master['added_date_iso'],$user_master['hash'],$user_master['password'],$user_master['role_master_tbl_id'],$user_master['cordinate'],$user_master['added_by_role'],$user_master['added_by_id'],$user_master['is_free']);
						$user_id = $id;
						$result = $this->store_model->updateClient($user_master,$user_id,'hash','user_master');
						$this->session->set_flashdata('msg_success', 'You have updated driver info successfully!');
						redirect(base_url('admin/user/view'));
					}else{
						$this->store_model->addClient($user_master,'user_master');
						$this->session->set_flashdata('msg_success', 'You have added driver info successfully!');
						redirect(base_url('admin/user/view'));
					}
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}

	public function userDetailView(){
		$user_id = $this->input->post('user_id');
		$userDetail = $this->user_model->getUserDetail($user_id);
		?>
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12 form-group p_star">
						<table class="table table-striped table-bordered">
							<tbody>	
								<tr>
									<td class="width-50">Name</td>
									<td class="text-right width-50"><?php echo $userDetail['name'];?></td>
								</tr>
								
								<tr>
									<td class="width-50">Email</td>
									<td class="text-right width-50"><?php echo $userDetail['email'];?></td>
								</tr>
								
								<tr>
									<td class="width-50">Mobile</td>
									<td class="text-right width-50"><?php echo $userDetail['mobile'];?></td>
								</tr>

								<tr>
									<td class="width-50">Role</td>
									<td class="text-right width-50"><?php echo ($userDetail['role_master_tbl_id']==3)?'Driver':'User';?></td>
								</tr>
								<?php
								if(!empty($userDetail['added_by_role']) && $userDetail['role_master_tbl_id']==3){ ?>
									<tr>
										<td class="width-50">Country</td>
										<td class="text-right width-50"><?php echo $userDetail['country'];?></td>
									</tr>

									<tr>
										<td class="width-50">State</td>
										<td class="text-right width-50"><?php echo $userDetail['state'];?></td>
									</tr>

									<tr>
										<td class="width-50">City</td>
										<td class="text-right width-50"><?php echo $userDetail['city'];?></td>
									</tr>

									<tr>
										<td class="width-50">Address</td>
										<td class="text-right width-50"><?php echo $userDetail['address'];?></td>
									</tr>

									<tr>
										<td class="width-50">Pincode</td>
										<td class="text-right width-50"><?php echo $userDetail['pincode'];?></td>
									</tr>
									<?php 
								} ?>
								<tr>
									<td class="width-50">Status</td>
									<td class="text-right width-50"><?php echo ($userDetail['status']==1)?'Active':'Inactive';?></td>
								</tr>

								<tr>
									<td class="width-50">Mail Status</td>
									<td class="text-right width-50"><?php echo ($userDetail['mail_status']==1)?'Verified':'Not Verified';?></td>
								</tr>

								<tr>
									<td class="width-50">Mobile Status</td>
									<td class="text-right width-50"><?php echo ($userDetail['mobile_status']==1)?'Verified':'Not Verified';?></td>
								</tr>
								<?php
								if(!empty($userDetail['added_by_role']) && $userDetail['added_by_role']==1 && $userDetail['role_master_tbl_id']==3){ ?>
									<tr>
										<td class="width-50">Added By</td>
										<td class="text-right width-50"><?php echo 'Admin';?></td>
									</tr>
									<?php
								}
								if( $userDetail['role_master_tbl_id']==3){ ?>
									<tr>
										<td class="width-50">Is Online</td>
										<td class="text-right width-50"><?php echo (!empty($userDetail['is_online']) && $userDetail['is_online']==1)?'Online':'Offline';?></td>
									</tr>
									<?php 
								} 
								if(!empty($userDetail['added_by_role']) && $userDetail['added_by_role']==2 && $userDetail['role_master_tbl_id']==3){
									$merchant = $userDetail['merchant'][0];
									?>
									<tr>
										<td class="width-50">Merchant Name</td>
										<td class="text-right width-50"><?php echo $merchant['merchant_name'];?></td>
									</tr>

									<tr>
										<td class="width-50">Merchant Phone</td>
										<td class="text-right width-50"><?php echo $merchant['merchant_phone'];?></td>
									</tr>

									<tr>
										<td class="width-50">Merchant Email</td>
										<td class="text-right width-50"><?php echo $merchant['contact_email'];?></td>
									</tr>
									<?php 
								}
								?>

								<tr>
									<td  class="width-50">Added Date</td>
									<td class="text-right width-50"><?php echo date('F j, Y, g:i a',$userDetail['added_date_timestamp']/1000);?></td>
								</tr>

								<tr>
									<td  class="width-50">Updated Date</td>
									<td class="text-right width-50"><?php echo date('F j, Y, g:i a',$userDetail['updated_date_timestamp']/1000);?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-12  form-group p_star">
						<button type="button" class="btn-block btn btn-success"  data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>	
		<?php	
	}
	
	
}