<?php
class User_model extends CI_Model{
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}
		
	public function getCountUser($filter){
		
		$match = [];
		$match['status']=array('$nin'=>array(2));
		$match['role_master_tbl_id']=array('$nin'=>array(1,2));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$adminDetail = $this->fetAdminId();
			$user_id = $this->session->userdata('user_id');
			$match['role_master_tbl_id']=array('$in'=>array(3));
			$match['added_by_id']=array('$in'=>array($user_id,$adminDetail->hash));
		}

		if(!empty($filter))
		{
			if(!empty($filter['filter_user_type']))
			{
				$filter_user_type = (int)($filter['filter_user_type']);
				$match['role_master_tbl_id']=array('$in'=>array($filter_user_type));
			}

			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				
				$status1 =trim($filter['filter_status']);
				if($status1=='active')
				{
					$s = 1;
				}elseif($status1=='inactive'){
					$s = 0;
				}
				$match['status']=$s;	
			}
		}
		
		return $query = $this->db->user_master->count($match);
	}
	
	public function getMasterUser($filter,$data = array()){
		$match = [];
		$match['status']=array('$nin'=>array(2));
		$match['role_master_tbl_id']=array('$nin'=>array(1,2));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$adminDetail = $this->fetAdminId();
			$user_id = $this->session->userdata('user_id');
			$match['role_master_tbl_id']=array('$in'=>array(3));
			$match['added_by_id']=array('$in'=>array($user_id,$adminDetail->hash));
		}

		if(!empty($filter))
		{
			if(!empty($filter['filter_user_type']))
			{
				$filter_user_type = (int)($filter['filter_user_type']);
				$match['role_master_tbl_id']=array('$in'=>array($filter_user_type));
			}

			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['mobile'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d',strtotime($filter['filter_from_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				
				$status1 =trim($filter['filter_status']);
				if($status1=='active')
				{
					$s = 1;
				}elseif($status1=='inactive'){
					$s = 0;
				}
				$match['status']=$s;	
			}
		}
		
		$results = $this->db->user_master->find($match,['skip' => $data['start']],['limit' => $data['limit']],['sort' => ['updated_date_timestamp' => -1]]);
		//$results = $this->db->user_master->find($match,$options);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
		
	}
	
	public function updateUser($data,$id){
		
		$cond = array('hash'=>$id);
		$this->db->user_master->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}

	public function getUserById($id){
		$results = $this->db->user_master->findOne(array('hash'=>$id));
		return (Object)$results;
	}

	public function fetAdminId(){
		$results = $this->db->user_master->findOne(array('role_master_tbl_id'=>1),array('projection' =>['_id'=>0,'hash'=>1]));
		return (Object)$results;
	}

	public function getUserDetail($user_id){
		
		$match = [];
		$match['$match']['role_master_tbl_id']=array('$in'=>array(3,4));
		$match['$match']['hash']=$user_id;
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "merchant_info_master",
					"localField" => "added_by_id",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					'pipeline' => [['$project' => ['_id'=>0,'merchant_name'=>1,'merchant_phone'=>1,'contact_name'=>1,'contact_phone'=>1,'contact_email'=>1]]],
					"as" => "merchant"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'cordinate'=>0,'added_date'=>0,'updated_date'=>0,'added_date_iso'=>0,'updated_date_iso'=>0,'verification_token'=>0,'verification_token_time'=>0]),
		);
		$results = $this->db->user_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr = $result;
		}
		// echo '<pre>';
		// print_r($rr);
		// echo '</pre>';
		// die;
		return $rr;
		
	}

	public function createIndex(){
		$this->db->user_master->createIndex(['location'=> "2dsphere" ] );
	}

}
?>