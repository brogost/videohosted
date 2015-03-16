<?php
/*
 *************************************************************************
* @filename        : grsearch.php
* @description    : Controller of Search page
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.26   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grsearch extends CI_Controller {
    public function __construct() {
        parent::__construct();
    
        $this->load->model('grsearch_model');
    }
    
    public function index() {
        if (!$this->session->userdata('is_front_login')) {            
            redirect('users');
        }
        $data['page'] = 'search';
        $data['main_content'] = "search/searchmain";
        $this->load->view('includes/maincontent', $data);
    }

    public function get_libraries () {
    	if ( !$this->session->userdata( 'is_front_login')) {
    		die ( json_encode( array( 'result'=>'failed', 'errmsg'=>'You need to login again') ) );
    	}
    	$result = $this->grsearch_model->GetLibraries();
    	die ( json_encode( array('result'=>$result) ) );
    }
    
    public function add_newlibrary () {
    	if ( !$this->session->userdata( 'is_front_login')) {
    		die ( array( 'result'=>'failed', 'errmsg'=>'You need to login again') );
    	}
    	$result = $this->grsearch_model->AddNewLibrary();
    	die ( json_encode( $result ) );
    }
}

/* End of file grsearch.php */
/* Location: ./application/controllers/grsearch.php */