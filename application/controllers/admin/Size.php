<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Size extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/size_model');
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
		redirect('admin/size/view');
	}
		
	public function sizeView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/size/view";
		$config["total_rows"] = $this->size_model->getCountSize($filter);
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
		$view['main_content']='admin/pages/size/size_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['sizes'] = $this->size_model->getMasterSize($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
		
	public function sizeAdd($id=''){
		if ($this->session->userdata('role_master_tbl_id')==1 && $id=='') {
			redirect('admin/size/view');
		}
		$user_id = $this->session->userdata('user_id');
		$view['page_title']='SIze';
		$view['main_content']='admin/pages/size/size_add';
		
		if(!empty($id)){
			$size = $this->size_model->getSizeById($id);
			$view['size'] = $size;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$size->user_hash_id) 
				{
					redirect('admin/size/view');
				}
			}
		
		}
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('size_name', 'Size Name', 'trim|required');
			$id = $this->input->post('id');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					'id' =>$hashUnique,
					'name' =>htmlspecialchars(strip_tags($this->input->post("size_name"))),
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
					$result = $this->size_model->updateSize($data,$id);
					$this->session->set_flashdata('msg_success', 'You have size ingredient successfully!');
					redirect(base_url('admin/size/view'));
				}else{

					$bookId = $this->size_model->addSize($data);
					if($bookId){
						$this->session->set_flashdata('msg_success', 'You have added size successfully!');
						redirect(base_url('admin/size/view'));
					}
				}
			}
			
		}else{
			$this->load->view('admin/template_admin',$view);
		}
		
	}
		
	public function sizeDelete(){
		
		$id = $this->input->post('id');
		$data = array('status' => 2);
		if(!empty($id)){
			$this->size_model->deleteSize($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Size deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Size you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
	public function SizeStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'Size successfully deactivated.':'Size successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->size_model->deleteSize($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Category does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
		
}