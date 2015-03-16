<script type="text/javascript" src="<?php echo base_url();?>assets/js/moment.js"></script>
<script type="text/javascript">
var g_WndHeight = 0;
var g_WndWidth = 0;
var g_EngineInfos = new Array();
// g_EngineInfos[0] = {id:0, name:"Location #1", hostName:"localhost", webport:7001, rtmpport: 1935, deleted: 0, connected: 1, isdefault:0, ptr_buildings: null, ptr_camerainfos: null};
var g_BuildingInfos = new Array();
// g_BuildingInfos[0] = {id: 0, name: "Building #1, posx: 10, posy: 10, engineId: 0, mapId: 1, deleted: 0, changed: 0}
var g_DeviceInfos = new Array();
// g_DeviceInfos[0] = {id:0, videoinindex:0, videoinenable: 1, name:"VideoIn Name", engineId:0, buildingId:0, posx: 20, posy: 20, livetoken:"stream_0", vodtoken:"stream_startdate_enddate", connected:1, deleted:0,ptzEnable:0, ptzInfo:null, audioEnable:''};
// ptzInfo = {up:'http://cameraip/ptzup', down: 'http://cameraip/ptzdown', left:'', right:'', upleft:'', upright:'', downleft:'', downright:''};
var g_LayoutInfos = new Array();
// g_LayoutInfos[0] = {id:0, name="Layout #1", shared:0, deviceIds:"0::2::3", deleted:0};

var g_boolMenuShow = true;
var g_offsetHeight = 0;
var g_curSelectedDeviceTree = null;

var ICON_DEVICES_LOCATION = "<?php echo HTTP_IMAGES_PATH;?>location.png";
var ICON_DEVICES_LOCATION_DIS = "<?php echo HTTP_IMAGES_PATH;?>location_disconnected.png";
var ICON_DEVICES_BUILDING = "<?php echo HTTP_IMAGES_PATH;?>building.png";
var ICON_DEVICES_BUILDING_DIS = "<?php echo HTTP_IMAGES_PATH;?>building_disconnected.png";
var	ICON_DEVICES_CAMERA = "<?php echo HTTP_IMAGES_PATH;?>camera.png";
var ICON_DEVICES_CAMERA_DIS = "<?php echo HTTP_IMAGES_PATH;?>camera_disconnected.png";

var DEBUG_MODE	= false;

var g_bool_checking_connectstatus = new Array();

var g_main_thread = true;

// resize event
var CallbackResize = null;

function g_OnLoadPage() {
	//get dimensions 
	g_WndHeight = $(window).height();
	g_WndWidth 	= $(window).width();

	$(window).resize(function(event) {
		g_WndHeight = $(this).height();
		g_WndWidth = $(this).width();
		g_ResizeMainwnd(event);
	});

	g_ResizeMainwnd();
	//alert(window.clipboardData.getData('Text'));
}

function g_RedrawPage() {
	g_WndHeight = $(window).height();
	g_WndWidth 	= $(window).width();
	g_ResizeMainwnd(null);
}

function g_ResizeMainwnd(event) {
	var m_intHeader = $(".navbar").height();
	var m_intSubHeader = $("#subHeader").height();

	$("#mainContainer").height(g_WndHeight - m_intHeader - m_intSubHeader - 18 + g_offsetHeight);
    
    if (CallbackResize != null && event != null ) {
        CallbackResize(event);
    }
}

function g_LoadEngineInfo(callback_func) {
	var str_uid = "<?php echo $this->session->userdata('masterid');?>";
	if ( str_uid == "") return;
	$.ajax({
		url: "<?php echo base_url()."common/globalinfo/loadengineinfos"?>",
		data: {uid: str_uid},
		method: "POST",
		success: function (data) {
			var result = JSON.parse(data);
			var ptr_defaultEngine = result.defaultEngine[0];
			var ptr_defaultBuildings = result.defaultBuildings;
			var ptr_defaultCameras = result.defaultCameras;
			var ptr_Engines = result.locations;
			var ptr_Buildings = result.buildings;
			var ptr_Cameras = result.cameras;
			var int_engine_count = 0;
			if (g_EngineInfos == null) {
				g_EngineInfos = new Array();
			}

			// Load default location
			/* g_EngineInfos[0] = {
					id: ptr_defaultEngine.id,
					name: ptr_defaultEngine.name,
					hostName: ptr_defaultEngine.ipaddress,
					webport: ptr_defaultEngine.webport,
					manageport: 8090,
					rtmpport: ptr_defaultEngine.rtmpport,
					mapid: ptr_defaultEngine.mpaId,
					deleted: 0,
					period: 0,
					changed: 0,
					connected: ptr_defaultEngine.connectStatus,
					isdefault: 1
			};
			g_bool_checking_connectstatus[int_engine_count] = false;
			int_engine_count ++; */
			// Load All Locations 
			var int_location_count = 0;
			for (var i = 0; i < ptr_Engines.length; i ++) {
				g_EngineInfos[int_location_count] = {
						id: ptr_Engines[i].id,
						name: ptr_Engines[i].name,
						hostName: ptr_Engines[i].ipaddress,
						webport: ptr_Engines[i].webport,
						manageport: 8090,
						rtmpport: ptr_Engines[i].rtmpport,
						mapid: ptr_Engines[i].mpaId,
						deleted: 0,
						changed: 0,
						period: 0,
						connected: ptr_Engines[i].connectStatus,
						isdefault: 0
				};
				g_bool_checking_connectstatus[int_engine_count] = false;
				int_engine_count ++;
				int_location_count ++;
			}
			
			// Load all buildings
			if (g_BuildingInfos == null) {
				g_BuildingInfos = new Array();
			}			

			var int_building_count = 0;
			// load default buildings
			/* for (var i = 0; i < ptr_defaultBuildings.length; i ++) {
				g_BuildingInfos[int_building_count] = {
					id: ptr_defaultBuildings[i].id,
					name: ptr_defaultBuildings[i].name,
					engineId: ptr_defaultBuildings[i].locationId,
					mapId: ptr_defaultBuildings[i].mapId,
					groupIds: ptr_defaultBuildings[i].groupIds,
					posx: ptr_defaultBuildings[i].posx,
					posy: ptr_defaultBuildings[i].posy,
					changed: 0,
					deleted: 0,
					isdefault: 1
				};
				int_building_count ++;
			} */
			// load all buildings
			for (var i = 0; i < ptr_Buildings.length; i ++) {
				g_BuildingInfos[int_building_count] = {
						id: ptr_Buildings[i].id,
						name: ptr_Buildings[i].name,
						engineId: ptr_Buildings[i].locationId,
						mapId: ptr_Buildings[i].mapId,
						groupIds: ptr_Buildings[i].groupIds,
						posx: ptr_Buildings[i].posx,
						posy: ptr_Buildings[i].posy,
						changed: 0,
						deleted: 0,
						isdefault: 0
					};
				int_building_count ++;
			}

			// Load All Cameras
			if (g_DeviceInfos == null) {
				g_DeviceInfos = new Array();
			}
			var int_device_count = 0;
			// load default cameras
			/* for (var i = 0; i < ptr_defaultCameras.length; i ++) {
				var tmp_liveinfo = new Array();
				tmp_liveinfo = {
					ptz: 'N',
					audio:'A',
					motion:'N',
					recording: 'A'
					};
				g_DeviceInfos[int_device_count] = {
						id: ptr_defaultCameras[i].id,
						videoinindex: ptr_defaultCameras[i].videoInIndex,
						name: ptr_defaultCameras[i].videoInName,
						videoinenable: ptr_defaultCameras[i].videoInEnable,
						engineId: ptr_defaultCameras[i].locationId,
						buildingId: ptr_defaultCameras[i].buildingId,
						livetoken: ptr_defaultCameras[i].videoLiveURL,
						vodtoken: ptr_defaultCameras[i].videoSearchURL,
                        posx: ptr_defaultCameras[i].posx,
                        posy: ptr_defaultCameras[i].posy,
						connected: ptr_defaultCameras[i].connectStatus,
						ptzEnable: 0,
						audioEnable: 1,
						liveInfo: tmp_liveinfo,
						deleted: 0,
						changed: 0,
						isdefault: 1
				};
				int_device_count ++;
			} */

			// load all cameras
			for (var i = 0; i < ptr_Cameras.length; i ++) {
				var tmp_liveinfo = new Array();
				tmp_liveinfo = {
					ptz: 'N',
					audio:'A',
					motion:'N',
					recording: 'A'
					};
				
				g_DeviceInfos[int_device_count] = {
						id: ptr_Cameras[i].id,
						videoinindex: ptr_Cameras[i].videoInIndex,
						name: ptr_Cameras[i].videoInName,
						videoinenable: ptr_Cameras[i].videoInEnable,
						engineId: ptr_Cameras[i].locationId,
						buildingId: ptr_Cameras[i].buildingId,
						livetoken: ptr_Cameras[i].videoLiveURL,
						vodtoken: ptr_Cameras[i].videoSearchURL,
                        posx: ptr_Cameras[i].posx,
                        posy: ptr_Cameras[i].posy,
						connected: ptr_Cameras[i].connectStatus,
						ptzEnable: 0,
						audioEnable: 1,
						liveInfo: tmp_liveinfo,
						deleted: 0,
						changed: 0,
						isdefault: 0
				};
				int_device_count ++;
			}
			
			callback_func();
		},
		error: function(e) {
			// alert(e);
		}
	});
}

