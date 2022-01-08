<?php
class Admin_model extends CI_Model{
		
	private $db;
	public function __construct(){
		parent::__construct();
		$this->load->library('mongoci');
		$this->db = $this->mongoci->db;
		//$query = $this->mongoci->db->article->insert($data);
	}
	
	public function checkAdminLogin($username, $password) {
		//$con = new MongoDB\Client("mongodb://localhost:27017");
		//$db1 = $con->db;
		// echo '<pre>';
		// print_r($this->db);
		// echo '</pre>';
		//die;
		$cond = array('email'=> $username,'status'=>1,'role_master_tbl_id'=>array('$nin'=>array(3,4)));
		$projection = array("_id" => true,'password'=> TRUE,'role_master_tbl_id'=>TRUE,'hash'=>TRUE);
		$admin_info = $this->db->user_master->findOne($cond,array('projection' =>$projection));
		$result = (array) $admin_info;
		// print_r($result );
		// die;
		if($result){
			 $hash = $admin_info->password; 
			$id = (string)$admin_info->hash;
			if(password_verify($password, $hash)){
				return $id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
		
	public function getAdminDetail($id){
		
		//$cond = array('_id'=> new MongoDB\BSON\ObjectID($id),'status'=>1);
		$cond = array('hash'=> $id,'status'=>1);
		$projection = array("_id" => true,'name'=> TRUE,'mobile'=>TRUE,"email" => true,'role_master_tbl_id'=> TRUE,'status'=>TRUE,'hash'=>TRUE);
		$admin_info = $this->db->user_master->findOne($cond,array('projection' =>$projection));
		return (object) $admin_info;
	}
	
	public function checkMailExist($email){
		$cond = array('email'=>$email);
		$query = $this->db->user_master->count($cond);
		if ($query> 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function update($data, $id){
		//$cond = array('hash'=> new MongoDB\BSON\ObjectID($id);
		$cond = array('hash'=> $id);
		$this->db->user_master->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function matchOldPassword($user_id, $password) {
		$cond = array('hash'=> $user_id);
		$projection = array('hash'=> TRUE,'password'=>TRUE);
		$admin_info = $this->db->user_master->findOne($cond,array('projection' =>$projection));
		$admin_info =  (object) $admin_info;
		
		if($admin_info){
			$hash = $admin_info->password; 
			$id = $admin_info->hash;
			if(password_verify($password, $hash)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
		
	public function changePassword($data, $id){
		$cond = array('hash'=> $id);
		$this->db->user_master->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
			
	public function checkMobileExist($mobile){
		
		$cond = array('mobile'=>$mobile);
		$projection = array("mobile" => true,'status'=> TRUE);
		$query = $this->db->user_master->count($cond);
		if ($query> 0){
			return true;
		}
		else{
			return false;
		}
	}
		
	public function resetLink($data){
		$this->db->forgot_password_link->insertOne($data);
		return true;
	}
		
	public function checkLinkIdForResrPassword($id){
		$cond = array('link_id'=> $id);
		$projection = array("_id" => true,'status'=> TRUE,'expire_time'=>TRUE,'email'=>TRUE);
		$admin_info = $this->db->forgot_password_link->findOne($cond,array('projection' =>$projection));
		return (array) $admin_info;
	}
		
	public function updatePassword($data, $email){
		$cond = array('email'=> $email);
		$this->db->user_master->updateMany($cond,array('$set'=>$data),array("multi"=>false));
		return true;
	}
		
	public function updateStatusForResrPassword($id){
		$cond = array('link_id'=> $id);
		$this->db->forgot_password_link->updateMany($cond,array('$set'=>array('status'=>1)),array("multi"=>false));
		return true;
	}
		
}
?>