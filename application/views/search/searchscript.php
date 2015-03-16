<?php
/*
 *************************************************************************
 * @filename        : searchscript.php
 * @description    : Search javascript page
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.26   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
$this->load->view("includes/globaljs");
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/angular/angular.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/angular/timer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/angular/hashKeyCopier.js"></script>
<?php $this->load->view('includes/searchController');?>
<link rel="stylesheet" id="themeCSS" href="<?php echo base_url();?>assets/css/jqslider.css"> 
<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRangeSliderMouseTouch.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRangeSliderDraggable.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRangeSliderHandle.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRangeSliderBar.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRangeSliderLabel.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRangeSlider.js"></script>
<script src="<?php echo base_url();?>assets/js/jqslider/jQRuler.js"></script>
<script type="text/javascript">

var m_tree_devices_search = null;
var m_tree_devices_thumbnail = null;

var m_cur_selected_server = null;
var m_cur_selected_camera = null;

var m_org_server = null;
var m_org_camera = null;

var m_date_last_query = null;

var m_bool_period_thread = new Array();
var m_bool_running = true;

var m_cur_selected_page = 0;
var m_cur_selected_date = new Date(0);

var m_cur_recorded_dates = new Array();
var m_cur_recorded_times = new Array();

var m_cur_selected_timeline = null;

$(window).unload(function () {
	m_bool_running = false;
});

$(document).ready(function() {
	 g_LoadEngineInfo(SearchInitFunc);
	 
	 g_OnLoadPage();
	 
	 m_tree_devices_search = $("#deviceTree1");
	 m_tree_devices_thumbnail = $("#deviceTree2");
	 g_curSelectedDeviceTree = $("#deviceTree1");
	 $('#searchMainSpliter').jqxSplitter({theme: 'energyblue', width: '100%', height: '100%', panels: [{ size: 300 }] });
	 $('#tabContainer').jqxTabs({theme: 'energyblue', width: '100%', height: '100%', initTabContent: function (tab) {
     }});
     $("#pageDetailSearch").jqxSplitter( {theme: 'energyblue', resizable: false, orientation: 'horizontal', width: '100%', height: '100%', panels: [{ size: 400 }] });

     // Initialize variables
     InitializeVariables ();

	// Change tab event 
	$("#tabContainer").on("selected", function( event ) {
		switch ( event.args.item ) {
		case 0:				// detailed search
			$("#pageDetailSearch").show();
			$("#pageThumgnailSearch").hide();
			$("#pageLibraries").hide();
			g_curSelectedDeviceTree = $("#deviceTree1");
			break;
		case 1:				// Thumbnail search
			$("#pageDetailSearch").hide();
			$("#pageThumgnailSearch").html('');
			$("#pageLibraries").hide();
			$("#pageThumgnailSearch").show();
			g_curSelectedDeviceTree = $("#deviceTree2");
			break;
		case 2:	
			$("#pageDetailSearch").hide();
			$("#pageThumgnailSearch").hide();
			$("#pageLibraries").show();			// Libraries
			ReloadLibraries();
			break;
		}
	});

	var source =
    {
        datatype: "json",
        datafields: [
            { name: 'exportDate', type: 'string' },
            { name: 'deviceName', type: 'string' },
            { name: 'locationName', type: 'string' },
            { name: 'description', type: 'string' },
            { name: 'username', type: 'string' },
            { name: 'videoFile', type: 'string' },
            { name: 'downurl', type: 'string' }
        ],
        id: 'id',
        url: "<?php echo base_url();?>grsearch/get_libraries",
        root: 'result'
    };
    var dataAdapter = new $.jqx.dataAdapter(source);

	var linkrenderer = function( row, column, value ) {
		if (value.indexOf('#') != -1) {
            value = value.substring(0, value.indexOf('#'));
        }
        var html = "<button onclick=\"ClickDownloadBtn('" + value + "');\"> Download</button>";
        
        return html;
	}
	    
	// Initialize Libraries tab
    $("#pageLibraries").jqxGrid(
    {
    	width: '99%',
    	height: '99%',
        sortable: true,
        pageable: true,
        columnsresize: true,
        source: dataAdapter,
        theme: 'energyblue',
        columns: [
        	{ text: '<?php echo $this->lang->line('gsearch.date');?>', width: '10%', dataField: 'exportDate' },
            { text: '<?php echo $this->lang->line('gsearch.camera');?>', width: '10%', dataField: 'deviceName' },
            { text: '<?php echo $this->lang->line('gsearch.location');?>',width: '10%', dataField: 'locationName' },
            { text: '<?php echo $this->lang->line('gsearch.description');?>', width: '20%', dataField: 'description' },
            { text: '<?php echo $this->lang->line('gsearch.user');?>', width: '10%', dataField: 'username' },
            { text: '<?php echo $this->lang->line('gsearch.videofile');?>', width: '10%', dataField: 'videoFile'},
            { text: '<?php echo $this->lang->line('gsearch.download');?>', width: '10%', dataField: 'downurl', cellsrenderer: linkrenderer}
        ]
	});



	$('#descWnd').jqxWindow({
        theme: 'energyblue', maxHeight: 240, maxWidth: 280, minHeight: 30, minWidth: 250, height: 140, width: 270,
        resizable: false, isModal: true, modalOpacity: 0.3,
        okButton: $('#btnOk'), cancelButton: $('#btnCancel'),
        initContent: function () {
            $('#btnOk').jqxButton({ theme: 'energyblue', width: '65px' });
            $('#btnCancel').jqxButton({ theme: 'energyblue', width: '65px' });
            $("#txtDescription").jqxInput({theme: 'energyblue', placeHolder: "<?php echo $this->lang->line('gsearch.description');?>", height: 25, width: 230, minLength: 1});
            $('#btnOk').focus();
            $('#descWnd').jqxWindow('close');
        }
    });

	$('#btnOk').unbind('click').bind('click', function() {
		if ( m_cur_selected_timeline == null )
			return;
		var obj_date = $('#search_calendar').jqxCalendar('getDate');
		var str_date = g_GetDateStringFormat1( obj_date );
		var str_hours = m_cur_selected_timeline.attr( "time-hour" );	
		var obj_slider = m_cur_selected_timeline.find(".timeline-slider");
		var ptr_range = obj_slider.rangeSlider( 'values' );
		var str_description = $("#txtDescription").val();
		$("#txtDescription").val('');
		var str_startmins = ptr_range.min;
		var str_endmins = ptr_range.max;
		if ( str_startmins < 10 ) 
			str_startmins = "0" + str_startmins;
		if ( str_endmins < 10 ) 
			str_endmins = "0" + str_endmins;
		var str_startdatetime = str_date + " " + str_hours + ":" + str_startmins + ":" + "00";
		var str_enddatetime = str_date + " " + str_hours + ":" + str_endmins + ":" + "59";

		var ptr_utcstarttime = g_GetServerUTCTime( moment( str_startdatetime ).toDate(), m_cur_selected_server.period );
		var ptr_utcendtime = g_GetServerUTCTime( moment( str_enddatetime ).toDate(), m_cur_selected_server.period );

		var str_utcstarttime 	= DateToString( ptr_utcstarttime );
		var str_utcendtime 		= DateToString( ptr_utcendtime );
		var str_streamname 		= GenerateStreamId(14);
		$.ajax({
			url: "http://" + m_cur_selected_server.hostName + ":" + m_cur_selected_server.webport + "/grcenter.search.exportVideo.nsf",
			type: "post",
			data: {videoinindex: m_cur_selected_camera.videoinindex, startdate: str_utcstarttime, enddate: str_utcendtime, stream_name: str_streamname},
			success: function ( data ) {
				// Add new entry for export file 
				$.ajax({
					url: "<?php echo base_url();?>grsearch/add_newlibrary",
					type: "post",
					data: {filename: str_streamname+".flv", devicename: m_cur_selected_camera.name, locationname: m_cur_selected_server.name, deviceid: m_cur_selected_camera.id, description: str_description},
					success: function ( result ) {
						alert ('Exported successfully!');
					},
					error: function ( result ) {
					}
				});
			},
			error: function ( err ) {
				
			}
		});
		$("#descWnd").jqxWindow( "close" );
	});
});

function ReloadLibraries () {
	$("#pageLibraries").jqxGrid('clear');
	var source =
    {
        datatype: "json",
        datafields: [
            { name: 'exportDate', type: 'string' },
            { name: 'deviceName', type: 'string' },
            { name: 'locationName', type: 'string' },
            { name: 'description', type: 'string' },
            { name: 'username', type: 'string' },
            { name: 'videoFile', type: 'string' },
            { name: 'downurl', type: 'string' }
        ],
        id: 'id',
        url: "<?php echo base_url();?>grsearch/get_libraries",
        root: 'result'
    };
    var dataAdapter = new $.jqx.dataAdapter(source);

	var linkrenderer = function( row, column, value ) {
		if (value.indexOf('#') != -1) {
            value = value.substring(0, value.indexOf('#'));
        }
        var html = "<button style='margin: 5px;' onclick=\"ClickDownloadBtn('" + value + "');\"> Download</button>";
        
        return html;
	}
	    
	// Initialize Libraries tab
    $("#pageLibraries").jqxGrid(
    {
    	width: '99%',
    	height: '99%',
        sortable: true,
        pageable: true,
        columnsresize: true,
        source: dataAdapter,
        theme: 'energyblue',
        columns: [
        	{ text: '<?php echo $this->lang->line('gsearch.date');?>', width: '10%', dataField: 'exportDate' },
            { text: '<?php echo $this->lang->line('gsearch.camera');?>', width: '10%', dataField: 'deviceName' },
            { text: '<?php echo $this->lang->line('gsearch.location');?>',width: '10%', dataField: 'locationName' },
            { text: '<?php echo $this->lang->line('gsearch.description');?>', width: '20%', dataField: 'description' },
            { text: '<?php echo $this->lang->line('gsearch.user');?>', width: '10%', dataField: 'username' },
            { text: '<?php echo $this->lang->line('gsearch.videofile');?>', width: '10%', dataField: 'videoFile'},
            { text: '<?php echo $this->lang->line('gsearch.download');?>', width: '10%', dataField: 'downurl', cellsrenderer: linkrenderer}
        ]
	});
}

function ClickDownloadBtn( str_url ) {
	document.location.href = str_url;
}

function InitializeVariables () {
	for (var i = 0; i < 31; i ++) {
		m_cur_recorded_dates[i] = 0;		
	}

	for (var i = 0; i < 24; i ++) {
		m_cur_recorded_times[i] = 0;
	}

	var ptr_specialdates = new Array();

	$("#thumbnail_calendar").jqxCalendar( { specialDates: ptr_specialdates } );
	$("#thumbnail_calendar").jqxCalendar('refreshControl');
}

function SearchInitFunc() {
	 $('#tab1').jqxSplitter({ theme: 'energyblue', height: '100%', width: '100%',orientation: 'horizontal',  panels: [{ size: "60%", collapsible: false }, { min: 250 }] });
     $("#search_calendar").jqxCalendar({ theme: "energyblue", enableTooltips: false, width: "100%", height: "250"});
     m_tree_devices_search.jqxTree({ theme: "energyblue", width: "100%", height: "100%" });
     $('#tab2').jqxSplitter({ theme: 'energyblue', orientation: 'horizontal', height: '100%', width: '100%', panels: [{ size: "60%", collapsible: false }, { min: 250 }], resizable: false });
     $("#thumbnail_calendar").jqxCalendar({ theme: "energyblue", enableTooltips: false, showOtherMonthDays: false, width: "100%", height: "250"});
     m_tree_devices_thumbnail.jqxTree({theme: "energyblue", width: "100%", height: "100%"});
     
	 g_GenerateDevicesTree( m_tree_devices_search, true ); 
     InitializeSearchTree();

     g_GenerateDevicesTree( m_tree_devices_thumbnail, true); 
     InitializeThumbnailTree();

    // g_CheckConnectStatus (Callback_ConnectStatus);
    // RetrieveTimePeriodThread();

    // Load all polling thread
	var obj_maincontroller = $("[ng-controller='mainController']").scope();
 	obj_maincontroller.$apply(function() {
 		obj_maincontroller.timerRunning = true;		
 	});
 
 	var obj_periodcontroller = $("[ng-controller='mainController']").scope();
}

function InitializeThumbnailTree() {
	$("#thumbnail_calendar").unbind('change').on('change', function (event) {
		var obj_time = $("#thumbnail_calendar").jqxCalendar( "getDate" );
		
		var int_day = obj_time.getDate();
	    if ( m_cur_recorded_dates[int_day - 1] == 1) {
			m_cur_selected_date = obj_time;
			LoadDailyThumbnails ( m_cur_selected_server, m_cur_selected_camera );   
			
	    } 
	});

	m_tree_devices_thumbnail.unbind("click").on("click", function ( event ) {
		m_cur_selected_page = 1;
	});

	m_tree_devices_thumbnail.unbind( "select" ).on( "select", function( event ) {
		 var oTarget = event.args.element;
		 var str_id = oTarget.id.split(":")[1];

		 if (oTarget.id.indexOf("Camera") < 0 )
			 return;
		 var ptr_camera = g_GetDeviceInfoById( str_id );
		 if ( ptr_camera == null ) {
			 return;
		 }  

		 var ptr_serverinfo = g_GetEngineInfoById( ptr_camera.engineId );
		 if (ptr_serverinfo == null ) {
			 return;
		 }

		 if ( ptr_serverinfo.connected == "0" ) {
			 alert ( "Recording server is not working now ... ");
			 return;
		 }


		 // if ( m_cur_selected_camera == null || m_cur_selected_camera.id != ptr_camera.id ) {
			 m_cur_selected_camera = ptr_camera;
			 m_cur_selected_server = ptr_serverinfo;
		 // }

		 LoadDailyThumbnails ( ptr_serverinfo, ptr_camera );
	 });
}

function LoadDailyThumbnails ( ptr_serverinfo, ptr_camera ) {

	InitializeVariables ();
	
	var str_manage_url = "http://" + ptr_serverinfo.hostName + ":9090/" + ptr_camera.videoinindex;
	var obj_date = $('#thumbnail_calendar').jqxCalendar('getDate');
	if ( m_cur_selected_date != obj_date )
		m_cur_selected_date = obj_date;
	
	var str_first_date = obj_date.getUTCFullYear();
	var int_year = obj_date.getUTCFullYear();
	var int_month = obj_date.getUTCMonth() + 1;
	if ( int_month < 10) 
		str_first_date += "-" + "0" + int_month + "-";
	else
		str_first_date += "-" + int_month + "-";
	var str_cur_date = str_first_date;
	var int_date = obj_date.getUTCDate();
	if (int_date < 10 ) {
		str_cur_date += "0" + int_date;
	} else {
		str_cur_date += int_date + "";
	}
	str_first_date += "01";
	 
	var str_videoinindex = ptr_camera.videoinindex;	
	var str_diffseconds = ptr_serverinfo.period;
	var obj_cur_date = new Date();
	
	var str_url = "http://" + ptr_serverinfo.hostName + ":" + ptr_serverinfo.webport + "/grcenter.search.retrieveRecordDateCmd.nsf";

	$.ajax({
		url: str_url,
		data: { videoInIndex: str_videoinindex, searchDate: str_first_date, periodTime: str_diffseconds, selecteddate: str_cur_date},
		success: function (data) {
			var result = data.result;
			var oStartdate = result.startdate;
			var oEnddate = result.stopdate;
			// Get current recorded hours on current selected time
			var ptr_available_times = data.cur_times;
// 			if (ptr_available_times.recordedtime) {
// 				for (var i = 0; i < ptr_available_times.recordedtime.length; i ++) {
// 					if (ptr_available_times.recordeddate[i] * 1 == int_date * 1) {
// 						m_cur_recorded_times[ptr_available_times.recordedtime[i] * 1] = 1;
// 					} 
// 				}
// 			}

			// Get recorded date
			if (oStartdate)
			{
				for (var i = 0; i < oStartdate.length; i ++) {
					var dateStart = oStartdate[i];
					var dateEnd = oEnddate[i];
					var date_start = g_GetLocalTimeFromUTC( moment( dateStart ).toDate(), str_diffseconds );
					var date_end = g_GetLocalTimeFromUTC( moment ( dateEnd ).toDate(), str_diffseconds );
					var date = Number(date_end.getDate());
					
					while(date - Number(date_start.getDate()) >= 0) {
					
						if(date_start.getFullYear() == int_year) {
							if((date_start.getMonth() + 1) == int_month) {
								m_cur_recorded_dates[date - 1] = 1;
							}
						}
						date --;
					}
					
				}
			}
			// Set Recorded date color with RED
			for(var i = 0; i < 31; i ++) {
				if (m_cur_recorded_dates[i] == 1) {
					int_month = int_month*1 < 10 ? "0" + int_month*1 : int_month;
					var int_secs = (i + 1)<10?"0"+(i*1+1):(i+1);
					var str_date = int_year + "-" + int_month + "-" + int_secs+" 00:00:00";
					var tmp_date = moment(str_date).toDate();		
					$('#thumbnail_calendar').jqxCalendar('addSpecialDate', tmp_date, '', 'Recorded');
					delete tmp_date;
				}
			}

			// Load Search Channels
			LoadSearchChannels( 0 );
		}	});
}

function LoadSearchChannels ( int_type) {
	if ( m_cur_selected_server == null ) return;
	if ( m_cur_selected_camera == null ) return; 
	if ( int_type == 0 ) // Daily view
	{
		// /videoinindex/thumbnail.cgi?startdate=yyyymmddhhmmss&enddate=yymmddhhmmss&type=0
		var str_thumbnail_url = "http://" + m_cur_selected_server.hostName + ":9090/" + m_cur_selected_camera.videoinindex +"/thumbnail.cgi?startdate=";
		var str_selected_date = g_GetDateStringFormat1( m_cur_selected_date );
		
		g_jqRemoveChildObject( $("#pageThumgnailSearch" ) );
		
		for ( var i = 0; i < 24; i ++) {
			var str_hours = i < 10? "0" + i:i;
			var tmptime_start = moment( str_selected_date + " " + str_hours + ":00:00" ).toDate();
			var tmptime_end = moment( str_selected_date + " " + str_hours + ":59:59" ).toDate();
			var utc_time_start = g_GetServerUTCTime(tmptime_start, 0);// , 0 );
			var utc_time_end = g_GetServerUTCTime(tmptime_end, 0);//g_GetServerUTCTime( tmptime_end, 0 );
			
			var str_starttime = DateToString( utc_time_start );
			var str_endtime = DateToString( utc_time_end );
			
			var str_target_url = str_thumbnail_url + str_starttime+ "&enddate="+str_endtime + "&type=1";
			var str_img = "<div style='margin: 5px;display: none; border: 1px solid gray' hours='"+str_hours+"' onclick='ViewHourlyDetails(this);'><div style='text-align: center'>" + str_selected_date + " " + str_hours + ":00:00" + "</div><div><img src='" + str_target_url + "' style='width: 240px;' onload='this.parentElement.parentElement.style.display=\"inline-block\"' onerror='ErrorLoadThumbnail(this)'></div></div>";
			$(str_img).appendTo($("#pageThumgnailSearch"));
		} 
	} 
	else	// Hourly view
	{
		
	}
}

function ViewHourlyDetails( obj ) {
	var str_hours = $(obj).attr("hours");
	if ( m_cur_selected_server == null ) return;
	if ( m_cur_selected_camera == null ) return; 
	var str_thumbnail_url = "http://" + m_cur_selected_server.hostName + ":9090/" + m_cur_selected_camera.videoinindex +"/thumbnail.cgi?startdate=";
	var utc_time = g_GetServerUTCTime( m_cur_selected_date, m_cur_selected_server.period );
	var str_utctime = DateToString (utc_time);
	var str_selected_date = g_GetDateStringFormat1( m_cur_selected_date );
	g_jqRemoveChildObject( $("#pageThumgnailSearch" ) );
	for ( var i = 0; i < 60; i ++) {
		var str_minutes = i < 10 ? "0" + i : i;
		var tmptime_start = moment( str_selected_date + " " + str_hours + ":" + str_minutes + ":00" ).toDate();
		var tmptime_end = moment( str_selected_date + " " + str_hours + ":" + str_minutes + ":59" ).toDate();
		var utc_time_start = g_GetServerUTCTime( tmptime_start, m_cur_selected_server.period );
		var utc_time_end = g_GetServerUTCTime( tmptime_end, m_cur_selected_server.period );
		var str_starttime = DateToString ( utc_time_start );
		var str_endtime = DateToString( utc_time_end );
		var str_target_url = str_thumbnail_url + str_starttime+ "&enddate="+str_endtime + "&type=1";
		var str_img = "<div style='margin: 5px;display: none; border: 1px solid gray' minutes='"+str_starttime+"' onclick='ViewMinutesDetails(this);'><div style='text-align: center;'>" + g_GetDateStringFormat2(tmptime_start) + "</div><div><img src='" + str_target_url + "' style='width: 240px;' onload='this.parentElement.parentElement.style.display=\"inline-block\"' onerror='ErrorLoadThumbnail(this)'></div></div>";
		$(str_img).appendTo($("#pageThumgnailSearch"));
	}
	
}

function ViewMinutesDetails( obj ) {
	var str_starttime = $(obj).attr("minutes");
	var str_endtime = str_starttime.substring(0, 12) + "59";
	if (m_cur_selected_server == null ) return;
	if ( m_cur_selected_camera == null ) return;
	var str_url = "http://" + m_cur_selected_server.hostName + ":9090/"+m_cur_selected_camera.videoinindex+"/playback.cgi";
	var str_streamname = GenerateStreamId(8);
	g_OpenExternalWindow("<?php base_url();?>common/globalinfo/searchplay?str_url="+str_url +"&starttime="+str_starttime + "&endtime="+str_endtime, "test");
	/* $.ajax({
		url: str_url,
		type: "POST",
		data: { videoinindex: m_cur_selected_camera.videoinindex, startdate: str_starttime, enddate: str_endtime, stream_name: str_streamname },
		success: function () {
			// var str_rtmp = "rtmp://" + m_cur_selected_server.hostName + ":" + m_cur_selected_server.rtmpport + "/live/search_" + str_streamname;
			 
		},
		error: function (data) {
		}
	}); */
}