// Get connect status of streaming server
function g_GetServerConnectStatus( ptr_serverinfo ) {
	$.ajax({
		url: str_serverurl + "/grcenter.allinfo.retrieveServerConnectStatus.nsf",
		type: "POST",
		success: function (data) {
			var engineIndex = g_GetEngineIndexById(ptr_serverinfo.id);
			if (engineIndex != -1) {
				if (g_EngineInfos[engineIndex].connected != 1) {
					g_EngineInfos[engineIndex].changed = 1;
					g_EigineInfos[engineIndex].connected = 1;
				}
			}
		},
		error: function(data) {
			var engineIndex = g_GetEngineIndexById(ptr_serverinfo.id);
			if (engineIndex != -1) {
				if (g_EngineInfos[engineIndex].connected != 0) {
					g_EngineInfos[engineIndex].changed = 1;
					g_EigineInfos[engineIndex].connected = 0;
				}
			}
		}
	});
}

function g_GetEngineIndexById( str_id ) {
	var retIndex = -1;
	for (var i = 0; i < g_EngineInfos.length; i ++) {
		if (g_EngineInfos[i].id == str_id)
		{
			retIndex = i;
			break;
		}
	}
	return retIndex;
}

function g_GetEngineInfoById( str_id ) {
    var ptr_return = null;
    for (var i = 0; i < g_EngineInfos.length; i ++) {
        if (g_EngineInfos[i].id == str_id)
        {
            ptr_return = g_EngineInfos[i];
            break;
        }
    }
    return ptr_return;
}

function g_GetBuildingInfoById( str_id ) {
    var ptr_return = null;
    for (var i = 0; i < g_BuildingInfos.length; i ++) {
        if (g_BuildingInfos[i].id == str_id)
        {
            ptr_return = g_BuildingInfos[i];
            break;
        }
    }
    return ptr_return;
}

function g_GetDeviceInfoById( str_id ) {
	var ptr_return = null;
	for (var i = 0; i < g_DeviceInfos.length; i ++) {
		if (g_DeviceInfos[i].id == str_id) {
			ptr_return = g_DeviceInfos[i];
			break;
		}
	}
	return ptr_return;
}

