<?php
/********************************************************************************************
 * Page				: AngularJS main controller
 * Author			: KCH
 * ------------------------------------------------------------------------------------------
 * File Name		: liveController.php
 * Description		: Angular Controller Javascript Functions
 * Date				: Sep 3, 2014 11:20:57 AM
 * Version			: 1.0
 ********************************************************************************************/
?>
<script type="text/javascript">
var appPolling = angular.module('PollingApp', ['timer', 'hashKeyCopier']);
appPolling.factory('$AllInfoService', function($http) {
	// Default engines, buildings, devices
	var default_engine 		= [];
	var default_buildings 	= [];
	var default_devices		= [];
	
	// Engines, buildings, devices
	var engines 			= [];
	var buildings			= [];
	var devices				= [];

	// Connect status of all the locations and devices
	var connect_statuses	= [];

	// All the information already set or not 
	var initialized			= false;

	var bDefaultEngineChanged = false;
	var bEngineChanged		= false;
	var bBuildingChanged	= false;
	var bDeviceChanged		= false;
	
	return {
		setDefaultEngineChanged: function () {
			bDefaultEngineChanged = true;
		},
		setEngineChanged: function ( ) {
			bEngineChanged = true;
		},
		setBuildingChanged: function() {
			bBuildingChanged = true;
		},
		setDeviceChanged: function() {
			bDeviceChanged = true;
		},
		setEngineInfos: function ( ptr_engines ) {
			engines = ptr_engines;			
			return true;
		},
		getEngineInfos: function () {
			return engines;
		},
		setBuildingInfos: function ( ptr_buildings ) {
			buildings = ptr_buildings;
			return true;
		},
		getBuildingInfos: function () {
			return buildings;
		},
		setDeviceInfos: function ( ptr_devices ) {
			devices = ptr_devices;
			return true;
		},
		getDeviceInfos: function () {
			return devices;
		},
		setInitialized: function () {
			initizliaed = true;
		},
		getInitialized: function () {
			return initialized;
		},
		setConnectStatus: function ( int_serverId, ptr_liveinfo ) {
			var bFind = false;
			for (var i = 0; i < connect_statuses.length; i ++) {
				if (connect_statuses[i].serverId == int_serverId) {
					connect_statuses[i].liveInfo = ptr_liveinfo;
					bFind = true;
				}
			}

			if ( bFind == false ) {
				connect_statuses.push({serverId: int_serverId, liveInfo: ptr_liveinfo});
			} 

			return true;
		},
		getConnectStatus: function ( int_serverId ) {
			var ptr_connectstatus = null;
			for (var i = 0; i < connect_statuses.length; i ++) {				
				if (connect_statuses[i].serverId == int_serverId) {
					ptr_connectstatus = connect_statuses[i];					
					break;
				}
			}
			return ptr_connectstatus;
		},
		/* refreshTreeInfo: function () {
			if ( bEngineChanged == true ) {
				refreshEngineInfo();
			} else if ( bBuildingChanged == true ) {
				refreshBuildingInfo();
			} else if ( bDeviceChanged == true ) {
				refreshDeviceInfo();
			}
		}, */
		refreshEngineInfo: function () {
			if ( bEngineChanged == false ) {
				return true;
			} else {
				g_EngineInfos = engines;
				var obj_devicetree = $("#deviceTree");
				for (var i = 0; i < engines.length; i ++) {
					var bool_updated = false;
					var jqobj_location = $("[id='Location_" + engines[i].id + "']");
					var jqobj_root = $("");
					if ( jqobj_location.length == 0) { 		// must be added
						var parent_item = obj_devicetree.jqxTree( 'getItems' )[0];
						obj_devicetree.jqxTree( 'addTo', {label: engines[i].name, id: "Location_" + engines[i].id, icon: ICON_DEVICES_LOCATION}, null );						 
						$( "[id='Location_" + engines[i].id + "']" ).attr('processed', '1');
						
					} else {								// must be updated
						var element = jqobj_location[0];
						var tree_item = obj_devicetree.jqxTree( 'getItem', element );
						
						if ( tree_item.label != engines[i].name)
							obj_devicetree.jqxTree( 'updateItem', element, {label: engines[i].name, icon: tree_item.icon} );			
						jqobj_location.attr('processed', '1');			
					}
				}
				// Delete Location
				$(document).find("[id^='Location_']").each(function() {
					var processed = $(this).attr('processed');
					if (processed && processed == '1') {
					} else {
						$(this).remove();
					}
					$(this).attr('processed', '0');
				});
				AttachDragDropEventListener();
			}
			bEngineChanged = false;
		},
		refreshBuildingInfo: function () {
			if ( bBuildingChanged == false ) {
				return true;
			} else {
				g_BuildingInfos = buildings;
				var obj_devicetree = $("#deviceTree");
				for (var i = 0; i < buildings.length; i ++) {
					var bool_updated = false;
					var jqobj_building = $( "[id='Building_" + buildings[i].engineId + ":" + buildings[i].id + "']" );
					var jqobj_location = $( "[id='Location_" + buildings[i].engineId + "']" );
					if ( jqobj_building.length == 0) { 		// must be added
						 var parent_element = jqobj_location[0];
						 var parent_item = obj_devicetree.jqxTree( 'getItem', parent_element );
						 obj_devicetree.jqxTree( 'addTo', {label: buildings[i].name, id: "Building_" + buildings[i].engineId + ":" + buildings[i].id, icon: ICON_DEVICES_BUILDING}, parent_item );						 
						 $( "[id='Building_" + buildings[i].engineId + ":" + buildings[i].id + "']" ).attr('processed', '1');
						
					} else {								// must be updated
						var element = jqobj_building[0];
						var tree_item = obj_devicetree.jqxTree( "getItem", element );
						if ( tree_item.label != buildings[i].name)
							obj_devicetree.jqxTree( 'updateItem', element, {label: buildings[i].name, icon: tree_item.icon } );		
						jqobj_building.attr('processed', '1');				
					}
				}
				// Delete Building
				$(document).find("[id^='Building_']").each(function() {
					var processed = $(this).attr('processed');
					if (processed && processed == '1') {
					} else {
						$(this).remove();
					}
					$(this).attr('processed', '0');
				});
				AttachDragDropEventListener();
				
			}
			bBuildingChanged = false;
		},
		refreshDeviceInfo: function() {
			if ( bDeviceChanged == false ) {
				return true;
			} else {
				g_DeviceInfos = devices;
				var obj_devicetree = $("#deviceTree");
				for (var i = 0; i < devices.length; i ++) {
					var bool_updated = false;
					var jqobj_device = $( "[id='Camera_" + devices[i].engineId + ":" + devices[i].id + "']" );
					var jqobj_building = $("[id='Building_" + devices[i].engineId + ":" + devices[i].buildingId + "']");
					var jqobj_location = $( "[id='Location_" + devices[i].engineId + "']");
					if ( jqobj_device.length == 0) { 		// must be added
						 var parent_element = jqobj_building[0];
						 var parent_item = obj_devicetree.jqxTree( 'getItem', parent_element );
						 obj_devicetree.jqxTree( 'addTo', {label: devices[i].name, id: "Camera_" + devices[i].engineId + ":" + devices[i].id, icon: ICON_DEVICES_CAMERA}, parent_item );
						 
						 $( "[id='Camera_" + devices[i].engineId + ":" + devices[i].id + "']" ).attr('processed', '1');		
					} else {								// must be updated
						var element = jqobj_device[0];
						var tree_item = obj_devicetree.jqxTree( 'getItem', element ); 
						var str_label = tree_item.label;
						if (str_label != devices[i].name) {
							obj_devicetree.jqxTree( 'updateItem', element, {label: devices[i].name, icon: tree_item.icon} );
						}
						jqobj_device.attr('processed', '1');
					}					
				}
				
				// Delete Camera
				$(document).find("[id^='Camera_']").each(function() {
					var processed = $(this).attr('processed');
					if (processed && processed == '1') {
					} else {
						$(this).remove();
					}
					$(this).attr('processed', '0');
				});
				AttachDragDropEventListener();
			}
			bDeviceChanged = false;
		},
		refreshConnectStatus: function () {
			if (engines == null) return;
			var obj_devicetree = $("#deviceTree");
			var tmp_engines = engines;
			for (var i = 0; i < tmp_engines.length; i ++) {
				var con_status = null;
				for ( var j = 0; j < connect_statuses.length; j ++) {
					if ( connect_statuses[j].serverId * 1 == tmp_engines[i].id ) {
						con_status = connect_statuses[j];
						break;
					}
				}
				var str_locationId = "#Location_" + tmp_engines[i].id;
				var jqobj_location = $("#Location_" + tmp_engines[i].id);
				if ( jqobj_location.length == 0) {
					continue; 
				}
				var str_connect = jqobj_location.attr("connected");
				if ( con_status == null || con_status.liveInfo.connectstatus * 1 == 0 ) { 		// Server disconnected
					var element = jqobj_location[0];
					var tree_item = obj_devicetree.jqxTree( 'getItem', element );
					if ( tree_item.icon != ICON_DEVICES_LOCATION_DIS)
						obj_devicetree.jqxTree( 'updateItem', element, {label: tree_item.label, icon: ICON_DEVICES_LOCATION_DIS} );
					jqobj_location.attr("connected", '0');
									
					$("[id^='Camera_" + tmp_engines[i].id + "']").each(function(){
						var element = $(this)[0];
						var tree_item = obj_devicetree.jqxTree( 'getItem', element );
						if ( tree_item.icon != ICON_DEVICES_CAMERA_DIS )
							obj_devicetree.jqxTree( 'updateItem', element, {label: tree_item.label, icon: ICON_DEVICES_CAMERA_DIS} );
						$(this).attr("connected", "0");
					});
					continue;
				} else if ( con_status != null && con_status.liveInfo.connectstatus * 1 == 1 && str_connect * 1	!= 1) {
					var element = jqobj_location[0];
					var tree_item = obj_devicetree.jqxTree( 'getItem', element );
					obj_devicetree.jqxTree( 'updateItem', element, {label: tree_item.label, icon: ICON_DEVICES_LOCATION} );
					jqobj_location.attr("connected", '1');					 
				}
				// Device connection status
				var tmp_liveInfo = con_status.liveInfo.liveinfo;
				var tmp_devices = devices;
				for ( var j = 0; j < tmp_liveInfo.rmstate.length; j ++) {
					var str_videoInIndex = tmp_liveInfo.videoinindex[j];
					var ptr_device = null;
					if ( tmp_devices ) {
						for ( var k = 0; k < tmp_devices.length; k ++ ) {
							if ( tmp_devices[k].videoinindex == str_videoInIndex && tmp_devices[k].engineId == engines[i].id) {
								ptr_device = tmp_devices[k];
								break;
							}
						}
					}
					if ( ptr_device) {
						var jqobj_device = $("[id='Camera_" + ptr_device.engineId + ":" + ptr_device.id + "']");
						if ( jqobj_device.length > 0) {
							jqobj_device.attr("rmstate", tmp_liveInfo.rmstate[j]);
							var cur_connect = jqobj_device.attr("connected");
							// if ( cur_connect * 1 != tmp_liveInfo.connect[j] ) {
								jqobj_device.attr("connected", tmp_liveInfo.connect[j]);
								var delement = jqobj_device[0];
								var dtree_item = obj_devicetree.jqxTree( 'getItem', delement ); 
								if (tmp_liveInfo.connect[j] * 1 == 0 && $("[id='" + dtree_item.id + "']").find("img").eq(0).attr( "src" ) != ICON_DEVICES_CAMERA_DIS) {
									$("[id='" + dtree_item.id + "']").find("img").eq(0).attr( "src", ICON_DEVICES_CAMERA_DIS );
								} else if (tmp_liveInfo.connect[j] * 1 == 1 && $("[id='" + dtree_item.id + "']").find("img").eq(0).attr( "src" ) != ICON_DEVICES_CAMERA){
									$("[id='" + dtree_item.id + "']").find("img").eq(0).attr( "src", ICON_DEVICES_CAMERA );
								}
							// }
						}
						// Update channel information 
						var str_deviceId = ptr_device.id;
						// ptz, motion, recording, audio
						var str_ptzst 		= tmp_liveInfo.rmstate[j].substring( 0, 1 );
						var str_motionst 	= tmp_liveInfo.rmstate[j].substring( 1, 2 );
						var str_recordingst	= tmp_liveInfo.rmstate[j].substring( 2, 3 );
						var str_audiost		= tmp_liveInfo.rmstate[j].substring( 3, 4 );

						
						$(".channelDiv").each( function() {
							var str_assignedId = $(this).attr( 'assignedid' );
							
							if ( str_deviceId == str_assignedId ) {
								var obj_overlay 	= $(this).find(".channelOverlay");
								var img_ptz 		= obj_overlay.find("img#livePtzSt");
								var img_recording 	= obj_overlay.find("img#liveRecordingSt");
								var img_audio	 	= obj_overlay.find("img#liveAudioSt");
								var img_motion	 	= obj_overlay.find("img#liveMotionSt");
								var str_imgsrc 		= '';

								str_imgsrc = img_ptz.attr("src");
								if ( str_ptzst == 'A' && str_imgsrc != ICON_PTZ_ACTIVE ) {
									img_ptz.attr( "src", ICON_PTZ_ACTIVE );
								} else if ( str_ptzst == 'N' && str_imgsrc != ICON_PTZ_DISABLE ) {
									img_ptz.attr( "src", ICON_PTZ_DISABLE );
								}
								
								str_imgsrc = img_motion.attr("src");
								if ( str_motionst == 'A' && str_imgsrc != ICON_MOTION_ACTIVE) {
									img_motion.attr( "src", ICON_MOTION_ACTIVE );
								} else if ( str_motionst == 'N' && str_imgsrc != ICON_MOTION_DISABLE ) {
									img_motion.attr( "src", ICON_MOTION_DISABLE );
								}

								str_imgsrc = img_audio.attr("src");
								if ( str_audiost == 'A' && str_imgsrc != ICON_AUDIO_ACTIVE) {
									img_audio.attr( "src", ICON_AUDIO_ACTIVE );
								} else if ( str_audiost == 'N' && str_imgsrc != ICON_PTZ_DISABLE ) {
									img_audio.attr( "src", ICON_AUDIO_DISABLE );
								}

								str_imgsrc = img_recording.attr("src");
								if ( str_recordingst == 'A' && str_imgsrc != ICON_RECORDING_ACTIVE) {
									img_recording.attr( "src", ICON_RECORDING_ACTIVE );
								} else if ( str_recordingst == 'N' && str_imgsrc != ICON_RECORDING_DISABLE ) {
									img_recording.attr( "src", ICON_RECORDING_DISABLE );
								}
							}
						});
					}
				}
			}
			obj_devicetree.jqxTree( 'refresh' );
		}
	};
});

