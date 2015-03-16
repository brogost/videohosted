<?php
/*
 *************************************************************************
* @filename        : grlive.php
* @description    : Controller of Live page
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.26   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grlive extends CI_Controller {
    public function __construct() {
        parent::__construct();
    
        $this->load->model('grlive_model');
    }
    
    public function index() {
        if (!$this->session->userdata('is_front_login')) {            
            redirect('users');
        }
        $data['page'] = 'live';
        // $data['layouts'] = $this->getalllayouts();
        $data['main_content'] = "live/livemain";
        $this->load->view('includes/maincontent', $data);
    }
    
    public function test() {
    	$data['page'] = 'live';
    	$data['main_content'] = "livemain";
    	$this->load->view('livemain', $data);
    }
    
    // Add new Layout - Added by KCH
    public function addnewlayout () {
    	$result = $this->grlive_model->AddNewLayout();
    	die(json_encode($result));
    }
    
    public function deletelayout () {
    	$result = $this->grlive_model->DeleteLayout();
    	die( json_encode($result) );
    }
    
    public function updatelayout() {
    	$result = $this->grlive_model->UpdateLayoutName();
    	die ( json_encode( $result ) );
    }
    
    // Get all layouts 
    public function getalllayouts () {
    	$ownlayout = $this->grlive_model->GetOwnLayouts();
    	die ( json_encode ( $ownlayout ) );
    }
}

/* End of file grlive.php */
/* Location: ./application/controllers/grlive.php */