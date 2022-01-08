<?php
use \Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
	function sendMail($to,$username,$subject,$message,$status=0){
		
		$CI =& get_instance();
		$CI->load->library('mongoci');
		$db = $CI->mongoci->db;
		$admin_info = $db->mail_setting_master->findOne();
		$row = (array) $admin_info;
		$mailFrom = $row['mail_from_email'];
		$mailFromName = $row['mail_from_name'];
		$host = $row['mail_host'];
		$port = $row['mail_port'];
		$mailUsername = $row['mail_username'];
		$mailPassword = $row['mail_password'];
		$adminMail = $row['admin_received_mail'];
		$adminName = $row['admin_received_name'];
		$currency ='£';
		
		$mail = new PHPMailer(true);
		$mail->CharSet = 'UTF-8';
		$mail->SetFrom($mailFrom,$mailFromName); 
		$mail->AddAddress($to,$username);
		$mail->AddAddress($to);
		// $mail->addReplyTo("reply@yourdomain.com","Reply");
		// $mail->addCC("cc@example.com");
		// $mail->addBCC("bcc@example.com");
		// $mail->addAttachment("file.txt", "File.txt");        
		// $mail->addAttachment("images/profile.png"); //Filename is optional
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;

		$mail->IsSMTP();
		$mail->SMTPAuth   = true; 
		$mail->SMTPSecure = "tls";  //tls,ssl
		$mail->Host       = $host;
		$mail->Port       = $port; //you could use port 25, 587, 465 for googlemail
		$mail->Username   = $mailUsername;
		$mail->Password   = $mailPassword;
		$mail->send();
		
		if($status==1){
			$adminMail = explode(',',$adminMail);
			$mail = new PHPMailer(true);
			$mail->CharSet = 'UTF-8';
			$mail->SetFrom($to,$username); 
			foreach ($adminMail as $to) {
				$mail->AddAddress(trim($to));
			}
			
			$mail->IsHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $message;

			$mail->IsSMTP();
			$mail->SMTPAuth   = true; 
			$mail->SMTPSecure = "tls";  //tls,ssl
			$mail->Host       = $host;
			$mail->Port       = $port; //you could use port 25, 587, 465 for googlemail
			$mail->Username   = $mailUsername;
			$mail->Password   = $mailPassword;
			$mail->send();
		}
	}
	
	function changeOrderStatusMail($orderId,$status,$order_remark){

		$CI =& get_instance();
		$CI->load->library('mongoci');
		$db = $CI->mongoci->db;
		$match = [];
		$match['$match']['order_id']= $orderId;
		$ops = array(
			array(
				'$lookup' => array(
					"from" => "user_master",
					"localField" => "user_id",// filed in matched collection
					"foreignField" => "hash", //filedin current collection
					"as" => "u"
				)
			),
			$match,
			array('$project' =>['_id'=>0,'order_id'=>1,'status_history'=>1,'guest_name'=>1,'guest_email'=>1,'u.name'=>1,'u.email'=>1]),
		);

		$results = $db->master_order_tbl->aggregate($ops);
		$rr = '';
		foreach($results as $result) {
			$rr = (array) $result;
		}
		$data = (array) $rr['u'][0];
		$name = $data['name'];
		$to = $data['email'];
		$status_text = order_status[$status];
		$subject = 'Order - '.$status_text;
		$message = "Dear $name,<br><br>Your order has been $status_text.<br>
					Your order id is $orderId
					<br><br>
					$order_remark
					<br><br>
					for any type of clarification and support please feel free to reach to us.";
		sendMail($to,$name,$subject,$message,0);
		return $rr;
	}

	function changeUserStatusMail($user_id,$status)
	{
		$CI =& get_instance();
		$sql = "SELECT u.name,u.email FROM user_master u  WHERE u.id='$user_id'";
		$result = $CI->db->query($sql);
		$data =  $result->row_array();//second option
		
		$name = $data['name'];
		$to = $data['email'];
		$status_text = ($status==1)?'Activated':'De-activated';

		$subject = 'Account status - '.$status_text;
		$message = "Dear $name,<br><br>Your account has been $status_text by admin.<br>
					<br><br>
					for any type of clarification and support please feel free to reach to us.";
		sendMail($to,$name,$subject,$message,0);
		
	}

function changePasswordMail($user_id){

	$this->load->library('mongoci');
	$db = $this->mongoci->db;
	
		$CI =& get_instance();
		$sql = "SELECT u.name,u.email FROM user_master u  WHERE u.id='$user_id'";
		$result = $CI->db->query($sql);
		$data =  $result->row_array();//second option
		$name = $data['name'];
		$to = $data['email'];

		$subject = 'Password Changed';
		$message = "Dear $name,<br><br>Your password has been successfully changed.
					<br><br>for any type of clarification and support please feel free to reach to us.";
			sendMail($to,$name,$subject,$message,0);
		
	}
	
	function forgotPasswordMailSend($email,$message){
		$CI =& get_instance();
		$sql = "SELECT u.name,u.email FROM user_master u  WHERE u.email='$email'";
		$result = $CI->db->query($sql);
		$data =  $result->row_array();//second option
		$name = $data['name'];
		$to = $data['email'];
		$subject = 'Reset password Link';
		sendMail($to,$name,$subject,$message,0);
	}
	
	function resetPasswordMail($email)
	{
		$CI =& get_instance();
		$sql = "SELECT u.name,u.email FROM user_master u  WHERE u.email='$email'";
		$result = $CI->db->query($sql);
		$data =  $result->row_array();//second option
		$name = $data['name'];
		$to = $data['email'];

		$subject = 'Password Reset';
		$message = "Dear $name,<br><br>Your password has been successfully reset. Please login with new password.
					<br><br>for any type of clarification and support please feel free to reach to us.";
			sendMail($to,$name,$subject,$message,0);
		
	}
	
	function contactEnquiryMailSend($name,$email,$phoneNumber,$subject,$message){
		
		$to = $email;
		$name = $name;
		$subject = 'New Enquirty '.$subject;
		
		$msg = '
		<table width="50%" bgcolor="#CCCCCC">
		  <tr><td width="252" height="10">Name</td><td width="208" height="10">'.$name.'</td></tr>
		  <tr><td >Telephone</td><td width="208" >'.$phoneNumber.'</td></tr>
		  <tr><td >Email</td><td width="208" >'.$email.'</td></tr>
		  <tr><td>Subject</td><td width="208" >'.$subject.'</td></tr>
		  <tr><td >Message</td><td width="208" >'.$message.'</td></tr>
		</table>';
		
		sendMail($to,$name,$subject,$msg,1);
	}
	
	function tableBookingConfirm($data)
	{
		$name = $data['name'];
		$to = $data['email'];
		$status = $data['status'];
		$email_message = $data['email_message'];
		
		$status_text = 'Denied';
		if($status==1){
			$status_text = 'Approved';
		}else if($status==3){
			$status_text = 'Denied';
		}else if($status==0){
			$status_text = 'Pending';
		}


		$subject = 'Table Booking '.$status_text;
		$message = "Dear $name,<br><br>Your table booking has been $status_text by admin.<br>
					<br>
					<p>Message: $email_message</p>
					for any type of clarification and support please feel free to reach to us.";
			sendMail($to,$name,$subject,$message,0);
		
	}
	
	