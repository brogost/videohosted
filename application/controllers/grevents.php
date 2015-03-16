<?php
/*
 *************************************************************************
* @filename        : grevents.php
* @description    : Controller of Events page
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.26   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grevents extends CI_Controller {
    public function __construct() {
        parent::__construct();
    
        $this->load->model('grevents_model');
    }
    
    public function index() {
        if (!$this->session->userdata('is_front_login')) {            
            redirect('users');
        }
        $data['page'] = 'events';
        $data['main_content'] = "events/eventsmain";
        $this->load->view('includes/maincontent', $data);
    }
    
}

/* End of file grevents.php */
/* Location: ./application/controllers/grevents.php */