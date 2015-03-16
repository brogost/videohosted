<?php
/*
 *************************************************************************
* @filename		: groups.php
* @description	: Model of Groups
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.09   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends CI_Controller {
	public function __construct() {
		parent::__construct();
	
		$this->load->model('groups_model');
	}
}

/* End of file groups.php */
/* Location: ./application/controllers/admin/groups.php */