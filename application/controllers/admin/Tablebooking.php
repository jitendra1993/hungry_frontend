<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tablebooking extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/tablebooking_model');
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
		redirect('admin/tablebooking/view');
	}
	
	public function tableBookingSettingView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(5))? $this->uri->segment(5) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/tablebooking/setting/view";
		$config["total_rows"] = $this->tablebooking_model->getCountBookingSetting($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 5;
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
		$view['main_content']='admin/pages/tablebooking/tablebooking_setting_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= (int)$rowno;
		$data['limit'] = (int)$rowperpage; 
		$data['start'] =(int)$rowno; 
		$view['table_booking_setting'] = $this->tablebooking_model->getMasterBookingSetting($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function tableBookingSettingEdit($id=''){
		
		$user_id = $this->session->userdata('user_id');
		if(empty($id)){
			redirect('admin/tablebooking/setting/view'); 
		}
		
		if(!empty($id)){
			$setting = $this->tablebooking_model->getTableBookingSettingById($id);
			$view['tablebooking_setting'] = $setting;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$setting->user_hash_id) 
				{
					redirect('admin/tablebooking/setting/view');
				}
			}
		
		}
		$view['page_title']='Table booking setting';
		$view['main_content']='admin/pages/tablebooking/tablebooking_setting';
		
		if($this->input->method(TRUE) == 'POST'){
			
			$id = $this->input->post('id');
			$table_booking_status = (!empty($this->input->post("table_booking_status")))?(int)$this->input->post("table_booking_status"):0;
			$accept_booking_sameday = (!empty($this->input->post("accept_booking_sameday")))?(int)$this->input->post("accept_booking_sameday"):0;
			$merchant_booking_alert = (!empty($this->input->post("merchant_booking_alert")))?(int)$this->input->post("merchant_booking_alert"):0;
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$hashUnique = md5(uniqid(rand(), true));
			$data = array(
					'id' =>$hashUnique,
					'max_table_monday' =>htmlspecialchars(strip_tags($this->input->post("max_table_monday"))),
					'max_table_tuesday' =>htmlspecialchars(strip_tags($this->input->post("max_table_tuesday"))),
					'max_table_wednesday' =>htmlspecialchars(strip_tags($this->input->post("max_table_wednesday"))),
					'max_table_thursday' =>htmlspecialchars(strip_tags($this->input->post("max_table_thursday"))),
					'max_table_friday' =>htmlspecialchars(strip_tags($this->input->post("max_table_friday"))),
					'max_table_saturday' =>htmlspecialchars(strip_tags($this->input->post("max_table_saturday"))),
					'max_table_sunday' =>htmlspecialchars(strip_tags($this->input->post("max_table_sunday"))),
					'fully_booked_msg' =>htmlspecialchars(strip_tags($this->input->post("fully_booked_msg"))),
					'booking_receiver_mail' =>htmlspecialchars(strip_tags($this->input->post("booking_receiver_mail"))),
					'table_booking_status' =>$table_booking_status,
					'accept_booking_sameday' =>$accept_booking_sameday,
					'merchant_booking_alert' =>$merchant_booking_alert,
					'user_hash_id' => $id,
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
					 );
			if(!empty($id)){
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
				$result = $this->tablebooking_model->updateTableBookingSetting($data,$id);
					
				$this->session->set_flashdata('msg_success', 'You have updated table booking setting successfully!');
				redirect(base_url('admin/tablebooking/setting/view'));
			}else{
				$storeId = $this->tablebooking_model->addTableBookingSetting($data);
				if($storeId){
					$this->session->set_flashdata('msg_success', 'You have added table booking setting successfully!');
					redirect(base_url('admin/tablebooking/setting/view'));
				}
			}
		}else{
			$this->load->view('admin/template_admin',$view);
		}
		
	}
	
	public function tableBookingView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/tablebooking/view";
		$config["total_rows"] = $this->tablebooking_model->getCountBooking($filter);
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
		$view['main_content']='admin/pages/tablebooking/tablebooking_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['table_booking'] = $this->tablebooking_model->getMasterBooking($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
		
	public function tableBookingAdd($id=''){
		
		if ($this->session->userdata('role_master_tbl_id')==1) {
			redirect('admin/tablebooking/view'); 
		}
		$user_id = $this->session->userdata('user_id');
		
		// if(empty($id)){
			// redirect('admin/tablebooking/view'); 
		// }
		$view['page_title']='Table Booking';
		$view['main_content']='admin/pages/tablebooking/tablebooking_add';
		
		if(!empty($id)){
			$tablebooking = $this->tablebooking_model->getTableBookingById($id);
			$view['tablebooking'] = $tablebooking;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$tablebooking->user_hash_id) 
				{
					redirect('admin/tablebooking/view');
				}
			}
		
		}
		$tablebooking_setting = $this->tablebooking_model->getTableBookingSettingById($user_id);
		$accept_booking_sameday = $tablebooking_setting->accept_booking_sameday;
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('number_guest', 'Number Of Guests', 'trim|required|min_length[1]|integer');
			$this->form_validation->set_rules('booking_date', 'Date Of Booking', 'trim|required');
			$this->form_validation->set_rules('booking_time', 'Time Of Booking', 'trim|required');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer');
			$id = $this->input->post('id');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					 'id' =>$hashUnique,
					 'number_guest' =>htmlspecialchars(strip_tags($this->input->post("number_guest"))),
					 'booking_date' =>htmlspecialchars(strip_tags(date("Y-m-d", strtotime($this->input->post("booking_date"))))),
					 'booking_timestamp' =>strtotime($this->input->post("booking_date"))*1000,
					 'booking_time' =>date("H:i", strtotime($this->input->post("booking_time"))),
					 'name' =>htmlspecialchars(strip_tags($this->input->post("name"))),
					 'email' =>htmlspecialchars(strip_tags($this->input->post("email"))),
					 'mobile' =>htmlspecialchars(strip_tags($this->input->post("mobile"))),
					 'booking_notes' =>htmlspecialchars(strip_tags($this->input->post("booking_notes"))),
					 'email_message' =>htmlspecialchars(strip_tags($this->input->post("email_message"))),
					 'status' =>(int)$this->input->post("status"),
					 'user_hash_id' => $user_id,
					 'added_date'=> date('d-m-Y H:i:s'),
					 'updated_date'=>date('d-m-Y H:i:s'),
					 'added_date_timestamp'=>time()*1000,
					 'updated_date_timestamp'=>time()*1000,
					 'added_date_iso'=>$date_created,
					 'updated_date_iso'=>$date_created
					 );
				if(!empty($id)){
					unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
					$result = $this->tablebooking_model->updateTableBooking($data,$id);
					
					if($this->input->post("status")==1 || $this->input->post("status")==3){
						//confirmation mail to customer
						tableBookingConfirm($data);
						
					}
					
					$this->session->set_flashdata('msg_success', 'You have updated table booking successfully!');
					redirect(base_url('admin/tablebooking/view'));
				}else{
					
					$current_date  = date('Y-m-d',time()); 
					$current_date_time  = date('Y-m-d H:i',time()); 
					
					$booking_date  = date("Y-m-d", strtotime($this->input->post("booking_date"))); 
					$form_date_time = date("Y-m-d", strtotime($this->input->post("booking_date"))).' '.(date("H:i", strtotime($this->input->post("booking_time"))));
					
					if($current_date>$booking_date){
						$this->session->set_flashdata('error_msg', 'Booking date can not be lesser than the current date');
						redirect(base_url('admin/tablebooking/add'));
						exit;
					}else if($accept_booking_sameday==0 && $current_date==$booking_date){
						$this->session->set_flashdata('error_msg', 'Same day Booking is not allowed.');
						redirect(base_url('admin/tablebooking/add'));
						exit;
					}
					else if($accept_booking_sameday==1 && $current_date_time>=$form_date_time){
						$this->session->set_flashdata('error_msg', 'Date and time should be greater than current time and date.');
						redirect(base_url('admin/tablebooking/add'));
						exit;
					}
					$bookId = $this->tablebooking_model->addTableBooking($data);
					if($bookId){
						$this->session->set_flashdata('msg_success', 'You have added table booking successfully!');
						redirect(base_url('admin/tablebooking/view'));
					}
				}
			}
			
		}else{
			$this->load->view('admin/template_admin',$view);
		}
		
	}
		
	public function tableBookingDelete(){
		
		$id = $this->input->post('id');
		$data = array(
			'status' => 2
		);
		if(!empty($id)){
			$booking_info = $this->tablebooking_model->getTableBookingById($id);
			$this->tablebooking_model->deleteBooking($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Table booking deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Booking you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
}