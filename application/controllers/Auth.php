<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	
    public $id;
    public $name;
    public $email;
    public $mobile;
	public $password;
	public $hashId;
    public $role_master_tbl_id;
    
    public $created;
    public $status;
    
	
	public function __construct() {
		parent::__construct();
		 $this->load->library('facebook'); 
		 $this->load->library('google');
	}
	
	public function authentication(){
		
		if(is_logged_in())
		{
			redirect(base_url());
			exit;
		}
		
		$error_message = !empty($this->input->get('error_code'))?$this->input->get('error_code'):''; 
		if($error_message){
			$this->session->set_flashdata('error_msg', 'Something went wrong');
			redirect(base_url('auth/login'));
		}
		$parameters = $this->input->get();
		if($this->facebook->is_authenticated()){
			$fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email');			
			$first_name = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
			$last_name = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
			
			$userData['oauth_provider'] = 'facebook'; 
            $userData['oauth_uid']    = !empty($fbUser['id'])?$fbUser['id']:'';; 
            $userData['name']    = trim($first_name.' '.$last_name); 
            $userData['email']        = !empty($fbUser['email'])?$fbUser['email']:'';
           
			$url = API_URL.'/user/facebook-login';
			$response = postCurlWithOutAuthorizationJson($url,$userData);
			$status = $response['status'];
			$result = $response['result'];
			$message = $response['message'];
			if(isset($result) && count($result)>0){ // success
				$newdata = array(
					'tokan'=>$result['jwt_token'],
					'full_name'  =>$result['full_name'],
					'email'  =>$result['email'],
					'phone'  =>$result['phone'],
					'user_id'  => $result['user_id'],
					'user_status'  => $result['status'],
					'role_master_tbl_id'  => 2,
					'user_type'  =>  'user',
					'logged_in' => TRUE 
					);
				$this->session->set_userdata($newdata);	
				if($redirect = $this->session->userdata('redirect'))
				{
					redirect(base_url().'/'.$redirect);
				}else{
					redirect(base_url('menu'));
				}
				
			}else{
				$this->session->set_flashdata('error_msg', $message);
				redirect(base_url('auth/login'));
			}
			
		}
		
	}
	
	public function googleLogin(){
		
		if(is_logged_in())
		{
			redirect(base_url());
			exit;
		}
		
		$error_message = !empty($this->input->get('error_code'))?$this->input->get('error_code'):''; 
		if($error_message){
			$this->session->set_flashdata('error_msg', 'Something went wrong');
			redirect(base_url('auth/login'));
		}
		$parameters = $this->input->get();
		$google_data=$this->google->validate();
		if(is_array($google_data) && count($google_data)>0){
			$userData['oauth_provider'] = 'google'; 
            $userData['oauth_uid']    = !empty($google_data['id'])?$google_data['id']:'';; 
            $userData['name']    = trim($google_data['name']); 
            $userData['email']        = !empty($google_data['email'])?$google_data['email']:'';
			
			$url = API_URL.'/user/google-login';
			$response = postCurlWithOutAuthorizationJson($url,$userData);
			$status = $response['status'];
			$result = $response['result'];
			$message = $response['message'];
			if(isset($result) && count($result)>0){ // success
				$newdata = array(
					'tokan'=>$result['jwt_token'],
					'full_name'  =>$result['full_name'],
					'email'  =>$result['email'],
					'phone'  =>$result['phone'],
					'user_id'  => $result['user_id'],
					'user_status'  => $result['status'],
					'role_master_tbl_id'  => 2,
					'user_type'  =>  'user',
					'logged_in' => TRUE 
					);
				$this->session->set_userdata($newdata);	
				if($redirect = $this->session->userdata('redirect'))
				{
					redirect(base_url().'/'.$redirect);
				}else{
					redirect(base_url('menu'));
				}
				
			}else{
				$this->session->set_flashdata('error_msg', $message);
				redirect(base_url('auth/login'));
			}
			
		}else{
			$this->session->set_flashdata('error_msg', 'Something went wrong');
			redirect(base_url('auth/login'));
			}
	}
	
	public function index(){
		
		if(is_logged_in() &&  (is_user_type()=='user' || is_user_type()=='driver'))
		{
			redirect(base_url());
			exit;
		}
		$view['msg'] ='';
		$view['page_title']="Authentication";
		$view['main_content']='pages/login';
		$view['facebook_url'] =  $this->facebook->login_url(); 
		$view['google_login_url']=$this->google->get_login_url();
		$this->load->view('template',$view);
	}
	
	public function registration(){
		
		if(is_logged_in() &&  (is_user_type()=='user' || is_user_type()=='driver'))
		{
			redirect(base_url());
		}
			
		$view['msg'] ='';
		$view['page_title']="Authentication";
		$view['main_content']='pages/registration';
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('full_name', 'Full name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone number', 'trim|required|integer|min_length[3]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required|matches[password]');
			
			if ($this->form_validation->run() == FALSE) {
				$e1 = '';
				$e1 .= !empty(form_error('full_name'))?form_error('full_name', '<span class="row">', '</span>'):''; 
				$e1 .= !empty(form_error('email'))?form_error('email', '<span class="row">', '</span>'):''; 
				$e1 .= !empty(form_error('phone'))?form_error('phone', '<span class="row">', '</span>'):''; 
				$e1 .= !empty(form_error('password'))?form_error('password', '<span class="row">', '</span>'):''; 
				$e1 .= !empty(form_error('cpassword'))?form_error('cpassword', '<span class="row">', '</span>'):''; 
				$errors = '<div class="alert alert-danger alert-dismissible" role="alert">'.$e1.'</div>';
				
				$response = array(
					'msg'=>$errors,
					'status'=>0,
					);
				header('Content-Type: application/json');
				echo json_encode($response);
			}else{
				$url = API_URL.'/api/user/signup';
				$data = array(
						'name' => htmlspecialchars(strip_tags($this->input->post('full_name'))),
						'email' => htmlspecialchars(strip_tags($this->input->post('email'))),
						'mobile' => htmlspecialchars(strip_tags($this->input->post('phone'))),
						'password' => htmlspecialchars(strip_tags($this->input->post('password'))),
						'loyalty_point' => 0,
					);
						
				$response = postCurlWithOutAuthorizationJson($url,$data);
				//print_r($response);
				$status = $response['status'];
				$result = $response['data'];
				$message = $response['message'];
				if(isset($result) && count($result)>0){ // success
					$response = array(
					'msg'=>msg['registration_otp_msg'],
					'status'=>1,
					);
				}else{
					$response = array(
					'msg'=>'<div class="alert alert-danger alert-dismissible" role="alert">'.$message.'</div>',
					'status'=>0,
					);
				}
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		}
		else
		{
			$this->load->view('template',$view);
			
		}
	}	

	public function resendMailotpReg(){
		$email = htmlspecialchars(strip_tags($this->input->post('email')));
		$url = API_URL.'/api/user/send/verification/email';
		$data = array('email' => $email);
		$response = postCurlWithOutAuthorizationJson($url,$data);

		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		if(isset($result) && count($result)>0){ // success
			$response = array(
			'msg'=>'<div class="alert alert-success alert-dismissible" role="alert">'.msg['registration_otp_msg'].'</div>',
			'status'=>1,
			);
		}else{
			$response = array(
			'msg'=>'<div class="alert alert-danger alert-dismissible" role="alert">'.$message.'</div>',
			'status'=>0,
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function verifyEmail(){
		$email = htmlspecialchars(strip_tags($this->input->post('email')));
		$otp = htmlspecialchars(strip_tags($this->input->post('otp')));
		if(is_logged_in())
		{
			$email = $this->session->userdata('email');
		}
		$url = API_URL.'/api/user/verify-mail';
		$data = array(
				'email' => $email,
				'verification_token' => $otp
				);
		$response = patchCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		
		if(isset($result) && count($result)>0){ // success
			$status=1;
			$msg = msg['email_verifeid_success'];
			if(is_logged_in())
			{
				$this->session->set_userdata('user_status',1);
			}
			
		}else{
			$status=0;
			$msg = $message;
		}
		
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		
	}
	
	public function login(){
		
		if(is_logged_in() &&  (is_user_type()=='user' || is_user_type()=='driver'))
		{
			redirect(base_url());
			exit;
		}
		
		$view['msg'] ='';
		$view['page_title']="Authentication";
		$view['main_content']='pages/login';
		$view['facebook_url'] =  $this->facebook->login_url(); 
		$view['google_login_url']=$this->google->get_login_url();
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('login_email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('login_password', 'Password', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('template',$view);
			}else{
				$login_email = htmlspecialchars(strip_tags($this->input->post('login_email')));
				$login_password = htmlspecialchars(strip_tags($this->input->post('login_password')));
				$url = API_URL.'/api/user/login';
				
				$data = array(
						'email' => $login_email,
						'password' => $login_password
					);
						
				$response = postCurlWithOutAuthorizationJson($url,$data);
				$status = $response['status'];
				$result = $response['data'];
				$message = $response['message'];

				if(isset($result) && count($result)>0){ // success
					
					$newdata = array(
						'tokan'=>$result['token'],
						'full_name'  =>$result['user']['name'],
						'email'  =>$result['user']['email'],
						'phone'  =>$result['user']['mobile'],
						'user_id'  => $result['user']['hash'],
						'user_status'  => $result['user']['status'],
						'role_master_tbl_id'  => $result['user']['role_master_tbl_id'],
						'user_type'  =>  'user',
						'logged_in' => TRUE 
						);
					$this->session->set_userdata($newdata);	
					if($redirect = $this->session->userdata('redirect'))
					{
						redirect(base_url().$redirect);
					}else{
						redirect(base_url());
					}
				}else{
					$this->session->set_flashdata('error_msg', $message);
					$this->load->view('template',$view);
				}
			}
		}
		else
		{
			$this->load->view('template',$view);
		}
	}	

	public function forgotPassword(){
		if(is_logged_in() &&  (is_user_type()=='user' || is_user_type()=='driver'))
		{
			redirect(base_url());
		}
		
		$view['msg'] ='';
		$view['page_title']="Forgot Password";
		$view['main_content']='pages/forgot-password';
		$this->load->view('template',$view);
		
	}	

	public function forgotPasswordAjax(){
		$email = htmlspecialchars(strip_tags($this->input->post('email')));
		$url = API_URL.'/api/user/forgot/password';
		$data = array('email' => $email);
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		
		if(isset($result) && count($result)>0){ // success
			$status=1;
			$msg = msg['forgot_password'];
		}else{
			$status=0;
			$msg = $message;
		}
		
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
	}	
	
	public function resetPassword(){

		$email = htmlspecialchars(strip_tags($this->input->post('email')));
		$otp = htmlspecialchars(strip_tags($this->input->post('otp')));
		$new_password = htmlspecialchars(strip_tags($this->input->post('new_password')));


		$url = API_URL.'/api/user/verify-mail';
		$data = array(
				'email' => $email,
				'verification_token' => $otp
			);
		$response = patchCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['data'];
		$message = $response['message'];
		
		if(isset($result) && count($result)>0){ // success
			
			$url = API_URL.'/api/user/reset/password';
			$data = array(
					'email' => $email,
					'confirm_password' => $new_password,
					'new_password' => $new_password,
			);
			$response = patchCurlWithOutAuthorizationJson($url,$data);
			$status = $response['status'];
			$result = $response['data'];
			$message = $response['message'];
			
			if(isset($result) && count($result)>0){ 
				$status=1;
				$msg = msg['reset_password_success'];
			}else{
				$status=0;
				$msg = $message;
			}
			
		}else{
			$status=0;
			$msg = $message;
		}

		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);

	}

	public function logout() {
	    
		$this->session->unset_userdata($this->session->userdata);
        $this->session->sess_destroy();
		redirect('/');
	}





	public function sendVerifyMailotp(){
		$email = htmlspecialchars(strip_tags($this->input->post('email')));
		$full_name = htmlspecialchars(strip_tags($this->input->post('full_name')));
		if(is_logged_in())
		{
			$email = $this->session->userdata('email');
			$full_name = $this->session->userdata('full_name');
		}
		$url = API_URL.'/common/send-otp';
		$data = array(
				'email' => $email,
				'name' => $full_name,
				);
		$response = postCurlWithOutAuthorizationJson($url,$data);
		$status = $response['status'];
		$result = $response['result'];
		$message = $response['message'];
		
		if($status==1){ // success
			$status=1;
			$msg = msg['registration_otp_msg'];
		}else{
			$status=0;
			$msg = $message;
		}
		
		$response = array(
			'status'=>$status,
			'msg'=>$msg,
			'redirect'=>'0'
			);
		header('Content-Type: application/json');
    	echo json_encode($response);
		
	}
	
	
	
	
	
	
	
	

	

}
