<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/product_model');
		$this->load->model('admin/catalog_model');
		$this->load->model('admin/ingredient_model');
		$this->load->model('admin/size_model');
		if (!$this->session->userdata('user_id')) {
			redirect('admin'); 
		}
	}
	
	public function index(){
		redirect('admin/products/view');
	}
	
	public function getSubCategories(){//ajax call
		
		$cat_id = $this->input->post('cat_id');
		$sub_cat_ids = $this->input->post('sub_cat_ids');
		$filter['cat_id'] = $cat_id; 
		$filter['sub_cat_ids'] = $sub_cat_ids; 
		$view['subcategories'] = $this->catalog_model->getSubCategories($filter);
	
	}
	
	public function productView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$filter['type'] = 1;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/product/view";
		$config["total_rows"] = $this->product_model->getCountMasterProduct($filter);
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
	
		$view['page_title']='Products';
		$view['main_content']='admin/pages/product/product_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['products'] = $this->product_model->getMasterProduct($filter,$data);
		$view['categories'] = $this->catalog_model->getMasterCategoryArray();
		
		$this->load->view('admin/template_admin',$view);
	}
	
	public function multipleFileUpload($files,$gallery_uploaded_filename)
	{
		$this->load->library('upload');
		$gallery_uploaded_filename_arr = explode(',',$gallery_uploaded_filename);
		$dataInfo = array();
		$cpt = count($files['gallery']['name']);
		for($i=0; $i<$cpt; $i++)
		{    
			if(in_array($files['gallery']['name'][$i],$gallery_uploaded_filename_arr ))
			{
				$_FILES['gallery']['name']= $files['gallery']['name'][$i];
				$_FILES['gallery']['type']= $files['gallery']['type'][$i];
				$_FILES['gallery']['tmp_name']= $files['gallery']['tmp_name'][$i];
				$_FILES['gallery']['error']= $files['gallery']['error'][$i];
				$_FILES['gallery']['size']= $files['gallery']['size'][$i];    

				$config['upload_path']   = './uploads/products/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
				$config['max_size']      = 1024;
				$config['file_ext_tolower']   = TRUE;
				$config['encrypt_name']   = TRUE;
				$config['remove_spaces']   = TRUE;
				$config['detect_mime']   = TRUE;
				$this->upload->initialize($config);
				$this->upload->do_upload('gallery');
				$img = $this->upload->data();
				$dataInfo[] = $img['file_name'];
			}
		}
		return $dataInfo;
	}
	
	public function productAdd($id=''){
		if ($this->session->userdata('role_master_tbl_id')==1 && $id=='') {
			redirect('admin/product/view');
		}
		$view['page_title']='Product';
		$view['main_content']='admin/pages/product/product_add';
		$subcategories = array();
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$product_info = $this->product_model->getMasterProductById($id);
			$view['product'] = $product_info;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$product_info->user_hash_id) 
				{
					redirect('admin/product/view');
				}
			}
		
		}
		
		$view['categories'] = $this->catalog_model->getMasterCategoryArray();
		$view['ingredients'] = $this->ingredient_model->getAllIngredientArray();
		$view['sizes'] = $this->size_model->getAllSizeArray();
		$addonCategoryWithItems = $this->product_model->getAddOnCategoryWithAddonItemArray();
		// echo '<pre>';
		// print_r($addonCategoryWithItems);
		// echo '</pre>';
		$view['addonCategoryWithItems'] = $addonCategoryWithItems;
		$existing_gallery_arr =array();
		if(!empty($id)){
			$product_info = $this->product_model->getMasterProductById($id);
			$view['product'] = $product_info;
			$cat_id = $product_info->main_cat_ids; 
			$existing_gallery_arr = (isset($product_info->gallery) && !empty($product_info->gallery))?$product_info->gallery:array(); 
			$view['subcategories'] = $this->catalog_model->getMasterSubCategoryArray($cat_id);
		
		}
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('item_name', 'Product Name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('max_price[]', 'Product Price', 'trim|required');
			$this->form_validation->set_rules('categories[]', 'Category', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$this->load->library('upload');
				$id = $this->input->post('id');
				$gallery_img_remove = $this->input->post('gallery_img_remove');
				$two_flavors = (!empty($this->input->post("two_flavors")))?(int)$this->input->post("two_flavors"):0;
				$non_taxable = (!empty($this->input->post("non_taxable")))?(int)$this->input->post("non_taxable"):0;
				$points_disabled = (!empty($this->input->post("points_disabled")))?(int)$this->input->post("points_disabled"):0;
				$meal_deal = (!empty($this->input->post("meal_deal")))?(int)$this->input->post("meal_deal"):0;
				$same_dinein = (!empty($this->input->post("same_dinein")))?(int)$this->input->post("same_dinein"):0;
				

				$size = $this->input->post('size');
				$max_price = array_filter($this->input->post('max_price'));
				$discount_price = $this->input->post('discount_price');
				$quantity = $this->input->post('quantity');


				$variation =[];
				foreach($max_price as $key=>$value){
					$item_variation =[];
					$item_variation['size'] = $size[$key] ;
					$item_variation['max_price'] = $value;
					$item_variation['discount_price'] = $discount_price[$key] ;
					$item_variation['quantity'] = $quantity[$key] ;

					$variation[] = $item_variation;

				}

				// echo '<pre>';
				// print_r($variation);
				// echo '</pre>';
				// die;
				
				
				/*Feature image upload start*/
				$feature_img_file_name = '';
				$old_img = $this->input->post('old_image');	  
				$img_status = $this->input->post('image_status');	
				if (!empty($_FILES['image']['name']))
				{
					$config['upload_path']   = './uploads/products/';
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
						$feature_img_file_name = $img['file_name'];
					}
					
					$path = BASEPATH.'../uploads/products/'.$old_img; 
					if(is_file($path))
					{
						unlink($path);
					}
				}
				/*Feature image upload finish*/
				$type = array(1);
				if($same_dinein==1){
					$type = array(1,2);
				}
				
				
				$meal_deal_item_qty = (!empty($this->input->post("meal_deal_item_qty")))?$this->input->post("meal_deal_item_qty"):array();
				$meal_deal_no_option = (!empty($this->input->post("meal_deal_no_option")))?$this->input->post("meal_deal_no_option"):array();
				
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				
				$addon_item_id = $this->input->post('addon_item_id[]');
				$addon_item =[];
				if(isset($addon_item_id) && count($addon_item_id)>0){
					foreach($addon_item_id as $k=>$v){
						$addon_item[$k] = array_keys($v);
					}
				}
				
				$product_data = array(
						'id' =>$hashUnique,
						'item_name' => htmlspecialchars(strip_tags($this->input->post('item_name'))),
						'description' =>  htmlspecialchars(strip_tags($this->input->post('description'))),
						'main_cat_ids' => explode(',',$this->input->post('main_cat_ids')),
						'sub_cat_ids' => explode(',',$this->input->post('sub_cat_ids')),
						'two_flavors' => $two_flavors,
						'in_stock' => 1,
						'multi_option' =>$this->input->post('multi_option[]'),
						'multi_option_value' => $this->input->post('multi_option_value[]'),
						'two_flavors_position' => $this->input->post('two_flavors_position[]'),
						'require_addon' => (!empty($this->input->post('require_addon[]')))?array_keys($this->input->post('require_addon[]')):array(),
						'addon_item_id' => $addon_item,
						'ingredients' => (!empty($this->input->post('ingredients[]')))?array_keys($this->input->post('ingredients[]')):[],
						'non_taxable' => $non_taxable,
						'points_earned' => htmlspecialchars(strip_tags($this->input->post('points_earned'))),
						'points_disabled' => $points_disabled,
						'item_variation' => $variation,
						'status' => (int)$this->input->post('status'),
						'meal_deal' => $meal_deal,
						'same_dinein' => $same_dinein,
						'type' => $type,
						'meal_deal_no_option' => $meal_deal_no_option,
						'meal_deal_item_qty' => $meal_deal_item_qty,
						'image'=>$old_img,
						'user_hash_id' => $user_id,
						'added_date'=> date('d-m-Y H:i:s'),
						'updated_date'=>date('d-m-Y H:i:s'),
						'added_date_timestamp'=>time()*1000,
						'updated_date_timestamp'=>time()*1000,
						'added_date_iso'=>$date_created,
						'updated_date_iso'=>$date_created
					);
				// echo '<pre>';
				// print_r($product_data);
				
				//  echo '</pre>';
				// die;
				/*Gallery image upload start*/
				$gallery_img_remove = $this->input->post('gallery_img_remove');
				$remove_gallery_arr = (isset($gallery_img_remove) && !empty($gallery_img_remove))?explode(',',$gallery_img_remove):array(); 
				$gallery_images_arr = array_diff((array)$existing_gallery_arr,$remove_gallery_arr);
				
				foreach($remove_gallery_arr as $v)
				{
					$path = BASEPATH.'../uploads/products/'.$v; 
					if(is_file($path)){
						unlink($path);
					}
				}
				if(isset($_FILES['gallery']) && count($_FILES['gallery'])>0 && !empty($this->input->post('gallery_uploaded_filename')))
				{
				  $up = $this->multipleFileUpload($_FILES,$this->input->post('gallery_uploaded_filename'));
				  $gallery_images_arr = array_merge($gallery_images_arr,$up);
				}
				$product_data['gallery'] = $gallery_images_arr;
				/*Gallery image upload finish*/
				
				if(!empty($feature_img_file_name)){
					$product_data['image']=$feature_img_file_name;
				}	
				
				if($img_status && empty($feature_img_file_name)){
				 $path = BASEPATH.'../uploads/products/'.$old_img; 
					if(is_file($path)){
						unlink($path);
					}
				}
				// echo '<pre>';
				// print_r($product_data);
				// echo '</pre>';
				// die;
				if(!empty($id)){
					unset($product_data['added_date'],$product_data['added_date_timestamp'],$product_data['added_date_iso'],$product_data['id'],$product_data['user_hash_id']);
					$result = $this->product_model->updateMasterProduct($product_data,$id);
					$this->session->set_flashdata('msg_success', 'You have updated product successfully!');
					redirect(base_url('admin/product/view'));
				}else{
					$result = $this->product_model->addMasterProduct($product_data);
					if($result){
						$this->session->set_flashdata('msg_success', 'You have added new product successfully!');
						redirect(base_url('admin/product/view'));
					}
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	
	public function productDelete(){
		
		 $id = $this->input->post('id');
		$user_id = $this->session->userdata('user_id');
		$data = array(
			'status' => 2
		);
		if(!empty($id)){
				$this->product_model->deleteMasterProduct($data,$id);
				$response = array(
				'status'=>'1',
				'msg'=>'Product deleted successfully.',
				'redirect'=>'0'
				);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Product you want to delete does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function productStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'Product successfully deactivated.':'Product successfully activated.';
		$data = array('status' => ($status==1)?0:1);
		if(!empty($id)){
				$this->product_model->deleteMasterProduct($data,$id);
				$response = array(
				'status'=>'1',
				'msg'=>$msg,
				'redirect'=>'0'
				);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Product does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function productStockStatus(){
		
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$user_id = $this->session->userdata('user_id');
		
		$msg = ($status==1)?'Product now out of stock.':'Product now in stock.';
		$data = array('in_stock' => ($status==1)?0:1);
		if(!empty($id)){
				$this->product_model->deleteMasterProduct($data,$id);
				$response = array(
				'status'=>'1',
				'msg'=>$msg,
				'redirect'=>'0'
				);
			
		}else{
			$response = array(
				'status'=>'0',
				'msg'=>'Product does not exists',
				'redirect'=>'0'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function uploadItem(){
		$view['page_title']='Product';
		$view['main_content']='admin/pages/product/upload_product';
		$this->load->view('admin/template_admin',$view);
	}
	
	public function csvView()
	{
		$view['msg'] ='';
		$view['page_title']='View Product CSV';
		$view['main_content']='admin/pages/product/product_csv_view';
		$view['categories'] = $this->catalog_model->getMasterCategoryArray();
		
		if($this->input->method(TRUE) == 'POST'){
			$csv = $_FILES['csv']['tmp_name'];
			$view['csv'] = array_map('str_getcsv', file($csv));
			$this->load->view('admin/template_admin',$view);
		}
		else
		{
			redirect(base_url('admin/product/upload-item'));
		}
		
	}
	
	public function uploadProduct()
	{
		
		$view['product'] = array();
		$view['main_content']='admin/pages/product/upload_product';
		$msg="No record found.";
		$user_id = $this->session->userdata('user_id');
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('item_name[]', 'Product Name', 'trim|required|min_length[2]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				$i=1;
				// echo '<pre>';
				// print_r($this->input->post());
				// echo '</pre>';
				$success =0;
				$error =0;
				foreach($this->input->post('item_name') as $key=>$value)
				{
					$hash = uniqid(rand(1,1000));
					$description = isset($this->input->post('description')[$key])?$this->input->post('description')[$key]:'';
					$item_price = isset($this->input->post('item_price')[$key])?$this->input->post('item_price')[$key]:'';
					$main_cat_ids = isset($this->input->post('main_cat_ids')[$key])?$this->input->post('main_cat_ids')[$key]:'';
					$discount = isset($this->input->post('discount')[$key])?$this->input->post('discount')[$key]:0;
					
					$product_data = array(
						'unique_id' => $hash,
						'item_name' => htmlspecialchars(strip_tags($value)),
						'description' =>  htmlspecialchars(strip_tags($description)),
						'item_price' =>  htmlspecialchars(strip_tags($item_price)),
						'main_cat_ids' => htmlspecialchars(strip_tags($main_cat_ids)),
						'discount' => htmlspecialchars(strip_tags($discount)),
						'status' => 1,
						'added_by' => $user_id,
						'updated_date'=> date('Y-m-d H:i:s'),
						'added_date_time'=>date('Y-m-d H:i:s')
					);
					$result = $this->product_model->addMasterProduct($product_data);
					if($result){
						$success++;
					}else{
						$error++;
					}
					$i++;
				}
				$this->session->set_flashdata('msg_success', 'You have added '.$success.' new product successfully!');
				$this->session->set_flashdata('error_msg', 'You have faliure '.$error.' product!');
				redirect(base_url('admin/product/upload-item'));
				
			}
		}else
		{
			redirect(base_url('admin/product/upload-item'));
		}
	}
	
	
	public function dineProductView(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$filter['type'] = 2;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/dinein-product/view";
		$config["total_rows"] = $this->product_model->getCountMasterProduct($filter);
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
	
		$view['page_title']='Products';
		$view['main_content']='admin/pages/product/dine_product_list';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['products'] = $this->product_model->getMasterProduct($filter,$data);
		$view['categories'] = $this->catalog_model->getMasterCategoryArray();
		
		$this->load->view('admin/template_admin',$view);
	}
	
	public function dineProductAdd($id=''){
		if ($this->session->userdata('role_master_tbl_id')==1 && $id=='') {
			redirect('admin/dinein-product/view');
		}
		$view['page_title']='Dinein Product';
		$view['main_content']='admin/pages/product/product_add';
		$subcategories = array();
		$user_id = $this->session->userdata('user_id');
		if(!empty($id)){
			$product_info = $this->product_model->getMasterProductById($id);
			$view['product'] = $product_info;
			if ($this->session->userdata('role_master_tbl_id')==2) {
				if($user_id!=$product_info->user_hash_id) 
				{
					redirect('admin/dinein-product/view');
				}
			}
		
		}
		
		$view['categories'] = $this->catalog_model->getMasterCategoryArray();
		$view['ingredients'] = $this->ingredient_model->getAllIngredientArray();
		$view['sizes'] = $this->size_model->getAllSizeArray();
		$addonCategoryWithItems = $this->product_model->getAddOnCategoryWithAddonItemArray();
		$view['addonCategoryWithItems'] = $addonCategoryWithItems;
		$existing_gallery_arr =array();
		if(!empty($id)){
			$product_info = $this->product_model->getMasterProductById($id);
			$view['product'] = $product_info;
			$cat_id = $product_info->main_cat_ids; 
			$existing_gallery_arr = (isset($product_info->gallery) && !empty($product_info->gallery))?$product_info->gallery:array(); 
			$view['subcategories'] = $this->catalog_model->getMasterSubCategoryArray($cat_id);
		
		}
		$file_error = 0;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('item_name', 'Product Name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('max_price[]', 'Product Price', 'trim|required');
			$this->form_validation->set_rules('categories[]', 'Category', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$this->load->library('upload');
				$id = $this->input->post('id');
				$gallery_img_remove = $this->input->post('gallery_img_remove');
				$two_flavors = (!empty($this->input->post("two_flavors")))?(int)$this->input->post("two_flavors"):0;
				$non_taxable = (!empty($this->input->post("non_taxable")))?(int)$this->input->post("non_taxable"):0;
				$points_disabled = (!empty($this->input->post("points_disabled")))?(int)$this->input->post("points_disabled"):0;
				$meal_deal = (!empty($this->input->post("meal_deal")))?(int)$this->input->post("meal_deal"):0;
				$same_dinein = (!empty($this->input->post("same_dinein")))?(int)$this->input->post("same_dinein"):0;
				
				$item_variation['size'] = $this->input->post('size');
				$item_variation['max_price'] =  $this->input->post('max_price');
				$item_variation['discount_price'] = $this->input->post('discount_price');
				$item_variation['quantity'] =  $this->input->post('quantity');
				
				
				/*Feature image upload start*/
				$feature_img_file_name = '';
				$old_img = $this->input->post('old_image');	  
				$img_status = $this->input->post('image_status');	
				if (!empty($_FILES['image']['name']))
				{
					$config['upload_path']   = './uploads/products/';
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
						$feature_img_file_name = $img['file_name'];
					}
					
					$path = BASEPATH.'../uploads/products/'.$old_img; 
					if(is_file($path))
					{
						unlink($path);
					}
				}
				/*Feature image upload finish*/
				$type = array(2);
				if($same_dinein==1){
					$type = array(1,2);
				}
				
				
				$meal_deal_item_qty = (!empty($this->input->post("meal_deal_item_qty")))?$this->input->post("meal_deal_item_qty"):array();
				$meal_deal_no_option = (!empty($this->input->post("meal_deal_no_option")))?$this->input->post("meal_deal_no_option"):array();
				
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				
				$addon_item_id = $this->input->post('addon_item_id[]');
				$addon_item =[];
				if(isset($addon_item_id) && count($addon_item_id)>0){
					foreach($addon_item_id as $k=>$v){
						$addon_item[$k] = array_keys($v);
					}
				}
				
				$product_data = array(
						'id' =>$hashUnique,
						'item_name' => htmlspecialchars(strip_tags($this->input->post('item_name'))),
						'description' =>  htmlspecialchars(strip_tags($this->input->post('description'))),
						'main_cat_ids' => explode(',',$this->input->post('main_cat_ids')),
						'sub_cat_ids' => explode(',',$this->input->post('sub_cat_ids')),
						'two_flavors' => $two_flavors,
						'in_stock' => 1,
						'multi_option' =>$this->input->post('multi_option[]'),
						'multi_option_value' => $this->input->post('multi_option_value[]'),
						'two_flavors_position' => $this->input->post('two_flavors_position[]'),
						'require_addon' => (!empty($this->input->post('require_addon[]')))?array_keys($this->input->post('require_addon[]')):array(),
						'addon_item_id' => $addon_item,
						'ingredients' => (!empty($this->input->post('ingredients[]')))?array_keys($this->input->post('ingredients[]')):[],
						'non_taxable' => $non_taxable,
						'points_earned' => htmlspecialchars(strip_tags($this->input->post('points_earned'))),
						'points_disabled' => $points_disabled,
						'item_variation' => $item_variation,
						'status' => (int)$this->input->post('status'),
						'meal_deal' => $meal_deal,
						'same_dinein' => $same_dinein,
						'type' => $type,
						'meal_deal_no_option' => $meal_deal_no_option,
						'meal_deal_item_qty' => $meal_deal_item_qty,
						'image'=>$old_img,
						'user_hash_id' => $user_id,
						'added_date'=> date('d-m-Y H:i:s'),
						'updated_date'=>date('d-m-Y H:i:s'),
						'added_date_timestamp'=>time()*1000,
						'updated_date_timestamp'=>time()*1000,
						'added_date_iso'=>$date_created,
						'updated_date_iso'=>$date_created
					);
				// echo '<pre>';
				// print_r($product_data);
				
				 //echo '</pre>';
				// die;
				/*Gallery image upload start*/
				$gallery_img_remove = $this->input->post('gallery_img_remove');
				$remove_gallery_arr = (isset($gallery_img_remove) && !empty($gallery_img_remove))?explode(',',$gallery_img_remove):array(); 
				$gallery_images_arr = array_diff((array)$existing_gallery_arr,$remove_gallery_arr);
				
				foreach($remove_gallery_arr as $v)
				{
					$path = BASEPATH.'../uploads/products/'.$v; 
					if(is_file($path)){
						unlink($path);
					}
				}
				if(isset($_FILES['gallery']) && count($_FILES['gallery'])>0 && !empty($this->input->post('gallery_uploaded_filename')))
				{
				  $up = $this->multipleFileUpload($_FILES,$this->input->post('gallery_uploaded_filename'));
				  $gallery_images_arr = array_merge($gallery_images_arr,$up);
				}
				$product_data['gallery'] = $gallery_images_arr;
				/*Gallery image upload finish*/
				
				if(!empty($feature_img_file_name)){
					$product_data['image']=$feature_img_file_name;
				}	
				
				if($img_status && empty($feature_img_file_name)){
				 $path = BASEPATH.'../uploads/products/'.$old_img; 
					if(is_file($path)){
						unlink($path);
					}
				}
				// echo '<pre>';
				// print_r($product_data);
				// echo '</pre>';
				// die;
				if(!empty($id)){
					unset($product_data['added_date'],$product_data['added_date_timestamp'],$product_data['added_date_iso'],$product_data['id'],$product_data['user_hash_id']);
					$result = $this->product_model->updateMasterProduct($product_data,$id);
					$this->session->set_flashdata('msg_success', 'You have updated Dinein product successfully!');
					redirect(base_url('admin/dinein-product/view'));
				}else{
					$result = $this->product_model->addMasterProduct($product_data);
					if($result){
						$this->session->set_flashdata('msg_success', 'You have added new Dinein product successfully!');
						redirect(base_url('admin/dinein-product/view'));
					}
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	
}