function g_GetDeviceInfosByLocationBuilding( str_location_id, str_building_id ) {
	var ptr_return = new Array();
	var int_count = 0;
	for (var i = 0; i < g_DeviceInfos.length; i ++) {
		if (str_building_id != "-1") {
			if (g_DeviceInfos[i].engineId == str_location_id && g_DeviceInfos[i].buildingId == str_building_id ) {
				ptr_return[int_count] = g_DeviceInfos[i];
				int_count ++;
			}
		} else {
			if (g_DeviceInfos[i].engineId == str_location_id ) {
				ptr_return[int_count] = g_DeviceInfos[i];
				int_count ++;
			}
		}
	}

	return int_count == 0?null:ptr_return;
}

// Get parent tree item 
function g_GetTreeItemByID(ptr_items, str_id) {
    var ret_item = null;
    
    for (var i = 0; i < ptr_items.length; i ++) {
        if (ptr_items[i].element.id == str_id) {
            ret_item = ptr_items[i];
            break;
        }
    }

    return ret_item;
}

// Get all Child items by parent id
function g_GetAllChildItemByLocationID( ptr_items, str_id ) {
	var ret_items = new Array();
	var int_count = 0;
	var str_tmp_id = "";
	for (var i = 0; i < ptr_items.length; i ++) {
		str_tmp_id = ptr_items[i].element.id.split("_")[1];
		
        if (str_tmp_id.split(":")[0] == str_id && ptr_items[i].element.id != "Location_" + str_id) {        	
            ret_items[int_count] = ptr_items[i];
            int_count ++; 
        }
    }

    return int_count == 0 ? null : ret_items;
}

// Remove All children elements
function g_jqRemoveChildObject(oparent) {
	oparent.children().each(function(){
		$(this).remove();
	});
}

function g_GeneratePlayer(str_server, str_video, str_width, str_height) {
	/* return */
	var str_overlay = "<div class='channelOverlay'></div>";
	return str_overlay + "<object type='application/x-shockwave-flash' class='livePlayer' data='<?php echo HTTP_SWF_PATH;?>' width='"+ str_width + "' height='"+str_height+"'><param name='movie' value='<?php echo HTTP_SWF_PATH;?>'><param name='wmode' value='opaque'><param name='allowfullscreen' value='true'><param name='flashvars' value='&amp;type=rtmp&amp;streamer="+str_server+"&amp;video="+str_video+"&amp;autoStart=true&amp;cbtopClr=0x484848'><param name='allowscriptaccess' value='always'></object>";
}

function g_GenerateMJPEGPlayer( str_server, str_videoinindex ) {
	var str_overlay = "<div class='channelOverlay'></div>";
	return str_overlay + "<img src='http://"+str_server+":9090/"+str_videoinindex+"/video.cgi' style='width: 100%;height: 100%'>";
}

function g_GenerateOldPlayer(str_url, str_width, str_height) {
	var str_overlay = "<div class='channelOverlay'></div>";
	return str_overlay + "<object class='livePlayer' width='"+ str_width + "' height='"+str_height+"'>" +  
					"<param name='movie' value='<?php echo HTTP_SWF_PATH;?>'></param>" + 
					"<param name='flashvars' value='src=" + str_url + "&streamType=live&autoPlay=true&playButtonOverlay=false&controlBarMode=none&controlBarAutoHide=false&bufferingOverlay=true&optimizeBuffering=true'></param>" + 
					"<param name='allowFullScreen' value='true'></param>" + 
					"<param name='wmode' value='opaque'></param>" + 
					"<embed src='<?php echo HTTP_SWF_PATH;?>' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' wmode='opaque' " + 
					"width='" + str_width + "' height='"+ str_height + "' flashvars='src="+str_url+"&streamType=live&autoPlay=true&playButtonOverlay=false&controlBarMode=none&controlBarAutoHide=false&bufferingOverlay=true&optimizeBuffering=true'></embed>"+ 
				"</object>";
}

