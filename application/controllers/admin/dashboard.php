<?php
/*
 *************************************************************************
* @filename		: dashboard.php
* @description	: Dashboard of Admin Panel
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.01   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // $this->load->library('form_validation');
        if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/');
        }
        
        $this->load->model('dashboard_model');
    }

    public function index() {
        $arr['page']='dash';
        
        $arr['providerInfo'] = $this->dashboard_model->GetProviderInfo( );
        $arr['initModelInfo'] = $this->dashboard_model->GetInitModelInfo( );
        $arr['checkLocation'] = $this->dashboard_model->GetLocationInfo();
        $this->load->view('admin/dashboard',$arr);
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */