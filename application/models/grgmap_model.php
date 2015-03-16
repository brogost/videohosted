<?php
/*
 *************************************************************************
* @filename		: grgmap_model.php
* @description	: Model of Grgmap
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.09   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Grgmap_model extends CI_Model {

	function __construct()
	{
		parent::__construct();

		$this->load->model('common_model');
	}
	
	// Retrieve map information
	function GetMapInfo() {
		$locationId = $_POST['locationId'];
		$buildingId = $_POST['buildingId'];
		
		$str_sql = "";
		$params = array();
		if ($buildingId == '-1') {
			$str_sql = "SELECT gm.* FROM gr_locations gl, gr_gmaps gm WHERE gm.deletedYN='N' AND gm.id=gl.mapId AND gl.id=?";
			$params = array($locationId);
		} else {
			$str_sql = "SELECT gm.* FROM gr_buildings gb, gr_gmaps gm WHERE gm.deletedYN='N' AND gm.id=gb.mapId AND gb.id=?";
			$params = array($buildingId);
		}
		
		$result = $this->db->query($str_sql, $params)->result();
		return $result == null ? null : $result[0];
	}
}

/* End of file grgmap_model.php */
/* Location: ./application/models/grgmap_model.php */