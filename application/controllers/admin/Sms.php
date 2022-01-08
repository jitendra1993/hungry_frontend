<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/sms_model');
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
		$view['page_title']='SMS';
		$view['main_content']='admin/pages/sms/sms';
		
		$user_id = $this->session->userdata('user_id');
		$where_array  = array('user_hash_id'=>$user_id);
		$sms_info = $this->sms_model->getSmsById($where_array,'master_sms_setting');
		$view['sms_info'] = $sms_info;
		$id= isset($sms_info->id)?$sms_info->id:0;
		if($this->input->method(TRUE) == 'POST'){
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$hashUnique = md5(uniqid(rand(), true));
			$disabled = (!empty($this->input->post("disabled")))?(int)$this->input->post("disabled"):0;
			$sms_url = (!empty($this->input->post("sms_url")))?$this->input->post("sms_url"):'';
			$sms_id = (!empty($this->input->post("sms_id")))?$this->input->post("sms_id"):'';
			$sms_password = (!empty($this->input->post("sms_password")))?$this->input->post("sms_password"):'';
			$sms_sender = (!empty($this->input->post("sms_sender")))?$this->input->post("sms_sender"):'';
			$sms_template = (!empty($this->input->post("sms_template")))?$this->input->post("sms_template"):'';
			$product_data = array(
					'id' =>$hashUnique,
					'disable' => $disabled,
					'sms_url' => htmlspecialchars(strip_tags($sms_url)),
					'sms_id' => htmlspecialchars(strip_tags($sms_id)),
					'sms_password' => htmlspecialchars(strip_tags($sms_password)),
					'sms_sender' => htmlspecialchars(strip_tags($sms_sender)),
					'sms_template' => htmlspecialchars(strip_tags($sms_template)),
					'user_hash_id' => $user_id,
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
				);
				
			if(!empty($id)){
				$where_array  = array('user_hash_id'=>$user_id);
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
				$result = $this->sms_model->updateSMS($product_data,$where_array,'master_sms_setting');
				$this->session->set_flashdata('msg_success', 'You have updated sms setting successfully!');
				redirect(base_url('admin/sms'));
			}else{
				$result = $this->sms_model->addSMS($product_data,'master_sms_setting');
				if($result){
					$this->session->set_flashdata('msg_success', 'You have added sms setting successfully!');
					redirect(base_url('admin/sms'));
				}
			}
			
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
}