<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->model('admin_model');
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
	
	public function index() {
	    if(!is_logged_in()) 
        {
	        $this->load->view('admin/main/login');  
        }
        else
        {
          redirect('admin/dashboard');   
        }
	}
	
	public function login() {
		$this->redirection();
		if($this->input->method(TRUE) == 'POST')
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			if ($this->form_validation->run() == false) {
				$this->load->view('admin/main/login');
			} else {
					$username = $this->input->post('username');
					$password = $this->input->post('password');
					
					if ($user_id = $this->admin_model->checkAdminLogin($username, $password)) {
						$admin  = $this->admin_model->getAdminDetail($user_id);
						// echo '<pre>';
						// print_r($admin);
						// echo '</pre>';
						// die;
						$newdata = array(
							'user_id'=>$admin->hash,
							'username'  => $admin->email,
							'email'  => $admin->email,
							'mobile'  => $admin->mobile,
							'name'  => $admin->name,
							'role_master_tbl_id'  => $admin->role_master_tbl_id,
							'status'  => $admin->status,
							'logged_in' => TRUE 
						);
						$this->session->set_userdata($newdata);
						$this->redirection();
					} else {
						// login failed
						$this->session->set_flashdata('error_msg','Wrong username or password.');
						$this->load->view('admin/main/login');
					}
			}
		}else{
			$this->load->view('admin/main/login');
		}
	}
	
	public function logout() {
		$this->session->unset_userdata($this->session->userdata);
        $this->session->sess_destroy();
		redirect('/admin');
	}

	public function forgotPassword() {
		$this->redirection();
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('username', 'Email', 'trim|required|valid_email');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/main/forgot_password'); 
			}else{
				$username = $this->input->post('username');	
				if($check_status = $this->admin_model->checkMailExist($username))
				{
					$this->forgotPasswordMail($username);
					$this->session->set_flashdata('msg_success', 'Check your email for reset password');
					redirect('admin/auth/forgot-password'); 
				}else {
	                $this->session->set_flashdata('error_msg','Email does not exist.');
					redirect('admin/auth/forgot-password'); 
			       
				}
			}
		}else{
			$this->load->view('admin/main/forgot_password'); 
		}
	}
	
	public function forgotPasswordMail($email) {
		
		$link_id = md5('unique'.$email.time());
		$expire_time = strtotime("+20 minutes");
		
		$data['link_id'] = $link_id;
		$data['expire_time'] = $expire_time;
		$data['email'] = $email;
		$data['status'] = 0;
		$this->admin_model->resetLink($data);
		$url = base_url() . "admin/auth/reset-password/".$link_id;
		$msg ='Please <a href="">click here</a> to reset your password. If you are not able to click link than, copy below link and paste in web browser.<br>'.$url;
		forgotPasswordMailSend($email,$msg);
	}
	
	public function resetPassword($id='') {
		$this->redirection();
		if($id=='')
		{
			 redirect('admin');  
			 exit;
		}
		$getData = $this->admin_model->checkLinkIdForResrPassword($id);
		if($getData)
		{
			$status = $getData['status']; 
			$expire_time = $getData['expire_time']; 
			$email = $getData['email']; 
			$current_time  = time();
			if($status!=0)
			{
				$this->session->set_flashdata('error_msg','Reset password url is not valid.');
				redirect('admin');
				exit;
			}else if($current_time>$expire_time)
			{
				$this->session->set_flashdata('error_msg','Reset password url has been expired.');
				redirect('admin');
				exit;
			}
		}else{
			$this->session->set_flashdata('error_msg','Reset password url is not valid.');
			redirect('admin');
			exit;
		}
		
		$view['id'] = $id;
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('cpassword', 'Confirm New Password', 'trim|required|min_length[3]|matches[password]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/main/reset_password'); 
			}else{
				$password = $this->input->post('password');	
				if($check_status = $this->admin_model->checkMailExist($email))
				{
					$userData = array('password' => password_hash($password,PASSWORD_DEFAULT));
					$this->admin_model->updatePassword($userData, $email);
					$this->admin_model->updateStatusForResrPassword($id);
					$this->session->set_flashdata('msg_success', 'You password has been successfully changed!');
					resetPasswordMail($email);
					redirect('admin'); 
				}else {
	                $this->session->set_flashdata('error_msg','Email does not exist.');
					redirect('admin/auth/forgot-password'); 
			       
				}
			}
		}else{
			$this->load->view('admin/main/reset_password',$view); 
		}
	}
	
	public function changePassword() {
		if (!$this->session->userdata('user_id')) {
            	redirect('admin'); 
        	}
		
		$view['page_title']='Change Password';
		$view['main_content']='admin/pages/auth/change_password';
		$user_id = $this->session->userdata('user_id');	
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('c_new_password', 'Confirm Password', 'trim|required|min_length[3]|matches[new_password]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				$current_password = $this->input->post('current_password');	  
				$new_password = $this->input->post('new_password');	  
				$c_new_password = $this->input->post('c_new_password');	  
				
				if($check_status = $this->admin_model->matchOldPassword($user_id, $current_password))
				{
					$userData = array('password' => password_hash($new_password,PASSWORD_DEFAULT));
					$this->admin_model->changePassword($userData, $user_id);
					$this->session->set_flashdata('msg_success', 'You password has been successfully changed!');
					changePasswordMail($user_id);
					redirect('admin/auth/change-password'); 
							
				}else {
	                $this->session->set_flashdata('error_msg','Current password does not match.');
					redirect('admin/auth/change-password'); 
			       
				}
			}
		}else{
			$this->load->view('admin/template_admin',$view);
		}
	}

	public function profile() {
		if (!$this->session->userdata('user_id')) {
            	redirect('admin'); 
        	}
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			
		$view['page_title']='Admin Profile';
		$view['main_content']='admin/pages/auth/profile';
		$user_id = $this->session->userdata('user_id');	
		$view['admin']  = $this->admin_model->getAdminDetail($user_id);
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email');
			$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[3]');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}else{
				$name = $this->input->post('name');	  
				$email = $this->input->post('email');	  
				$mobile = $this->input->post('mobile');	  
				$username = $this->session->userdata('username');
				
				if($email!=$username){
					if($this->admin_model->checkMailExist($email))
					{
						$this->session->set_flashdata('error_msg','Email already exist.');
						redirect('admin/profile'); 
					}
					$userData['email']= htmlspecialchars(strip_tags($this->input->post('email')));
				}
				
				$userData['name']= htmlspecialchars(strip_tags($this->input->post('name')));
				$userData['mobile']= htmlspecialchars(strip_tags($this->input->post('mobile')));
				$userData['updated_date']= date('d-m-Y H:i:s');
				$userData['updated_date_iso']= $date_created;
				$userData['updated_date_timestamp']=  time()*1000;
				
				$this->admin_model->update($userData, $user_id);
				$this->session->set_userdata('username',$email);
				$this->session->set_userdata('email',$email);
				$this->session->set_userdata('mobile',$mobile);
				$this->session->set_userdata('name',$name);
				
				$this->session->set_flashdata('msg_success', 'You profile has been successfully changed!');
				redirect('admin/profile'); 
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}

}