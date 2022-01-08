<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Catalog extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/catalog_model');
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
		redirect('admin/catalog/category');
	}
	
	public function categoryView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(5))? $this->uri->segment(5) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/catalog/category/view";
		$config["total_rows"] = $this->catalog_model->getCountMainCategory($filter);
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
		//$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config);
	
		$view['page_title']='Category';
		$view['main_content']='admin/pages/category/category_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['categories'] = $this->catalog_model->getMasterCategory($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
	
	public function categoryAdd($id=''){
		if ($this->session->userdata('role_master_tbl_id')==1 && $id=='') {
			redirect('admin/catalog/category/view');
		}

		$view['page_title']='Category';
		$view['main_content']='admin/pages/category/category_add';
		$view['image'] ='assets/admin/vendors/images/placeholder.png';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$category_info = $this->catalog_model->getMasterCategoryById($id);
			
			$view['category'] = $category_info;
			
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$category_info->user_hash_id) 
				{
					redirect('admin/catalog/category/view');
				}
			}
		
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
						'category_for'=>$this->input->post('category_for'),
						'category_discount' => (!empty($this->input->post("category_discount")))?(int)$this->input->post("category_discount"):0,
						'restricted_category' => (!empty($this->input->post("restricted_category")))?(int)$this->input->post("restricted_category"):0,
						'restricted_with_time' => (!empty($this->input->post("restricted_with_time")))?(int)$this->input->post("restricted_with_time"):0,
						'voucher_discount' => (!empty($this->input->post("voucher_discount")))?(int)$this->input->post("voucher_discount"):0,
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
						$result = $this->catalog_model->updateMasterCategory($data,$id);
						$this->session->set_flashdata('msg_success', 'You have updated category successfully!');
						redirect(base_url('admin/catalog/category/view'));
					}else{
						$result = $this->catalog_model->addMasterCategory($data);
						if($result){
							$this->session->set_flashdata('msg_success', 'You have added category successfully!');
							redirect(base_url('admin/catalog/category/view'));
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
	
	public function categoryDelete(){

		$id = $this->input->post('id');
		$data = array('status' => 2);
		if(!empty($id)){
			$this->catalog_model->deleteMasterCategory($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Category deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Category you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function categoryStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'Category successfully deactivated.':'Category successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->catalog_model->deleteMasterCategory($data,$id);
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
	
	public function subCategoryView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(5))? $this->uri->segment(5) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/catalog/sub-category/view";
		$config["total_rows"] = $this->catalog_model->getCountSubCategory($filter);
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
		//$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config);
	
		$view['page_title']='Category';
		$view['main_content']='admin/pages/category/sub_category_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['subcategories'] = $this->catalog_model->getSubCategory($filter,$data);
		$data1['limit'] = 10000; 
		$data1['start'] =0; 
		$filterCategory = array();
		$view['categories'] = $this->catalog_model->getMasterCategory($filterCategory,$data1);
		
		$this->load->view('admin/template_admin',$view);
	}
	
	public function subCategoryadd($id=''){
		if ($this->session->userdata('role_master_tbl_id')==1 && $id=='') {
			redirect('admin/catalog/sub-category/view');
		}
		$view['page_title']='Sub Category';
		$view['main_content']='admin/pages/category/sub_category_add';
		$view['image'] ='assets/admin/vendors/images/placeholder.png';
		$view['placeholder'] = base_url().'assets/admin/vendors/images/placeholder.png';
		$data1['limit'] = 10000; 
		$data1['start'] =0; 
		$filterCategory = array();
		$view['categories'] = $this->catalog_model->getMasterCategory($filterCategory,$data1);
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$category_info = $this->catalog_model->getSubCategoryById($id);
			$view['subcategory'] = $category_info;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$category_info->user_hash_id) 
				{
					redirect('admin/catalog/sub-category/view');
				}
			}
		}
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('categoryId', 'Master Category', 'trim|required');
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
						'category_id' => htmlspecialchars(strip_tags($this->input->post('categoryId'))),
						'name' => htmlspecialchars(strip_tags($this->input->post('name'))),
						'description' =>  htmlspecialchars(strip_tags($this->input->post('description'))),
						'sort_order' => (int)$this->input->post('sort_order'),
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
						$result = $this->catalog_model->updateSubCategory($data,$id);
						$this->session->set_flashdata('msg_success', 'You have updated sub category successfully!');
						redirect(base_url('admin/catalog/sub-category/view'));
					}else{
						$result = $this->catalog_model->addSubCategory($data);
						if($result){
							$this->session->set_flashdata('msg_success', 'You have added sub category successfully!');
							redirect(base_url('admin/catalog/sub-category/view'));
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
	
	public function subCategoryDelete(){
		
		$id = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$data = array(
			'status' => 2
		);
		if(!empty($id)){
			$this->catalog_model->deleteSubCategory($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Sub category deleted successfully.',
			'redirect'=>'0'
			);

		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Sub category you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function subCategoryStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'Sub category successfully deactivated.':'Sub category successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			
				$this->catalog_model->deleteSubCategory($data,$id);
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
	
	public function addonCategoryView(){
			
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(5))? $this->uri->segment(5) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/catalog/addon-category/view";
		$config["total_rows"] = $this->catalog_model->getCountAddonCategory($filter);
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
		//$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config);
	
		$view['page_title']='Category';
		$view['main_content']='admin/pages/category/addon_category_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['categories'] = $this->catalog_model->getMasterAddOnCategory($filter,$data);
		$this->load->view('admin/template_admin',$view);
	}
		
	public function addonCategoryadd($id=''){
		if ($this->session->userdata('role_master_tbl_id')==1 && $id=='') {
			redirect('admin/catalog/addon-category/view');
		}
		$view['page_title']='Add On Category';
		$view['main_content']='admin/pages/category/addon_category_add';
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$category_info = $this->catalog_model->getMasterAddonCategoryById($id);
			$view['category'] = $category_info;
			
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$category_info->user_hash_id) 
				{
					redirect('admin/catalog/addon-category/view');
				}
			}
			
		}
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('name', 'AddOn Category Name', 'trim|required|min_length[2]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					'id' =>$hashUnique,
					'name' => htmlspecialchars(strip_tags($this->input->post('name'))),
					'description' =>  htmlspecialchars(strip_tags($this->input->post('description'))),
					'sort_order' =>(int) $this->input->post('sort_order'),
					'meal_deal' => (!empty($this->input->post("meal_deal")))?(int)$this->input->post("meal_deal"):0,
					'default_qty' => (!empty($this->input->post("default_qty")))?(int)$this->input->post("default_qty"):0,
					'multiple_meal_deal' => (!empty($this->input->post("multiple_meal_deal")))?(int)$this->input->post("multiple_meal_deal"):0,
					'status' =>(int)$this->input->post('status'),
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
					$result = $this->catalog_model->updateMasterAddonCategory($data,$id,$user_id);
					$this->session->set_flashdata('msg_success', 'You have updated addon category successfully!');
					redirect(base_url('admin/catalog/addon-category/view'));
				}else{
					$result = $this->catalog_model->addMasterAddonCategory($data);
					if($result){
						$this->session->set_flashdata('msg_success', 'You have added addon category successfully!');
						redirect(base_url('admin/catalog/addon-category/view'));
					}
				}
					
				
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	
	public function addonCategoryDelete(){
		
		$id = $this->input->post('id');
		$data = array('status' => 2);
		if(!empty($id)){
			$this->catalog_model->deleteAddonCategory($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>'Addon Category deleted successfully.',
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Addon Category you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
	public function addonCategoryStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		
		$msg = ($status==1)?'Addon category successfully deactivated.':'Addon category successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
			$this->catalog_model->deleteAddonCategory($data,$id);
			$response = array(
			'status'=>'1',
			'msg'=>$msg,
			'redirect'=>'0'
			);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Addon Category does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
	
}