<?php
class Storecategory_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	}
	
	public function getCountStoreCategory($filter){
		
		$match = [];
		$match['status']=array('$nin'=>array(2));

		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lte'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				
				$status = (trim($filter['filter_status'])=='active')?1:0;
				$match['status']=$status;	
			}
		}
		
		return $query = $this->db->store_category->count($match);
		
		
	}
	
	public function getMasterCategory($filter,$data = array()){
		$match = [];
		$match['status']=array('$nin'=>array(2));

		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$or']=$name;
				
			}
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lte'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$match['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$match['added_date_timestamp']=['$lte'=>strtotime($from_date)*1000];
			}
			
			if(!empty($filter['filter_status']))
			{
				
				$status = (trim($filter['filter_status'])=='active')?1:0;
				$match['status']=$status;	
			}
		}
		
		$results = $this->db->store_category->find($match,['skip' => $data['start'],'limit' => $data['limit'],['sort' => ['name' => -1]]);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
	
	public function addStoreCategory($data){
		$this->db->store_category->insertOne($data);
		return true;
	}
	
	public function getStoreCategoryById($id){
		
		$cond = array('id'=> $id);
		$admin_info = $this->db->store_category->findOne($cond);
		return (object) $admin_info;

	}
	
	public function updateStoreCategory($data,$id){
		
		$cond = array('id'=>$id);
		$this->db->store_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function deleteStoreCategory( $data,$id){
		
		$cond = array('id'=>$id);
		$this->db->store_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
}
?>