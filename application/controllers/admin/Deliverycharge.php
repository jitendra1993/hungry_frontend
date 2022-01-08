<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Deliverycharge extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/deliverycharges_model');
		$this->load->model('admin/setting_model');
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
		redirect('admin/delivery-charges/view');
	}
	
	public function deliveryChargesView(){
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/delivery-charges/view";
		$config["total_rows"] = $this->deliverycharges_model->getCountdeliveryCharges($filter);
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
		$view['main_content']='admin/pages/charges/delivery_charges_view';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['clients'] = $this->deliverycharges_model->getMasterDeliveryCharges($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function deliveryChargesAdd($id=''){
		
		$user_id = $this->session->userdata('user_id');
		$view['page_title']='Store Delivery charges';
		$view['main_content']='admin/pages/charges/delivery_charges';
		
		$charges = $this->deliverycharges_model->getDeliveryChargesById($id);
		$view['delivery_charges'] = $charges;
		$store_setting = $this->setting_model->getStoreSettingById($id);
		$view['store_setting'] = $store_setting;
		if($this->input->method(TRUE) == 'POST'){
			
			$hiddenId = $this->input->post('id');
			$free_delivery_above_price = $this->input->post('free_delivery_above_price');
			
			$shipping_enabled = (!empty($this->input->post("shipping_enabled")))?(int)$this->input->post("shipping_enabled"):0;
			$delivery_type = (!empty($this->input->post("delivery_type")))?(int)$this->input->post("delivery_type"):2;
			$item_variation['distance_from'] = $this->input->post('distance_from');
			$item_variation['distance_to'] =  $this->input->post('distance_to');
			$item_variation['distance_type'] = $this->input->post('distance_type');
			$item_variation['price'] =  $this->input->post('price');
			
			$setting['free_delivery_above_price'] = $free_delivery_above_price;
			$this->setting_model->updateStoreSetting($setting,$id);
		
			
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$hashUnique = md5(uniqid(rand(), true));
			$data = array(
				'id' =>$hashUnique,
				'shipping_enabled' => $shipping_enabled,
				'delivery_type' => $delivery_type,
				'variation' => $item_variation,
				'user_hash_id' => $id,
				'added_date'=> date('d-m-Y H:i:s'),
				'updated_date'=>date('d-m-Y H:i:s'),
				'added_date_timestamp'=>time()*1000,
				'updated_date_timestamp'=>time()*1000,
				'added_date_iso'=>$date_created,
				'updated_date_iso'=>$date_created
			);
			
			if(!empty($hiddenId)){
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
				$result = $this->deliverycharges_model->updateDeliveryCharges($data,$id);
				$this->session->set_flashdata('msg_success', 'You have updated delivery Charges rates successfully!');
				redirect(base_url('admin/delivery-charges/view'));
				
			}else{
				$result = $this->deliverycharges_model->addDeliveryCharges($data);
				if($result){
					$this->session->set_flashdata('msg_success', 'You have added delivery Charges rates successfully!');
					redirect(base_url('admin/delivery-charges/view'));
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}

	public function fixedDeliveryChargesView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/fixed-delivery-charges/view";
		$config["total_rows"] = $this->deliverycharges_model->getCountFixedDeliveryCharges($filter);
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
		//$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config);
	
		$view['page_title']='View Fixed Delivery Charges';
		$view['main_content']='admin/pages/charges/fixed_delivery_charges_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['deliveryCharges'] = $this->deliverycharges_model->getFixedDeliveryCharges($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function fixedDeliveryChargesAdd($id=''){
		
		$view['page_title']='Add Fixed Delivery Charges';
		$view['main_content']='admin/pages/charges/fixed_delivery_charges_add';
		$user_id = $this->session->userdata('user_id');
		
		if(!empty($id)){
			$category_info = $this->deliverycharges_model->getFixedDeliveryChargesById($id);
			$view['deliveryCharges'] = $category_info;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$category_info->user_hash_id) 
				{
					redirect('admin/fixed-delivery-charges//view');
				}
			}
		
		}
	   
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('place', 'Place Name', 'trim|required|min_length[2]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					'id' =>$hashUnique,
					'place' => htmlspecialchars(strip_tags($this->input->post('place'))),
					'cost' => htmlspecialchars(strip_tags($this->input->post('cost'))),
					'status' => (int)$this->input->post('status'),
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
					$result = $this->deliverycharges_model->updateFixedDeliveryCharges($data,$id);
					$this->session->set_flashdata('msg_success', 'You have updated delivery price successfully!');
					redirect(base_url('admin/fixed-delivery-charges/view'));
					
				}else{
					$result = $this->deliverycharges_model->addFixedDeliveryCharges($data);
					if($result){
						$this->session->set_flashdata('msg_success', 'You have added delivery price successfully!');
						redirect(base_url('admin/fixed-delivery-charges/view'));
					}
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	
	public function fixedDelete(){
		
		 $id = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$data = array(
			'status' => 2
		);
		if($id > 0){
			$category_info = $this->deliverycharges_model->getFixedDeliveryChargesById($id);
				$this->deliverycharges_model->deleteFixedDeliveryCharges($data,$id);
				$response = array(
				'status'=>'1',
				'msg'=>'Delivery charges deleted successfully.',
				'redirect'=>'0'
				);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Delivery charges you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function chargesStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'Delivery charges successfully deactivated.':'Delivery charges successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->deliverycharges_model->deleteFixedDeliveryCharges($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Delivery charges does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}