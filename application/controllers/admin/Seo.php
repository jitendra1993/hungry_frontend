<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Seo extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/seo_model');
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
		redirect('admin/seo/view');
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
		$config["base_url"] = base_url() . "admin/seo/view";
		$config["total_rows"] = $this->seo_model->getCountSeopage($filter);
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
	
		$view['page_title']='SEO page';
		$view['main_content']='admin/pages/seo/view';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['seo'] = $this->seo_model->getMasterSeopage($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function add($id=''){
		
		$user_id = $this->session->userdata('user_id');
		$view['page_title']='Add SEO Page';
		$view['main_content']='admin/pages/seo/add';
		$seo = $this->seo_model->getSeopageById($id);
		$view['seo'] = $seo;
		
		if($this->input->method(TRUE) == 'POST'){
			
			$this->form_validation->set_rules('page_name', 'Page name', 'trim|required');
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			$this->form_validation->set_rules('keywords', 'Keywords', 'trim|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			
			$id = $this->input->post('id');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					'id' =>$hashUnique,
					 'page_name' =>htmlspecialchars(strip_tags($this->input->post("page_name"))),
					 'title' =>htmlspecialchars(strip_tags($this->input->post("title"))),
					 'keywords' =>htmlspecialchars(strip_tags($this->input->post("keywords"))),
					 'description' =>htmlspecialchars(strip_tags($this->input->post("description"))),
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
					$result = $this->seo_model->updateSeopage($data,$id);
					
					$this->session->set_flashdata('msg_success', 'You have updated SEO page successfully!');
					redirect(base_url('admin/seo/view'));
				}else{
					
					$bookId = $this->seo_model->addSeopage($data);
					if($bookId){
						$this->session->set_flashdata('msg_success', 'You have added SEO page successfully!');
						redirect(base_url('admin/seo/view'));
					}
				}
			}
			
		}else{
			$this->load->view('admin/template_admin',$view);
		}
		
	}
	
	public function seodelete(){
		$id = $this->input->post('id');
		$data = array('status' => 2);
		if(!empty($id)){
			$this->seo_model->deleteSeopage($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'SEO page deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'SEO page you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function seostatus(){
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'SEO page successfully deactivated.':'SEO page successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if($id > 0){
			$this->seo_model->deleteSeopage($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'SEO page does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
}