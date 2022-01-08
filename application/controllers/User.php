<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index(){
		redirect(base_url('/user/profile'));
	}
	
	public function profile(){
		if(!is_logged_in())
		{
			redirect(base_url('auth'));
		}
		 $user_id = $this->session->userdata('user_id');
		 $tokan = $this->session->userdata('tokan');
		$view['msg'] ='';
		$view['page_title']="Profile";
		$view['main_content']='pages/profile';
		$view['profile'] = array();
		$view['address'] = array();
		
		$url = API_URL.'/api/user/profile/'.$user_id;
		
		$responseProfile = getCurlWithAuthorizationWithOutData($url);
		$profileStatus = $responseProfile['status'];
		$profileResult = $responseProfile['data'];

		if(isset($profileResult) && count($profileResult)>0){ // success
			$view['profile']= $profileResult;
		}
		
		$addressUrl = API_URL.'/api/user/address/list?userId='.$user_id;
		$responseAddress = getCurlWithAuthorizationWithOutData($addressUrl);
		$addressStatus = $responseAddress['status'];
		$addressResult = $responseAddress['data'];
		
		if(isset($addressResult) && count($addressResult)>0){ // success
			$view['address']= $addressResult;

		}elseif(isset($addressResult) && count($addressResult)==0){
			$view['address']= msg['no_address'];

		}
		
		if($this->input->method(TRUE) == 'POST'){
				$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				
				if ($this->form_validation->run() == FALSE) {
					$this->load->view('template',$view);
				}else{
					$name = htmlspecialchars(strip_tags($this->input->post('name')));
					$email = htmlspecialchars(strip_tags($this->input->post('email')));
					$phoneNumber = htmlspecialchars(strip_tags($this->input->post('phoneNumber')));
					
					$updateProfileURL = API_URL.'/api/user/profile/update';
					$data = array(
						'name' => htmlspecialchars(strip_tags($this->input->post('name'))),
						'email' => htmlspecialchars(strip_tags($this->input->post('email'))),
						'mobile' => htmlspecialchars(strip_tags($this->input->post('phoneNumber'))),
						'userId' => $user_id
					);
					
					$response = postCurlWithAuthorization($updateProfileURL,$data,'PUT');
					$status = $response['status'];
					$result = $response['data'];
					$message = $response['message'];
					
					if(isset($result) && count($result)>0){ // success
						$this->session->set_flashdata('msg_success', msg['updt_profile_success_msg']);
					}elseif($status==0){
						$this->session->set_flashdata('error_msg', $message);
					}
					redirect(base_url('user/profile'));
				}
		}
		else{
			$this->load->view('template',$view);
		}
	}
	
	
	public function changePassword(){
		if(!is_logged_in())
		{
			redirect(base_url('auth'));
		}
		
		$view['msg'] ='';
		$view['page_title']="Change Password";
		$view['main_content']='pages/change-password';
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required');
			$this->form_validation->set_rules('c_new_password', 'Confirm new password', 'trim|required|matches[new_password]');
			
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('template',$view);
			}else{
				$url = API_URL.'/api/user/update/password';
				$data = array(
					'password' => htmlspecialchars(strip_tags($this->input->post('old_password'))),
					'new_password' => htmlspecialchars(strip_tags($this->input->post('new_password'))),
					'confirm_password' => htmlspecialchars(strip_tags($this->input->post('new_password')))
					);
				
				$response = postCurlWithAuthorization($url,$data,'PATCH');
				
				$status = $response['status'];
				$result = $response['data'];
				$msg = $response['message'];
				if(isset($result) && count($result)>0){ // success

					$this->session->set_flashdata('msg_success', msg['change_password_success']);
					redirect(base_url('user/change-password'));
				}else{
					$this->session->set_flashdata('error_msg', $msg);
					$this->load->view('template',$view);
				}
		
			}
		}else{
			$this->load->view('template',$view);
		}
	}

	public function addUpdateAddress(){

		$user_id = $this->session->userdata('user_id');
		$id = $this->input->post('address_id');
		$name = htmlspecialchars(strip_tags($this->input->post('address_name')));
		$phoneNumber = htmlspecialchars(strip_tags($this->input->post('address_phoneNumber')));
		$pincode = htmlspecialchars(strip_tags($this->input->post('address_pincode')));
		$addressLine1 = htmlspecialchars(strip_tags($this->input->post('address_addressLine1')));
		$addressLine2 = htmlspecialchars(strip_tags($this->input->post('address_addressLine2')));
		$addressType = htmlspecialchars(strip_tags($this->input->post('address_type')));
	
		$addressDetail = array(
			"userId" => $user_id,
			"name" => $name,
			"phoneNumber" => $phoneNumber,
			"pincode" => $pincode,
			"addressLine1" => $addressLine1,
			"addressLine2" => $addressLine2,
			"addressType" => $addressType,
		);
		
		if(!empty($id) && isset($id))
		{
			$addressDetail['addressId']= $id;
			$url = API_URL.'/api/user/address/update';
			$responseAddress = postCurlWithAuthorization($url,$addressDetail,'PUT');
			// print_r($addressDetail);
			// print_r($responseAddress);
			// die;
			$addressStatus = $responseAddress['status'];
			$addressResult = $responseAddress['data'];
			$msg = $responseAddress['message'];

			if(isset($addressResult) && count($addressResult)>0){ // success
				$status=1;
				$msg= msg['update_address_msg'];
			}else{
				$status=0;
			}
			
			$type ='update';
			
		}else{
			$type ='add';
			$url = API_URL.'/api/user/address/add';
			$responseAddress = postCurlWithAuthorization($url,$addressDetail);
			
			$addressStatus = $responseAddress['status'];
			$addressResult = $responseAddress['data'];
			$msg = $responseAddress['message'];
			
			if(isset($addressResult) && count($addressResult)>0){ // success
				$status=1;
				$msg= msg['add_address_msg'];
				
			}else{
				$status=0;
			}
			
		}
		
		$response = array(
			'status'=>$status,
			'type'=>$type,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
	}
	
	public function deleteAddress(){
		$id = $this->input->post('address_id');
		$user_id = $this->session->userdata('user_id');
		$addressDetail = array("addressId" => $id);
			
		$url = API_URL.'/api/user/address/delete';
		$responseAddress = postCurlWithAuthorization($url,$addressDetail,'PATCH');
		$addressStatus = $responseAddress['status'];
		$addressResult = $responseAddress['data'];
		$msg = $responseAddress['message'];

		if(isset($addressResult) && count($addressResult)>0){ // success
			$status=1;
			$msg= msg['delete_address_msg'];

		}else{
			$status=0;
			//$msg= msg['something_went_wrong'];
		}
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
	}

	public function addressList(){

		if(!is_logged_in() &&  is_user_type()!='user'){
			redirect(base_url('auth'));
		}
		$view['msg'] ='';
		$view['page_title']="Address";
		$view['main_content']='pages/address';
		$view['address'] = array();
		
		$user_id = $this->session->userdata('user_id');
		sleep(2);
		$addressUrl = API_URL.'/api/user/address/list?userId='.$user_id;
		$responseAddress = getCurlWithAuthorizationWithOutData($addressUrl);
		$addressStatus = $responseAddress['status'];
		$addressResult = $responseAddress['data'];
	
		if(isset($addressResult) && count($addressResult)>0){ // success
			$view['address']= $addressResult;

		}elseif(isset($addressResult) && count($addressResult)==0 && $addressStatus==200){
			$view['address']= msg['no_address'];
		}
		$this->load->view('template',$view);
	}
	
}
