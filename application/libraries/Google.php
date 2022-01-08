<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
/** 
 * Facebook PHP SDK v5 for CodeIgniter 3.x 
 * 
 * Library for Facebook PHP SDK v5. It helps the user to login with their Facebook account 
 * in CodeIgniter application. 
 * 
 * This library requires the Facebook PHP SDK v5 and it should be placed in libraries folder. 
 * 
 * It also requires social configuration file and it should be placed in the config directory. 
 * 
 * @package     CodeIgniter 
 * @category    Libraries 
 * @author      CodexWorld 
 * @license     http://www.codexworld.com/license/ 
 * @link        http://www.codexworld.com 
 * @version     3.0 
 */ 
 
// Include the autoloader provided in the SDK 
//require_once APPPATH .'third_party/facebook-php-graph-sdk/autoload.php';  
require FCPATH . 'vendor/autoload.php';
 class Google {
	protected $CI;
	private $db;  
	public function __construct(){
		$this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->config->load('google');

		$this->CI->load->library('mongoci');
		$this->db = $this->CI->mongoci->db;
		
		$admin_info = $this->db->social_media_setting_master->findOne();
		$data = (array) $admin_info;
		
		$this->client = new Google\Client();
		$this->client->setRedirectUri($this->CI->config->item('google_redirect_url'));
		$this->client->setClientId($data['google_client_id']);
		$this->client->setClientSecret($data['google_client_secret']);
		$this->client->setScopes(array(
			"https://www.googleapis.com/auth/plus.login",
			"https://www.googleapis.com/auth/plus.me",
			"https://www.googleapis.com/auth/userinfo.email",
			"https://www.googleapis.com/auth/userinfo.profile"
			)
		);
	}

	public function get_login_url(){
		return  $this->client->createAuthUrl();

	}

	public function validate(){	
		
		$info = array();
		if (isset($_GET['code'])) {
			$this->client->authenticate($_GET['code']);
			$access_token = $this->client->getAccessToken();
			$this->client->setAccessToken($access_token);
			$plus = new Google_Service_Oauth2($this->client);
			$person = $plus->userinfo->get();
			$info['id']=$person['id'];
			$info['email']=$person['email'];
			$info['name']=$person['name'];
		}
		return  $info;
	}

}