<?php
class Catalog_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	}
	
	public function getCountMainCategory($filter){
		
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
			array('$project' =>['_id'=>0,'added_date_iso'=>0,'updated_date_iso'=>0,'m._id'=>0,'m.country'=>0,'m.country_id'=>0,'m.state'=>0,'m.state_id'=>0,'m.city'=>0,'m.city_id'=>0,'m.address'=>0,'m.pincode'=>0,'m.about'=>0,'m.logo'=>0,'m.favicon'=>0,'m.social_media'=>0,'m.site_url'=>0,'m.added_by'=>0,'m.added_date_timestamp'=>0,'m.updated_date_timestamp'=>0,'m.added_date_iso'=>0,'m.updated_date_iso'=>0]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_category->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
		
	}
	
	public function getMasterCategory($filter,$data = array()){
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
			array('$project' =>['_id'=>0,'added_date_iso'=>0,'updated_date_iso'=>0,'m._id'=>0,'m.country'=>0,'m.country_id'=>0,'m.state'=>0,'m.state_id'=>0,'m.city'=>0,'m.city_id'=>0,'m.address'=>0,'m.pincode'=>0,'m.about'=>0,'m.logo'=>0,'m.favicon'=>0,'m.social_media'=>0,'m.site_url'=>0,'m.added_by'=>0,'m.added_date_timestamp'=>0,'m.updated_date_timestamp'=>0,'m.added_date_iso'=>0,'m.updated_date_iso'=>0]),
			['$sort' => ['name' => 1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_category->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
	}
	
	public function addMasterCategory($data){
		$this->db->master_category->insertOne($data);
		return true;
	}
	
	public function getMasterCategoryById($id){
		
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_category->findOne($cond);
		return (object) $admin_info;

	}
	
	public function updateMasterCategory($data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function deleteMasterCategory( $data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function getCountSubCategory($filter){
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		$match['$match']['m.status']=array('$nin'=>array(2));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']=$user_id;
		}	
			
		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.description'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[0]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[1]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[2]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[3]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[4]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[5]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
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
			if(!empty($filter['filter_master_cat_id']))
			{
				$master_cat_id = trim($filter['filter_master_cat_id']);
				$match['$match']['category_id']=$master_cat_id;	
			}
			
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "master_category",
					"localField" => "category_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_sub_category->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		// echo $total;
		// die;
		return $total;
	}
	
	public function getSubCategory($filter,$data = array()){
		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		$match['$match']['m.status']=array('$nin'=>array(2));
		
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['user_hash_id']=$user_id;
		}

		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['m.name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['m.description'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[0]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[1]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[2]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[3]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[4]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
				// $name[5]['name'] = array('$regex'=>$filter_name,'$options'=>'i');
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
			if(!empty($filter['filter_master_cat_id']))
			{
				$master_cat_id = trim($filter['filter_master_cat_id']);
				$match['$match']['category_id']=$master_cat_id;	
			}
			
		}
		
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "master_category",
					"localField" => "category_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					"as" => "m"
				)
			),
			array(
				'$lookup' => array(
					"from" => "master_category",
					"localField" => "category_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					"as" => "m"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'id'=>1,'category_id'=>1,'name'=>1,'description'=>1,'sort_order'=>1,'status'=>1,'user_hash_id'=>1,'image'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'m.name'=>1,'m.description'=>1]),
			['$sort' => ['name' => -1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_sub_category->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		// echo '<pre>';
		// print_r($rr);
		// die;
		return $rr;
	}
	
	public function addSubCategory($data){
		$this->db->master_sub_category->insertOne($data);
		return true;
	}
	
	public function getSubCategoryById($id){
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_sub_category->findOne($cond);
		return (object) $admin_info;
	}
	
	public function updateSubCategory($data,$id){
		$cond = array('id'=>$id);
		$this->db->master_sub_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function deleteSubCategory( $data,$id){
		$cond = array('id'=>$id);
		$this->db->master_sub_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function getCountAddonCategory($filter){
		
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
				$match['$match']['m.status']=$status;	
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
			array('$project' =>['id'=>0]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_addon_category->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
	}
		
	public function getMasterAddOnCategory($filter,$data = array()){
		
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
			array('$project' =>['_id'=>0,'name'=>1,'description'=>1,'sort_order'=>1,'meal_deal'=>1,'default_qty'=>1,'multiple_meal_deal'=>1,'status'=>1,'added_date_timestamp'=>1,'updated_date_timestamp'=>1,'user_hash_id'=>1,'id'=>1,'m.contact_email'=>1,'m.contact_phone'=>1,'m.contact_name'=>1,'m.merchant_phone'=>1,'m.merchant_name'=>1]),
			['$sort' => ['updated_date_timestamp' => 1]],
			['$skip' =>  $data['start']],
			['$limit' => $data['limit']]
		);
		
		$results = $this->db->master_addon_category->aggregate($ops);
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
		
	public function getMasterAddonCategoryById($id){
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_addon_category->findOne($cond);
		return (object) $admin_info;
	}
		
	public function updateMasterAddonCategory($data,$id){
		$cond = array('id'=>$id);
		$this->db->master_addon_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function addMasterAddonCategory($data){
		$this->db->master_addon_category->insertOne($data);
		return true;
	}
		
	public function addonCategoryDelete( $data,$id){
		$cond = array('id'=>$id);
		$this->db->master_addon_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function deleteAddonCategory( $data,$id){
		$cond = array('id'=>$id);
		$this->db->master_addon_category->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function getMasterSubCategoryArray($cat_id){
		$cond = array('status'=>array('$nin'=>array(2,0)),'category_id'=>array('$in'=>$cat_id));
		$results = $this->db->master_sub_category->find($cond,['sort' => ['name' => -1]]);
		$arr =[];
		foreach( $results as $k=>$v)
		{
			$arr[$v['id']] = $v['name'];
				
		}
		return $arr;
	}
	
	
	public function getSubCategories($filter){
		
		$cond = [];
		$cond['status'] = array('$nin'=>array(2,0));
		$projection = array('id'=>1,'name'=>1);
		
		$html ='';
		if(!empty($filter['cat_id'])){
			
			$cat_id = explode(',',trim($filter['cat_id']));
			$sub_cat_id = array_filter(explode(',',trim($filter['sub_cat_ids'])));
			
			$cond['category_id'] = array('$in'=>$cat_id);
			// echo '<pre>';
			// print_r($cond);
			// echo '</pre>';
			// die;
			$results = $this->db->master_sub_category->find($cond,array('projection' =>$projection),['sort' => ['name' => -1]]);
			
			foreach( $results as $k=>$v){
				
				$html .='<div class="col-sm-12 col-md-3 col-form-label pr-0 pb-0">
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input subcategoryIds"  master_sub_cat_id="'.$v['id'].'" id="sub_categories_'.$v['id'].'" name="sub_categories['.$v['id'].']"  value="1" '.(in_array($v['id'],$sub_cat_id)?'checked':"").'>
					<label class="custom-control-label" for="sub_categories_'.$v['id'].'">'.$v['name'].'</label>
				</div>
				</div>';
			}
		}

		$html .='<div class="has-danger form-control-feedback err_sub_categories row"></div>';
		echo $html;
		exit;
	}
	
	public function getMasterCategoryArray(){

		$user_id = $this->session->userdata('user_id');
	
		if ($this->session->userdata('role_master_tbl_id')==1 ) {
			$cond = array('status'=>array('$nin'=>array(2,0)));
		}else{
			$cond = array('status'=>array('$nin'=>array(2,0)),'user_hash_id'=>$user_id);
		}
		
		$results = $this->db->master_category->find($cond,['sort' => ['name' => -1]]);
		$arr =[];
		foreach( $results as $k=>$v)
		{
			$arr[$v['id']] = $v['name'];
				
		}
		return $arr;
	}
	
}
?>