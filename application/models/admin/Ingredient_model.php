<?php
class Ingredient_model extends CI_Model{
		
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}
	public function getCountIngredient($filter){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']=$user_id;
		}

			
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
			array('$project' =>['_id'=>0,'id'=>1,'name'=>1,'status'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'user_hash_id'=>1,'m.merchant_name'=>1,'m.merchant_phone'=>1,'m.contact_name'=>1,'m.contact_phone'=>1,'m.contact_email'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_ingredient->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
		
	}
	
	public function getMasterIngredient($filter,$data = array()){
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']=$user_id;
		}
	
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
			array('$project' =>['_id'=>0,'id'=>1,'name'=>1,'status'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'user_hash_id'=>1,'m.merchant_name'=>1,'m.merchant_phone'=>1,'m.contact_name'=>1,'m.contact_phone'=>1,'m.contact_email'=>1]),
			['$sort' => ['updated_date_timestamp' => 1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_ingredient->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
	
	public function addIngredient($data){
		$this->db->master_ingredient->insertOne($data);
		return true;
	}
	
	public function getIngredientById($id){
		
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_ingredient->findOne($cond);
		return (object) $admin_info;

	}
	
	public function updateIngredient($data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_ingredient->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function deleteIngredient( $data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_ingredient->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function getAllIngredientArray(){
		$user_id = $this->session->userdata('user_id');
		if ($this->session->userdata('role_master_tbl_id')==1 ) {
			$cond = array('status'=>array('$nin'=>array(2,0)));
		}else{
			$cond = array('status'=>array('$nin'=>array(2,0)),'user_hash_id'=>$user_id);
		}
		$results = $this->db->master_ingredient->find($cond,['sort' => ['name' => -1]]);
		$arr =[];
		foreach( $results as $k=>$v)
		{
			$arr[$v['id']] = $v['name'];
				
		}
		return $arr;
	}
		
}
?>