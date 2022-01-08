<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
		if(!isset($cookieId) || empty($cookieId) || $cookieId==''){
			$hash = md5(time().uniqid(rand(1,10000), true));	
			set_cookie('cookieId',$hash,time() + (10 * 365 * 24 * 60 * 60)); 
		}


		$user_id='';
		$hash = md5(uniqid(rand(), true));
		if(!empty($this->session->userdata('user_id'))){
			$user_id = $this->session->userdata('user_id');
			$this->session->unset_userdata('guest_user_id');
			
		}else if(!empty($this->session->userdata('guest_user_id'))){
			$user_id = $this->session->userdata('guest_user_id');
			
		}else{
			$user_id = 'guest_'.$hash;
			$this->session->set_userdata('guest_user_id',$user_id);
		}

		if(is_logged_in() &&  is_user_type()!='user'){
			redirect(base_url());
		}
	}
	
	
	public function list() {

        $id= $this->uri->segment(2);
		$pincode =  preg_replace('/\s+/', '', urldecode($id));
        $sub_pincode = substr($pincode,0,4);
        $view['result'] =[];
        $view['sub_pincode'] =$pincode;
		$this->session->set_userdata('postcode',$pincode);
        if(empty($pincode) || strlen($pincode)<6){
           redirect(base_url());
        }
		$this->session->set_userdata('redirect','area/'.$pincode);

        $this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE_FRONT;
		$seg = 3;
		 $rowno = ($this->uri->segment($seg))? $this->uri->segment($seg) : 0;
        
		if($rowno!= 0){
			 $rowno = ($rowno-1) * $rowperpage;
		}	
		
		$url = API_URL.'/api/restaurant/list';

		$post['postcode'] =$sub_pincode;
		$post['restaurant'] = '';
		$post['category_id'] = [];
		$post['restaurant_open'] = 0;
		$post['service_status'] = 3;
		$post['skip'] = $rowno ;
		$post['limit'] =  $rowperpage;

        if(!empty($filter) && count($filter)>0)
		{
            if(($filter['delivery']==0 && $filter['collection']==0) || ($filter['delivery']==1 && $filter['collection']==1) ){
                $post['service_status'] =3;
            }
            else if($filter['delivery']==1 && $filter['collection']==0){
                $post['service_status'] =2;
            }
            else if($filter['delivery']==0 && $filter['collection']==1){
                $post['service_status'] =1;
            }

			$post['restaurant'] = !empty($filter['restaurant'])?$filter['restaurant']:'' ;
			$post['category_id'] = !empty($filter['category'])?explode(',',$filter['category']):[];
			$post['restaurant_open'] = !empty($filter['restaurnat_open'])?$filter['restaurnat_open']:0;
			$post['order_by'] = !empty($filter['order'])?$filter['order']:0;
		}

        // echo '<pre>';
        // print_r($post);
        // echo '</pre>';
       // die;
		
		$response = postCurlWithOutAuthorizationJson($url,$post);
        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';
		$status = $response['status'];
		$result = (isset($response['data']) && count($response['data'])>0 && is_array($response['data']))?$response['data']:[];
        if($status==200 &&  is_array($result) && count($result)>0)
		{
			$view['result']  = $result;
			$config["total_rows"] = $result['totalRecord'];
		}
        $config["base_url"] = base_url() . "/area".'/'.$pincode;
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = $seg;;
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
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$view['page_title']='Restaurant List';
		$view['main_content']='pages/restaurant_list.php';
		$this->load->view('template',$view);	
	}

	public function storeProducts($id='') {

		$restaurantId =  $_GET['id'];
		if(empty($restaurantId)){
			redirect(base_url());
		}
	
		$setting = get_store_detail_by_id($restaurantId);
		$this->session->set_userdata('restaurant_id',$restaurantId);
	
		$view['page_title']='Grocery';
		$view['main_content']='pages/store_product.php';
		$view['result'] =[];
		$view['basket'] =''; 

		if (isset($_COOKIE["menu_type"]) && $_COOKIE["menu_type"]==2) {
			redirect(base_url('store/'.$id.'?id='.$restaurantId.'&menu_type=2'));
			$this->session->set_userdata('redirect','store/'.$id.'?id='.$restaurantId.'&menu_type=2');

		}else{
			setcookie('menu_type', 1, 0, "/");  // collection , delivery
			$this->session->set_userdata('redirect','store/'.$id.'?id='.$restaurantId.'&menu_type=1');
		}
		
		
		$user_id='';
		if(is_logged_in()){

			$user_id = $this->session->userdata('user_id');
		}

		$url = API_URL.'/api/restaurant/'.$restaurantId.'/items/1';  // 1 for collection and delivery
		$response = getCurlWithOutAuthorizationWithOutData($url);
	
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];

		if(isset($result) && count($result)>0){ // success
			$view['result'] = $result;
		}
		$view['basket'] = $this->load->view('pages/cart', array("merchant_info"=>$setting), true);
		$this->load->view('template',$view);
	}
	

	
}
