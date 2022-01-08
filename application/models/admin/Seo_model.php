<?php
class Seo_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}
	
	public function getCountSeopage($filter){
		
		$match = [];
		$match['status']=array('$nin'=>array(2));

		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['page_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['title'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['keywords'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$or']=$name;
				
			}
			
			if(!empty($filter['filter_status']))
			{
				
				$status =trim($filter['filter_status']);
				$s =0;
				if($status=='inactive')
				{
					$s = 0;
				}elseif($status=='active'){
					$s = 1;
				}
				$match['status']=$s;	
			}
		}
		
		return $query = $this->db->master_seo_page->count($match);
	}
	
	public function getMasterSeopage($filter,$data = array()){
		
		$match = [];
		$match['status']=array('$nin'=>array(2));

		if(!empty($filter))
		{
			if(!empty($filter['filter_name']))
			{
				$filter_name = trim($filter['filter_name']);
				$name[0]['page_name'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[1]['title'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[2]['keywords'] = array('$regex'=>$filter_name,'$options'=>'i');
				$name[3]['description'] = array('$regex'=>$filter_name,'$options'=>'i');
				$match['$or']=$name;
				
			}
			
			if(!empty($filter['filter_status']))
			{
				
				$status =trim($filter['filter_status']);
				$s =0;
				if($status=='inactive')
				{
					$s = 0;
				}elseif($status=='active'){
					$s = 1;
				}
				$match['status']=$s;	
			}
		}
		
		$results = $this->db->master_seo_page->find($match,['skip' => $data['start']],['limit' => $data['limit']],['sort' => ['added_date_timestamp' => 1]]);
		$rr = [];
		foreach($results as $result) {
			$rr[] = $result;
		}
		return $rr;
		
	}
	
	public function getSeopageById($id){
		
		$cond = array('id'=> $id);
		$admin_info = $this->db->master_seo_page->findOne($cond);
		return (object) $admin_info;
	}
	
	public function deleteSeopage( $data,$id){
		
		$cond = array('id'=>$id);
		$this->db->master_seo_page->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function updateSeopage($data,$id){
		$cond = array('id'=>$id);
		$this->db->master_seo_page->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function addSeopage($data){
		$this->db->master_seo_page->insertOne($data);
		return true;
	}
	
}
?>