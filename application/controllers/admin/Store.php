<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Store extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
			
		$this->load->model('admin/store_model');
		$this->load->model('admin_model');
		if (!$this->session->userdata('user_id')) {
			redirect('admin'); 
		}
		if ($this->session->userdata('role_master_tbl_id')!=1) {
			redirect('admin/dashboard'); 
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
		redirect('admin/client/view');
	}
		
	public function view(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/store/view";
		$config["total_rows"] = $this->store_model->getCountStore($filter);
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
	
		$view['page_title']='Store Branch';
		$view['main_content']='admin/pages/store/view';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['clients'] = $this->store_model->getMasterStore($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
		
	public function add($id=''){
		
		$view['getState'] = array();
		$view['getCity'] = array();
		$view['page_title']='Add Store';
		$view['main_content']='admin/pages/store/add';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		$client = $this->store_model->getStoreById($id);
	
		$view['client'] = $client;
		if(isset($client) && !empty($client->info_docs[0]->country_id) && $client->info_docs[0]->country_id){
			$condition = array('country_id'=>$client->info_docs[0]->country_id);
			$view['getState'] = $this->store_model->getState($condition);
		}
		if(isset($client) && !empty($client->info_docs[0]->state_id) && $client->info_docs[0]->state_id){
			$condition = array('state_id'=>$client->info_docs[0]->state_id);
			$view['getCity'] = $this->store_model->getCity($condition);
		}
		$condition = array();
		$getCountry = $this->store_model->getCountry($condition);
		$getStoreCategory = $this->store_model->getStoreCategory();
		
		$view['getCountry'] = $getCountry;
		$view['getStoreCategory'] = $getStoreCategory;
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$id = $this->input->post('id');
			
			$this->form_validation->set_rules('merchant_name', 'Merchant Name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('merchant_phone', 'Merchant phone', 'trim|required');
			$this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('contact_phone', 'Contact phone', 'trim|required');
			$this->form_validation->set_rules('contact_email', 'Contact Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('store_category[]', 'Storecategory', 'trim|required');
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
					$this->load->library('upload');
					$file_name = '';
					$old_logo = $this->input->post('old_logo');	  
					$logo_status = $this->input->post('logo_status');	  
					
					if (!empty($_FILES['logo']['name']))
					{
						$config['upload_path']   = './uploads/client_logo/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
						$config['max_size']      = 1024;
						$config['file_ext_tolower']   = TRUE;
						$config['encrypt_name']   = TRUE;
						$config['remove_spaces']   = TRUE;
						$config['detect_mime']   = TRUE;
						 $this->upload->initialize($config);
						
						if (!$this->upload->do_upload('logo'))
						{
							$file_error  = $this->upload->display_errors('<span>', '</span>');
							$view['error'] = $file_error;
						}else{
							$img = $this->upload->data();
							$file_name = $img['file_name'];
						}
						
						$path = BASEPATH.'../uploads/client_logo/'.$old_logo; 
						if(is_file($path))
						{
							unlink($path);
						}
					}else{
						
					}
					
					if(!$file_error){
						
						$country = explode('^',htmlspecialchars(strip_tags($this->input->post('country'))));
						$state = explode('^',htmlspecialchars(strip_tags($this->input->post('state'))));
						$city = explode('^',htmlspecialchars(strip_tags($this->input->post('city'))));
						
						$store_category = $this->input->post('store_category[]');
						
						$socilaMedia['facebook'] =$this->input->post('facebook');
						$socilaMedia['twitter'] =$this->input->post('twitter');
						$socilaMedia['youtube'] =$this->input->post('youtube');
						$socilaMedia['instagram'] =$this->input->post('instagram');
						$socilaMedia['linkedin'] =$this->input->post('linkedin');
					
						$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
						$hashUnique = md5(uniqid(rand(), true));
						$data = array(
							'user_hash_id' =>$hashUnique,
							'merchant_name' => htmlspecialchars(strip_tags($this->input->post('merchant_name'))),
							'merchant_phone' => htmlspecialchars(strip_tags($this->input->post('merchant_phone'))),
							'contact_name' =>  htmlspecialchars(strip_tags($this->input->post('contact_name'))),
							'contact_phone' => htmlspecialchars(strip_tags($this->input->post('contact_phone'))),
							'contact_email' => htmlspecialchars(strip_tags($this->input->post('contact_email'))),
							'store_category' => $store_category,
							'country' => $country[0],
							'country_id' => (int)$country[1],
							'state' => $state[0],
							'state_id' => (int)$state[1],
							'city' => $city[0],
							'city_id' => (int)$city[1],
							'address' => htmlspecialchars(strip_tags($this->input->post('address'))),
							'pincode' => htmlspecialchars(strip_tags($this->input->post('pincode'))),
							'about' => htmlspecialchars(strip_tags($this->input->post('about'))),
							'logo' => $this->input->post('old_logo'),
							'favicon' => '',
							'social_media' => $socilaMedia,
							'site_url' => '',
							'mail_logo_url' => $this->input->post('mail_logo_url'),
							'added_by' => $user_id,
							'added_date'=> date('d-m-Y H:i:s'),
							'updated_date'=>date('d-m-Y H:i:s'),
							'added_date_timestamp'=>time()*1000,
							'updated_date_timestamp'=>time()*1000,
							'added_date_iso'=>$date_created,
							'updated_date_iso'=>$date_created
						);
						
						if(!empty($file_name)){
							$data['logo']=$file_name;
						}
						
						if($logo_status && empty($file_name)){
						 $path = BASEPATH.'../uploads/client_logo/'.$old_logo; 
							if(is_file($path)){
							unlink($path);
							}
							$data['logo']='';
						}
						
						
						$user_master = array(
							'hash'=> $hashUnique,
							'name' =>htmlspecialchars(strip_tags($this->input->post("contact_name"))),
							'email' =>htmlspecialchars(strip_tags($this->input->post("username"))),
							'password' =>password_hash($password,PASSWORD_DEFAULT),
							'mobile' =>$this->input->post("contact_phone"),
							'role_master_tbl_id' =>(int)2,
							'status' =>(int)$this->input->post("status"),
							"mail_status"  =>1,
							"mobile_status"  => 1,
							'added_date'=> date('d-m-Y H:i:s'),
							'updated_date'=>date('d-m-Y H:i:s'),
							'added_date_timestamp'=>time()*1000,
							'updated_date_timestamp'=>time()*1000,
							'added_date_iso'=>$date_created,
							'updated_date_iso'=>$date_created,
							"verification_token" => 111111,
							"verification_token_time" => (time()*1000)+6000
						);
						
						$setting = array(
							'user_hash_id' =>$hashUnique,
							'added_date'=> date('d-m-Y H:i:s'),
							'updated_date'=>date('d-m-Y H:i:s'),
							'added_date_timestamp'=>time()*1000,
							'updated_date_timestamp'=>time()*1000,
							'added_date_iso'=>$date_created,
							'updated_date_iso'=>$date_created
						);
						
						if(!empty($id)){
							unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['user_hash_id']);
							unset($user_master['added_date'],$user_master['added_date_timestamp'],$user_master['added_date_iso'],$user_master['hash'],$user_master['password'],$user_master['role_master_tbl_id']);
							
							$user_id = $id;
							$result = $this->store_model->updateClient($user_master,$user_id,'hash','user_master');
							$result = $this->store_model->updateClient($data,$id,'user_hash_id','merchant_info_master');
							
							$this->session->set_flashdata('msg_success', 'You have updated store info successfully!');
							redirect(base_url('admin/store/view'));
						}else{
							$this->store_model->addClient($user_master,'user_master');
							$result = $this->store_model->addClient($data,'merchant_info_master');
							$result = $this->store_model->addClient($setting,'store_setting_master');
							$this->session->set_flashdata('msg_success', 'You have added store info successfully!');
							redirect(base_url('admin/store/view'));
						}
					}else{
						$this->load->view('admin/template_admin',$view);
					}
					
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
		
	public function getState(){
		 $id = $this->input->post('id');
		if($id > 0){
			$condition = array('country_id'=>$id);
			$getState = $this->setting_model->getState($condition);
			$html ='<select class="custom-select2 form-control state" name="state" id="state"><option value="">State</option>';
			foreach($getState as $state)
			{
				$html .='<option value="'.$state['name'].'^'.$state['id'].'" data-id="'.$state['id'].'">'.$state['name'].'</option>';
			}
			$html .='</select>';
			echo $html;
		}
	}
		
	public function getCity(){
		$id = $this->input->post('id');
		
		if($id > 0){
			$condition = array('state_id'=>$id);
			$getCity = $this->setting_model->getCity($condition);
			$html ='<select class="custom-select2 form-control city" name="city" id="city"><option value="">City</option>';
			foreach($getCity as $city)
			{
				$html .='<option value="'.$city['name'].'^'.$city['id'].'" data-id="'.$city['id'].'">'.$city['name'].'</option>';
			}
			$html .='</select>';
			echo $html;
			exit;
		}
	}
		
	public function clientDelete(){
		 $id = $this->input->post('id');
		$data = array(
			'status' => 2
		);
		if(!empty($id)){
			$this->store_model->updateClient($data,$id,'hash','user_master');
			$response = array(
			'status'=>'1',
			'msg'=>'Store deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Store you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function clientStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'Client successfully deactivated.':'Client successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if($id > 0){
			$info = $this->client_model->getClientById($id);
			if($info->added_by==$user_id){
				$this->client_model->updateClient($data,$id);
				$response = array(
				'status'=>'1',
				'msg'=>$msg,
				'redirect'=>'0'
				);
			}
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Branch does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
	}