<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StoreCategory extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/storecategory_model');
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
		redirect('admin/store-category/view');
	}
	
	public function storeCategoryView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/store-category/view";
		$config["total_rows"] = $this->storecategory_model->getCountStoreCategory($filter);
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
	
		$view['page_title']='Store Category';
		$view['main_content']='admin/pages/store-category/category_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['categories'] = $this->storecategory_model->getMasterCategory($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function storeCategoryAdd($id=''){
		
		$view['page_title']='Category';
		$view['main_content']='admin/pages/store-category/category_add';
		$view['image'] ='assets/admin/vendors/images/placeholder.png';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$category_info = $this->storecategory_model->getStoreCategoryById($id);
			$view['category'] = $category_info;
		}
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('name', 'Category Name', 'trim|required|min_length[2]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				
				$this->load->library('upload');
				$file_name = '';
				$old_img = $this->input->post('old_img');	  
				$img_status = $this->input->post('img_status');	  
				
				if (!empty($_FILES['image']['name'])){
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
				}
				
				if(!$file_error){
					$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
					$hashUnique = md5(uniqid(rand(), true));
					$data = array(
						'id' =>$hashUnique,
						'name' => htmlspecialchars(strip_tags($this->input->post('name'))),
						'description' =>  htmlspecialchars(strip_tags($this->input->post('description'))),
						'sort_order' =>(int)$this->input->post('sort_order'),
						'status' => (int)$this->input->post('status'),
						'user_hash_id' => $user_id,
						'image'=>$this->input->post('old_img'),
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
					
					if(!empty($id)){
						unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
						$result = $this->storecategory_model->updateStoreCategory($data,$id);
						$this->session->set_flashdata('msg_success', 'You have updated store category successfully!');
						redirect(base_url('admin/store-category/view'));
					}else{
						$result = $this->storecategory_model->addStoreCategory($data);
						if($result){
							$this->session->set_flashdata('msg_success', 'You have added store category successfully!');
							redirect(base_url('admin/store-category/view'));
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
	
	public function storeCategoryDelete(){

		$id = $this->input->post('id');
		$data = array('status' => 2);
		if(!empty($id)){
			$this->storecategory_model->deleteStoreCategory($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Store category deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Store category you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function storeCategoryStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'Store category successfully deactivated.':'Store category successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->storecategory_model->deleteStoreCategory($data,$id);
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