function GenerateStreamId(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
    	var randomPoz = Math.floor(Math.random() * charSet.length);
    	randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}

function ErrorLoadThumbnail( obj ) {
	$(obj).parent().parent().remove();
}

function InitializeSearchTree() {
	m_tree_devices_search.unbind( "select" ).on( "select", function ( event ) {
		var oTarget = event.args.element;
		 var str_id = oTarget.id.split(":")[1];

		 if (oTarget.id.indexOf("Camera") < 0 )
			 return;
		 var ptr_camera = g_GetDeviceInfoById( str_id );
		 if ( ptr_camera == null ) {
			 return;
		 }  
		 var ptr_serverinfo = g_GetEngineInfoById( ptr_camera.engineId );
		 if (ptr_serverinfo == null ) {
			 return;
		 }

		 if ( ptr_serverinfo.connected == "0" ) {
			 alert ( "Recording server is not working now ... ");
			 return;
		 }


		 // if ( m_cur_selected_camera == null || m_cur_selected_camera.id != ptr_camera.id ) {
			 m_cur_selected_camera = ptr_camera;
			 m_cur_selected_server = ptr_serverinfo;
		 // }
		LoadSearchDetails ( ptr_serverinfo, ptr_camera );
		
	});

	m_tree_devices_search.unbind("click").on("click", function ( event ) {
		m_cur_selected_page = 0;
	});
	$("#search_calendar").unbind('change').on('change', function (event) {
		var obj_time = $("#search_calendar").jqxCalendar( "getDate" );
		var int_day = obj_time.getDate();
	    if ( m_cur_recorded_dates[int_day - 1] == 1) {
			m_cur_selected_date = obj_time;
			LoadGapInfoTable ( m_cur_selected_server, m_cur_selected_camera );   
			
	    } 
	});
}

