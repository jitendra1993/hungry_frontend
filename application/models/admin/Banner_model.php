<?php
class Banner_model extends CI_Model{
		
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	}
	
	public function getCountBanner($filter){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
			
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
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
				$status = (trim($filter['filter_status'])=='active')?1:0;
				$match['$match']['status']=$status;	
			}
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "merchant_info_master",
					"localField" => "user_hash_id",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_banner->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
	}
	
	public function getMasterBanner($filter,$data = array()){
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
			
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
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
				$status = (trim($filter['filter_status'])=='active')?1:0;
				$match['$match']['status']=$status;	
			}
		}
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "merchant_info_master",
					"localField" => "user_hash_id",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'m.merchant_name'=>1,'m.merchant_phone'=>1,'m.contact_name'=>1,'m.contact_phone'=>1,'m.contact_email'=>1,'id'=>1,'name'=>1,'alt_text'=>1,'description'=>1,'status'=>1,'user_hash_id'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'image'=>1,'status'=>1]),
			['$sort' => ['updated_date_timestamp' => -1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_banner->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
		
	public function getBannerById($id){
		$cond = array('id'=>$id);
		$admin_info = $this->db->master_banner->findOne($cond);
		return  (object) $admin_info;
	}
		
	public function updateBanner($data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_banner->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function addBanner($data){
		$this->db->master_banner->insertOne($data);
		return true;
	}
		
	public function deleteBanner( $data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_banner->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
}
?>