<?php
/*
 *************************************************************************
* @filename		: reporting.php
* @description	: Controller of reporting
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.21   Chanry         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class defaults extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        //if (!$this->session->userdata('is_admin_login')) {
        //    redirect('admin/home');
        //}
        
        $this->load->model('defaults_model');
    }
    
    public function get_archiveServerInfo(){
    	$result = $this->defaults_model->GetDefaultArchiveServerInfo( );
    	$data['defaults'] = $result;
    	$this->load->view('admin/archive_server_defaults', $data);
    }
    public function save_archiveServerInfo(){
    	$data = array();
    	$defaultsId = $_POST['defaultsId'];
    	$description = $_POST['description'];
    	$lanIpAddress = $_POST['lanIpAddress'];
    	$wanIpAddress = $_POST['wanIpAddress'];
    	$dnsName = $_POST['dnsName'];
    	
    	$result = $this->defaults_model->SaveDefaultArchiveServerInfo($defaultsId, $description, $lanIpAddress, $wanIpAddress, $dnsName);
    	if($result > 0){
    		$data['result'] = "success";
    	}
    	echo json_encode($data);
    }

    public function get_defaults_camera(){
    	$result = $this->defaults_model->GetDefaultsCameraInfo( );
    	if(count($result)>0)
    		$data['defaultsCamera'] = $result;
    	else 
    		$data = array();
    	$this->load->view('admin/camera_defaults', $data);
    }
}

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */