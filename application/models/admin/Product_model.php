<?php
class Product_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}
	
	public function getAddOnCategoryWithAddonItemArray(){
		$user_id = $this->session->userdata('user_id');
	
		if ($this->session->userdata('role_master_tbl_id')==1 ) {
			$cond = array('status'=>array('$nin'=>array(2,0)));
		}else{
			$cond = array('status'=>array('$nin'=>array(2,0)),'user_hash_id'=>$user_id);
		}

		
		$projection = array('id'=>1,'name'=>1,'description'=>1,'meal_deal'=>1,'default_qty'=>1,'multiple_meal_deal'=>1);
		$results = $this->db->master_addon_category->find($cond,array('projection' =>$projection),['sort' => ['name' => -1]]);
		$arr =[];
		foreach( $results as $k=>$v)
		{
			$category =[];
			$cat_id = $v['id'];
			
			$category['id'] = $v['id'];
			$category['meal_deal'] = $v['meal_deal'];
			$category['default_qty'] = $v['default_qty'];
			$category['name'] = $v['name'];
			$category['multiple_meal_deal'] = $v['multiple_meal_deal'];
			$category['desc'] = $v['description'];
			
			$arr[$cat_id]['category'] = $category;
			$items = $this->getAddonItemArray($cat_id);	
			$arr[$cat_id]['items'] = $items;
		}
		return $arr;
	}
	
	public function getAddonItemArray($cat_id){
		
		$cond = array('status'=>array('$nin'=>array(2,0)),'addon_categories'=>array('$in'=>array($cat_id)));
		$projection = array('id'=>1,'name'=>1,'description'=>1,'price'=>1);
		$results = $this->db->master_addon_item->find($cond,array('projection' =>$projection),['sort' => ['name' => -1]]);
		$arr =[];
		$item =[];
		foreach( $results as $k=>$v)
		{
			$arr =[];
			$arr['item_id'] = $v['id'];
			$arr['item_name'] = $v['name'];
			$arr['item_desc'] = $v['description'];
			$arr['item_price'] = $v['price'];
			$item[] = $arr;
				
		}
		return $item;
	}
	
	public function getCountMasterProduct($filter){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']=$user_id;
		}
			
		if(!empty($filter))
		{
			if(!empty($filter['type'])){
				$type = (int)trim($filter['type']);
				$match['$match']['type']=array('$all'=>array($type));
			}
			
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['item_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[6]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
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
			array('$project' =>['_id'=>0,'id'=>1,'item_name'=>1,'status'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'user_hash_id'=>1,'m.merchant_name'=>1,'m.merchant_phone'=>1,'m.contact_name'=>1,'m.contact_phone'=>1,'m.contact_email'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_product->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		// echo '<pre>';
		// print_r($match);
		// echo '</pre>';
		// echo $total;
		// die;
		return $total;
	}
	
	public function getMasterProduct($filter,$data = array()){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']=$user_id;
		}
			
		if(!empty($filter))
		{
			if(!empty($filter['type'])){
				$type =(int) trim($filter['type']);
				$match['$match']['type']=array('$all'=>array($type));
			}
			
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['item_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[6]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
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
			array('$project' =>['_id'=>0,'id'=>1,'item_name'=>1,'description'=>1,'main_cat_ids'=>1,'sub_cat_ids'=>1,'two_flavors'=>1,'in_stock'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'user_hash_id'=>1,'status'=>1,'image'=>1,'m.merchant_name'=>1,'m.merchant_phone'=>1,'m.contact_name'=>1,'m.contact_phone'=>1,'m.contact_email'=>1]),
			['$sort' => ['updated_date_timestamp' => -1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_product->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
	
	public function getMasterProductById($id){
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_product->findOne($cond);
		return (object) $admin_info;
	}
	
	public function addMasterProduct($data){
		$this->db->master_product->insertOne($data);
		return true;
	}
	
	public function updateMasterProduct($data,$id){
		$cond = array('id'=>$id);
		$this->db->master_product->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function deleteMasterProduct( $data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_product->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	
}
?>