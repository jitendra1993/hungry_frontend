<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session'));
		if(is_logged_in() && is_user_type()=='admin') 
        {
            redirect('admin/dashboard'); 
        }
		
	    $cookieId = isset($_COOKIE['cookieId'])?$_COOKIE['cookieId']:'';
		if(!isset($cookieId) || empty($cookieId) || $cookieId=='')
		{
			$hash = md5(time().uniqid(rand(1,10000), true));	
			set_cookie('cookieId',$hash,time() + (10 * 365 * 24 * 60 * 60)); 
		}


		
		
		
	}
	
	public function redirection(){
	
	    if(is_logged_in() && is_user_type()=='admin') 
        {
            redirect('admin/dashboard'); 
        }
       else if(is_logged_in() && (is_user_type()=='user' || is_user_type()=='driver')) 
        {
            redirect(base_url());
        }
	}
	







	
	public function postcodecheck() {
		$postcode = $this->input->post('postcode');
		$delivery_type = $this->input->post('delivery_type');
		$name = $this->input->post('name');
		$this->session->set_userdata('postcode', $postcode);
		if($delivery_type==2){
			$url = API_URL.'/common/postcode-verification';
			$data['postcode'] = $postcode;
			$response = getCurlWithOutAuthorizationJson($url,$data);
			//print_r($response);
			$status = $response['status'];
			$result = $response['result'];
			$message = $response['message'];
		}else{
			$status =1;
			$message ='Delivery available on '.$name;
		}
		$response = array(
			'status'=>$status,
			'msg'=>$message,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		die;
	}
	

	
}
