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
		$match['role_master_tbl_id']=array('$nin'=>array(1));
		if(!empty($filter))
		{
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
		$match['role_master_tbl_id']=array('$nin'=>array(1));

		if(!empty($filter))
		{
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
		$results = $this->db->user_master->find($match,['skip' => $data['start']],['limit' => $data['limit']],['sort' => ['added_date_timestamp' => 1]]);
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

}
?>