// Main controller for all information of locations and buildings and devices - Added by KCH
function mainController($scope, $timeout, $http, $HashKeyCopier, $AllInfoService) {
    $scope.timerRunning 		= false;
    $scope.timerType 			= '';
	$scope.hostUrl 				= '<?php echo base_url();?>common/globalinfo/loadengineinfos';
	$scope.defaultEngine		= [];
	$scope.engineInfos			= [];	
	$scope.buildings			= [];
	$scope.devices				= [];
	$scope.connectStatus		= '';
	$scope.bTreeInitialized 	= false;

    $scope.startTimer = function () {
        $scope.$broadcast( 'timer-start' );
        $scope.timerRunning = true;
    };

    $scope.stopTimer = function () {
        $scope.$broadcast( 'timer-stop' );
        $scope.timerRunning = false;
    };

    $scope.buildServerInfo = function ( ptr_serverinfos ) {
	    $scope.engineInfos = $HashKeyCopier.copyHashKeys( $scope.engineInfos, ptr_serverinfos );
    };

    $scope.buildBuildingInfo = function ( ptr_buildinfos ) {
	    $scope.buildings = $HashKeyCopier.copyHashKeys( $scope.buildings, ptr_buildinfos );
    };

    $scope.buildDeviceInfo = function ( ptr_deviceinfos ) {
	    $scope.devices = $HashKeyCopier.copyHashKeys( $scope.devices, ptr_deviceinfos );
    };

    $scope.buildDefaultEngine = function ( ptr_engineInfo ) {
	    $scope.defaultEngine = $HashKeyCopier.copyHashKeys( $scope.defaultEngine, ptr_engineInfo );
    };

    $scope.GetLocationInfo = function() {
	    return $scope.engineInfos;
    }

    $scope.$on( 'timer-tick', function (event, args) {
        $timeout( function () {
        	var str_uid = "<?php echo $this->session->userdata('masterid'); ?>";
	    	if ( str_uid == '')
		    	return;
			$http.get( $scope.hostUrl, {uid: "<?php echo $this->session->userdata('masterid'); ?>"} ) . 
				success( function( data, status, headers, config ) {
					var engineInfos 	= [];
					var buildingInfos 	= [];
					var deviceInfos 	= [];

					// Retrieve default engine information  - Start
					var tmpDefaultEngine = [];
					angular.forEach ( data.defaultEngine, function ( str_value, str_key ) {
						tmpDefaultEngine = {
								id: 		str_value.id,
								name: 		str_value.name,
								hostName:	str_value.ipaddress,
								webport:	str_value.webport,
								rtmpport:	str_value.rtmpport,
								mapId:		str_value.mapId,
								isdefault:  1,
								period:		0
						};
					});

					// default building information
					angular.forEach ( data.defaultBuildings, function( str_value, str_key) {
						var buildingInfo = {
								id: str_value.id,
								name: str_value.name,
								mapId: str_value.mapId,
								posx: str_value.posx,
								posy: str_value.posy
								};
						buildingInfos.push( buildingInfo ); 
					} );
					tmpDefaultEngine.buildingInfos = buildingInfos;

					// default device information
					angular.forEach ( data.defaultCameras, function ( ptr_value, str_key ) {
						var deviceInfo = {
								id: ptr_value.id,
								videoinindex: ptr_value.videoInIndex,
								name: ptr_value.videoInName,
								videoinenable: ptr_value.videoInEnable,
								engineId: ptr_value.locationId,
								buildingId: ptr_value.buildingId,
								livetoken: ptr_value.videoLiveURL,
								vodtoken: ptr_value.videoSearchURL,
		                        posx: ptr_value.posx,
		                        posy: ptr_value.posy,
								connected: ptr_value.connectStatus,
								liveInfo: {
									ptz: 'N',
									audio:'N',
									motion:'N',
									recording: 'N'
									},
								ptzEnable: 0,
								audioEnable: 1,
								deleted: 0,
								changed: 0,
								isdefault: 1
						};
						deviceInfos.push ( deviceInfo );
					});
					tmpDefaultEngine.deviceInfos = deviceInfos;

					// Check if the default engine changed
					if ( angular.equals( tmpDefaultEngine, $scope.defaultEngine ) == false ) {
						$AllInfoService.setDefaultEngineChanged();
					} 
					// Merge default engine
					$scope.buildDefaultEngine ( tmpDefaultEngine );
					// Retrieve default engine - end
					
					// Retrieve all locations except default start 
					angular.forEach( data.locations, function ( str_value, str_key ) {
						var engineInfo = {
								id: 		str_value.id,
								name: 		str_value.name,
								hostName: 	str_value.ipaddress,
								webport: 	str_value.webport,
								rtmpport: 	str_value.rtmpport,
								mapId:		str_value.mapId,
								isdefault:  0,
								period:		0
						};
						
						engineInfos.push ( engineInfo ); 
					} );					
					
					// Check if the engine info changed
					if ( angular.equals ( engineInfos, $scope.engineInfos ) == false ) {
						$AllInfoService.setEngineChanged();
					}

					// Merge engine info
					$scope.buildServerInfo ( engineInfos );
					// Set Global variable
					$AllInfoService.setEngineInfos ( engineInfos );
					
					buildingInfos = [];
					angular.forEach ( data.buildings, function ( ptr_value, str_key ) {							
						var buildingInfo = {
								id: ptr_value.id,
								name: ptr_value.name,
								mapId: ptr_value.mapId,
								engineId: ptr_value.locationId,
								posx: ptr_value.posx,
								posy: ptr_value.posy
						};
						buildingInfos.push ( buildingInfo );
					});

					// Check if the building info changed
					if ( angular.equals ( buildingInfos, $scope.buildings ) == false ) {
						$AllInfoService.setBuildingChanged();
					}

					// Merge building info
					$scope.buildBuildingInfo ( buildingInfos );
					// Set global variable
					$AllInfoService.setBuildingInfos( buildingInfos );
					
					deviceInfos = [];
					angular.forEach ( data.cameras, function ( ptr_value, str_key ) {
						var deviceInfo = {
								id: ptr_value.id,
								videoinindex: ptr_value.videoInIndex,
								name: ptr_value.videoInName,
								videoinenable: ptr_value.videoInEnable,
								engineId: ptr_value.locationId,
								buildingId: ptr_value.buildingId,
								livetoken: ptr_value.videoLiveURL,
								vodtoken: ptr_value.videoSearchURL,
		                        posx: ptr_value.posx,
		                        posy: ptr_value.posy,
								connected: ptr_value.connectStatus,
								liveInfo: {
									ptz: 'N',
									audio:'N',
									motion:'N',
									recording: 'N'
									},
								ptzEnable: 0,
								audioEnable: 1,
								deleted: 0,
								changed: 0,
								isdefault: 0
						};
						deviceInfos.push ( deviceInfo );
					});

					// check if the device changed 
					if ( angular.equals ( deviceInfos, $scope.devices ) == false ) {
						$AllInfoService.setDeviceChanged();
					}

					// Merge device info
					$scope.buildDeviceInfo ( deviceInfos );
					
					// Set Global Variable
					$AllInfoService.setDeviceInfos ( deviceInfos );			

					// Refresh Left Tree Information 
					if ( $scope.bTreeInitialized ) {
						$AllInfoService.refreshEngineInfo();
						$AllInfoService.refreshBuildingInfo();
						$AllInfoService.refreshDeviceInfo();
					}	
				} ) . 
				error( function( data, status, headers, config ) {
				} );
        } );
    } );
}

