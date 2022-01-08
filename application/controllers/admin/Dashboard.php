<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();	
		$this->load->model('admin/order_model');
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
		
		$view['page_title']='Admin Dashboard';
		$view['main_content']='admin/pages/dashboard';
		$view['name'] = $this->session->userdata('name');
		$view['order_info'] = $this->order_model->dashboardOrder();
		$this->load->view('admin/template_admin',$view);
	}
	

}
	