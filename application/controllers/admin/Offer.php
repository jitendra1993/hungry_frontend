<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/offer_model');
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
		redirect('admin/offer/view');
	}
	
	public function offerView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/offer/view";
		$config["total_rows"] = $this->offer_model->getCountOffer($filter);
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
	
		$view['page_title']='Offer';
		$view['main_content']='admin/pages/offer/offer_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['offers'] = $this->offer_model->getMasterOffers($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function offerAdd($id=''){
		if ($this->session->userdata('role_master_tbl_id')!=1) {
			redirect('admin/offer/view');
		}
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$offer = $this->offer_model->getOfferById($id);
			$view['offer'] = $offer;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$offer->user_hash_id) 
				{
					redirect('admin/offer/view');
				}
			}
		
		}
		
		$view['page_title']='Add offer';
		$view['main_content']='admin/pages/offer/offer_add';
		
		if($this->input->method(TRUE) == 'POST'){
			
			$this->form_validation->set_rules('discount_type', 'Discount Type', 'trim|required');
			$this->form_validation->set_rules('discount_price', 'Offer Price', 'trim|required');
			$this->form_validation->set_rules('min_order', 'Orders Above', 'trim|required');
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
				if($valid_from>$valid_to){
						$this->session->set_flashdata('error_msg', 'Valid from should be less than the valid to date.');
						redirect(base_url('admin/offer/add'));
						exit;
				}
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					 'id' =>$hashUnique,
					 'discount_type' =>$this->input->post("discount_type"),
					 'discount_price' =>htmlspecialchars(strip_tags($this->input->post("discount_price"))),
					 'min_order' =>htmlspecialchars(strip_tags($this->input->post("min_order"))),
					 'valid_from' =>htmlspecialchars(strip_tags($valid_from)),
					 'valid_to' =>htmlspecialchars(strip_tags($valid_to)),
					 'valid_from_timestamp' =>strtotime($valid_from)*1000,
					 'valid_to_timestamp' =>strtotime($valid_to)*1000,
					 'delivery' =>$delivery,
					 'pickup' =>$pickup,
					 'dinein' =>$dinein,
					 'max_discount' =>$this->input->post("max_discount"),
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
					$result = $this->offer_model->updateOffer($data,$id);
					
					$this->session->set_flashdata('msg_success', 'You have updated offer successfully!');
					redirect(base_url('admin/offer/view'));
				}else{
					
					$bookId = $this->offer_model->addOffer($data);
					if($bookId){
						$this->session->set_flashdata('msg_success', 'You have added offer successfully!');
						redirect(base_url('admin/offer/view'));
					}
				}
			}
			
		}else{
			$this->load->view('admin/template_admin',$view);
		}
		
	}
	
	public function offerDelete(){
		
		$id = $this->input->post('id');
		$data = array(
			'status' => 2
		);
		if(!empty($id)){
			$this->offer_model->deleteOffer($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Offer deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Offer you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function offerStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'Offer successfully deactivated.':'Offer successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->offer_model->deleteOffer($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Offer does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
}