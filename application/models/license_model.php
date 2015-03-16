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
class License_model extends CI_Model {

	function __construct()
	{
		parent::__construct();

		$this->load->model('common_model');
	}
	function getLicenses () {
	    $userId = $this->session->userdata('id');
	    $str_sql = "SELECT * FROM gr_licenses WHERE userId = ?";
	    $params = array( 'userId'=>$userId );
	    $result = $this->db->query($str_sql, $params)->result();
	    
	    return $result;
	}
	function addLicense($str_newFileName, $liveEnable, $searchEnable, $alerts_enable, $totalCount, $allowedUsers) {
	    $userId = $this->session->userdata('id');
	    $str_sql = "INSERT INTO gr_licenses (userId, filename, issueDate, liveEnable, searchEnable, alertEnable, totalCount, allowedUsers, modifiedDate)
	                VALUES (?, ?, now(), ?, ?, ?, ?, ?, now())";
	    $params = array (
	                    'userId' 		=> $userId,
	                    'filename' 	=> $str_newFileName,
	                    'liveEnable' 		=> $liveEnable,
	                    'searchEnable' 		=> $searchEnable,
	                    'alertEnable' 		=> $alerts_enable,
	                    'totalCount' 		=> $totalCount,
	                    'allowedUsers' 	=> $allowedUsers,
	                );
	    
	    $this->db->query( $str_sql, $params);
	    return true;
	}
}

/* End of file groups_model.php */
/* Location: ./application/models/admin/groups_model.php */