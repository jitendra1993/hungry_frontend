<?php
class Order_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}

	public function getCountOrder($filter){
	
		$match = [];
		$match['$match']['status']=array('$nin'=>array(0));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['restaurant_id']=$user_id;
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
				$name[0]['product.item_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['instruction'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['payment_remark'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['order_id'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[6]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[7]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[8]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
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
				$status =trim($filter['filter_status']);
				$match['$match']['status']=(int)$status;	
			}

			if(!empty($filter['settled']))
			{
				$match['$match']['settled']=array('$nin'=>array(1));	
			}

			if(!empty($filter['status_not_in']))
			{
				$ee = $filter['status_not_in'];
				$match['$match']['status']=array('$nin'=>array(0,4,5,6,12));
			}

			if(!empty($filter['merchant_id']))
			{
				$ee = $filter['merchant_id'];
				$match['$match']['restaurant_id']=$ee;
			}

			if(!empty($filter['filter_order_type']))
			{
				$ee = (int)$filter['filter_order_type'];
				$match['$match']['order_type']=$ee;
			}
		}
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "merchant_info_master",
					"localField" => "restaurant_id",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					"as" => "m"
				)
			),
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "u"
				)
			),
			array(
				'$lookup' => array(
					"from" => "order_item_detail",
					"localField" => "order_id",// filed in matched collection
					"foreignField" => "order_id", //filedin current collection
					"as" => "order_item"
				)
			),
			array(
				'$lookup' => array(
					"from" => "master_product",
					"localField" => "order_item.item_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					"as" => "product"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'order_id'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		
		$results = $this->db->master_order_tbl->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
		
	}
	
	public function getMasterOrder($filter,$data = array()){
		$match = [];
		$match['$match']['status']=array('$nin'=>array(0));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['restaurant_id']=$user_id;
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
				$name[0]['product.item_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['instruction'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['payment_remark'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['order_id'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[4]['m.merchant_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[5]['m.merchant_phone'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[6]['m.contact_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[7]['m.contact_email'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[8]['m.address'] = array('$regex'=>$filter_name,'$options'=>'i');
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
				$status =trim($filter['filter_status']);
				$match['$match']['status']=(int)$status;	
			}
			if(!empty($filter['settled']))
			{
				$match['$match']['settled']=array('$nin'=>array(1));	
			}
			if(!empty($filter['status_not_in']))
			{
				$ee = $filter['status_not_in'];
				$match['$match']['status']=array('$nin'=>array(0,4,5,6,12));
			}
			if(!empty($filter['merchant_id']))
			{
				$ee = $filter['merchant_id'];
				$match['$match']['restaurant_id']=$ee;
			}

			if(!empty($filter['filter_order_type']))
			{
				$ee = (int)$filter['filter_order_type'];
				$match['$match']['order_type']=$ee;
			}
		}
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "merchant_info_master",
					"localField" => "restaurant_id",// filed in matched collection
					"foreignField" => "user_hash_id", //filedin current collection
					"as" => "m"
				)
			),
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "u"
				)
			),

			array(
				'$lookup' => array(
					"from" => "order_item_detail",
					"localField" => "order_id",// filed in matched collection
					"foreignField" => "order_id", //filedin current collection
					"as" => "order_item"
				)
			),
			
			array(
				'$lookup' => array(
					"from" => "master_product",
					"localField" => "order_item.item_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					"as" => "product"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'order_id'=>1,'restaurant_id'=>1,'user_id'=>1,'order_type'=>1,'payment_type'=>1,'address_id'=>1,'platform'=>1,'delivery_time'=>1,'total_item'=>1,'discount'=>1,'driver_status'=>1,'driver_id'=>1,'status'=>1,'service_charge'=>1,'sub_total'=>1,'grand_total'=>1,'payment_status'=>1,'added_date_timestamp'=>1,'guest_name'=>1,'guest_email'=>1,'guest_phone'=>1,'settled'=>1,'m.user_hash_id'=>1,'m.merchant_name'=>1,'m.merchant_phone'=>1,'m.contact_name'=>1,'m.contact_phone'=>1,'m.contact_email'=>1,'u.name'=>1,'u.email'=>1,'u.mobile'=>1,'product.id'=>1,'product.item_name'=>1]),
			['$sort' => ['added_date_timestamp' => -1]],
			['$skip' =>  $data['start']],
			
		);
		if(!empty($data['limit']) && $data['limit']>0){
			$ops[] = ['$limit' => $data['limit']];
		}
		// echo '<pre>';
		// print_r($ops);
		// echo '</pre>';
		// die;
		$results = $this->db->master_order_tbl->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		
		return $rr;
		
	}

	public function updateOrder($data,$id){
		$cond = array('order_id'=>$id);
		$this->db->master_order_tbl->updateOne($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}

	public function getStore(){
		
		$match = [];
		$match['$match']['u.status']=1;
		$match['$match']['u.role_master_tbl_id']=2;
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
			array('$project' =>['_id'=>0,'user_hash_id'=>1,'merchant_name'=>1,'merchant_phone'=>1,'contact_name'=>1,'contact_phone'=>1,'contact_email'=>1]),
			['$sort' => ['merchant_name' => 1]]
		);
		
		$results = $this->db->merchant_info_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
		
	}
	
	public function todaySalesPrint($filter){

		$data =[];
		$merchant = array('merchant_name'=>'All','address'=>'N/F','contact_phone'=>'N/F');
		$user_id = $this->session->userdata('user_id');
		$match = [];
		$match['$match']['status']=array('$nin'=>array(0,4,5,6,12));
		$match['$match']['settled']=0;


		$collectionCond = array('settled'=>0,'order_type'=>1,"status"=>array('$nin'=>[0,4,5,6,12]));
		$deliveryCond = array('settled'=>0,'order_type'=>2,"status"=>array('$nin'=>[0,4,5,6,12]));
		$cashCond = array('settled'=>0,'payment_type'=>1,"status"=>array('$nin'=>[0,4,5,6,12]));
		$onlineCond = array('settled'=>0,'payment_type'=>2,"status"=>array('$nin'=>[0,4,5,6,12]));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$match['$match']['restaurant_id']=$user_id;
			$collectionCond['restaurant_id']=$user_id;
			$deliveryCond['restaurant_id']=$user_id;
			$cashCond['restaurant_id']=$user_id;
			$onlineCond['restaurant_id']=$user_id;

			$merchant = $this->db->merchant_info_master->findOne(['user_hash_id'=>$user_id],['projection'=>['_id'=>0,'merchant_name'=>1,'address'=>1,'contact_phone'=>1]]);

		}else if($this->session->userdata('role_master_tbl_id')==1) {
			
			if(!empty($filter)){

				if(!empty($filter['merchant_id'])){
					$merchant_id = $filter['merchant_id'];
					$match['$match']['restaurant_id']=$merchant_id;
					$collectionCond['restaurant_id']=$merchant_id;
					$deliveryCond['restaurant_id']=$merchant_id;
					$cashCond['restaurant_id']=$merchant_id;
					$onlineCond['restaurant_id']=$merchant_id;

					$merchant = $this->db->merchant_info_master->findOne(['user_hash_id'=>$merchant_id],['projection'=>['_id'=>0,'merchant_name'=>1,'address'=>1,'contact_phone'=>1]]);
				}
			}
		}

		//collection count
		$collection = $this->db->master_order_tbl->count($collectionCond);
		$data['collection'] = $collection;
		//delivery count
		$delivery = $this->db->master_order_tbl->count($deliveryCond);
		$data['delivery'] = $delivery;
		//cash count
		$cash = $this->db->master_order_tbl->count($cashCond);
		$data['cash'] = $cash;
		//cash count
		$online = $this->db->master_order_tbl->count($onlineCond);
		$data['online'] = $online;

		$ops = array(
			$match,
			//array('$project' =>['_id'=>0,'order_id'=>1]),
			array('$group' => array('_id' => null,'sum' => array('$sum' => '$grand_total')))
		);

		$results = $this->db->master_order_tbl->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		if(count($rr)>0){
			$totalSales = $rr[0]['sum'];
		}else{
			$totalSales = 0;
		}
		$data['totalSales'] = $totalSales;
		$data['store_info'] = (array)$merchant;
		
		return $data;
		
	}

	public function dashboardOrder(){
		
		$today = time()*1000;
		$todayStart =  strtotime(date('d-m-Y 00:00:00'))*1000;
		$todayEnd =  strtotime(date('d-m-Y 23:59:59'))*1000;

		$monthStart =  strtotime(date('01-m-Y 00:00:00'))*1000;
		$monthEnd =  strtotime(date('31-m-Y 23:59:59'))*1000;

		$match = [];
		$match['$match']['status']=array('$nin'=>array(0,4,5,6,12));
		$match['$match']['added_date_timestamp']=['$gte'=>$todayStart,'$lt'=>$todayEnd];
		$match['$match']['settled']=array('$nin'=>array(1));
		
		$data =[];
		$user_id = $this->session->userdata('user_id');
		$newOrderCond = array("status"=>1,"settled"=>array('$nin'=>[1]));
		$todayOrderCond = array('added_date_timestamp'=>array('$gte'=>$todayStart,'$lt'=>$todayEnd),"status"=>array('$nin'=>[0,4,5,6,12]),"settled"=>array('$nin'=>[1]));
		$monthOrderCond = array('added_date_timestamp'=>array('$gte'=>$monthStart,'$lt'=>$monthEnd),"status"=>array('$nin'=>[0,4,5,6,12]),"settled"=>array('$nin'=>[1]));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$match['$match']['restaurant_id']=$user_id;
			$newOrderCond['restaurant_id']=$user_id;
			$todayOrderCond['restaurant_id']=$user_id;
			$monthOrderCond['restaurant_id']=$user_id;
		}

		//newOrder count
		$newOrder = $this->db->master_order_tbl->count($newOrderCond);
		$data['newOrder'] = $newOrder;

		//todayOrder count
		$todayOrder = $this->db->master_order_tbl->count($todayOrderCond);
		$data['todayOrder'] = $todayOrder;

		//monthOrder count
		$monthOrder = $this->db->master_order_tbl->count($monthOrderCond);
		$data['monthOrder'] = $monthOrder;

		$ops = array(
			$match,
			array('$group' => array('_id' => null,'sum' => array('$sum' => '$grand_total')))
		);

		$results = $this->db->master_order_tbl->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		if(count($rr)>0){
			$totalSales = $rr[0]['sum'];
		}else{
			$totalSales = 0;
		}
		$data['todaySales'] = $totalSales;
		
		return $data;
	}

	public function notification(){
		$user_id = $this->session->userdata('user_id');
		$where['status']=1;
		$where['settled']=array('$nin'=>[1]);
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$where['restaurant_id']=$user_id;
		}
		return $newOrder = $this->db->master_order_tbl->count($where);
	}

	public function settlement(){
		$user_id = $this->session->userdata('user_id');
		$data= array('settled'=>1);
		$cond['restaurant_id']=$user_id;
		$this->db->master_order_tbl->updateMany($cond,array('$set'=>$data));
		echo 1;
	}

	public function getStoreSettingById(){
		$user_id = $this->session->userdata('user_id');
		$cond = array('user_hash_id'=> $user_id);
		$projection = array('projection' =>['_id'=>0,'user_hash_id'=>1,'used_admin_driver'=>1,'merchant_delivery_coverd'=>1,'merchant_distance_type'=>1]);
		$admin_info = $this->db->store_setting_master->findOne($cond,$projection);
		return $result = (object) $admin_info;
	}

	public function getStoreInfoById(){
		$user_id = $this->session->userdata('user_id');
		$cond = array('user_hash_id'=> $user_id);
		$projection = array('projection' =>['_id'=>0,'user_hash_id'=>1,'location'=>1]);
		$admin_info = $this->db->merchant_info_master->findOne($cond,$projection);
		return $result = (object) $admin_info;
	}

	public function distanceBetweenStoreCustomer($storeSetting,$latitude,$longitude,$order_id){

		$store_id = $storeSetting['user_hash_id'];
		$merchant_delivery_coverd = (float)$storeSetting['merchant_delivery_coverd'];
		$merchant_distance_type = $storeSetting['merchant_distance_type'];
		$max_distance = ($merchant_distance_type=='mi')?$merchant_delivery_coverd*1610:$merchant_delivery_coverd*1000;

		$match = [];
		$match['$match']['restaurant_id']=$store_id;
		$match['$match']['order_id']=$order_id;

		$ops = array(
			array(
				'$geoNear'=> array(
					'near'=> array(
						'type'=> 'Point',
						'coordinates'=> [$latitude,$longitude]
					),
					'distanceField'=> 'DistanceBetweenStoreCustomer',
					'spherical'=> true,
				)
			), 
			$match ,
			array('$project' =>[
				'_id'=>0,
				'DistanceBetweenStoreCustomer'=>1
				]
			)
		);

		$results = $this->db->master_order_tbl->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr = $result;
		}
		return (count($rr)>0)?round($rr['DistanceBetweenStoreCustomer'],2):10000;
	}

	public function getAdminDriver($storeSetting,$order_id){

		
		$adminDetail = $this->fetchAdminId();
		$adminid = $adminDetail->hash;
		$match = [];
		$match['$match']['status']=array('$in'=>array(1));
		$match['$match']['role_master_tbl_id']=array('$in'=>array(3));
		$match['$match']['is_online']=1;
		$match['$match']['added_by_id']=array('$in'=>array($adminid));
		$match['$match']['driver_order.order_assigned']=0;
		$storeInfo =[];
		$storeInfo = $this->getStoreInfoById();
		$merchant_delivery_coverd = (float)$storeSetting['merchant_delivery_coverd']+2;
		$merchant_distance_type = $storeSetting['merchant_distance_type'];
		$max_distance = ($merchant_distance_type=='mi')?$merchant_delivery_coverd*1610:$merchant_delivery_coverd*1000;

		if(!empty($storeInfo['location']['coordinates'])){
			$loc = $storeInfo['location']['coordinates'];
			$latitude = (float)$loc[0];
			$longitude = (float)$loc[1];
		}else{
			$latitude = 0;
			$longitude = 0;
		}
		$distanceBetweenStoreCustomer = $this->distanceBetweenStoreCustomer($storeSetting,$latitude,$longitude,$order_id);
		$ops = array(
			array(
				'$geoNear'=> array(
					'near'=> array(
						'type'=> 'Point',
						'coordinates'=> [$latitude,$longitude]
					),
					'distanceField'=> 'DistanceBetweenStoreDriver',
					'maxDistance'=> $max_distance,
					'spherical'=> true
				)
			), 
			array(
				'$lookup' => array(
					"from" => "master_driver_order",
					"localField" => "hash",// filed in matched collection
					"foreignField" => "driver_user_id", //filedin current collection
					'pipeline' => [
									['$match'=> ['order_assigned'=>0,'driver_order_status'=>array('$in'=>array(1,2,3,5))]],
									['$project' => ['_id'=>0,'order_assigned'=>1,'hash'=>1,'order_id'=>1,'driver_user_id'=>1,"driver_order_status"=>1]]
								],
					"as" => "driver_order"
				)
			),
			$match,
			array('$project' =>[
					'_id'=>0,
					'hash'=>1,
					'name'=>1,
					'status'=>1,
					'is_online'=>1,
					'driver_delivery_coverd'=>'$driver_delivery_coverd',
					'driver_distance_type'=>1,
					'driver_order'=>'$driver_order',
					'DistanceBetweenStoreDriver'=>1,
				]
			),
			array('$project' =>[
				'_id'=>0,
				'hash'=>1,
				'name'=>1,
				'status'=>1,
				'is_online'=>1,
				//'driver_delivery_coverd'=>'$driver_delivery_coverd',
				//'driver_distance_type'=>1,
				'driver_order'=>'$driver_order',
				'DistanceBetweenStoreDriver'=>1,
				'userDistanceCoveredInMeter'=>[ '$sum'=> 
												['$multiply'=>[ 
																['$toInt'=> '$driver_delivery_coverd' ], 
																[
																	'$cond' => [
																		'if' => ['$driver_distance_type', 'mi'],
																		'then' => 1600,
																		'else' => 1000
																	] 
																] 
															]
												]
											]
							]
			),
			// array(
			// 	'$match'=> ["userDistanceInMeter"=> ['$lte'=> 5000 ] ]
			// ),
			array('$limit'=>50),
			['$sort' => ['DistanceBetweenStoreDriver' => 1]]
		);
		$results = $this->db->user_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$userDistanceCoveredInMeter = $result['userDistanceCoveredInMeter'];
			if($userDistanceCoveredInMeter>=$distanceBetweenStoreCustomer){
				$rr[] = $result;
			}
		}
		return $rr;

	}

	public function getDriver($order_id){

		$match = [];
		$match['$match']['status']=array('$in'=>array(1));
		$match['$match']['role_master_tbl_id']=array('$in'=>array(3));
		$match['$match']['is_online']=1;
		$match['$match']['driver_order.order_assigned']=0;
		$adminInfo =[];
		$adminDriverRes  =[];
		$adminSetting = $this->getStoreSettingById();
		if($adminSetting['used_admin_driver']==1){
			$adminDriverRes = $this->getAdminDriver($adminSetting,$order_id);
		}
			$user_id = $this->session->userdata('user_id');
			$match['$match']['role_master_tbl_id']=array('$in'=>array(3));
			$match['$match']['added_by_id']=array('$in'=>array($user_id));
		
	
		
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "master_driver_order",
					"localField" => "hash",// filed in matched collection
					"foreignField" => "driver_user_id", //filedin current collection
					'pipeline' => [
									['$match'=> ['order_assigned'=>0,'driver_order_status'=>array('$in'=>array(1,2,3,5))]],
									['$project' => ['_id'=>0,'hash'=>1,'order_assigned'=>1,'order_id'=>1,'driver_user_id'=>1,"driver_order_status"=>1]]
								],
					"as" => "driver_order"
				)
			),
			$match,
			array('$project' =>[
				'_id'=>0,
				'hash'=>1,
				'name'=>1,
				'status'=>1,
				'is_online'=>1,
				'driver_order'=>'$driver_order',
				]
			),
			['$sort' => ['name' => 1]]
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
		return ['adminDriver'=>$adminDriverRes,'storeDriver'=>$rr];
		
	}

	public function fetchAdminId(){
		$results = $this->db->user_master->findOne(array('role_master_tbl_id'=>1),array('projection' =>['_id'=>0,'hash'=>1]));
		return (Object)$results;
	}

	public function addOrderForDriver($data){
		$this->db->master_driver_order->insertOne($data);
		return true;
	}

	public function getOrderInvitedDriverDetail($order_id){ //used in order detail popup

		$match = [];
		$match['$match']['status']=array('$nin'=>array(2));
		$match['$match']['role_master_tbl_id']=array('$in'=>array(3));

		$ops = array(
			array(
				'$lookup' => array(
					"from" => "master_driver_order",
					"localField" => "hash",// filed in matched collection
					"foreignField" => "driver_user_id", //filedin current collection
					'pipeline' => [
									['$match'=> ["order_id"=>$order_id]],
									['$project' => ['_id'=>0,'hash'=>1,'driver_order_status'=>1]]
								],
					"as" => "driver_order"
				)
			),
			array('$unwind'=>'$driver_order'),
			$match,
			array('$project' =>[
				'_id'=>0,
				'name'=>1,
				'mobile'=>1,
				'driver_order_status'=>'$driver_order.driver_order_status',
				]
			),
			['$sort' => ['name' => 1]]
		);
		
		$results = $this->db->user_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		
		return $rr;
		
	}

	public function checkDriverAssignedAjax($order_id){
		return  $this->db->master_order_tbl->count(array('order_id'=>$order_id,'driver_id'=>['$nin'=>['',NULL]]));
	}

	public function assignedDriverDetail($order_id,$driver_id){
		$results = $this->db->user_master->findOne(array('hash'=>$driver_id),array('projection' =>['_id'=>0,'hash'=>1,'name'=>1,'email'=>1,'mobile'=>1,'added_by_role'=>1,'address'=>1,'pincode'=>1,'status'=>1,'is_online'=>1]));
		return (Object)$results;
	}

	public function removeDriver($order_id,$driver_id){
		
		$this->db->master_order_tbl->updateOne(['order_id'=>$order_id],array('$set'=>array('driver_status'=>2,'driver_id'=>'')),array("multi"=>false));
		$this->db->master_driver_order->updateMany(['order_id'=>$order_id],array('$set'=>array('order_assigned'=>0)));
		$this->db->master_driver_order->updateOne(['order_id'=>$order_id,'driver_user_id'=>$driver_id],array('$set'=>array('driver_order_status'=>5)),array("multi"=>false));// remove driver
		return 1;
		
	}
		
}
?>