// Checking location connection status thread - Added by KCH
function locationController($scope, $timeout, $http, $HashKeyCopier, $AllInfoService) {
	$scope.timerRunning 	= true;
    $scope.timerType 		= '';
	$scope.hostName			= '';
	$scope.webport			= '';
	$scope.locationId 		= '';
	$scope.serverIndex 		= '';
	
	$scope.$on( 'timer-tick', function (event, args) {
        $timeout( function () {		       
	        if ($scope.hostName != '' && $scope.webport != '' ) {
		        var str_url = '';
		        str_url = "http:\/\/" + $scope.hostName + ":" + $scope.webport + "/grcenter.allinfo.retrieveServerConnectStatus.nsf";
				$http.get( str_url ).
					success ( function ( data, status, headers, config) {
						/* connectstatus: 1
						liveinfo: Object
						connect: Array[2]
						event: Array[2]
						rmstate: Array[2]
						videoinindex: Array[2]
						videoinname: Array[2] */
						$AllInfoService.setConnectStatus( $scope.locationId * 1, data );
						$AllInfoService.refreshConnectStatus();
					}).
					error ( function( data, status, headers, config ) {
					} );
	        }
        });
	});
}
mainController.$inject = [ '$scope', '$timeout', '$http', 'HashKeyCopier', '$AllInfoService' ];
locationController.$inject = [ '$scope', '$timeout', '$http', 'HashKeyCopier', '$AllInfoService' ];
</script>