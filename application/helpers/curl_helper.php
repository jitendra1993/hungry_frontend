<?php
function postCurlWithOutAuthorizationJson($url,$data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}

function getCurlWithOutAuthorizationJson($url,$data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}

function getCurlWithOutAuthorizationWithOutData($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}

function getCurlWithAuthorizationWithOutData($url)
{
	$CI =& get_instance();
	$user_id = $CI->session->userdata('user_id');
	$tokan = $CI->session->userdata('tokan');
	$authorization = "Authorization: Bearer $tokan";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization)); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}

function getCurlWithAuthorization($url,$data)
{
	$CI =& get_instance();
	$user_id = $CI->session->userdata('user_id');
	$tokan = $CI->session->userdata('tokan');
	$authorization = "Authorization: Bearer $tokan";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization)); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}


function postCurlWithAuthorization($url,$data,$method="POST")
{
	$CI =& get_instance();
	$tokan = $CI->session->userdata('tokan');
	$authorization = "Authorization: Bearer $tokan";
	
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',$authorization)); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}

function patchCurlWithOutAuthorizationJson($url,$data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
	$result = curl_exec($ch); 
	curl_close($ch); 
	return json_decode($result,true);

}