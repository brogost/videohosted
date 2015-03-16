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
class Defaults_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('common_model');
	}
	
	function GetDefaultArchiveServerInfo() {
		$str_sql = "SELECT * FROM gr_locations WHERE deletedYN ='N'";
		return $this->db->query($str_sql)->result();
	}
	function saveDefaultArchiveServerInfo($defaultsId, $description, $lanIpAddress, $wanIpAddress, $dnsName) {
		$str_sql = "UPDATE gr_locations SET name=?, ipaddress=?, wanipaddress=?, dnsname=? WHERE id=?";
		$params = array(
				'name' => $description,
				'ipaddress' => $lanIpAddress,
				'wanipaddress' => $wanIpAddress,
				'dnsname'=> $dnsName,
				'id' => $defaultsId
				);
		return $this->db->query($str_sql, $params);
	}
	function GetDefaultsCameraInfo(){
		$str_sql = "SELECT * FROM gr_videoins WHERE deletedYN = 'N' AND defaultYN = 'Y'";
		return $this->db->query($str_sql)->result();
	}
}

/* End of file users_model.php */
/* Location: ./application/models/admin/users_model.php */