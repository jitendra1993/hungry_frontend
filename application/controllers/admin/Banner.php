<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banner extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/banner_model');
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
		redirect('admin/banner/view');
	}
		
	public function bannerView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/banner/view";
		$config["total_rows"] = $this->banner_model->getCountBanner($filter);
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
	
		$view['page_title']='Banner';
		$view['main_content']='admin/pages/banner/banner_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['banners'] = $this->banner_model->getMasterBanner($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
		
	public function bannerAdd($id=0){
		
		$view['page_title']='Banner';
		$view['main_content']='admin/pages/banner/banner_add';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$banner_info = $this->banner_model->getBannerById($id);
			$view['banner'] = $banner_info;
		}
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('name', 'Banner Name', 'trim|required|min_length[2]');
			if (empty($_FILES['image']['name']) && $this->input->post('img_status')==1)
			{
				$this->form_validation->set_rules('image', 'Banner image', 'required');
			}

			
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				
				$this->load->library('upload');
				$file_name = '';
				$old_img = $this->input->post('old_img');	  
				$img_status = $this->input->post('img_status');	  
				
				if (!empty($_FILES['image']['name']))
				{
					$config['upload_path']   = './uploads/files/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
					$config['max_size']      = 1024;
					$config['file_ext_tolower']   = TRUE;
					$config['encrypt_name']   = TRUE;
					$config['remove_spaces']   = TRUE;
					$config['detect_mime']   = TRUE;
					 $this->upload->initialize($config);
					
					if (!$this->upload->do_upload('image'))
					{
						$file_error  = $this->upload->display_errors('<span>', '</span>');
						$view['error'] = $file_error;
					}else{
						$img = $this->upload->data();
						$file_name = $img['file_name'];
					}
					
					$path = BASEPATH.'../uploads/files/'.$old_img; 
					if(is_file($path))
					{
						unlink($path);
					}
				}else{
					
				}
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				if(!$file_error){
					$data = array(
						'id' =>$hashUnique,
						'name' => htmlspecialchars(strip_tags($this->input->post('name'))),
						'alt_text' => htmlspecialchars(strip_tags($this->input->post('alt_text'))),
						'description' =>  htmlspecialchars(strip_tags($this->input->post('description'))),
						'sort_order' => (int)htmlspecialchars(strip_tags($this->input->post('sort_order'))),
						'status' => (int)htmlspecialchars(strip_tags($this->input->post('status'))),
						'image' => htmlspecialchars(strip_tags($this->input->post('old_img'))),
						'user_hash_id' => $user_id,
						'added_date'=> date('d-m-Y H:i:s'),
						'updated_date'=>date('d-m-Y H:i:s'),
						'added_date_timestamp'=>time()*1000,
						'updated_date_timestamp'=>time()*1000,
						'added_date_iso'=>$date_created,
						'updated_date_iso'=>$date_created
					);
					
					if(!empty($file_name)){
						$data['image']=$file_name;
					}
					
					
					
					if($img_status && empty($file_name)){
					 $path = BASEPATH.'../uploads/files/'.$old_img; 
						if(is_file($path)){
						unlink($path);
						}
						$data['image']='';
					}
					
					
					if ($this->session->userdata('role_master_tbl_id')==3) {
						$data['user_id']= $user_id;
					}
					
					if(!empty($id)){
						unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id']);
						$result = $this->banner_model->updateBanner($data,$id);
						$this->session->set_flashdata('msg_success', 'You have updated banner successfully!');
						redirect(base_url('admin/banner/view'));
					}else{
						$result = $this->banner_model->addBanner($data);
						if($result){
							$this->session->set_flashdata('msg_success', 'You have added banner successfully!');
							redirect(base_url('admin/banner/view'));
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
		
	public function bannerDelete(){
		
		 $id = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$data = array(
			'status' => 2
		);
		
			
		if($id > 0){
			$this->banner_model->deleteBanner($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Banner deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Banner you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
	public function bannerStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'Banner successfully deactivated.':'Banner successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->banner_model->deleteBanner($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Banner does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
		
	}