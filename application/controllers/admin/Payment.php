<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/payment_model');
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
		redirect('admin/payment/cash');
	}
	
	public function cash(){
		$view['page_title']='Cash';
		$view['main_content']='admin/pages/payment/cash';
		
		$user_id = $this->session->userdata('user_id');
		$where_array  = array('user_hash_id'=>$user_id,'payment_type'=>'cash');
		$cash_info = $this->payment_model->getPaymentById($where_array,'master_payment_option');
		$view['cash_info'] = $cash_info;
		$id= isset($cash_info->id)?$cash_info->id:0;
		if($this->input->method(TRUE) == 'POST'){
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$hashUnique = md5(uniqid(rand(), true));
			$disabled_cod = (!empty($this->input->post("disabled_cod")))?(int)$this->input->post("disabled_cod"):0;
			$product_data = array(
					'id' =>$hashUnique,
					'disable' => $disabled_cod,
					'payment_type' => 'cash',
					'user_hash_id' => $user_id,
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
				);
				
			if(!empty($id)){
				$where_array  = array('user_hash_id'=>$user_id,'payment_type'=>'cash');
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
				$result = $this->payment_model->updatePayment($product_data,$where_array,'master_payment_option');
				$this->session->set_flashdata('msg_success', 'You have updated cash payment option successfully!');
				redirect(base_url('admin/payment/cash'));
			}else{
				$result = $this->payment_model->addPayment($product_data,'master_payment_option');
				if($result){
					$this->session->set_flashdata('msg_success', 'You have added new cash payment option successfully!');
					redirect(base_url('admin/payment/cash'));
				}
			}
			
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	
	public function nochex(){
		$view['page_title']='Nochex';
		$view['main_content']='admin/pages/payment/nochex';
		
		$user_id = $this->session->userdata('user_id');
		$where_array  = array('user_hash_id'=>$user_id,'payment_type'=>'nochex');
		$nochex_info = $this->payment_model->getPaymentById($where_array,'master_payment_option');
		$view['nochex_info'] = $nochex_info;
		$id= isset($nochex_info->id)?$nochex_info->id:0;
		if($this->input->method(TRUE) == 'POST'){
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$hashUnique = md5(uniqid(rand(), true));
			$disabled_cod = (!empty($this->input->post("disabled_cod")))?(int)$this->input->post("disabled_cod"):0;
			$merchant_id = (!empty($this->input->post("merchant_id")))?$this->input->post("merchant_id"):'';
			$description = (!empty($this->input->post("description")))?$this->input->post("description"):'';
			$product_data = array(
					'id' =>$hashUnique,
					'disable' => $disabled_cod,
					'merchant_id' => htmlspecialchars(strip_tags($merchant_id)),
					'description' => htmlspecialchars(strip_tags($description)),
					'payment_type' => 'nochex',
					'user_hash_id' => $user_id,
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
				);
				
			if(!empty($id)){
				$where_array  = array('user_hash_id'=>$user_id,'payment_type'=>'nochex');
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
				$result = $this->payment_model->updatePayment($product_data,$where_array,'master_payment_option');
				$this->session->set_flashdata('msg_success', 'You have updated  nochex payment option successfully!');
				redirect(base_url('admin/payment/nochex'));
			}else{
				$result = $this->payment_model->addPayment($product_data,'master_payment_option');
				if($result){
					$this->session->set_flashdata('msg_success', 'You have added new nochex option successfully!');
					redirect(base_url('admin/payment/nochex'));
				}
			}
			
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
	
	public function rms(){
		$view['page_title']='RMS';
		$view['main_content']='admin/pages/payment/rms';
		
		$user_id = $this->session->userdata('user_id');
		$where_array  = array('user_hash_id'=>$user_id,'payment_type'=>'rms');
		$rms_info = $this->payment_model->getPaymentById($where_array,'master_payment_option');
		$view['rms_info'] = $rms_info;
		$id= isset($rms_info->id)?$rms_info->id:0;
		if($this->input->method(TRUE) == 'POST'){
			$date_created = new \MongoDB\BSON\UTCDateTime(time()*1000);
			$hashUnique = md5(uniqid(rand(), true));
			$disabled_cod = (!empty($this->input->post("disabled_cod")))?(int)$this->input->post("disabled_cod"):0;
			$merchant_id = (!empty($this->input->post("merchant_id")))?$this->input->post("merchant_id"):'';
			$account_id = (!empty($this->input->post("account_id")))?$this->input->post("account_id"):'';
			$secret = (!empty($this->input->post("secret")))?$this->input->post("secret"):'';
			$description = (!empty($this->input->post("description")))?$this->input->post("description"):'';
			$product_data = array(
					'id' =>$hashUnique,
					'disable' => $disabled_cod,
					'merchant_id' => htmlspecialchars(strip_tags($merchant_id)),
					'account_id' => htmlspecialchars(strip_tags($account_id)),
					'secret' => htmlspecialchars(strip_tags($secret)),
					'description' => htmlspecialchars(strip_tags($description)),
					'payment_type' => 'rms',
					'user_hash_id' => $user_id,
					'added_date'=> date('d-m-Y H:i:s'),
					'updated_date'=>date('d-m-Y H:i:s'),
					'added_date_timestamp'=>time()*1000,
					'updated_date_timestamp'=>time()*1000,
					'added_date_iso'=>$date_created,
					'updated_date_iso'=>$date_created
				);
				
			if(!empty($id)){
				$where_array  = array('user_hash_id'=>$user_id,'payment_type'=>'rms');
				unset($data['added_date'],$data['added_date_timestamp'],$data['added_date_iso'],$data['id'],$data['user_hash_id']);
				$result = $this->payment_model->updatePayment($product_data,$where_array,'master_payment_option');
				$this->session->set_flashdata('msg_success', 'You have updated  rms payment option successfully!');
				redirect(base_url('admin/payment/rms'));
			}else{
				$result = $this->payment_model->addPayment($product_data,'master_payment_option');
				if($result){
					$this->session->set_flashdata('msg_success', 'You have added new rms option successfully!');
					redirect(base_url('admin/payment/rms'));
				}
			}
			
		}
		else{
			$this->load->view('admin/template_admin',$view);
		}
	}
}