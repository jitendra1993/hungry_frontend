<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class User extends CI_Controller {
		public function __construct(){
			parent::__construct();
			
			$this->load->model('admin/user_model');
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
		
	
		
		
	}