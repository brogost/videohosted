<?php
/*
 *************************************************************************
* @filename		: groups_model.php
* @description	: Model of Groups
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.09   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Groups_model extends CI_Model {

	function __construct()
	{
		parent::__construct();

		$this->load->model('common_model');
	}
}

/* End of file groups_model.php */
/* Location: ./application/models/admin/groups_model.php */