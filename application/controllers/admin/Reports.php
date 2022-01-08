<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('admin/order_model');
		$this->load->model('admin/report_model');
		if (!$this->session->userdata('user_id')) {
			redirect('admin'); 
		}
		$this->redirection();
	}
	
	public function redirection(){
		if(!is_logged_in()) 
		{
			redirect('admin/login'); 
		}
		else if(is_logged_in() && (is_user_type()=='user' || is_user_type()=='driver')) 
		{
			redirect(base_url()); 
		}
	}

	public function index(){
		redirect('admin/reports/sales');
	}
	
	public function export(){

		$serialize_filters = json_decode(base64_decode($this->input->post('filter')),true);
		$file_name = $this->input->post('file_name');
		$data['start'] =0; 
		$orders = $this->order_model->getMasterOrder($serialize_filters,$data);
		$arr = array();
		$header = array();

		$file_name = $file_name.'_'.date('Y_m_d').'.csv'; 
		$file = fopen('php://output', 'w');

		if(isset($orders) && count($orders)>0){
			$header = array('Order Id','Merchant Name','Name','Mobile','Email','Item Name','Order Type','Payment Type','Total','Status','Date'); 
			fputcsv($file, $header);
			foreach($orders as $order){

				$product = (array)$order->product;
				$st = $order->status;

				$excelStatus = order_status[$st];
				$product_name ='';
				foreach($product  as $val){
					$val = (array)$val;
					$product_name .= $val['item_name'].', ';
				}
				if($order->order_type==1){
					$order_type = 'Pickup';
				}else if($order->order_type==2){
					$order_type = 'Delivery';
				}else if($order->order_type==3){
					$order_type = 'Dinein';
				}

				$order_id = $order->order_id;
				$merchant_name = $order->m[0]->merchant_name;
				$name = !empty($order->u[0]->name)?$order->u[0]->name:$order->guest_name.'<br>(Guest)';
				$mobile = !empty($order->u[0]->mobile)?$order->u[0]->mobile:$order->guest_phone.'<br>(Guest)';
				$email = !empty($order->u[0]->email)?$order->u[0]->email:$order->guest_email.'<br>(Guest)';
				$item_name = $product_name;
				$payment_type = ($order->payment_type==1)?'Cash':'Online';
				$grand_total = number_format($order->grand_total,2);;
				$added_date = date('Y-m-d',$order->added_date_timestamp/1000);
				$arr[] = $line = array($order_id,$merchant_name,$name,$mobile,$email,$item_name,$order_type,$payment_type,$grand_total,$excelStatus,$added_date);
				fputcsv($file,$line);
			}
		}
	
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
		fclose($file);
		exit;
		
	}

	public function exportBookingSummary(){

		$serialize_filters = json_decode(base64_decode($this->input->post('filter')),true);
		$data['start'] =0; 
		$orders = $this->report_model->getSalesBooking($serialize_filters,$data);
		$arr = array();
		$header = array();
		$file_name = 'booking_summary_report_'.date('Y_m_d').'.csv'; 
		$file = fopen('php://output', 'w');

		if(isset($orders) && count($orders)>0){
			$header = $header = array('Merchant Name','Total Approved','Total Cancelled','Total Pending');
			fputcsv($file, $header);
			foreach($orders as $order){

				$merchant = $order['merchant_name'];
				$approved = $order['approved'];
				$cancel = $order['cancel'];
				$pending = $order['pending'];
				$line = array($merchant,$approved,$cancel,$pending);
				fputcsv($file,$line);
			}
		}
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
		fclose($file);
		exit;
		
	}

	public function exportSalesSummary(){

		$serialize_filters = json_decode(base64_decode($this->input->post('filter')),true);
		$data['start'] =0; 
		$orders = $this->report_model->getSalesSummary($serialize_filters,$data);
		$arr = array();
		$header = array();
		$file_name = 'sales_summary_report_'.date('Y_m_d').'.csv'; 
		$file = fopen('php://output', 'w');

		if(isset($orders) && count($orders)>0){
			$header = array('Merchant Name','Item Name','Quantity','Total Price'); 
			fputcsv($file, $header);
			foreach($orders as $order){
				$merchant = $order->merchant_name;
				$item_name = $order->item_name;
				$item_price = number_format($order->item_price,2);
				$quantity = $order->count;
				$line = array($merchant,$item_name,$quantity,$item_price);
				fputcsv($file,$line);
			}
		}
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$file_name"); 
		header("Content-Type: application/csv;");
		fclose($file);
		exit;
		
	}
	
	public function salesReport(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/reports/sales";
		$config["total_rows"] = $this->order_model->getCountOrder($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
		$config['use_page_numbers'] = TRUE;
		if(!empty($_GET))
		{
			$config['suffix'] = '?'.http_build_query($_GET, '', "&");
		}
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
	
		$view['page_title']='Table Booking';
		$view['main_content']='admin/pages/reports/sales';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->order_model->getMasterOrder($filter,$data);
		if ($this->session->userdata('role_master_tbl_id')==1) {
			$view['stores'] = $this->order_model->getStore();
		}else{
			$view['stores'] = [];
		}
		$this->load->view('admin/template_admin',$view);
	}
	
	public function salesSummaryReport(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/reports/sales-summary";
		$config["total_rows"] = $this->report_model->getCountSaleSummary($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
		$config['use_page_numbers'] = TRUE;
		if(!empty($_GET))
		{
			$config['suffix'] = '?'.http_build_query($_GET, '', "&");
		}
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
	
		$view['page_title']='Sales Summary Report';
		$view['main_content']='admin/pages/reports/sales_summery';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->report_model->getSalesSummary($filter,$data);
		if ($this->session->userdata('role_master_tbl_id')==1) {
			$view['stores'] = $this->order_model->getStore();
		}else{
			$view['stores'] = [];
		}
		$this->load->view('admin/template_admin',$view);
	}
	
	public function bookingReport(){
		
		$this->load->library('pagination');
		$filter = $_GET;
		$uri = http_build_query($_GET);
		
		$rowperpage = ROW_PER_PAGE;
		$rowno = ($this->uri->segment(4))? $this->uri->segment(4) : 0;
		if($rowno!= 0){
			$rowno = ($rowno-1) * $rowperpage;
		}	
		
		$config = array();
		$config["base_url"] = base_url() . "admin/reports/booking";
		$config["total_rows"] = $this->report_model->getCountBookingSummary($filter);
		$config["per_page"] = $rowperpage;
		$config["uri_segment"] = 4;
		$config['use_page_numbers'] = TRUE;
		if(!empty($_GET))
		{
			$config['suffix'] = '?'.http_build_query($_GET, '', "&");
		}
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);

		$view['page_title']='Booking Summary Report';
		$view['main_content']='admin/pages/reports/booking';
		$view["links"] = $this->pagination->create_links();
		$view['filters']=$filter;
		$view['start']= $rowno;
		$data['limit'] = $rowperpage; 
		$data['start'] =$rowno; 
		$view['orders'] = $this->report_model->getSalesBooking($filter,$data);
		if ($this->session->userdata('role_master_tbl_id')==1) {
			$view['stores'] = $this->order_model->getStore();
		}else{
			$view['stores'] = [];
		}
		$this->load->view('admin/template_admin',$view);
	}	
	
}