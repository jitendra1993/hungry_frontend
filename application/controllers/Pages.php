<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {
	
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
	}
	
	public function index(){
		$view['page_title']='Home';
		$view['main_content']='pages/home.php';
		$this->load->view('template',$view);
	}
	
	public function about() {
		
		$view['page_title']='About us';
		$view['main_content']='pages/about.php';
		$this->load->view('template',$view);	
	}
	
	public function contact() {
		
		$view['page_title']='Contact us';
		$view['main_content']='pages/contact.php';
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('name', 'Full name', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('phoneNumber', 'Phone number', 'trim|required|integer|min_length[3]');
			$this->form_validation->set_rules('subject', 'subject', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('message', 'message', 'trim|required|min_length[10]');

			
			if ($this->form_validation->run() == FALSE) {
				
				$this->load->view('template',$view);
			}else{
				$name = htmlspecialchars(strip_tags($this->input->post('name')));
				$email = htmlspecialchars(strip_tags($this->input->post('email')));
				$phoneNumber = htmlspecialchars(strip_tags($this->input->post('phoneNumber')));
				$subject = htmlspecialchars(strip_tags($this->input->post('subject')));
				$message = htmlspecialchars(strip_tags($this->input->post('message')));
				$send = contactEnquiryMailSend($name,$email,$phoneNumber,$subject,$message);
			
				$this->session->set_flashdata('msg_success', 'Your enquiry has been successfully sent.');
				redirect(base_url('contact-us'));
				
			}
		}else
		{
			$this->load->view('template',$view);	
		}
	}

	public function terms() {
		
		$view['page_title']='Terms and condition';
		$view['main_content']='pages/terms.php';
		$this->load->view('template',$view);	
	}
	
	public function policy() {
		
		$view['page_title']='Policy';
		$view['main_content']='pages/policy.php';
		$this->load->view('template',$view);	
	}
	
	public function privacy() {
		
		$view['page_title']='Privacy';
		$view['main_content']='pages/privacy.php';
		$this->load->view('template',$view);	
	}
	
	public function review(){
		$view['page_title']='review';
		$view['main_content']='pages/review.php';
		$this->load->view('template',$view);
	}
	
	public function gallery(){
		$view['page_title']='gallery';
		$view['main_content']='pages/gallery.php';
		$this->load->view('template',$view);
	}
	
}
