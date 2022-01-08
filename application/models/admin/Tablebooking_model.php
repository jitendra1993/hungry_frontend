<?php
class Tablebooking_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	}
	
	public function getCountBookingSetting($filter){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		$match['$match']['role_master_tbl_id']=array('$nin'=>array(1));
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['hash']= $user_id;
		}	
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$match']['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				$status = (trim($filter['filter_status'])=='approved')?1:0;
				$match['$match']['status']=$status;		
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "master_table_booking_setting",
					"localField" => "hash",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'hash'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->user_master->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
	}
	
	public function getMasterBookingSetting($filter,$data){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		$match['$match']['role_master_tbl_id']=array('$nin'=>array(1));
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['hash']= $user_id;
		}	
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$match']['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				$status = (trim($filter['filter_status'])=='approved')?1:0;
				$match['$match']['status']=$status;		
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "master_table_booking_setting",
					"localField" => "hash",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'hash'=>1,'name'=>1,'email'=>1,'mobile'=>1,'status'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'m.table_booking_status'=>1,'m.accept_booking_sameday'=>1]),
			['$sort' => ['updated_date_timestamp' => 1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->user_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		// echo '<pre>';
		// print_r($rr);
		// echo '</pre>';
		// die;
		return $rr;
	}
	
	public function getTableBookingSettingById($id){
		
		$cond = array('user_hash_id'=> $id);
		$admin_info = $this->db->master_table_booking_setting->findOne($cond);
		return (object) $admin_info;
	}
	
	public function updateTableBookingSetting($data,$user_id){
		
		$cond = array('user_hash_id'=>$user_id);
		$this->db->master_table_booking_setting->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function addTableBookingSetting($data){
		$this->db->master_table_booking_setting->insertOne($data);
		return true;
	}
	
	public function getCountBooking($filter){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']= $user_id;
		}	
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['m.mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[6]['booking_notes'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$match']['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['booking_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$match['$match']['booking_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['booking_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				$status =trim($filter['filter_status']);
				$s =0;
				if($status=='pending')
				{
					$s = 0;
				}elseif($status=='approved'){
					$s = 1;
				}elseif($status=='denied'){
					$s = 3;
				}
				$match['$match']['status']=$s;		
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_hash_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'user_hash_id'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_table_booking->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
	}
	
	public function getMasterBooking($filter,$data = array()){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']= $user_id;
		}	
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['m.mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[6]['booking_notes'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$match']['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['booking_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$match['$match']['booking_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['$match']['booking_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				$status =trim($filter['filter_status']);
				$s =0;
				if($status=='pending')
				{
					$s = 0;
				}elseif($status=='approved'){
					$s = 1;
				}elseif($status=='denied'){
					$s = 3;
				}
				$match['$match']['status']=$s;		
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_hash_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'id'=>1,'number_guest'=>1,'booking_date'=>1,'booking_time'=>1,'name'=>1,'email'=>1,'mobile'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'user_hash_id'=>1,'status'=>1,'m.name'=>1,'m.email'=>1,'m.mobile'=>1]),
			['$sort' => ['updated_date_timestamp' => 1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_table_booking->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
	
	public function getTableBookingById($id){
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_table_booking->findOne($cond);
		return (object) $admin_info;
	}
	
	public function deleteBooking( $data,$id){
		$cond = array('id'=>$id);
		$this->db->master_table_booking->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
		
	}
	
	public function updateTableBooking($data,$id){
		$cond = array('id'=>$id);
		$this->db->master_table_booking->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function addTableBooking($data){
		$this->db->master_table_booking->insertOne($data);
		return true;
	}
	
}

?>