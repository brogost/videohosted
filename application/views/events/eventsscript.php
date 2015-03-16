<?php 
/*
 *************************************************************************
 * @filename        : eventsscript.php
 * @description    : Events page javascript functions
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.09.20   Jimm         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */ 
$this->load->view("includes/globaljs");
?>
<script type="text/javascript">
var m_cur_selected_camera = null;
var m_cur_selected_server = null;
var m_cur_maintype = -1;
var m_cur_subtype = -1;
var m_cur_mindate = null;
var m_cur_maxdate = null;
var EVENT_TYPES = [
		'', 
		'System Event',
		'System Event',
		'Storage Event',
		'Motion Event',
		'Video Loss Event',
		'Video Loss Event'];
var eventtyperender = function(a,b,value){
    var html = "<span style='padding: 5px;'>";
    html += EVENT_TYPES[value * 1];
    html += "</span>";
    return html;
};
$(document).ready(function () {
	g_LoadEngineInfo(EventInitFunc);
	 
	g_OnLoadPage();
	g_curSelectedDeviceTree = $("#deviceTree");
	//jqx splitter function
	$('#leftSplitter').jqxSplitter({ theme: 'energyblue', width: "100%", height: "100%", panels: [{ size: 250 }] });
	$('#rightSplitter').jqxSplitter({ theme: 'energyblue', width: "100%", height: "100%", panels: [{ size: "76%", collapsible: false }] });

	// Range Calendar
	$("#docking1").jqxDocking({theme: 'energyblue', orientation: 'horizontal', width: 250, mode: 'default'});
	$('#docking1').jqxDocking('hideAllCloseButtons');
    $('#docking1').jqxDocking('showAllCollapseButtons');

  	//jqx date time
	$("#rangeDateTime").jqxDateTimeInput({ theme: 'energyblue', width: '100%', height: '25px',  selectionMode: 'range' });


    // NVR Server Event type
    $("#docking2").jqxDocking({theme: 'energyblue', orientation: 'horizontal', width: 250, mode: 'default'});
	$('#docking2').jqxDocking('hideAllCloseButtons');
    $('#docking2').jqxDocking('showAllCollapseButtons');
  	
	$("#selAll").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selMotion").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selBoot").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selShutdown").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selVideoloss").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selStorageevent").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});


	// System event type
    $("#docking3").jqxDocking({theme: 'energyblue', orientation: 'horizontal', width: 250, mode: 'default'});
	$('#docking3').jqxDocking('hideAllCloseButtons');
    $('#docking3').jqxDocking('showAllCollapseButtons');
	
	$("#selLogin").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selLogout").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selLoginfail").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});
	$("#selModifySystem").jqxRadioButton({theme: 'energyblue', width: 250, height: 25});

	$("#mainTable").jqxGrid({
		theme: 'energyblue', 
		width: "100%", 
		height: "100%",
        sortable: true,
        pageable: true,
        autoheight: false,
        columnsresize: true,
        columns: [
          { text: 'Date / Time', width: "30%", cellsformat: 'D' },
          { text: 'Camera Name', width: "20%"},
          { text: 'Event', width: "20%"},
          { text: 'Type', width: "15%"},
          { text: 'Action', width: "15%" }
        ]
	});


	// Radio Button Event
	$("#selAll").on('change', function (event) { var checked = event.args.checked; if (checked) { m_cur_maintype = 0; m_cur_subtype = 0; RetrieveEvents();} });
	$("#selMotion").on('change', function( event ) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 0; m_cur_subtype = 4; RetrieveEvents();} });
	$("#selBoot").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 0; m_cur_subtype = 1; RetrieveEvents();}});
	$("#selShutdown").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 0; m_cur_subtype = 2; RetrieveEvents();}});
	$("#selVideoloss").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 0; m_cur_subtype = 5; RetrieveEvents();}});
	$("#selStorageevent").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 0; m_cur_subtype = 3; RetrieveEvents();}});

	$("#selLogin").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 1; m_cur_subtype = 0; RetrieveEvents();}});
	$("#selLogout").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 1; m_cur_subtype = 1; RetrieveEvents();}});
	$("#selLoginfail").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 1; m_cur_subtype = 2; RetrieveEvents();}});
	$("#selModifySystem").on('change', function (event) { var checked = event.args.checked; if ( checked ) { m_cur_maintype = 1; m_cur_subtype = 3; RetrieveEvents();}});

	// Range Date input change event
	$("#rangeDateTime").on('change', function (event) {
        var selection = $("#rangeDateTime").jqxDateTimeInput('getRange');
        if (selection.from != null ) {
            m_cur_mindate = selection.to;
            m_cur_maxdate = selection.from;
            RetrieveEvents();
        }
    });
});

function EventInitFunc() {
	$("#deviceTree").jqxTree({ theme: "energyblue", width: "100%", height: "100%" });
	g_GenerateDevicesTree( $("#deviceTree"), true );
    InitializeEventTree();
}

