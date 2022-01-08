<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Voucher extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/voucher_model');
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
		redirect('admin/voucher/view');
	}
	
	public function voucherView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/voucher/view";
		$config["total_rows"] = $this->voucher_model->getCountVoucher($filter);
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
	
		$view['page_title']='Voucher List';
		$view['main_content']='admin/pages/voucher/voucher_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['voucher'] = $this->voucher_model->getMasterVouchers($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function voucherAdd($id=''){
		
		$user_id = $this->session->userdata('user_id');
		$view['page_title']='Add Voucher';
		$view['main_content']='admin/pages/voucher/voucher_add';
		
		if ($this->session->userdata('role_master_tbl_id')!=1) {
			redirect('admin/voucher/view');
		}
		
		if(!empty($id)){
			$voucher = $this->voucher_model->getVoucherById($id);
			$view['voucher'] = $voucher;
		}
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('voucher_name', 'Voucher Name', 'trim|required');
			$this->form_validation->set_rules('voucher_code', 'Voucher Code', 'trim|required');
			$this->form_validation->set_rules('voucher_type', 'Voucher Type', 'trim|required');
			$this->form_validation->set_rules('voucher_price', 'Voucher Price', 'trim|required');
			$this->form_validation->set_rules('voucher_min_order', 'Voucher Above', 'trim|required');
			$this->form_validation->set_rules('valid_from', 'Valid From', 'trim|required');
			$this->form_validation->set_rules('valid_to', 'Valid To', 'trim|required');
			$id = $this->input->post('id');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				
				$valid_from = date('Y-m-d',strtotime($this->input->post("valid_from")));
				$valid_to = date('Y-m-d',strtotime($this->input->post("valid_to")));
				$delivery = (!empty($this->input->post("delivery")))?(int)$this->input->post("delivery"):0;
				$pickup = (!empty($this->input->post("pickup")))?(int)$this->input->post("pickup"):0;
				$dinein = (!empty($this->input->post("dinein")))?(int)$this->input->post("dinein"):0;
				$used_once = (!empty($this->input->post("used_once")))?(int)$this->input->post("used_once"):0;
				if($valid_from>$valid_to){
						$this->session->set_flashdata('error_msg', 'Valid from should be less than the valid to date.');
						redirect(base_url('admin/voucher/add'));
						exit;
				}
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					 'id' =>$hashUnique,
					 'voucher_name' =>htmlspecialchars(strip_tags($this->input->post("voucher_name"))),
					 'voucher_code' =>htmlspecialchars(strip_tags($this->input->post("voucher_code"))),
					 'voucher_type' =>$this->input->post("voucher_type"),
					 'voucher_price' =>$this->input->post("voucher_price"),
					 'voucher_min_order' =>$this->input->post("voucher_min_order"),
					 'max_discount' =>htmlspecialchars(strip_tags($this->input->post("max_discount"))),
					 'valid_from' =>$valid_from,
					 'valid_to' =>$valid_to,
					 'valid_from_timestamp' =>strtotime($valid_from)*1000,
					 'valid_to_timestamp' =>strtotime($valid_to)*1000,
					 'delivery' =>$delivery,
					 'pickup' =>$pickup,
					 'dinein' =>$dinein,
					 'used_once' =>$used_once,
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
					$result = $this->voucher_model->updateVoucher($data,$id);
					
					$this->session->set_flashdata('msg_success', 'You have updated voucher successfully!');
					redirect(base_url('admin/voucher/view'));
				}else{
					$bookId = $this->voucher_model->addVoucher($data);
					if($bookId){
						$this->session->set_flashdata('msg_success', 'You have added voucher successfully!');
						redirect(base_url('admin/voucher/view'));
					}
				}
			}
			
		}else{
			$this->load->view('admin/template_admin',$view);
		}
		
	}
	
	public function voucherDelete(){
		
		$id = $this->input->post('id');
		$data = array(
			'status' => 2
		);
		if(!empty($id)){
			$this->voucher_model->deleteVoucher($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'voucher deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'voucher you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function voucherStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'voucher successfully deactivated.':'voucher successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->voucher_model->deleteVoucher($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'voucher does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
}