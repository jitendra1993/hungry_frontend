<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/setting_model');
		$this->load->model('admin/store_model');
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
		redirect('admin/setting/merchant-info');
	}
		
	public function merchantInfo(){
		
		if ($this->session->userdata('role_master_tbl_id')!=2) {
			redirect('admin/dashboard'); 
		}
		
		$view['getState'] = array();
		$view['getCity'] = array();
		$view['page_title']='Store Info';
		$view['main_content']='admin/pages/setting/mechant_info';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		$mechant_info = $this->setting_model->getMechantInfoById($user_id);
		$view['merchant_info'] = $mechant_info;
		
		if(isset($mechant_info) && !empty($mechant_info->country_id) && $mechant_info->country_id){
			$condition = array('country_id'=>$mechant_info->country_id);
			$view['getState'] = $this->setting_model->getState($condition);
		}
		if(isset($mechant_info) && !empty($mechant_info->state_id) && $mechant_info->state_id){
			$condition = array('state_id'=>$mechant_info->state_id);
			$view['getCity'] = $this->setting_model->getCity($condition);
		}
		$condition = array();
		$getCountry = $this->setting_model->getCountry($condition);
		$getStoreCategory = $this->store_model->getStoreCategory();
		$view['getStoreCategory'] = $getStoreCategory;
		$view['getCountry'] = $getCountry;
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			
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

			$id = $this->input->post('id');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				
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
						'location' => array('type'=>'Point','coordinates'=> [28.412894,77.311299]),
						'pincode' => htmlspecialchars(strip_tags($this->input->post('pincode'))),
						'about' => htmlspecialchars(strip_tags($this->input->post('about'))),
						'logo' => $this->input->post('old_logo'),
						'favicon' => '',
						'social_media' => $socilaMedia,
						'site_url' => '',
						'mail_logo_url' => $this->input->post('mail_logo_url'),
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
					
					if(!empty($id)){
						unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['user_hash_id']);
						
						$result = $this->setting_model->updateMerchant($data,$id);
						$this->setting_model->createIndex();
						$this->session->set_flashdata('msg_success', 'You have updated store info successfully!');
						redirect(base_url('admin/setting/store-info'));
					}else{
						$result = $this->setting_model->addMerchantInfo($data);
						$this->setting_model->createIndex();
						if($result){
							$this->session->set_flashdata('msg_success', 'You have added store info successfully!');
							redirect(base_url('admin/setting/store-info'));
						}
					}
					
				}else{
					$this->load->view('admin/template_admin',$view);
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
		
	public function settingView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/setting/setting-view";
		$config["total_rows"] = $this->setting_model->getCountSetting($filter);
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
	
		$view['page_title']='Setting';
		$view['main_content']='admin/pages/setting/settingView';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['clients'] = $this->setting_model->getMasterSetting($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
		
	public function storeSetting($id=''){
		$user_id = $this->session->userdata('user_id');
		if ($this->session->userdata('role_master_tbl_id')==2 && $id!=$user_id) {
			redirect('admin/dashboard'); 
		}
		$userRole = $this->setting_model->getUserDetailId($id)->role_master_tbl_id;
	
		$view['getZone'] = array();
		$view['page_title']='Store Setting';
		$view['main_content']='admin/pages/setting/store_setting';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$store_setting = $this->setting_model->getStoreSettingById($id);
		$view['store_setting'] = $store_setting;
		$view['userRole'] = $userRole;
		
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			
			$id = $this->input->post('id');
			$disabled_food_gallery = (!empty($this->input->post("disabled_food_gallery")))?$this->input->post("disabled_food_gallery"):0;
			$merchant_close_store = (!empty($this->input->post("merchant_close_store")))?$this->input->post("merchant_close_store"):0;
			$merchant_disabled_ordering = (!empty($this->input->post("merchant_disabled_ordering")))?$this->input->post("merchant_disabled_ordering"):0;
			$merchant_enabled_voucher = (!empty($this->input->post("merchant_enabled_voucher")))?$this->input->post("merchant_enabled_voucher"):0;
			$merchant_enabled_tip = (!empty($this->input->post("merchant_enabled_tip")))?$this->input->post("merchant_enabled_tip"):0;
			$pre_order = (!empty($this->input->post("pre_order")))?$this->input->post("pre_order"):0;
			$dinein_enable = (!empty($this->input->post("dinein_enable")))?$this->input->post("dinein_enable"):0;
			$dinein_open_table_enable = (!empty($this->input->post("dinein_open_table_enable")))?$this->input->post("dinein_open_table_enable"):0;
			$merchant_show_time = (!empty($this->input->post("merchant_show_time")))?$this->input->post("merchant_show_time"):0;
			$used_admin_driver = (!empty($this->input->post("used_admin_driver")))?$this->input->post("used_admin_driver"):0;
			
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$data = array(
					 'delivery_estimation' =>htmlspecialchars(strip_tags($this->input->post("delivery_estimation"))),
					 'pickup_estimation' =>htmlspecialchars(strip_tags($this->input->post("pickup_estimation"))),
					 'merchant_delivery_coverd' =>htmlspecialchars(strip_tags($this->input->post("merchant_delivery_coverd"))),
					 'merchant_distance_type' =>$this->input->post("merchant_distance_type"),
					 'food_option_not_available' =>$this->input->post("food_option_not_available"),
					 'disabled_food_gallery' =>(int)$disabled_food_gallery,
					 'merchant_tax_number' =>htmlspecialchars(strip_tags($this->input->post("merchant_tax_number"))),
					 'free_delivery_above_price' =>htmlspecialchars(strip_tags($this->input->post("free_delivery_above_price"))),
					 'merchant_close_store' =>(int)$merchant_close_store,
					 'merchant_disabled_ordering' =>(int)$merchant_disabled_ordering,
					 'merchant_close_msg' =>htmlspecialchars(strip_tags($this->input->post("merchant_close_msg"))),
					 'merchant_enabled_voucher' =>(int)$merchant_enabled_voucher,
					 'service_status' =>$this->input->post("service_status"),
					 'merchant_minimum_order_delivery' =>htmlspecialchars(strip_tags($this->input->post("merchant_minimum_order_delivery"))),
					 'merchant_maximum_order_delivery' =>htmlspecialchars(strip_tags($this->input->post("merchant_maximum_order_delivery"))),
					 'merchant_minimum_order_pickup' =>htmlspecialchars(strip_tags($this->input->post("merchant_minimum_order_pickup"))),
					 'merchant_maximum_order_pickup' =>htmlspecialchars(strip_tags($this->input->post("merchant_maximum_order_pickup"))),
					 'merchant_minimum_order_dinein' =>htmlspecialchars(strip_tags($this->input->post("merchant_minimum_order_dinein"))),
					 'merchant_maximum_order_dinein' =>htmlspecialchars(strip_tags($this->input->post("merchant_maximum_order_dinein"))),
					 'merchant_packaging_charge' =>htmlspecialchars(strip_tags($this->input->post("merchant_packaging_charge"))),
					 'service_charge' => htmlspecialchars(strip_tags($this->input->post("service_charge"))),
					 'dinein_service_charge' => htmlspecialchars(strip_tags($this->input->post("dinein_service_charge"))),
					 'dinein_enable' =>(int)$dinein_enable,
					 'merchant_delivery_charges' =>htmlspecialchars(strip_tags($this->input->post("merchant_delivery_charges"))),
					 'merchant_enabled_tip' =>(int)$merchant_enabled_tip,
					 'merchant_tip_perchant' =>$this->input->post("merchant_tip_perchant"),
					 'pre_order' =>(int)$pre_order,
					 'dinein_open_table_enable' =>(int)$dinein_open_table_enable,
					 'merchant_show_time' =>(int)$merchant_show_time,
					 'used_admin_driver' =>(int)$used_admin_driver,
					 'restricted_from' => (!empty($this->input->post("restricted_from")) || $this->input->post("restricted_from")!='00:00:00')? date("H:i", strtotime($this->input->post("restricted_from"))):'00:00:00',
					 'restricted_to' => (!empty($this->input->post("restricted_to")) || $this->input->post("restricted_to")!='00:00:00')? date("H:i", strtotime($this->input->post("restricted_to"))):'00:00:00',
					 'store_time'=>[],
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
				 );
					
				
			$open_time_mrng = $this->input->post("open_time_mrng[]");
			$close_time_mrng = $this->input->post("close_time_mrng[]");
			$open_time_evening = $this->input->post("open_time_evening[]");
			$close_time_evening = $this->input->post("close_time_evening[]");
			$day = $this->input->post("day[]");
			$dayMonth = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
			
			$i=0;
			$st_time_arr =[];
			foreach($open_time_mrng as $k=>$v)
			{
				$time = [];
				$time['store_day'] =$dayMonth[$i];
				$time['open_time_mrng'] =!empty($v)?date("H:i", strtotime($v)):'00:00';
				$time['close_time_mrng'] =!empty($close_time_mrng[$k])?date("H:i", strtotime($close_time_mrng[$k])):'00:00';
				$time['open_time_evening'] =!empty($open_time_evening[$k])?date("H:i", strtotime($open_time_evening[$k])):'00:00';
				$time['close_time_evening'] =!empty($close_time_evening[$k])?date("H:i", strtotime($close_time_evening[$k])):'00:00';
				$time['is_open'] = isset($day[$k])?$day[$k]:0;
				$st_time_arr[] = $time;
				$i++;
			}
			$data['store_time'] = $st_time_arr;
			
			if(!empty($id)){
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['user_hash_id']);
				$result = $this->setting_model->updateStoreSetting($data,$id);
					
				$this->session->set_flashdata('msg_success', 'You have updated store setting successfully!');
				redirect(base_url('admin/setting/setting-view'));
			} 
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
		
	public function mailSetting(){
		if ($this->session->userdata('role_master_tbl_id')!=1) {
			redirect('admin/dashboard'); 
		}
		$view['page_title']='Mail Setting';
		$view['main_content']='admin/pages/setting/mail_setting';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		$mail_setting = $this->setting_model->getMailSettingById($user_id);
		$view['mail_setting'] = $mail_setting;
		
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('mail_from_email', 'From Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('mail_from_name', 'From Name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('mail_host', 'Host', 'trim|required|min_length[5]');
			$this->form_validation->set_rules('mail_port', 'Port', 'trim|required|integer');
			$this->form_validation->set_rules('mail_username', 'User Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('mail_password', 'Password', 'trim|required');
			$this->form_validation->set_rules('admin_received_mail', 'Admin Received Email', 'trim|required');
			$this->form_validation->set_rules('admin_received_name', 'Admin Received Name', 'trim|required|min_length[2]');
			$id = $this->input->post('id');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$data = array(
					'mail_from_email' => $this->input->post('mail_from_email'),
					'mail_from_name' => $this->input->post('mail_from_name'),
					'mail_host' =>  $this->input->post('mail_host'),
					'mail_port' => $this->input->post('mail_port'),
					'mail_username' => $this->input->post('mail_username'),
					'mail_password' => $this->input->post('mail_password'),
					'admin_received_mail' => $this->input->post('admin_received_mail'),
					'admin_received_name' => $this->input->post('admin_received_name'),
					'user_hash_id'=>$user_id,
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
				);
				
				if(!empty($id)){
					unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['user_hash_id']);
					$result = $this->setting_model->updateMailSetting($data,$user_id);
					$this->session->set_flashdata('msg_success', 'You have updated mail setting successfully!');
					redirect(base_url('admin/setting/mail-setting'));
				}else{
					$result = $this->setting_model->addMailSetting($data);
					if($result){
						$this->session->set_flashdata('msg_success', 'You have added mail setting successfully!');
						redirect(base_url('admin/setting/mail-setting'));
					}
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
		
	public function smSetting(){
		
		
		$view['page_title']='Social Media Setting';
		$view['main_content']='admin/pages/setting/sm_setting';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		$sm_setting = $this->setting_model->getSMsettingById($user_id);
		$view['sm_setting'] = $sm_setting;
		
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$id = $this->input->post('id');
			$enable_fb_sm = (!empty($this->input->post("enable_fb_sm")))?$this->input->post("enable_fb_sm"):0;
			$enable_google_sm = (!empty($this->input->post("enable_google_sm")))?$this->input->post("enable_google_sm"):0;
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$data = array(
				'enable_fb_sm' => (int)$enable_fb_sm,
				'facebook_app_id' => $this->input->post('facebook_app_id'),
				'facebook_app_secret' =>$this->input->post('facebook_app_secret'),
				'enable_google_sm' => (int)$enable_google_sm,
				'google_client_id' =>  $this->input->post('google_client_id'),
				'google_client_secret' => $this->input->post('google_client_secret'),
				'user_hash_id'=>$user_id,
				'added_date'=> date('d-m-Y H:i:s'),
				'updated_date'=>date('d-m-Y H:i:s'),
				'added_date_timestamp'=>time()*1000,
				'updated_date_timestamp'=>time()*1000,
				'added_date_iso'=>$date_created,
				'updated_date_iso'=>$date_created
			);
			
			
			if(!empty($id)){
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['user_hash_id']);
				$result = $this->setting_model->updateSMsetting($data,$user_id);
				$this->session->set_flashdata('msg_success', 'You have updated social media setting successfully!');
				redirect(base_url('admin/setting/sm-setting'));
			}else{
				$result = $this->setting_model->addSMsetting($data);
				if($result){
					$this->session->set_flashdata('msg_success', 'You have added social media setting successfully!');
					redirect(base_url('admin/setting/sm-setting'));
				}
			}
			
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
		
	}