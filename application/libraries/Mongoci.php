<?php

class Mongoci {
	private $connect;
	public $db;
	private $hostname;
	private $port;
	private $database;
	private $no_auth;
	private $username;
	private $password;


	function __construct(){ 
		if(!class_exists('MongoDB\Driver\Manager'))
		{
			show_error("The MongoDB PECL extension has not been installed or enabled", 500);
		}
		$ci = get_instance();
		$ci->load->config('mongoci');

		$this->hostname = $ci->config->item('hostname');
		$this->port = $ci->config->item('port');
		$this->database = $ci->config->item('database');
		$this->username = $ci->config->item('username');
		$this->password = $ci->config->item('password');
		

		try {
			$dns = "mongodb://{$this->hostname}:{$this->port}";
			if($ci->config->item('no_auth') === FALSE){
				$options = array();
			}else{
				$options = array('username'=>$this->username, 'password'=>$this->password);
			}
			//$this->connect = $this->db = new MongoDB\Driver\Manager($dns, $options);
			$con = new MongoDB\Client($dns, $options);
			$this->db = $con->{$this->database};
			// echo '<pre>';
			// print_r($this->db);
			// echo '</pre>';
			// die;
		}
		catch (MongoDB\Driver\Exception\Exception $e)
		{
			if(isset($this->debug) == TRUE && $this->debug == TRUE)
			{
				show_error("Unable to connect to MongoDB: {$e->getMessage()}", 500);
			}
			else
			{
				show_error("Unable to connect to MongoDB", 500);
			}
		}
	}
	
	
	public function cursor_data($data){
		$final_array = array();
		foreach(iterator_to_array($data) as $data_array)
		{
		$final_array[] = $data_array;
		}
		return $final_array;
	}
}

?>