function InitializeDetailsVariables() {
	
}

function LoadSearchDetails( ptr_serverinfo, ptr_camera ) {
	var str_manage_url = "http://" + ptr_serverinfo.hostName + ":9090/" + ptr_camera.videoinindex;
	var obj_date = $('#search_calendar').jqxCalendar('getDate');
	if ( m_cur_selected_date != obj_date )
		m_cur_selected_date = obj_date;
	var str_videoinindex = ptr_camera.videoinindex;	
	var str_diffseconds = ptr_serverinfo.period;

	var int_year = obj_date.getFullYear();
	var int_month = obj_date.getMonth() + 1;
	var int_date = obj_date.getDate();
	if ( int_month < 10) 
		int_month = "0" + int_month;
	var str_startdatetime = int_year + "-" + int_month + "-01 00:00:00";
	var date_startdatetime = moment( str_startdatetime ).toDate();
	var server_startdatetime = g_GetServerUTCTime( date_startdatetime, str_diffseconds);
	var str_first_date = g_GetDateStringFormat2( server_startdatetime );
	var str_cur_date = g_GetDateStringFormat1( m_cur_selected_date );
	str_cur_date = str_cur_date + " 00:00:00";
	var date_cur_date = moment( str_cur_date ).toDate();
	var cur_utc_time = g_GetServerUTCTime( date_cur_date, str_diffseconds );
	str_cur_date = g_GetDateStringFormat2 ( cur_utc_time );
	
	// str_first_date += "01";
	 
	
	var obj_cur_date = new Date();
	
	var str_url = "http://" + ptr_serverinfo.hostName + ":" + ptr_serverinfo.webport + "/grcenter.search.retrieveRecordDateCmd.nsf";

	var ptr_specialdates = new Array();

	$("#search_calendar").jqxCalendar( { specialDates: ptr_specialdates } );
	$("#search_calendar").jqxCalendar('refreshControl');
	$.ajax({
		url: str_url,
		data: { videoInIndex: str_videoinindex, searchDate: str_first_date, periodTime: 0, selecteddate: str_cur_date},
		success: function (data) {
			var result = data.result;
			var oStartdate = result.startdate;
			var oEnddate = result.stopdate;
			// Get current recorded hours on current selected time
			var ptr_available_times = data.cur_times;

			// Get recorded date
			if (oStartdate)
			{
				for (var i = 0; i < oStartdate.length; i ++) {
					var dateStart = oStartdate[i];
					var dateEnd = oEnddate[i];
					var date_start = g_GetLocalTimeFromUTC( moment( dateStart ).toDate(), str_diffseconds );
					var date_end = g_GetLocalTimeFromUTC( moment ( dateEnd ).toDate(), str_diffseconds );
					var date = Number(date_end.getDate());
					
					while(date - Number(date_start.getDate()) >= 0) {
					
						if(date_start.getFullYear() == int_year) {
							if((date_start.getMonth() + 1) == int_month) {
								m_cur_recorded_dates[date - 1] = 1;
							}
						}
						date --;
					}
					
				}
			}
			// Set Recorded date color with RED
			for(var i = 0; i < 31; i ++) {
				if (m_cur_recorded_dates[i] == 1) {
					int_month = int_month*1 < 10 ? "0" + int_month*1 : int_month;
					var int_secs = (i*1 + 1)<10?"0"+(i*1+1):(i+1);
					var str_date = int_year + "-" + int_month + "-" + int_secs+" 00:00:00";
					var tmp_date = moment(str_date).toDate();
					$('#search_calendar').jqxCalendar('addSpecialDate', tmp_date, '', 'Recorded');
					delete tmp_date;
				}
			}

			// Load Search Channels
			LoadGapInfoTable(ptr_serverinfo, ptr_camera);
		}
	});
}

