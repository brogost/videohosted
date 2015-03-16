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

class reporting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        //if (!$this->session->userdata('is_admin_login')) {
        //    redirect('admin/home');
        //}
        
        $this->load->model('dashboard_model');
    }
    
    public function reporting_detail(){
    	$locations = $this->dashboard_model->GetLocationInfo ();
    	$result['result'] = json_encode( $locations );
    	$this->load->view('admin/reporting_detail', $result);
    }
    
}

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */