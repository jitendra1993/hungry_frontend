<?php
class Point_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}
	
	public function getPointSettingById($user_id){
		$cond = array('user_hash_id'=> $user_id);
		$admin_info = $this->db->master_loyalty_point->findOne($cond);
		return (object) $admin_info;
	}
	
	public function updatePoints($data,$user_id){
		$cond = array('user_hash_id'=>$user_id);
		$this->db->master_loyalty_point->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;

	}
	
	public function addPoints($data){
		$this->db->master_loyalty_point->insertOne($data);
		return true;
	}

}
?>