function LoadGapInfoTable(ptr_serverinfo, ptr_camera) {
	var str_manage_url = "http://" + ptr_serverinfo.hostName + ":9090/" + ptr_camera.videoinindex;
	var obj_date = $('#search_calendar').jqxCalendar('getDate');
	if ( m_cur_selected_date != obj_date )
		m_cur_selected_date = obj_date;
	
	var str_videoinindex = ptr_camera.videoinindex;	
	var str_diffseconds = ptr_serverinfo.period;
	var obj_cur_date = new Date();
	var tmp_date_string = g_GetDateStringFormat1( obj_date );
	var obj_first_date = moment( tmp_date_string + " 00:00:00").toDate();
	var obj_end_date = moment( tmp_date_string + " 23:59:59").toDate();
	
	var obj_utc_sdate = g_GetServerUTCTime( obj_first_date, str_diffseconds );
	var obj_utc_edate = g_GetServerUTCTime( obj_end_date, str_diffseconds );
	
	var str_first_date = DateToString( obj_utc_sdate );
	var str_end_date = DateToString( obj_utc_edate );
	var str_first_time = g_GetTimeString(obj_utc_sdate);
	var str_end_time = g_GetTimeString(obj_utc_edate);
	
	var str_url = "http://" + ptr_serverinfo.hostName + ":" + ptr_serverinfo.webport + "/grcenter.search.retrieveGapInfo.nsf";

	$.ajax({
		url: str_url,
		data: { videoInIndex: str_videoinindex, startDate: str_first_date, endDate: str_end_date,startTime: str_first_time, endTime:str_end_time },
		success: function (data) {
			var result = data.result;
			if (!result.recmode) return;
			var obj_recording_gap = new Array();
			for ( var i = 0; i < result.recmode.length; i ++) {
				obj_recording_gap.push(
				{
					recmode: result.recmode[i], 
					start: (g_GetLocalTimeFromUTC( moment( result.startdate[i]).toDate(), str_diffseconds ) ), 
					end: (g_GetLocalTimeFromUTC( moment( result.stopdate[i]).toDate(), str_diffseconds ) )
				});
			}

			DrawGapInfoTable( obj_recording_gap );
		}
	});
}

