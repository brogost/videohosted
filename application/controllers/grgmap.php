<?php
/*
 *************************************************************************
* @filename		: grgmap.php
* @description	: Controller of GMap page
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.09   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grgmap extends CI_Controller {
	public function __construct() {
		parent::__construct();
	
		$this->load->model('grgmap_model');
	}
	
	public function index() {
		if (!$this->session->userdata('is_front_login')) {			
			redirect('users');
		}
		
		$data['main_content'] = "gmap/gmapmain";
        $this->load->view('includes/maincontent', $data);
	}
	
	public function loadmapinfo() {
		$result = $this->grgmap_model->GetMapInfo();
		
		die( $result == null ? '' : json_encode($result) );
	}
}

/* End of file groups.php */
/* Location: ./application/controllers/grgmap.php */