// Open popup window
function g_OpenExternalWindow(str_url, str_title) {
    var ext_window = window.open(str_url, "_blank", "toolbar=no, scrollbars=no, resizable=no, top=100, left=100, width=800, height=600");    
    return ext_window;
}

// Retrieve Device tree
function g_GenerateDevicesTree( obj_tree, bool_hasdevice ) {
	for (var i = 0; i < g_EngineInfos.length; i ++) {
		var str_icon = ICON_DEVICES_LOCATION;
		obj_tree.jqxTree('addTo', {icon: str_icon, expanded: true, label: g_EngineInfos[i].name, id: "Location_" + g_EngineInfos[i].id});    			
   	}
	var parentItems = obj_tree.jqxTree('getItems');
   	for (var i = 0; i < g_BuildingInfos.length; i ++) {
   		var parentElement = g_GetTreeItemByID(parentItems, "Location_" + g_BuildingInfos[i].engineId);
   		obj_tree.jqxTree('addTo', {icon: ICON_DEVICES_BUILDING, label: g_BuildingInfos[i].name, id: "Building_" + g_BuildingInfos[i].engineId + ":" + g_BuildingInfos[i].id}, parentElement );
   	}

   	if (bool_hasdevice) {
   		parentItems = obj_tree.jqxTree('getItems');
   		for (var i = 0; i < g_DeviceInfos.length; i ++) {
   	   		var aDevice = g_DeviceInfos[i]; 
			var str_icon = ICON_DEVICES_CAMERA;
			
   	   		// determine parentElement
   	   		var parentElement;
   	   		if ( aDevice.buildingId != '-1') 	// Camera in Building
   	   		{
   	   	   		parentElement = g_GetTreeItemByID(parentItems, "Building_" + aDevice.engineId + ":" + aDevice.buildingId);
   	   		}
   	   		else								// Camera in Location directly
   	   		{
   	   	   		parentElement = g_GetTreeItemByID(parentItems, "Location_" + aDevice.engineId);
   	   		}

   	   		var bool_disabled = false;
   	   		if (aDevice.connected == "0")
   	   	   		bool_disabled = true;
	   	   		
   	   		obj_tree.jqxTree('addTo', { icon: str_icon, label: aDevice.name, id: "Camera_" + aDevice.engineId + ":" + aDevice.id }, parentElement );//, disabled: bool_disabled
   	   		document.getElementById("Camera_"  + aDevice.engineId + ":" + aDevice.id).setAttribute ( "connected", aDevice.connected );
   		}
   	}
   	obj_tree.jqxTree('render');
}

function g_GetUTCDateTime( obj_time ) {
	var utc_time = new Date ( 0 );
	utc_time.setYear ( obj_time.getUTCFullYear() );
	utc_time.setMonth ( obj_time.getUTCMonth() );
	utc_time.setDate ( obj_time.getUTCDate() );
	utc_time.setHours ( 0 );
	utc_time.setMinutes ( 0 );
	utc_time.setSeconds ( 1 );

	delete obj_time;
	
	return utc_time;
}

