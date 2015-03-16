<?php
/*
*************************************************************************
* @filename		: devices_model.php
* @description	: Model of Devices
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.10   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Devices_model extends CI_Model {

	function __construct()
	{
		parent::__construct();

		$this->load->model('common_model');
	}
	
	// Retrieve All Locations
	function GetAllLocations() {
		// Must Get User Id from SESSION
		// $str_uid;
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
	
	// Retrieve Buildings or Locations by parentId
	function GetLocations($str_pid='-1') {
		$str_sql = "SELECT t1.*, t2.image_path FROM gr_locations t1, gr_gmaps t2 WHERE t1.mapId = t2.id AND t1.id=?"; // AND userId = ?
		$params = array(
				'pid' => $str_pid
				);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	
	// Retrieve All Buildings for current user
	function GetAllBuildings() {
	    $groupId = $this->session->userdata('group_id');
	    $loggedId = $this->session->userdata('id');
	    if ($groupId == 3) {
	        $str_sql = "SELECT gb.* FROM gr_buildings gb, gr_locations gl 
	                     WHERE gb.locationId = gl.id AND gl.adminId = '".$loggedId."'
	                       AND gb.deletedYN='N' AND gb.defaultYN='N'";
	     } else if ($groupId == 2) {
	        $str_sql = "SELECT * FROM gr_buildings
	                     WHERE deletedYN='N' AND defaultYN='N'";
	     }
		$result = $this->db->query( $str_sql )->result();
		return $result;
	}
	
	// Retrieve All Cameras on this system for current user
	function GetAllCameras() {
	    $groupId = $this->session->userdata('group_id');
	    $loggedId = $this->session->userdata('id');
	    if ($groupId == 2) {
	        $str_sql = "SELECT * FROM gr_videoins WHERE deletedYN='N' AND defaultYN='N'"; // AND userId = ?
	    } else if ($groupId == 3) {
	        $str_sql = "SELECT gv.* FROM gr_videoins gv, gr_locations gl
	                     WHERE gv.locationId = gl.id AND gl.adminId = '".$loggedId."'
	                       AND gv.deletedYN='N' AND gv.defaultYN='N'";
	    }
	    $result = $this->db->query( $str_sql )->result();
		return $result;
	}
	
	// Retrieve Cameras By Location ID
	// @Params 
	//		str_pid : String - Location Identifier
	function GetCameras( $str_pid, $str_cid ) {
		$str_sql = "SELECT FROM gr_videoins WHERE deletedYN='N' AND locationId=? AND buildingId=?";
		$params = array(
				'pid'=>$str_pid,
				'cid'=>$str_cid
				);
		$result = $this->db->query( $str_sql, $params )->result();
		return $result;
	}
	
	//get model info according provider id
	function GetDeviceModelInfos( $providerId ){
		
		$str_sql = "SELECT * FROM models_tbl WHERE indexProvider = ?";
		$params = array(
			'indexProvider'=>$providerId
		);
		return $this->db->query( $str_sql, $params )->result();
	}
	//add new location by supper admin ADC
	function AddNewLocation() {
	    $str_sql = "select * from gr_locations where name = ?";
	    $params = array(
	                    'name'=>$_POST['txtLocationName']
	                );
	    $result = $this->db->query($str_sql, $params)->result();
	    if ($result == null) {
    		$str_sql = "INSERT INTO gr_locations(name,ipaddress,webport,rtmpport) VALUES (?,?,?,?)";
    		$params = array(
    				'name'=>$_POST['txtLocationName'],
    				'ip'=>$_POST['txtLocationIP'],
    				'webport'=>$_POST['txtLocationWEBPort'],
    				'rtmpport'=>$_POST['txtLocationRTMPPort']
    				);
    		
    		$this->db->query($str_sql, $params);
    		$str_id = $this->db->insert_id();
    		//log event
    		$loggedUserName = $this->session->userdata('username');
    		$eventMsg  = "One Location(Name: ".$_POST['txtLocationName'].")  added by ".$loggedUserName;
    		$this->common_model->saveEventLog(4, $str_id, "location", $eventMsg);
    		
    		$str_sql = "INSERT INTO gr_gmaps (locationId, buildingId,deletedYN) VALUES (?,-1,'N')";
    		$this->db->query($str_sql, array($str_id));
    		$str_mapid = $this->db->insert_id();
    		
    		$str_sql = "UPDATE gr_locations SET mapId=? WHERE id=?";
    		$this->db->query($str_sql, array($str_mapid, $str_id));
    		
    		$str_name = $_POST['txtLocationName'];
    		$result['id'] = $str_id;
    		$result['name'] = $str_name;
    		$result['ipaddress'] = $_POST['txtLocationIP'];
    		$result['webport'] = $_POST['txtLocationWEBPort'];
	    } else 
	        $result['id'] = -1;
		return $result;
	}
	
	function DeleteLocation($str_lid) {
	    $str_sql = "select * from gr_locations where id=?";
	    $params = array(
	                    'lid'=>$str_lid,
	                );
	    $location = $this->db->query($str_sql, $params)->result();
		$str_sql = "DELETE FROM gr_locations WHERE id=?"; // userid
		$params = array(
				'lid'=>$str_lid,
				'plid'=>$str_lid
				);
		$result = $this->db->query($str_sql, $params);
		if ($result) {
		    //event logs
    		$loggedUserName = $this->session->userdata('username');
    		$eventMsg  = "One Location(Name: ".$location[0]->name.")  removed by ".$loggedUserName;
    		$this->common_model->saveEventLog(4, $str_lid, "location", $eventMsg);
		}
		// $str_sql = "UPDATE gr_videoins SET deletedYN='Y' WHERE gr_locations_id=?";//userid
		// $this->db->query($str_sql, $params);
	}
	
	function AddNewBuilding(  ) {
	    $str_sql = "select * from gr_buildings where name = ? and deletedYN = 'N'";
	    $params = array(
	                    'name'=>$_POST['txtBuildingName']
	    );
	    $result = $this->db->query($str_sql, $params)->result();
	    if ($result == null) {
    		$str_sql = "INSERT INTO gr_buildings(name,locationId) VALUES (?,?)";//userid
    		$params = array(
    				'name'=>$_POST['txtBuildingName'],
    				'parentid'=>$_POST['locationId']
    		);
    		$this->db->query($str_sql, $params);
    		$str_id = $this->db->insert_id();
    		$str_name = $_POST['txtBuildingName'];
    		
    		//log event
    		$loggedUserName = $this->session->userdata('username');
    		$eventMsg  = "One Building(Name: ".$_POST['txtBuildingName'].")  added by ".$loggedUserName;
    		$this->common_model->saveEventLog(4, $str_id, "building", $eventMsg);
    		
    		$result['id'] = $str_id;
    		$result['name'] = $str_name;
    		$result['pid'] = $_POST['locationId'];
    		
    		$str_sql = "INSERT INTO gr_gmaps(locationId, buildingId, deletedYN) VALUES (?,?,'N')";
    		$this->db->query($str_sql, array($_POST['locationId'], $str_id));
    		
    		$str_mapid = $this->db->insert_id();
    		$str_sql = "UPDATE gr_buildings SET mapId=? WHERE id=?";
    		$this->db->query($str_sql, array($str_mapid, $str_id));
	    } else
	        $result['id'] = -1;
		return $result;
	}
	
	function DeleteBuilding($str_bid) {
	    $str_sql = "select * from gr_buildings where id=?";
	    $params = array(
	                    'bid'=>$str_bid,
	    );
	    $building = $this->db->query($str_sql, $params)->result();
		$str_sql ="UPDATE gr_buildings SET deletedYN='Y' WHERE id=?"; // userid
		$params = array('id'=>$str_bid);
		$result = $this->db->query($str_sql, $params);
		
		// Delete Cameras from this building
		$str_sql = "UPDATE gr_videoins SET deletedYN='Y' WHERE buildingId=?";
		$params = array('buildingId'=>$str_bid);
		$this->db->query($str_sql, $params);
		if ($result) {
		    //event logs
		    $loggedUserName = $this->session->userdata('username');
		    $eventMsg  = "One Building(Name: ".$building[0]->name.")  removed by ".$loggedUserName;
		    $this->common_model->saveEventLog(4, $str_bid, "building", $eventMsg);
		}
		return $result;
	}
	
	function AddNewCamera( ) {
		$videoIndex = $_POST['videoIndex'];
		$systemIndex = $_POST['systemIndex'];
		$videoInName = $_POST['videoInName'];
		$locationId = $_POST['locationId'];
		$liveToken = $_POST['liveToken'];
		$buidingId = $_POST['buildingId'];
		$connectStatus = $_POST['connectStatus'];
		$deviceModel = $_POST['deviceModel'];
		$localAccessAddress = $_POST['localAccessAddress'];
		$str_sql = "INSERT INTO gr_videoins(videoInIndex, systemIndex, videoInName, locationId, buildingId, connectStatus, videoLiveUrl, deviceModelName, localAccessAddress, modified) VALUES (?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP())";//userid
		$params = array(
				'videoInIndex' => $videoIndex,
				'systemIndex'  => $systemIndex,
				'videoInName'  => $videoInName,
				'locationId'   => $locationId,
				'buildingId'   => $buidingId,
				'connectStatus'=> $connectStatus,
				'liveToekn'    => $liveToken,
				'deviceModel'  => $deviceModel,
				'localAccessAddress'=> $localAccessAddress
		);
		$this->db->query($str_sql, $params);
		$str_id = $this->db->insert_id();
		$result = array(
				'success'=>'success',
				'pid'=>$locationId.":".$buidingId,
				'id' => $str_id,
				'name' => $videoInName
		);
		
		$str_ptz = isset($_POST['ptzEnable'])?$_POST['ptzEnable']:'0';
		$str_audio = isset($_POST['audioEnable'])?$_POST['audioEnable']:'0';
		$str_sql = "INSERT INTO gr_deviceproperties (deviceId, ptzEnable, audioEnable) VALUES (?,?,?)";
		$params = array (
				'deviceId' => $str_id,
				'ptzEnable' => $str_ptz,
				'audioEnable' => $str_audio
		);
		
		if ( $str_ptz == "1" ) {
			$str_ptzup = $_POST['ptzUpUrl'];
			$str_ptzdown = $_POST['ptzDownUrl'];
			$str_ptzleft = $_POST['ptzLeftUrl'];
			$str_ptzright = $_POST['ptzRightUrl'];
			$str_ptzupleft = $_POST['ptzUpLeftUrl'];
			$str_ptzupright = $_POST['ptzUpRightUrl'];
			$str_ptzdownleft = $_POST['ptzDownLeftUrl'];
			$str_ptzdownright = $_POST['ptzDownRightUrl'];
			$str_sql = "INSERT INTO gr_deviceproperties (deviceId, ptzEnable, audioEnable, ptzUp, ptzDown, ptzLeft, ptzRight, ptzUpLeft, ptzUpRight, ptzDownLeft, ptzDownRight) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			$params = array (
					'deviceId' 		=> $str_id,
					'ptzEnable' 	=> $str_ptz,
					'audioEnable' 	=> $str_audio,
					'ptzUp' 		=> $str_ptzup,
					'ptzDown' 		=> $str_ptzdown,
					'ptzLeft' 		=> $str_ptzleft,
					'ptzRight' 		=> $str_ptzright,
					'ptzUpLeft' 	=> $str_ptzupleft,
					'ptzUpRight' 	=> $str_ptzupright,
					'ptzDownLeft' 	=> $str_ptzdownleft,
					'ptzDownRight' 	=> $str_ptzdownright
			);
		}
		
		$this->db->query( $str_sql, $params);
		
		return $result;
	}
	
	function checkCameraInfoExist($cameraName, $ipAddress){
		$str_sql = "SELECT * FROM gr_videoins WHERE (videoInName = ? OR localAccessAddress = ?) AND deletedYN = 'N'"; 
		$params = array( 'videoInName'=>$cameraName,
						'localAccessAddress'=>$ipAddress );
		$result = $this->db->query($str_sql, $params)->result();
		
		return $result;
	}
	
	function DeleteCamera($str_cid) {
		$str_sql ="UPDATE gr_videoins SET deletedYN='Y' WHERE id=?"; // userid
		$params = array('cid'=>$str_cid);
		if($this->db->query($str_sql, $params)){
			$str_sql ="SELECT systemIndex FROM gr_videoins WHERE id=?"; // userid
			$params = array('id'=>$str_cid);
			$result = $this->db->query($str_sql, $params)->result();
			
			return $result;
		}else return false;
	}
	
	function GetServerInfo($str_pid) {
		$str_sql = "SELECT * FROM gr_locations WHERE id=?"; // userid
		$params = array('pid'=>$str_pid);
		$result = $this->db->query($str_sql, $params)->result();
		
		return $result[0];
	}
	function GetBuildings($str_bid){
		$str_sql = "SELECT t1.*, t2.image_path FROM gr_buildings t1, gr_gmaps t2 WHERE t1.mapId = t2.id AND t1.id=?";
		$params = array(
				'id' => $str_bid
		);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	function GetBuildingCamera( $str_bid ) {
		$str_sql = "SELECT * FROM gr_videoins WHERE buildingId = ? AND deletedYN = 'N'";
		$params = array('buildingId'=>$str_bid);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	
	function GetCameraMap($str_cid) {
		$str_sql = "SELECT gv.*, gb.mapId, gb.id AS buildingId, gg.image_path FROM gr_videoins gv, gr_buildings gb, gr_gmaps gg
					 WHERE gv.buildingId = gb.id AND gb.mapId = gg.id 
					   AND gv.id = ? AND gv.deletedYN = 'N'";
		$params = array('id'=>$str_cid);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	
	// Added by Ko.C.H - 2014.07.24
	// @desc : Get Map information from gr_maps table
	function GetMapInfo($str_lid, $str_bid) {
		$str_sql = "SELECT gm.* FROM gr_locations gl, gr_gmaps gm WHERE gm.deletedYN='N' AND gm.id=gl.mapId AND gl.id=?"; // userid
		$params = array('locationId'=>$str_lid);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	
	function GetAllMapImages() {
		$str_sql = "SELECT id, name, image_path FROM gr_gmaps WHERE deletedYN='N' GROUP BY image_path";
		$result = $this->db->query($str_sql);
		return $result->result();
	}
	
	//get building gmap information
	public function GetBuildingMapInfo( $locationId ){
		$str_sql = "SELECT * FROM gr_buildings WHERE locationId = ? AND deletedYN = 'N'"; // userid
		$params = array('locationId'=>$locationId);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	
	//get camera gmap information
	public function GetCameraMapInfo($locationId){
		$str_sql = "SELECT * FROM gr_videoins WHERE locationId = ? AND buildingId = '-1' AND deletedYN = 'N'"; // userid
		$params = array('locationId'=>$locationId);
		$result = $this->db->query($str_sql, $params)->result();
		return $result;
	}
	
	//save Map image
	function saveMapImage($description, $fullPath, $originName){
		$str_sql1 = "SELECT name FROM gr_gmaps WHERE name=?";
		$params = array('id'=>$description);
		$resultExist = $this->db->query($str_sql1, $params)->result();
		if(count($resultExist) > 0)
			return "-1";
		$str_sql2 = "INSERT INTO gr_gmaps(name, image_origin_name, image_path, deletedYN, created, buildingId) VALUES (?,?,?,'N',now(),-1)";//userid
		$params = array(
				'name'=>$description,
				'image_origin_name'=>$originName,
				'image_path'=>$fullPath,
		);
		$this->db->query($str_sql2, $params);
		//event logs
		$loggedUserName = $this->session->userdata('username');
		$eventMsg  = "One Map Image(Name: ".$originName.")  added by ".$loggedUserName;
		$this->common_model->saveEventLog(4, $this->db->insert_id(), "mapImage", $eventMsg);
		
		return $this->db->insert_id();
	}
	//parent gmap position informations(Location)
	public function saveLocGMapPositions($buildingTopRate, $buildingLeftRate, $gmapId, $locationId){
		foreach ($buildingTopRate as $k => $v){
			if($v == "" || $v == null)
				continue ;
			$str_sql ="UPDATE gr_buildings SET posy = ? WHERE id = ?"; // userid
			$params = array(
					'posy'=>$v,
					'id'=>$k
			);
			$this->db->query($str_sql, $params);
		}
		foreach ($buildingLeftRate as $k => $v){
			if($v == "" || $v == null)
				continue ;
			$str_sql ="UPDATE gr_buildings SET posx = ? WHERE id = ?"; // userid
			$params = array(
					'posx'=>$v,
					'id'=>$k
			);
			$this->db->query($str_sql, $params);
		}
		$str_sql ="UPDATE gr_locations SET mapId = ? WHERE id = ?"; // userid
		$params = array(
				'gmapId'=>$gmapId,
				'id'=>$locationId
		);
		$this->db->query($str_sql, $params);
		//event logs
		$loggedUserName = $this->session->userdata('username');
		$eventMsg  = "Location Map Buildings Position changed by ".$loggedUserName;
		$this->common_model->saveEventLog(4, $locationId, "mapImage", $eventMsg);
	}
	
	//parent gmap position informations(Building)
	public function saveBuiGMapPositions($topRate, $leftRate, $gmapId, $buildingId){
		foreach ($topRate as $k => $v){
			if($v == "" || $v == null)
				continue ;
			$str_sql ="UPDATE gr_videoins SET posy = ? WHERE id = ?"; // userid
			$params = array(
					'posy'=>$v,
					'id'=>$k
			);
			$result = $this->db->query($str_sql, $params);
		}
		foreach ($leftRate as $k => $v){
			if($v == "" || $v == null)
				continue ;
			$str_sql ="UPDATE gr_videoins SET posx = ? WHERE id = ?"; // userid
			$params = array(
					'posx'=>$v,
					'id'=>$k
			);
			$result = $this->db->query($str_sql, $params);
		}
		$str_sql ="UPDATE gr_buildings SET mapId = ? WHERE id = ?"; // userid
		$params = array(
				'mapId'=>$gmapId,
				'id'=>$buildingId
		);
		$result = $this->db->query($str_sql, $params);
		
		//event logs
		$loggedUserName = $this->session->userdata('username');
		$eventMsg  = "Building Map Cameras Position changed by ".$loggedUserName;
		$this->common_model->saveEventLog(4, $buildingId, "mapImage", $eventMsg);
		return $result;
	}
	
	//save Stream profile information
	public function saveStreamProfileInfo($cameraName, $cameraProvider, $imageType, $frameRate, $audioCheck){
		return true;
	}
	
	// Added by KCH
	public function GetDeviceProperties( $str_cid ) {
		$str_sql = "SELECT * FROM gr_deviceproperties WHERE deviceId=?";
		$ret_array = $this->db->query($str_sql, array($str_cid))->result();
		return $ret_array?$ret_array[0]:null;
	}
	
	//save location detail information 
	function saveLocDetail ( $locationId, $locationName, $locationIp, $locationWebport, $locationRtmpPort) {
	    $str_sql = "UPDATE gr_locations 
	                  SET name = ?,
	                      ipaddress = ?,
	                      webport = ?,
	                      rtmpport = ?
	                WHERE id = ?";
	    $params = array(
	                    'name'=>$locationName,
	                    'ipaddress'=>$locationIp,
	                    'webport'=>$locationWebport,
	                    'rtmpport'=>$locationRtmpPort, 
	                    'id'=>$locationId
	    );
	    //event logs
	    $loggedUserName = $this->session->userdata('username');
	    $eventMsg  = "location Detail Info changed by ".$loggedUserName;
	    $this->common_model->saveEventLog(4, $locationId, "location", $eventMsg);
	    
	    return $this->db->query($str_sql, $params);
	}
	/* device loaction page get system logs information  */
	function getSystemLogs() {
	    $systemFromDate = $_POST['systemFromDate'];
	    $systemToDate = $_POST['systemToDate'];
	    $maxResult = $_POST['maxResult'];
	    
	    if ($systemFromDate == "")
	        $systemFromDate = 0;
	    $str_sql = "SELECT * FROM gr_eventlogs WHERE eventTime > '$systemFromDate' AND eventType IN (";
	   
	    if (isset($_POST['systemLogin']))
	        $str_sql .= "1,";
	    if (isset($_POST['systemLogout']))
	        $str_sql .= "2,";
	    if (isset($_POST['systemloginFail']))
	        $str_sql .= "3,";
	    if (isset($_POST['systemModify']))
	        $str_sql .= "4,";
	    $str_sql = rtrim($str_sql,',');
	    $str_sql .= ")";
	    if ($systemToDate != "")
	        $str_sql .= " AND eventTime < '$systemToDate' ";
	    if (!isset($_POST['systemLogin']) && !isset($_POST['systemLogout']) && !isset($_POST['systemloginFail']) && !isset($_POST['systemModify']))
	        $data['result'] = 'failed';
	    else {
	        $str_sql .=" ORDER BY eventTime DESC LIMIT $maxResult";
	        $result = $this->db->query($str_sql)->result();
	        $data['result'] = $result;
	    }
	    return $data;
	    
	}
}

/* End of file devices_model.php */
/* Location: ./application/models/admin/devices_model.php */