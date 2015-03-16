<?php
/*
 *************************************************************************
* @filename		: devices.php
* @description	: Model of Devices
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.10   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Devices extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->model('devices_model');
	}
	
	public function add_location() {
		$result = $this->devices_model->AddNewLocation();
		die(json_encode($result));
	}
	
	public function get_all_locations() {
		$locations = $this->devices_model->GetAllLocations();
		$buildings = $this->devices_model->GetAllBuildings();
		$cameras = $this->devices_model->GetAllCameras();
		$result['locations'] = $locations;
		$result['buildings'] = $buildings;
		$result['cameras'] = $cameras;
		die(json_encode($result));
	}
	
	//get device model info according to provider name
	public function get_deviceModelInfo(){
		$providerId = $_POST['providerId'];
		$result  = $this->devices_model->GetDeviceModelInfos( $providerId );
		
		$data['deviceModel'] = $result;
		$data['result'] = "success";
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	public function location_detail( $str_lid ) {
		$resultLocation = $this->devices_model->GetLocations( $str_lid );
		$data['locations'] = $resultLocation[0];
		
		$data['locationId'] = $str_lid;
		//get location map information
		$resultGmapInfo = $this->devices_model->GetMapInfo( $resultLocation[0]->id, "-1" );
		$data['gmapInfo'] = $resultGmapInfo;
		
		//get building map information
		$resultBuildingGmapInfo = $this->devices_model->GetBuildingMapInfo( $resultLocation[0]->id );
		$data['buildingGmapInfo'] = $resultBuildingGmapInfo;
		
		//get camera(videoin) map information
		$resultCameraGmapInfo = $this->devices_model->GetCameraMapInfo( $resultLocation[0]->id );
		$data['cameraGmapInfo'] = $resultCameraGmapInfo;
		
		//get map image map
		$resultMapImage = $this->devices_model->GetAllMapImages(  );
		$data['mapImages'] = $resultMapImage;
		
		$this->load->view('admin/devices_location', $data); 
	}
	
	public function get_locationMapInfo(){
		$locationId = $_POST['locationId'];
		$result = $this->devices_model->GetBuildingMapInfo( $locationId );
		$data['result'] = "success";
		$data['buildingGmapInfos'] = $result;
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	public function building_detail( $str_bid ) {
		$resultBuildings = $this->devices_model->GetBuildings( $str_bid );
		$data['buildings'] = $resultBuildings[0];
		//get camera information in building id
		$result = $this->devices_model->GetBuildingCamera( $str_bid );
		$data['buildingCameraGmapInfo'] = $result;
		$data['buildingId'] = $str_bid;
		//get all images map
		$resultMapImage = $this->devices_model->GetAllMapImages(  );
		$data['mapImages'] = $resultMapImage;
		
		$this->load->view('admin/devices_building', $data);
	}
	
	public function get_buildingMapInfo(){
		$buildingId = $_POST['buildingId'];
		$result = $this->devices_model->GetBuildingCamera( $buildingId );
		$data['result'] = "success";
		$data['cameraGmapInfos'] = $result;
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	public function camera_detail($str_cid) {
		//get camera information in Camera id
		$result = $this->devices_model->GetCameraMap( $str_cid );
		$data['cameraInfo'] = $result;
		$data['cameraId'] = $str_cid;
		//get all images map
		$resultMapImage = $this->devices_model->GetAllMapImages(  );
		$data['mapImages'] = $resultMapImage;
		
		// Added by KCH
		$str_locaitonId = $result[0]->locationId;
		$str_bid = $result[0]->buildingId;
		$ret = $this->devices_model->GetServerInfo( $str_locaitonId );
		$this->load->model('dashboard_model');
		$data['location'] = $ret;
		$data['bid'] = $str_bid;
		$ret_properties = $this->devices_model->GetDeviceProperties( $str_cid );
		$data['properties'] = $ret_properties; 
		$this->load->view('admin/devices_camera', $data);
	}
	
	public function get_cameraMapInfo(){
		$cameraId = $_POST['cameraId'];
		$result = $this->devices_model->GetCameraMap( $cameraId );
		$data['result'] = "success";
		$data['cameraInfo'] = $result;
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	public function delete_location($str_lid) {
		// $str_tmp_ids = explode($str_lid, ":");
		$this->devices_model->DeleteLocation($str_lid);
		die('success');
	}
	
	public function add_building() {
		$result = $this->devices_model->AddNewBuilding();
		die(json_encode($result));
	}
	
	public function delete_building($str_bid) {
		$result = $this->devices_model->DeleteBuilding($str_bid);
		if ($result)
		    $data['result'] = "success";
		else $data['result'] = 'failed';
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function add_camera() {
		$data = array();		
		
		$result = $this->devices_model->AddNewCamera( );
		if($result){
			$data['result'] = $result;
		}else 
			$data['result'] = array('success'=>'failed');
		//$ptr_serverinfo = $this->GetServerInfo($_POST['locationId']);
		//$str_videoliveurl = "rtmp://".$ptr_serverinfo->ipaddress.":".$ptr_serverinfo->rtmpport."/live/";
		//$str_videosearchurl = "rtmp://".$ptr_serverinfo->ipaddress.":".$ptr_serverinfo->rtmpport."/live/";
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	//camera exist check when add camera
	public function check_cameraExist(){
		$cameraName = $_POST['cameraName'];
		$ipAddress = $_POST['ipAddress'];
		
		$result = $this->devices_model->checkCameraInfoExist($cameraName, $ipAddress);
		if( count($result) > 0 ){
			$data['result'] = "exist";
		}else
			$data['result'] = 'success';
		
		header('Content-Type: application/json');
		echo json_encode($data);
	} 
	public function delete_camera() {
		$str_cid = $_POST['str_id'];
		$data = array();
		$result = $this->devices_model->DeleteCamera($str_cid);
		if( count($result) > 0 ){
			$data['result'] = "success";
			$data['systemIndexId'] = $result[0]->systemIndex;  
		}else
			$data['result'] = 'failed';
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	//location posistion info save
	public function save_locationPositionInfo(){
		$widthRate = $_POST['widthRate'];
		$heightRate = $_POST['heightRate'];
		$gmapId = $_POST['gmapId'];
		$locationId = $_POST['locationId'];
		
		$result = $this->devices_model->saveLocationPos($widthRate, $heightRate, $gmapId, $locationId);
	}
	
	public function saveStreamProfile(){
		$data['result'] = "error";
		$cameraName = $_POST['cameraName'];
		$cameraProvider = $_POST['cameraProvider'];
		$imageType = $_POST['imageType'];
		$frameRate = $_POST['frameRate'];
		if(isset($_POST['audioCheck']))
			$audioCheck = $_POST['audioCheck'];
		else
			$audioCheck = "-1";
		if(isset($_POST['cameraCheckEnable']))
			$cameraCheckEnable = $_POST['cameraCheckEnable'];
		else
			$cameraCheckEnable = "-1";
		$result = $this->devices_model->saveStreamProfileInfo($cameraName, $cameraCheckEnable, $cameraProvider, $imageType, $frameRate, $audioCheck);
		if($result)
			$data['result'] = "success";
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	public function uploadImage(){
		if(!empty($_FILES['fileUpload']['name'])) {
			$mapDescription = $_POST['uploadDescription'];
			
			$config['upload_path'] = './assets/uploads/gmap/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
			$config['max_size'] = '9000';
			$config['encrypt_name'] = true;
	
			$this->load->library('upload', $config);
			 
			// if there was an error, return and display it
			if(!$this->upload->do_upload('fileUpload')){
				$data['result'] = "error";
				$data['error'] = $this->upload->display_errors();
			}else{
				$data['upload_data'] = $this->upload->data();
				$data['origin_name'] = $_FILES['fileUpload']['name'];
				$result = $this->devices_model->saveMapImage($mapDescription, $data['upload_data']['file_name'], $_FILES['fileUpload']['name']);
				if($result > 0){
					$data['gmapId'] = $result;
					$data['result'] = "success" ;
				}else if($result == "-1"){
					$data['result'] = "exist" ;
				}
			}
		}
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	//save gmap positions information
	public function save_loc_gmapPositionInfo(){
		$buildingTopRate = $_POST['buildingTopRate'];
		$buildingLeftRate = $_POST['buildingLeftRate'];
		$gmapId = $_POST['gmapId'];
		$locationId = $_POST['locationId'];
		
		$result = $this->devices_model->saveLocGMapPositions($buildingTopRate, $buildingLeftRate, $gmapId, $locationId);
		$data['result'] = "success" ;
		
		header('Content-Type: application/json');
		echo json_encode($data);
		
	}
	public function save_bui_gmapPositionInfo(){
		$cameraTopRate = $_POST['cameraTopRate'];
		$cameraLeftRate = $_POST['cameraLeftRate'];
		$buildingId = $_POST['buildingId'];
		$gmapId = $_POST['gmapId'];
		
		$result = $this->devices_model->saveBuiGMapPositions($cameraTopRate, $cameraLeftRate, $gmapId, $buildingId);
		if($result)
			$data['result'] = "success" ;
	
		header('Content-Type: application/json');
		echo json_encode($data);
	
	}
	
	/**
	 * Go add new device page
	 * @param $_GET[bid], $_GET[lid] - Location and Building ID which is to added new device 
	 */
	public function go_adddevice() {
		$str_lid = $_GET['lid'];
		$str_bid = $_GET['bid'];
		$result = $this->devices_model->GetServerInfo( $str_lid );
		$this->load->model('dashboard_model');
		$providers = $this->dashboard_model->GetProviderInfo();
		$modelInfos = $this->dashboard_model->GetInitModelInfo();
		$data['location'] = $result;
		$data['bid'] = $str_bid;
		$data['providers'] = $providers;
		$data['modelInfos'] = $modelInfos; 
		$this->load->view('admin/devices_adddevice', $data);
	}
	
	/* save location detail information */
	public function saveLocDetail () {
	    $locationId = $_POST['locationId'];
	    $locationName = $_POST['locationName'];
	    $locationIp = $_POST['locationIp'];
	    $locationWebport = $_POST['locationWebport'];
	    $locationRtmpPort = $_POST['locationRtmpPort'];
	    
	    $result = $this->devices_model->saveLocDetail( $locationId, $locationName, $locationIp, $locationWebport, $locationRtmpPort);
	    if($result)
	        $data['result'] = "success" ;
	    
	    header('Content-Type: application/json');
	    echo json_encode($data);
	}
	/* search sytem log function  */
	public function searchSystemLog () {
	    $result = $this->devices_model->getSystemLogs();
	    header('Content-Type: application/json');
	    echo json_encode($result);
	}
	
}

/* End of file devices.php */
/* Location: ./application/controllers/admin/devices.php */