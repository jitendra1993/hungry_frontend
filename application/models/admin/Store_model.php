<?php
class Store_model extends CI_Model{
	
	private $db;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	}
	
	public function getCountStore($filter){
		
		$match = [];
		$match['$match']['u.status']=array('$nin'=>array(2));
			
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['address'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['u.email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$match']['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
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
				}
				$match['$match']['u.status']=$s;	
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_hash_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "u"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'logo'=>0,'favicon'=>0,'social_media'=>0,'site_url'=>0,'mail_logo_url'=>0,'added_date'=>0,'updated_date'=>0,'added_date_iso'=>0,'updated_date_iso'=>0,'u.added_date'=>0,'u.updated_date'=>0,'u.added_date_iso'=>0,'u.updated_date_iso'=>0,'u.password'=>0]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->merchant_info_master->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
	}
	
	public function getMasterStore($filter,$data = array()){
		
		$match = [];
		$match['$match']['u.status']=array('$nin'=>array(2));
			
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['address'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['u.email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$match']['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$match['$match']['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['$match']['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
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
				}
				$match['$match']['u.status']=$s;	
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_hash_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "u"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'logo'=>0,'favicon'=>0,'social_media'=>0,'site_url'=>0,'mail_logo_url'=>0,'added_date'=>0,'updated_date'=>0,'added_date_iso'=>0,'updated_date_iso'=>0,'u.added_date'=>0,'u.updated_date'=>0,'u.added_date_iso'=>0,'u.updated_date_iso'=>0,'u.password'=>0]),
			['$sort' => ['updated_date_timestamp' => -1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
			
		);
		
		$results = $this->db->merchant_info_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
		
	public function getStoreById($id){
		$out = array();
		$ops = array(
					array(
						'$lookup' => array(
							"from" => "merchant_info_master",
							"localField" => "hash",
							"foreignField" => "user_hash_id",
							"as" => "info_docs"
						)
					),
					array('$match'=>['hash'=>$id,'status'=>array('$nin'=>array(2))]),
					//array('$project' =>['_id'=>0,'info_docs.merchant_name'=>1]),
					 array('$limit' => 1)
				);
		
		$results = $this->db->user_master->aggregate($ops);
		foreach($results as $result) {
			$out = $result;
		}
		return $out;
	}
		
	public function updateClient($data,$id,$column,$tbl){
		$cond = array($column=>$id);
		$this->db->$tbl->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function addClient($data,$table){
		$this->db->$table->insertOne($data);
		return true;
	}
		
	public function getCountry($filter){
		$cond=[];
		if(!empty($filter)){
			 if(!empty($filter['filter_status'])){
				$status = (trim($filter['filter_status'])=='active')?1:0;
				$cond['status'] = $status;
			 }
		}
	
		$projection = array("_id" => false,'id'=> TRUE,'name'=>TRUE);
		$states = $this->db->master_countries->find($cond,array('projection' =>$projection),['sort' => ['name' => 1]]);
		$out = array();
		foreach($states as $result) {
			$out[] = $result;
		}
		return $out;
		
	}
		
	public function getState($filter){
		
		$cond=[];
		if(!empty($filter)){
			 if(!empty($filter['country_id'])){
				$country_id = $filter['country_id'];
				$cond['country_id'] = $country_id;
			 }
		}
	
		
		$projection = array("_id" => false,'id'=> TRUE,'name'=>TRUE);
		$states = $this->db->master_states->find($cond,array('projection' =>$projection),['sort' => ['name' => 1]]);
		$out = array();
		foreach($states as $result) {
			$out[] = $result;
		}
		return $out;
		
	}
		
	public function getCity($filter){
		
		$cond=[];
		if(!empty($filter)){
			 if(!empty($filter['state_id'])){
				$state_id = $filter['state_id'];
				$cond['state_id'] = $state_id;
			 }
		}
	
		$projection = array("_id" => false,'id'=> TRUE,'name'=>TRUE);
		$states = $this->db->master_cities->find($cond,array('projection' =>$projection),['sort' => ['name' => 1]]);
		$out = array();
		foreach($states as $result) {
			$out[] = $result;
		}
		return $out;
	}
		
	public function checkMailExistOnUpdt($email,$id){
		$cond = array('email'=>$email,"hash"=>array('$ne'=>$id));
		$query = $this->db->user_master->count($cond);
		if ($query> 0){
			return true;
		}
		else{
			return false;
		}
		
	}
		
	public function getStoreCategory(){
		$cond=[];
		$cond['status'] = 1;
			 
		$projection = array("_id" => false,'id'=> TRUE,'name'=>TRUE);
		$states = $this->db->store_category->find($cond,array('projection' =>$projection),['sort' => ['name' => -1]]);
		$out = array();
		foreach($states as $result) {
			$out[] = $result;
		}
		return $out;
		
	}
	
	public function createIndex(){
		$this->db->merchant_info_master->createIndex(['location'=> "2dsphere" ] );
	}
		
	}
?>