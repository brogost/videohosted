<?php
/*
 *************************************************************************
* @filename		: globalinfo.php
* @description	: Controller of GlobalInfo
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.24   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Globalinfo extends CI_Controller {
	public function __construct() {
		parent::__construct();
	
		$this->load->model('globalinfo_model');
	}
	
	public function loadengineinfos() {		
		$defaultEngine = $this->globalinfo_model->GetDefaultLocation(); 
		$defaultBuildings = $this->globalinfo_model->GetDefaultBuilding();
		$defaultCameras = $this->globalinfo_model->GetDefaultCameras();
		$locations = $this->globalinfo_model->GetLocations();
		$buildings = $this->globalinfo_model->GetBuildings();
		$cameras = $this->globalinfo_model->GetCameras();
		$data['defaultEngine'] = $defaultEngine;
		$data['defaultBuildings'] = $defaultBuildings;
		$data['defaultCameras'] = $defaultCameras;
		$data['locations'] = $locations;
		$data['buildings'] = $buildings;
		$data['cameras'] = $cameras;
		die(json_encode($data));
		// $locations = $this->
	}
	public function getLIveUrlInfo($serverip, $rtmpPort, $videoIndex){
		 return "rtmp://" . $serverip . ":" . $rtmpport . "/live/stream_" . $videoinindex;
	}
	public function getServerUrlInfo($serverip, $rtmpPort, $videoIndex){
		return "rtmp://" . $serverip . ":" . $rtmpport . "/live/search_" . $videoinindex;
	}
	
	public function searchplay() {
		$str_url = $_GET['str_url'];
		$data['str_url'] = $str_url;
		$data['str_starttime'] = $_GET['starttime'];
		$data['str_endtime'] = $_GET['endtime'];
		$this->load->view('searchplayer', $data);
	}
	
	public function eventLogs() {
	    
	}
}

/* End of file globalinfo.php */
/* Location: ./application/controllers/common/globalinfo.php */