function GetGapInfo( obj_gaps, int_start, int_end ) {
	for ( var i = 0; i < obj_gaps.length; i ++) {
		var time_start = obj_gaps[i].start.getTime();
		var time_end = obj_gaps[i].end.getTime();
		if ( (int_start >= time_start && int_start <= time_end) || (int_end >= time_start && int_end <= time_end))
			return obj_gaps[i]; 
	}
	return null;
}

function DrawGapInfoTable ( obj_gap ) {
	var str_tmp_starttime, str_tmp_endtime;
	var obj_date = $('#search_calendar').jqxCalendar('getDate');
	var str_date = g_GetDateStringFormat1( obj_date );
	var ptr_gapInfo = new Array();
	var obj_cur_time = new Date();
	var dao_current = false;
	for ( var i = 0; i <= 23; i ++ ) {
		var hour_gap = new Array();
		for ( j = 0; j < 60; j ++) {
			var str_hour = i*1 < 10 ? "0" + i:i;
			var str_minutes = j*1 < 10 ? "0" + j:j;
			var str_endmins = (j + 1) * 1 < 10? "0" + (j* 1 + 1): ( j + 1 );
			str_tmp_starttime = str_date + " " + str_hour + ":" + str_minutes + ":59";
			str_tmp_endtime = str_date + " " + str_hour + ":" + str_endmins + ":00";
			var ptr_start = moment( str_tmp_starttime ).toDate();
			if ( ptr_start.getTime() > obj_cur_time.getTime() ) {
				dao_current = true;
			}
			var ptr_end = moment( str_tmp_endtime ).toDate();
			var aGap = GetGapInfo ( obj_gap, ptr_start.getTime(), ptr_end.getTime());
			print_log ( aGap );
			delete ptr_start;
			delete ptr_end;
			if ( aGap == null ) { 
				hour_gap.push( '0' );
			} else {
				hour_gap.push( aGap.recmode );
			}
		} 
		ptr_gapInfo.push ( hour_gap );		
		delete hour_gap;
		if ( dao_current == true)
			break;
	}
	delete obj_cur_time;
	$("#searchDetailTable").find(".timeline-slider").remove();
	var oTimelineContainer = $("#searchDetailTable").find(".timeline"); 
	for ( var i = 0; i < ptr_gapInfo.length; i ++) {
		oTimelineContainer.eq(i).append('<div class="timeline-slider"></div>');
		$(document).find(".timeline-slider").eq(i).rangeSlider({
			bounds: {min: 0, max: 59},
			step: 1,
			valueLabels:'hide',
			arrows: false,
			  
			scales: [
			// Primary scale
			{
				first: function(val){ return val; },
			    next: function(val){ return val + 5; },
			    stop: function(val){ return false; },
			    label: function(val){ return val; },
				isgap: false
			},
			// Secondary scale
			{
				first: function(val){ return val; },
			    next: function(val){
			      
			    return val + 1;
			},
			stop: function(val){ return false; },
			label: function(){ return ''; },
			format: function(tickContainer, tickStart, tickEnd){ 
				// tickContainer.addClass();
			},
			isgap: true,
			formats: ['norecording', 'recorded', 'motion'],
			gapinfo: ptr_gapInfo[i]}]
		});
	}
}

