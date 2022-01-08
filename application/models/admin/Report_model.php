<?php
class Report_model extends CI_Model{

	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	}
	
	public function getCountBookingSummary($filter){
	
		$user_id = $this->session->userdata('user_id');
		$condition = [];

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$condition['restaurant_id']=$user_id;
		}
		$match = [];
		$match['$match']['u.status']=1;
		$match['$match']['u.role_master_tbl_id']=2;
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$match['$match']['user_hash_id']=$user_id;
		}
		if(!empty($filter))
		{
			if(!empty($filter['merchant_id']))
				{
					echo $ee = $filter['merchant_id'];
					$match['$match']['user_hash_id']=$ee;
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
			array('$project' =>['_id'=>0,'user_hash_id'=>1,'merchant_name'=>1]),
			array('$group' => array('_id' => null,'count' => array('$sum' => 1)))
		);
		$results = $this->db->merchant_info_master->aggregate($ops);
		$total = 0;
		foreach($results as $result) {
			$total = $result->count;
		}
		return $total;
		
	}

	public function getSalesBooking($filter,$data){

		$todayStart =  strtotime(date('d-m-Y 00:00:00'))*1000;
		$todayEnd =  strtotime(date('d-m-Y 23:59:59'))*1000;
		$user_id = $this->session->userdata('user_id');
		
		$condition['added_date_timestamp'] = ['$gte'=>$todayStart,'$lt'=>$todayEnd];
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$condition['restaurant_id']=$user_id;
		}
		
		if(!empty($filter))
		{
			if(!empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
			
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$condition['added_date_timestamp']=['$gte'=>strtotime($from_date)*1000,'$lt'=>strtotime($to_date)*1000];
				
			}
			if(!empty($filter['filter_from_date']) && empty($filter['filter_to_date']))
			{
				
				$from_date = date('Y-m-d 00:00:00',strtotime($filter['filter_from_date']));
				$condition['added_date_timestamp'] = ['$gte'=>strtotime($from_date)*1000];
			}
			
			if(empty($filter['filter_from_date']) && !empty($filter['filter_to_date']))
			{
				$to_date = date('Y-m-d 23:59:59',strtotime($filter['filter_to_date']));
				$condition['added_date_timestamp'] =['$lte'=>strtotime($to_date)*1000];
			}
			
		}
		$match = [];
		$match['$match']['u.status']=1;
		$match['$match']['u.role_master_tbl_id']=2;
		if ($this->session->userdata('role_master_tbl_id')==2) {
			$match['$match']['user_hash_id']=$user_id;
		}
		if(!empty($filter))
		{
			if(!empty($filter['merchant_id']))
			{
				$ee = $filter['merchant_id'];
				$match['$match']['user_hash_id']=$ee;
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
			array('$project' =>['_id'=>0,'user_hash_id'=>1,'merchant_name'=>1]),
			['$sort' => ['merchant_name' => 1]],
			['$skip' =>  $data['start']],
		);
		if(!empty($data['limit']) && $data['limit']>0){
			$ops[] = ['$limit' => $data['limit']];
		}
		
		$results = $this->db->merchant_info_master->aggregate($ops);
		$rr = [];
		foreach($results as $result) {

			$approvedCond = array_merge(array("restaurant_id"=>$result['user_hash_id'],"status"=>array('$in'=>[1,2,3,7,8,9,10,11])),$condition);
			$cancelCond = array_merge(array("restaurant_id"=>$result['user_hash_id'],"status"=>array('$in'=>[4,6,12])),$condition);
			$pendingCond = array_merge(array("restaurant_id"=>$result['user_hash_id'],"status"=>array('$in'=>[5])),$condition);

			$approved = $this->db->master_order_tbl->count($approvedCond);
			$result['approved'] = $approved;
			$cancel = $this->db->master_order_tbl->count($cancelCond);
			$result['cancel'] = $cancel;
			$pending = $this->db->master_order_tbl->count($pendingCond);
			$result['pending'] = $pending;
			$rr[] = $result;
		}
		//die;
		return $rr;
		// echo '<pre>';
		// print_r($condition );
		// echo '</pre>';
		// die;
		

	}

	public function getCountSaleSummary($filter){
		
		$match = [];
		$match['$match']['status']=array('$nin'=>array(0));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['restaurant_id']=$user_id;
		}

		if(!empty($filter))
		{
			
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
					'pipeline' => [['$project' => ['_id'=>0,'merchant_name'=> 1]]],
					"as" => "m"
				)
			),
			array('$unwind'=>'$m'),
			array(
				'$lookup' => array(
					"from" => "order_item_detail",
					"localField" => "order_id",// filed in matched collection
					"foreignField" => "order_id", //filedin current collection
					'pipeline' => [['$project' => ['_id'=>0,'id'=>1,'item_id'=> 1,'quantity'=>1,'price'=>1]]],
					"as" => "order_item"
				)
			),
			array('$unwind'=>'$order_item'),
			array(
				'$lookup' => array(
					"from" => "master_product",
					"localField" => "order_item.item_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					'pipeline' => [['$project' => ['_id'=>0,'item_name'=> 1]]],
					"as" => "product"
				)
			),
			array('$unwind'=>'$product'),
			$match,
			array(
				'$project' =>[
					'_id'=>0,
					'order_type'=>'$order_type',
					'payment_type'=>'$payment_type',
					'payment_status'=>1,
					'status'=>1,
					'merchant_name'=>'$m.merchant_name',
					'item_id'=>'$order_item.item_id',
					'order_item_id'=>'$order_item.id',
					'item_price'=>'$order_item.price',
					'item_quantity'=>'$order_item.quantity',
					'item_name'=>'$product.item_name',
					'mul'=>array('$multiply'=>['$order_item.quantity','$order_item.price'])
				]),
			// array(
			// 	'$group' => array(
			// 		'_id' =>array('item_id'=>'$item_id','merchant_name'=>'$merchant_name'),
			// 		'order_type'=> ['$first'=>'$order_type'],
			// 		'payment_type'=> ['$first'=>'$payment_type'],
			// 		'payment_status'=> ['$first'=>'$payment_status'],
			// 		'status'=> ['$first'=>'$status'],
			// 		'merchant_name'=> ['$first'=>'$merchant_name'],
			// 		'item_id'=> ['$first'=>'$item_id'],
			// 		'item_name'=> ['$first'=>'$item_name'],
			// 		//'order_item_id'=> ['$push'=>'$order_item_id'],
			// 		'item_quantity'=> ['$sum'=>'$item_quantity'],
			// 		'item_price'=> ['$sum'=>'$mul'],
			// 		//'mul'=> ['$push'=>'$mul'],
			// 		'count' => array('$sum' => 1)
			// 		)
			// 	),
			array('$group' => array(
				'_id' =>array('item_id'=>'$item_id','merchant_name'=>'$merchant_name'),
				'count' => array('$sum' => 1)
				)
			)
	
		);
		
		$results = $this->db->master_order_tbl->aggregate($ops);
		return $total =  count(iterator_to_array($results));
	}
	
	public function getSalesSummary($filter,$data = array()){
		$match = [];
		$match['$match']['status']=array('$nin'=>array(0));

		if ($this->session->userdata('role_master_tbl_id')==2) {
			$user_id = $this->session->userdata('user_id');
			$match['$match']['restaurant_id']=$user_id;
		}

		if(!empty($filter))
		{
			
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
					'pipeline' => [['$project' => ['_id'=>0,'merchant_name'=> 1]]],
					"as" => "m"
				)
			),
			array('$unwind'=>'$m'),
			array(
				'$lookup' => array(
					"from" => "order_item_detail",
					"localField" => "order_id",// filed in matched collection
					"foreignField" => "order_id", //filedin current collection
					'pipeline' => [['$project' => ['_id'=>0,'id'=>1,'item_id'=> 1,'quantity'=>1,'price'=>1]]],
					"as" => "order_item"
				)
			),
			array('$unwind'=>'$order_item'),
			array(
				'$lookup' => array(
					"from" => "master_product",
					"localField" => "order_item.item_id",// filed in matched collection
					"foreignField" => "id", //filedin current collection
					'pipeline' => [['$project' => ['_id'=>0,'item_name'=> 1]]],
					"as" => "product"
				)
			),
			array('$unwind'=>'$product'),
			$match,
			array(
				'$project' =>[
					'_id'=>0,
					'order_type'=>'$order_type',
					'payment_type'=>'$payment_type',
					'payment_status'=>1,
					'status'=>1,
					'merchant_name'=>'$m.merchant_name',
					'item_id'=>'$order_item.item_id',
					'order_item_id'=>'$order_item.id',
					'item_price'=>'$order_item.price',
					'item_quantity'=>'$order_item.quantity',
					'item_name'=>'$product.item_name',
					'mul'=>array('$multiply'=>['$order_item.quantity','$order_item.price'])
				]),
			array(
				'$group' => array(
					'_id' =>array('item_id'=>'$item_id','merchant_name'=>'$merchant_name'),
					'order_type'=> ['$first'=>'$order_type'],
					'payment_type'=> ['$first'=>'$payment_type'],
					'payment_status'=> ['$first'=>'$payment_status'],
					'status'=> ['$first'=>'$status'],
					'merchant_name'=> ['$first'=>'$merchant_name'],
					'item_id'=> ['$first'=>'$item_id'],
					'item_name'=> ['$first'=>'$item_name'],
					'item_quantity'=> ['$sum'=>'$item_quantity'],
					'item_price'=> ['$sum'=>'$mul'],
					'single_item_price'=> ['$first'=>'$item_price'],
					'count' => array('$sum' => 1)
					)
				),
				['$skip' =>  $data['start']],
	
		);
		if(!empty($data['limit']) && $data['limit']>0){
			$ops[] = ['$limit' => $data['limit']];
		}
		$results = $this->db->master_order_tbl->aggregate($ops);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		// echo '<pre>';
		// print_r($ops);
		// print_r($rr);
		// echo '</pre>';
		//die;
		return $rr;
	}
	
	
	
	
}
?>