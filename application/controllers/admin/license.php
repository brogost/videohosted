<?php
/*
 *************************************************************************
* @filename		: groups.php
* @description	: Controller of Groups 
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.09   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class License extends CI_Controller {
	public function __construct() {
		parent::__construct();
	
		$this->load->model('license_model');
		$this->load->helper('string');
	}
	
	public function index() {
	    $data['licenses'] = $this->license_model->getLicenses();
	    $this->load->view('admin/license_detail', $data);
	}
	
	/* upload new license  */
	public function uploadLicense () {
	    //permission check
	    $groupId = $this->session->userdata('group_id');
	    if ($groupId == 3) {
    	    $filePath = pathinfo( $_FILES['fileUpload']['name'] );
    	    $file_size = $_FILES['fileUpload']['size'];
    	    $ext = $filePath['extension'];
    	    $ptr_date = new DateTime();
    	    $str_fdate = $ptr_date->format('YmdHis');
    	    // Store marker image to the image storage
    	    $str_newFileName = random_string('alnum', 7)."_".$str_fdate.".$ext";
    	    $file_path = $_SERVER['DOCUMENT_ROOT'].'/assets/uploads/license/';
    	    
    	    $allowed_extensions = array("dat");
    	    $file_size_max = 2147483;
    	    $pattern = implode ($allowed_extensions, "|");
    	    if (preg_match("/({$pattern})$/i", $str_newFileName) && $file_size < $file_size_max) {
        	    while (file_exists($file_path.$str_newFileName)) {
        	        $str_newFileName = random_string('alnum', 6)."_".$str_fdate.".$ext";
        	        Sleep(1);
        	    }
        	    
        	    // Write png file
        	    if (!move_uploaded_file($_FILES['fileUpload']['tmp_name'], $file_path.$str_newFileName))
        	    {
        	        $data = array( 'result'=>'failed', 'error'=>'Invalid File Type' );
        	    } else {
        	        $myfile = fopen($file_path.$str_newFileName, "r") or die("Unable to open file!");
        	        $fileContents = fread($myfile, filesize($file_path.$str_newFileName));
        	        $contents = simplexml_load_string($fileContents);
        	        $liveEnable = $contents->livecams_enable;
        	        $searchEnable = $contents->recording_enable;
        	        $alerts_enable = $contents->alerts_enable;
        	        $totalCount = $contents->countoflicense;
        	        $allowedUsers = $contents->allowedusercount;
        	        $result = $this->license_model->addLicense($str_newFileName, $liveEnable, $searchEnable, $alerts_enable, $totalCount, $allowedUsers);
        	        $data = array( 'result'=>'success');
        	    }
    	    } else $data = array('result' => 'not_allowed');
	    } else 
	        $data = array('result' => 'groupNo');
	    header('Content-Type: application/json');
	    echo json_encode($data);
	}
	
}

/* End of file groups.php */
/* Location: ./application/controllers/admin/groups.php */