// Location ( Server ) Connection Check Thread
function g_CheckConnectStatus (callback_func) {
	for (var i = 0; i < g_EngineInfos.length; i ++) {
		if (!g_EngineInfos[i] || g_EngineInfos[i].deleted == "Y") {
			continue;
		}
		window.setTimeout("g_AsyncCheckServerConnectStatus(" + i + "," + callback_func+ ")", 50);
	}

	if (g_main_thread) {
		window.setTimeout( "g_CheckConnectStatus(" + callback_func + ")", 2000);
	}
}
function g_AsyncCheckServerConnectStatus( str_index, callback_func ) {
	if ( g_bool_checking_connectstatus[ str_index * 1 ] == true ) 
		return;
	
	var ptr_engine = g_EngineInfos[ str_index * 1 ];
	if ( ptr_engine == null || ptr_engine.deleted == 'Y' ) return;
	var str_url = "http://" + ptr_engine.hostName + ":" + ptr_engine.webport + "/grcenter.allinfo.retrieveServerConnectStatus.nsf";
	$.ajax({
		url: str_url,
		type: "POST",
		data: {lid: str_index * 1 },
		success: function( data ) {
			var org_status = g_EngineInfos[str_index * 1].connected;
			if ( org_status * 1 != data.connectstatus * 1) {
				g_EngineInfos[str_index * 1].connected = data.connectstatus;
				g_EngineInfos[str_index * 1].changed = 1;
				if (callback_func != null) {
					callback_func(str_index, null);
				}
				g_bool_checking_connectstatus[str_index * 1] = false;
			}
		},
		error: function ( XMLHttpRequest, textStatus, errorThrown ) {
			if (g_EngineInfos[str_index * 1]) {
				g_bool_checking_connectstatus[str_index * 1] = false;
				var org_connected = g_EngineInfos[str_index * 1].connected;
				if (org_connected == 0)
					return;
				g_EngineInfos[str_index * 1].connected = 0;
				if (callback_func != null)
					callback_func(str_index, null);				
			}
		}
	});
}

function g_date_inc(date) {
    var ty = date.substr(0, 4);
    var tm = date.substr(4, 2);
    var td = date.substr(6, 2);
   
    var y = Number(ty);
	var m = Number(tm);
    var d = Number(td);
    

    switch(m) {
    	case 1:
    	case 3:
    	case 5:
    	case 7:
    	case 8:
    	case 10:
        	if(d > 31) {
            	m++;
            	if(m > 12) {
					y++;
					m = 1;
                }
            	d = 1;
        	}
        	break;
    	case 4:
    	case 6:
    	case 9:
    	case 11:
        	if(d > 30) {
            	m++;
            	if(m > 12) {
					y++;
					m = 1;
                }
            	d = 1;
        	}
        	break;
    	case 2:
        	if((y % 4) == 0) 
            	var t_d = 29;
            else
                var t_d = 28;
            
          	if(d > t_d) {
            	m++;
            	d = 1;
        	}
        	break;
        default:
            break;
    }

    if(d < 10) 
        var dd = "0".concat(String(d));
    else
        var dd = String(d);

    if(m < 10) 
        var mm = "0".concat(String(m));
    else
        var mm = String(m);
    
    var result;
    result = String(y).concat(mm).concat(dd);

    return result;
}

function g_GetServerUTCTime( obj_time, int_difftime ) {
	var target_time = new Date();
	
	target_time.setTime(obj_time.getTime() + obj_time.getTimezoneOffset()*60*1000 - int_difftime );
	return target_time; 
}

function g_GetLocalTimeFromUTC( obj_time, int_difftime ) {
	var target_time = new Date();
	target_time.setTime(obj_time.getTime() - obj_time.getTimezoneOffset()*60*1000 + int_difftime);
	return target_time
}

function g_GetDateStringFormat1( obj_time ) {
	var str_year = obj_time.getFullYear();
	var str_month = obj_time.getMonth() + 1;
	var str_date = obj_time.getDate();
	if (str_month * 1 < 10)
		str_month = "0" + str_month;
	if (str_date < 10) 
		str_date = "0" + str_date;
	return str_year + "-" + str_month + "-" + str_date;
}