function InitializeEventTree() {
	$("#deviceTree").unbind( "select" ).on( "select", function( event ) {
		 var oTarget = event.args.element;
		 var str_id = oTarget.id.split(":")[1];
		 
		 if (oTarget.id.indexOf("Building") >= 0 )
			 return;
		 
		 var ptr_camera = null;
		 var ptr_serverinfo = null;
		 
		 if ( oTarget.id.indexOf("Location") >= 0) {
			 ptr_camera = null;
			 str_id = oTarget.id.split("_")[1];
			 ptr_serverinfo = g_GetEngineInfoById( str_id );
		 } else {
			 str_id = oTarget.id.split(":")[1];
			 ptr_camera = g_GetDeviceInfoById( str_id );
			 ptr_serverinfo = g_GetEngineInfoById( ptr_camera.engineId );
		 }
		 
		 if (ptr_serverinfo == null ) {
			 return;
		 }
	 
		 m_cur_selected_camera = ptr_camera;
		 if ( m_cur_selected_server == null || m_cur_selected_server.id != ptr_serverinfo.id) {
			 m_cur_selected_server = ptr_serverinfo;
			 var str_url = "http://" + m_cur_selected_server.hostName + ":" + m_cur_selected_server.webport + "/grcenter.search.retrieveTimePeriod.nsf";
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
				 data: {year: int_year, month: int_month, date: int_date, hours: int_hours, minutes: int_minutes, seconds: int_seconds},
				 success: function ( result ) {
					 m_cur_selected_server.period = result.result.periodTime;
					 RetrieveEvents();
				 },
				 error: function ( err ) {
					 m_cur_selected_server.period = 0;
					 RetrieveEvents();
				 }
			 });
		 } else {
			 RetrieveEvents();
		 }
		 		
	});
}

function RetrieveEvents() {
	if ( m_cur_selected_server == null ) 
		return false;
	if (m_cur_mindate == null || m_cur_maxdate == null)
		return false;
	if ( m_cur_maintype == -1 || m_cur_subtype == -1 )
		return false;

	var str_startdate = g_GetDateStringFormat1( m_cur_maxdate );
	var str_starttime = g_GetTimeString( m_cur_maxdate );
	var str_enddate = g_GetDateStringFormat1( m_cur_mindate );
	var str_endtime = g_GetTimeString( m_cur_mindate );
	if ( m_cur_maintype == 0 ) {
		var str_url = "http://" + m_cur_selected_server.hostName + ":" + m_cur_selected_server.webport + "/grcenter.event.retrieveEvents.nsf";
		var str_videoinindex = "-1";
		if ( m_cur_selected_camera != null) {
			str_videoinindex = m_cur_selected_camera.videoinindex;
		}
		$.ajax({
			url: str_url,
			type: "post",
			data: {videoinindex: str_videoinindex, maxResult: 300, hid_start_date: str_startdate, hid_end_date: str_enddate, hid_start_time: str_starttime, hid_end_time: str_endtime, periodTime: m_cur_selected_server.period, selBox: m_cur_subtype},
			success: function ( data ) {
				$("#mainTable").jqxGrid('clear');
				var result = data.result;
				if ( result.eventtime ) {
					var ptr_list = new Array();
					for ( var i = 0; i < result.eventtime.length; i ++) {
						var a_row = {
								eventtime: result.eventtime[i],
								eventmessage: result.eventmessage[i],
								eventtypedetail: result.eventtypedetail[i],
								eventsourceindex: result.eventsourceindex[i]
								};
						ptr_list.push( a_row );
					}
					var source =
		            {
		                localdata: ptr_list,
		                datatype: "array",
		                datafields:
		                [
		                    { name: 'eventtime', type: 'string' },
		                    { name: 'eventmessage', type: 'string' },
		                    { name: 'eventtypedetail', type: 'string' },
		                    { name: 'eventsourceindex', type: 'int' }
		                ]
		            };
		            var dataAdapter = new $.jqx.dataAdapter(source);
		       
		            $("#mainTable").jqxGrid(
		            {
		                width: "100%",
		                height: "100%",
		                source: dataAdapter,
		                sortable: true,
		                pageable: true,
		                autoheight: false,
		                columnsresize: true,
		                columns: [
		                  { text: 'Date / Time', width: "30%", cellsformat: 'D', datafield: 'eventtime' },
		                  { text: 'Camera Name', width: "20%", cellsrenderer: function(){
			                  if (m_cur_selected_camera != null ) {
				                  return "<span>" + m_cur_selected_camera.name + "</span>";
			                  } else {
			                	  return "<span>" + m_cur_selected_server.name + "</span>";
			                  }
			                  }},
		                  { text: 'Event', width: "20%",  datafield: 'eventmessage'},
		                  { text: 'Type', width: "15%", datafield: 'eventtypedetail', cellsrenderer: eventtyperender},
		                  { text: 'Action', width: "15%" }
		                ]
		            });
				}
			},
			error: function ( err ) {
				$("#mainTable").jqxGrid('clear');
			}
		});
	}
}
</script>