function OnViewDetail( obj_button ) {
	if ( m_cur_selected_server == null || m_cur_selected_camera == null ) 
		return;
	
	var obj_tr = $(obj_button).parent().parent();
	var obj_timeline = obj_tr.find(".timeline");
	var obj_date = $('#search_calendar').jqxCalendar('getDate');
	var str_date = g_GetDateStringFormat1( obj_date );
	var str_hours = obj_timeline.attr( "time-hour" );	
	var obj_slider = obj_timeline.find( ".timeline-slider" );
	var ptr_range = obj_slider.rangeSlider( 'values' );
	var str_startmins = ptr_range.min;
	if (str_startmins * 1 < 10) 
		str_startmins = "0" + str_startmins;
	var str_endmins = ptr_range.max;
	if ( str_endmins * 1 < 10 ) 
		str_endmins = "0" + str_endmins;
	var str_startdatetime = str_date + " " + str_hours + ":" + str_startmins + ":" + "00";
	var str_enddatetime = str_date + " " + str_hours + ":" + str_endmins + ":" + "59";

	var ptr_utcstarttime = g_GetServerUTCTime( moment( str_startdatetime ).toDate(), m_cur_selected_server.period );
	var ptr_utcendtime = g_GetServerUTCTime( moment( str_enddatetime ).toDate(), m_cur_selected_server.period );

	var str_utcstarttime = DateToString( ptr_utcstarttime );
	var str_utcendtime = DateToString( ptr_utcendtime );

	
	$("#detailSearchPlayer").find("img").remove();
	$("#detailSearchPlayer").append( "<img src='http://" + m_cur_selected_server.hostName + ":9090/" + m_cur_selected_camera.videoinindex + "/playback.cgi?starttime="+str_utcstarttime + "&endtime="+str_utcendtime + "' style='width:350px;display:none' onload='this.style.display=\"inline-block\";'>");  
}

function OnDoExport( obj_button ) {
	var obj_tr = $(obj_button).parent().parent();
	m_cur_selected_timeline = obj_tr.find(".timeline");
	$('#descWnd').jqxWindow('open');
	
}
</script>