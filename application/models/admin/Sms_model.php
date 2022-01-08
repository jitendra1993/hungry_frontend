<?php
class Sms_model extends CI_Model{
	
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
	
	}
	
	public function getSmsById($where_array,$tbl){
		//$cond = array('id'=> $id);
		$admin_info = $this->db->$tbl->findOne($where_array);
		return (object) $admin_info;
	}
	
	public function updateSMS($data,$cond,$tbl){
		
		$this->db->$tbl->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
	
	public function addSMS($data,$tbl){
		$this->db->$tbl->insertOne($data);
		return true;
	}
	
}
?>