function g_GetTimeString( obj_time ) {
	var str_hours = obj_time.getHours();
	if (str_hours < 10) 
		str_hours = "0" + str_hours;
	var str_minutes = obj_time.getMinutes();
	if (str_minutes < 10)
		str_minutes = "0" + str_minutes;
	var str_seconds = obj_time.getSeconds();
	if (str_seconds < 10)
		str_seconds = "0" + str_seconds;
	return str_hours + ":" + str_minutes + ":" + str_seconds;
}
function DateToString( obj_date ) {
	var str_year = obj_date.getFullYear();
	var str_month = obj_date.getMonth() + 1;
	var str_date = obj_date.getDate();
	var str_hours = obj_date.getHours();
	var str_minutes = obj_date.getMinutes();
	var str_seconds = obj_date.getSeconds();
	if (str_month * 1 < 10)
		str_month = "0" + str_month;
	if (str_date < 10) 
		str_date = "0" + str_date;
	if ( str_hours < 10) str_hours = "0" + str_hours;
	if ( str_minutes < 10 ) str_minutes = "0" + str_minutes;
	if ( str_seconds < 10 ) str_seconds = "0" + str_seconds;
	return "" + str_year + "" + str_month + "" + str_date + str_hours + str_minutes + str_seconds;
}

function ConvertUTCDateToLocalDate( str_date ) {
	var obj_date = new Date ( str_date );
    var newDate = new Date(obj_date.getTime()-obj_date.getTimezoneOffset()*60*1000);
    return newDate;   
}
function g_GetDateStringFormat2( obj_time ) {
	var str_year = obj_time.getFullYear();
	var str_month = obj_time.getMonth() + 1;
	var str_date = obj_time.getDate();
	if (str_month * 1 < 10)
		str_month = "0" + str_month;
	if (str_date < 10) 
		str_date = "0" + str_date;
	var str_hours = obj_time.getHours();
	if (str_hours < 10) 
		str_hours = "0" + str_hours;
	var str_minutes = obj_time.getMinutes();
	if (str_minutes < 10)
		str_minutes = "0" + str_minutes;
	var str_seconds = obj_time.getSeconds();
	if (str_seconds < 10)
		str_seconds = "0" + str_seconds;
	return str_year + "-" + str_month + "-" + str_date + " " + str_hours + ":" + str_minutes + ":" + str_seconds;
}

function print_log ( str_log ) {
	if (DEBUG_MODE == true)
	{
		console.log ( str_log );
	}
}

/**
 * Param	: String "YYYY-mm-dd hh:mm:ss"
 * Return	: Date ret_date
 */
function StringToLocalDateTime( str_datetime ) {
	var obj_moment = moment ( str_datetime );
	var ret_date = moment.toDate();
	return ret_date;
}

/**
 * Param	: String "YYYY-mm-dd hh:mm:ss"
 * Return	: Date ret_date
 */
function StringToGMTDateTime( str_datetime ) {
	var obj_moment = moment ( str_datetime );
	var ret_date = moment.utc();
	return ret_date.toDate();
}

function OnClickHideMenu() {
	if ( g_boolMenuShow == true ) {
		if ( $(".arrowup").hasClass('glyphicon-chevron-up') ) {
			$(".arrowup").removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
			$("#menuContainer").get(0).style.display = "none";
			g_offsetHeight = 50;
			$(window).resize();
		}
		g_boolMenuShow = false;
	} else {
		if ( $(".arrowup").hasClass('glyphicon-chevron-down') ) {
			$(".arrowup").removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
			$("#menuContainer").get(0).style.display = "block";
			g_offsetHeight = 0;
			$(window).resize();
		}
		g_boolMenuShow = true;
	}
}

function OnSearchCameras( obj ) {
	var str_key = obj.value;
	if ( g_curSelectedDeviceTree == null )
		return ;
	var items = g_curSelectedDeviceTree.jqxTree('getItems');
	for ( var i = 0; i < items.length; i ++ ) {
		var str_id = items[i].element.id;
		if ( str_id.indexOf( 'Camera' ) < 0 ) continue; 
		var str_title = items[i].label;
		console.log ( str_title.indexOf( str_key ) );
		if ( str_title.indexOf( str_key ) > -1 ) {
			$("li[id='" + str_id+"']").show();
		} else {
			$("li[id='" + str_id+"']").hide();
		}
	}
}
</script>