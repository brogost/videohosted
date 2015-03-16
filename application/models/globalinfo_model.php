<?php
/*
 *************************************************************************
* @filename		: globalinfo_model.php
* @description	: Model of Globalinfo
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.09   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Globalinfo_model extends CI_Model {

	function __construct()
	{
		parent::__construct();

		// $this->load->model('common_model');
	}
	
	// Retrieve default locations
	function GetDefaultLocation() {
		$str_sql = "SELECT * FROM gr_locations WHERE defaultYN='Y' LIMIT 1";
		$result = $this->db->query($str_sql);
		return $result->result();
	}
	
	// Retrieve default buildings
	function GetDefaultBuilding() {
		$str_sql = "SELECT * FROM gr_buildings WHERE deletedYN='N' AND defaultYN='Y'";
		$result = $this->db->query($str_sql );
		return $result->result();
	}
	
	// Retrieve default cameras
	function GetDefaultCameras(  ) {
		$str_sql = "SELECT * FROM gr_videoins WHERE defaultYN='Y' AND deletedYN='N'";
		$result = $this->db->query($str_sql);
		return $result->result();
	}

	// Retireve Non default locations
	function GetLocations() {
		if ($this->session->userdata('masterid') == '' ) {
			return null;
		}
		$str_sql = "SELECT * FROM gr_locations WHERE deletedYN='N' AND defaultYN='N' AND adminId=?"; //
		
		$result = $this->db->query($str_sql, $this->session->userdata('masterid'))->result();
		return $result;
	}
	// Non-default buildings
	function GetBuildings() {	
		if ( $this->session->userdata('masterid') == '' ) {
			return null;
		}	
		$str_sql = "SELECT * FROM gr_buildings WHERE deletedYN='N' AND defaultYN='N' ";
		$result = $this->db->query( $str_sql, $this->session->userdata('masterid') );
		return $result->result();
	}
	
	// Retrieve Non default cameras
	function GetCameras() {     
		if ( $this->session->userdata('masterid') == '' ) {
			return null;
		}
		$str_sql = "SELECT * FROM gr_videoins WHERE defaultYN='N' AND deletedYN='N' ";
		$result = $this->db->query($str_sql, array($this->session->userdata('masterid')));
		return $result->result();
	}
	
	// Retrieve a row of location by location id
	function GetLocationLine($str_lid) {
		$str_sql = "SELECT * FROM gr_locations WHERE id=? LIMIT 1";
		$result = $this->db->query($str_sql, array($str_lid))->result();
		return $result[0];
	}
	
	// Retrieve all building by location id
	function GetBuildingsByLocation( $str_lid ) {
		$str_sql = "SELECT * FROM gr_buildings WHERE locationId=? AND deletedYN='N'";
		$result = $this->db->query($str_sql, array($str_lid))->result();
		return $result;
	}
	
	// Retrieve all cameras by location id 
	function GetCamerasByLocation( $str_lid ) {
		$str_sql = "SELECT * FROM gr_videoins WHERE locationId=? AND buildingId='-1' AND deletedYN='N'";
		$result = $this->db->query($str_sql, array($str_lid))->result();
		return $result;
	}
	
	// Retrieve a row of building by building id
	function GetBuildingLine($str_bid) {
		$str_sql = "SELECT * FROM gr_buildings WHERE id=? LIMIT 1";
		$result = $this->db->query($str_sql, array($str_bid))->result();
		return $result[0];
	}
	
	// Retrieve all cameras by building id
	function GetCamerasByBuilding( $str_bid ) {
		$str_sql = "SELECT * FROM gr_videoins WHERE buildingId=? AND deletedYN='N'";
		$result = $this->db->query($str_sql, array($str_bid))->result();
		return $result;
	}
	
	// Retrieve a row of camera by camera id
	function GetCameraLine( $str_cid ) {
		$str_sql = "SELECT * FROM gr_videoins WHERE id=? LIMIT 1";
		$result = $this->db->query($str_sql, array($str_cid))->result();
		return $result[0];
	}
}

/* End of file globalinfo_model.php */
/* Location: ./application/models/common/globalinfo_model.php */