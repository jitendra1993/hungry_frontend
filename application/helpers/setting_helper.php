<?php
function get_store_detail_by_id($id) {
	
    // Get current CodeIgniter instance
    $CI =& get_instance();
	$CI->load->library('mongoci');
	$db = $CI->mongoci->db;

	$url = API_URL.'/api/common/restaurant/setting/'.$id;
	$response = getCurlWithOutAuthorizationWithOutData($url);
	return $response['data'];
	
	
	
}

function get_admin_store_detail() {
    // Get current CodeIgniter instance
	$CI =& get_instance();
	$CI->load->library('mongoci');
	$db = $CI->mongoci->db;

    $url = API_URL.'/api/common/admin/setting';
	$response = getCurlWithOutAuthorizationWithOutData($url);
	$status = $response['status'];
	$result = $response['data'];
	$message = $response['message'];
	return $result;
}
	

	
function seoTag() {

    $CI =& get_instance();
	$CI->load->library('mongoci');
	$db = $CI->mongoci->db;
	$sql = $db->master_seo_page->find(['status'=>1]);
	$arr= [];
	foreach($sql as $value){
		
		$arr[$value['page_name']] = array(
			'title'=>$value['title'],
			'keywords'=>$value['keywords'],
			'description'=>$value['description']
			);
		
	}
	return $arr;
}	
