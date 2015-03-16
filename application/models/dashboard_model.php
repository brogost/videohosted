<?php
/*
 *************************************************************************
* @filename		: defaults_model.php
* @description	: Model of Users
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.22   Chanry         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Dashboard_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('common_model');
	}
	
	function GetProviderInfo() {
		$str_sql = "SELECT providerIndex, providerName
        			  FROM provider_tbl
      				 WHERE providerIndex NOT IN (1, 9, 11, 12, 14)";
		return $this->db->query($str_sql)->result();
	}
	function GetInitModelInfo(){
		$str_sql = "SELECT * FROM models_tbl WHERE indexProvider = 2";
		return $this->db->query($str_sql)->result();
	}
	//location information check
	function GetLocationInfo () {
	    $groupId = $this->session->userdata('group_id');
	    $loggedId = $this->session->userdata('id');
	    if ($groupId == 3) {
	        $str_sql = "SELECT * FROM gr_locations WHERE deletedYN='N' AND defaultYN='N' AND adminId = '".$loggedId."'";// AND userId = ?
	        $result = $this->db->query($str_sql)->result();
	        return $result;
	    } else if ($groupId == 2) {
	        $str_sql = "SELECT * FROM gr_locations WHERE deletedYN='N' AND defaultYN='N'";// AND userId = ?
	        $result = $this->db->query($str_sql)->result();
	        return $result;
	    }
	}
}

/* End of file users_model.php */
/* Location: ./application/models/admin/users_model.php */