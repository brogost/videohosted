<?php
/********************************************************************************************
 * Page				: 
 * Author			: KCH
 * ------------------------------------------------------------------------------------------
 * File Name		: file_name
 * Description		: 
 * Date				: Sep 16, 2014 7:45:15 PM
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
	// Time periods between nvr servers and managing server
	var time_periods		= [];

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
		setTimePeriod: function ( int_serverId, int_period ) {
			var bFind = false;
			for ( var i = 0; i < time_periods.length; i ++ ) {
				if ( time_periods[i].serverId == int_serverId ) {					
					bFind = true;
					time_periods[i].timeperiod = int_period;
				}
			} 

			if ( bFind == false ) {
				time_periods.push ( { serverId: int_serverId, timeperiod: int_period } );
			}
		},
		getTimePeriod: function ( int_serverId ) {
		},
		refreshTimePeriod: function () {
			var tmp_periods = time_periods;
			$("[id^='Location_']").each ( function () {
				var obj_location = $(this);
				var str_locationId = obj_location.attr("id").split("_")[1];
				var int_period = 0;
				for (var i = 0; i < tmp_periods.length; i ++) {
					if ( str_locationId * 1 == tmp_periods[i].serverId * 1) {
						int_period = tmp_periods[i].timeperiod * 1;
						break;
					}
				}				
				var old_timeperiod = obj_location.attr("timeperiod");
				if ( old_timeperiod * 1 != int_period * 1 ) {
					obj_location.attr( "timeperiod", int_period );
				}
			});
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
		refreshEngineInfo: function () {
			if ( bEngineChanged == false ) {
				return true;
			} else {
				g_EngineInfos = engines;
				$("[id^='deviceTree']").each (function () {
					var obj_devicetree = $(this);
					for (var i = 0; i < engines.length; i ++) {
						var bool_updated = false;
						var jqobj_location = $("[id='Location_" + engines[i].id + "']");
						var jqobj_root = $("");
						if ( jqobj_location.length == 0) { 		// must be added
							var parent_item = obj_devicetree.jqxTree( 'getItems' )[0];
							obj_devicetree.jqxTree( 'addTo', {label: engines[i].name, id: "Location_" + engines[i].id, icon: ICON_DEVICES_LOCATION}, null );						 
							$( "[id='Location_" + engines[i].id + "']" ).attr('processed', '1');
							AttachDragDropEventListener();
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
					obj_devicetree.jqxTree( 'refresh' );
				});				
			}
			bEngineChanged = false;
		},
		refreshBuildingInfo: function () {
			if ( bBuildingChanged == false ) {
				return true;
			} else {
				g_BuildingInfos = buildings;
				$("[id^='deviceTree']").each( function () {
					var obj_devicetree = $(this);
					for (var i = 0; i < buildings.length; i ++) {
						var bool_updated = false;
						var jqobj_building = $( "[id='Building_" + buildings[i].engineId + ":" + buildings[i].id + "']" );
						var jqobj_location = $( "[id='Location_" + buildings[i].engineId + "']" );
						if ( jqobj_building.length == 0) { 		// must be added
							 var parent_element = jqobj_location[0];
							 var parent_item = obj_devicetree.jqxTree( 'getItem', parent_element );
							 obj_devicetree.jqxTree( 'addTo', {label: buildings[i].name, id: "Building_" + buildings[i].engineId + ":" + buildings[i].id, icon: ICON_DEVICES_BUILDING}, parent_item );						 
							 $( "[id='Building_" + buildings[i].engineId + ":" + buildings[i].id + "']" ).attr('processed', '1');
							 AttachDragDropEventListener();
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
					
					obj_devicetree.jqxTree( 'refresh' );
				});
			}
			bBuildingChanged = false;
		},
		refreshDeviceInfo: function() {
			if ( bDeviceChanged == false ) {
				return true;
			} else {
				g_DeviceInfos = devices;
				$("[id^='deviceTree']").each( function () {
					var obj_devicetree = $( this );
					for (var i = 0; i < devices.length; i ++) {
						var bool_updated = false;
						var jqobj_device = $( "[id='Camera_" + devices[i].engineId + ":" + devices[i].id + "']" );
						var jqobj_building = $("[id='Building_" + devices[i].engineId + ":" + devices[i].buildingId + "']");
						var jqobj_location = $( "[id='Location_" + devices[i].engineId + "']");
						if ( jqobj_device.length == 0) { 		// must be added
							 var parent_element = jqobj_building[0];
							 var parent_item = obj_devicetree.jqxTree( 'getItem', parent_element );
							 obj_devicetree.jqxTree( 'addTo', {label: devices[i].name, id: "Camera_" + devices[i].engineId + ":" + devices[i].id, icon: ICON_DEVICES_CAMERA}, parent_item );
							 AttachDragDropEventListener();
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
						obj_devicetree.jqxTree( 'refresh' );
						
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
				});
			}
			bDeviceChanged = false;
		},
		refreshConnectStatus: function () {
			if (engines == null) return;
			$("[id^='deviceTree']").each( function () {
				var obj_devicetree = $( this );
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
						jqobj_location.attr("connected", "0");
										
						$("[id^='Camera_" + tmp_engines[i].id + "']").each(function(){
							var element = $(this)[0];
							var tree_item = obj_devicetree.jqxTree( 'getItem', element );
							if ( tree_item.icon != ICON_DEVICES_CAMERA_DIS )
								obj_devicetree.jqxTree( 'updateItem', element, {label: tree_item.label, icon: ICON_DEVICES_CAMERA_DIS} );
							$(this).attr( "connected", "0" );
						});
						continue;
					} else if ( con_status.liveInfo.connectstatus * 1 == 1 && str_connect * 1	!= 1) {
						var element = jqobj_location[0];
						var tree_item = obj_devicetree.jqxTree( 'getItem', element );
						obj_devicetree.jqxTree( 'updateItem', element, {label: tree_item.label, icon: ICON_DEVICES_LOCATION} );
						jqobj_location.attr( "connected", "1" );					 
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
								var cur_connect = jqobj_device.attr("connected");
								// if ( cur_connect * 1 != tmp_liveInfo.connect[j] ) {
									jqobj_device.attr("connected", tmp_liveInfo.connect[j]);
									var delement = jqobj_device[0];
									// var dtree_item = obj_devicetree.jqxTree( 'getItem', delement );
									var str_img = jqobj_device.find("img").eq(0).attr("src"); 
									if (tmp_liveInfo.connect[j] * 1 == 0 && str_img != ICON_DEVICES_CAMERA_DIS) { // dtree_item.icon != ICON_DEVICES_CAMERA_DIS
										obj_devicetree.jqxTree( 'updateItem', delement, {label: dtree_item.label, icon: ICON_DEVICES_CAMERA_DIS} );
										// jqobj_device.find("img").eq(0).attr("src", ICON_DEVICES_CAMERA_DIS);
									} else if (tmp_liveInfo.connect[j] * 1 == 1 && str_img != ICON_DEVICES_CAMERA){
										obj_devicetree.jqxTree( 'updateItem', delement, {label: dtree_item.label, icon: ICON_DEVICES_CAMERA} );
										// jqobj_device.find("img").eq(0).attr("src", ICON_DEVICES_CAMERA);
									}
								// }
							}
						}
					}
				}
				obj_devicetree.jqxTree( 'refresh' );
			});
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
			$http.get( $scope.hostUrl ) . 
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

function timeperiodController ($scope, $timeout, $http, $HashKeyCopier, $AllInfoService) {
	$scope.timerRunning 	= true;
    $scope.timerType 		= '';
	$scope.hostName			= '';
	$scope.webport			= '';
	$scope.locationId 		= '';
	$scope.serverIndex 		= '';
	
	$scope.$on( 'timer-tick', function (event, args) {
        $timeout( function () {		       
	        if ($scope.hostName != '' && $scope.webport != '' ) {
		        var str_url = "http:\/\/" + $scope.hostName + ":" + $scope.webport + "/grcenter.search.retrieveTimePeriod.nsf";
		        var obj_date = new Date();
		        var int_year = obj_date.getUTCFullYear();
		        var int_month = obj_date.getUTCMonth();
		        
		        if(int_month.toString().length == 1)
		        {
		            int_month = "0" + int_month.toString();
		        }
		        
		        var int_date = obj_date.getUTCDate();
		           
		        if(int_date.toString().length == 1)
		            int_date = "0" + int_date.toString();
		        
		        var int_hours = obj_date.getUTCHours();
		        
		        if(int_hours.toString().length == 1)
		            int_hours = "0" + int_hours;
		        
		        var int_minutes = obj_date.getUTCMinutes();
		    	if( int_minutes.toString().length == 1 )
		        	int_minutes = "0" + int_minutes;

		        var int_seconds = obj_date.getUTCSeconds();
		        if(int_seconds.toString().length == 1)
		            int_seconds = "0" + int_seconds;
		        $.ajax({
			        url: str_url,
			        type: "post",
			        data: { lid: 0, year: int_year, month: int_month, date: int_date, hours: int_hours, minutes: int_minutes, seconds: int_seconds},
			        success: function ( result ) {
			        	$AllInfoService.setTimePeriod( $scope.locationId * 1, result.result.periodTime );
			        	$AllInfoService.refreshTimePeriod( );
			        },
			        error: function () {
			        	$AllInfoService.setTimePeriod( $scope.locationId * 1, 0 );
			        	$AllInfoService.refreshTimePeriod( );
			        }
		        });
	        }
        });
	});
}
mainController.$inject = [ '$scope', '$timeout', '$http', 'HashKeyCopier', '$AllInfoService' ];
locationController.$inject = [ '$scope', '$timeout', '$http', 'HashKeyCopier', '$AllInfoService' ];
timeperiodController.$inject = [ '$scope', '$timeout', '$http', 'HashKeyCopier', '$AllInfoService' ];
</script>