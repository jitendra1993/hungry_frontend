<?php
function is_logged_in() {
    // Get current CodeIgniter instance
    $CI =& get_instance();
    // We need to use $CI->session instead of $this->session
     $user = $CI->session->userdata('user_id');
    if (!isset($user)) 
    { 
        return FALSE; 
        
    } else { 
        return TRUE; 
        
    }
}

function is_user_type() {
    $CI =& get_instance();
    $role_master_tbl_id = $CI->session->userdata('role_master_tbl_id');
    if ($role_master_tbl_id==1) 
    { 
        return 'admin'; 
        
    } else if ($role_master_tbl_id==2) 
    { 
        return 'admin'; 
    }
	 else if ($role_master_tbl_id==3) 
    { 
        return 'driver'; 
    }
	else if ($role_master_tbl_id==4) 
    { 
        return 'user'; 
    }
}