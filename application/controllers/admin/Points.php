<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Points extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/point_model');
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
		redirect('admin/points-settings');
	}
	
	public function setting(){
		
		$view['page_title']='Loyalty Point Setting';
		$view['main_content']='admin/pages/points/setting';
		$user_id = $this->session->userdata('user_id');
		$mechant_info = $this->point_model->getPointSettingById($user_id);
		$view['setting'] = $mechant_info;
		
		if($this->input->method(TRUE) == 'POST'){
			$this->form_validation->set_rules('earning_points_value', 'Earning Point Value', 'trim|required');
			$this->form_validation->set_rules('earn_above_amount', 'Earn points above order', 'trim|required');
			$this->form_validation->set_rules('min_redeeming_point', 'Mimimum Redeeming Point', 'trim|required');
			$this->form_validation->set_rules('points_apply_order_amt', 'Redeem points above orders', 'trim|required');
			$this->form_validation->set_rules('min_point_used', 'Minimum points can be used', 'trim|required');
			$this->form_validation->set_rules('max_point_used', 'Maximum points can be used', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('admin/template_admin',$view);
			}
			else{
				$id = $this->input->post('id');
				$enable_pts = (!empty($this->input->post("enable_pts")))?(int)$this->input->post("enable_pts"):0;
				$enable_redeem = (!empty($this->input->post("enable_redeem")))?(int)$this->input->post("enable_redeem"):0;
				
				$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
				$hashUnique = md5(uniqid(rand(), true));
				$data = array(
					'id' =>$hashUnique,
					'enable_pts' => $enable_pts,
					'points_based_earn' => $this->input->post('points_based_earn'),
					'pts_earning' =>  htmlspecialchars(strip_tags($this->input->post('pts_earning'))),
					'every_spent' => htmlspecialchars(strip_tags($this->input->post('every_spent'))),
					'earning_points_value' => htmlspecialchars(strip_tags($this->input->post('earning_points_value'))),
					'earn_above_amount' => htmlspecialchars(strip_tags($this->input->post('earn_above_amount'))),
					'enable_redeem' => $enable_redeem,
					'min_redeeming_point' => htmlspecialchars(strip_tags($this->input->post('min_redeeming_point'))),
					'points_apply_order_amt' => htmlspecialchars(strip_tags($this->input->post('points_apply_order_amt'))),
					'min_point_used' => htmlspecialchars(strip_tags($this->input->post('min_point_used'))),
					'max_point_used' => htmlspecialchars(strip_tags($this->input->post('max_point_used'))),
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
					$result = $this->point_model->updatePoints($data,$user_id);
					$this->session->set_flashdata('msg_success', 'You have updated point setting successfully!');
					redirect(base_url('admin/points-settings'));
				}else{
					$result = $this->point_model->addPoints($data);
					if($result){
						$this->session->set_flashdata('msg_success', 'You have added point setting successfully!');
						redirect(base_url('admin/points-settings'));
					}
				}
			}
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	

}