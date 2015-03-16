<?php
/*
 *************************************************************************
 * @filename	: reportingDetail.php
 * @description	: Details of a reporting
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.21   chanry         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<html>
	<head class="admin_body">
		<title></title>
	    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
	    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.bootstrap.css';?>" type="text/css" />
	    <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
	
		<link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
		<link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
		<script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
	    <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'bootstrap.css';?>" type="text/css" />
	    
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'tab.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'json3.min.js';?>"></script>
		<link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.ui-redmond.css';?>" media="screen">
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
var ptr_locations = [];
var str_locations = '<?php echo isset( $result )?$result:'';?>';
var bool_allinfo_loaded = false;

if (str_locations != '') {
	ptr_locations = JSON3.parse( str_locations );		 
	GetAllServerInformation ();   	
}

function GetAllServerInformation () {
	for ( var i = 0; i < ptr_locations.length; i ++) {
		if ( ptr_locations[i].processed != 1 && ptr_locations[i] != 2 ) {			
			ptr_locations[i].connectInfo = null;
			ptr_locations[i].processed = 2;
			GetConnectStatus ( i );
			break;
		}
	}
}

function GetConnectStatus ( int_index ) {
	var str_url = "http://" + ptr_locations[int_index].ipaddress + ":" + ptr_locations[int_index].webport + "/grcenter.allinfo.retrieveServerConnectStatus.nsf";
	
	$.ajax({
		url: str_url,
		type: "post",
		success: function( data ) {
			ptr_locations[int_index].connectInfo = data;
			ptr_locations[int_index].processed = 1;
			GetAllServerInformation();
		}, 
		error: function ( err ) {
			ptr_locations[int_index].connectInfo = null;
			ptr_locations[int_index].processed = 1;
			GetAllServerInformation();
		}
	});
}

function CheckAllInfoLoaded () {
	for ( var i = 0; i < ptr_locations.length; i ++ ) {
		if ( ptr_locations[i].processed != 1 )
			return false;
	}
	return true;
}

function GetOnlineCameraCount() {
	var int_cnt = 0;
	var int_dcnt = 0;
	for ( var i = 0; i < ptr_locations.length; i ++ ) {
		if ( ptr_locations[i].connectInfo != null ) {
			for ( var j = 0; j < ptr_locations[i].connectInfo.liveinfo.connect.length; j ++) {
				if ( ptr_locations[i].connectInfo.liveinfo.connect[j] * 1 == 1 ) {
					int_cnt ++;
				} else {
					int_dcnt ++;
				}
			}
		}
	}
	return { online: int_cnt, offline: int_dcnt };
}

function GetOnlineServerCount() {
	var int_cnt = 0;
	var int_dcnt = 0;
	for ( var i = 0; i < ptr_locations.length; i ++ ) {
		if ( ptr_locations[i].connectInfo != null && ptr_locations[i].connectInfo.connectstatus * 1 == 1) {
			int_cnt ++;
		} else {
			int_dcnt ++;
		}
	}
	return { online: int_cnt, offline: int_dcnt };
}

$(document).ready(function(){
	//reporting tab function  
	$('#reportingTab a').click(function(e){
		e.preventDefault();
		$(this).tab('show');
	});
});
	      
//Google Chat Package Load function	
google.load("visualization", "1", {packages:["corechart"]});

//Draw Camera Health
google.setOnLoadCallback(drawChartCamera);

//Draw Server Health
google.setOnLoadCallback(drawServerHealth);

//Draw Last 24 Hours
// google.setOnLoadCallback(drawLastOneDay);

//Draw Last Storage
//google.setOnLoadCallback(drawStorage);	

//Draw Camera Health
function drawChartCamera() {
	if ( CheckAllInfoLoaded() == false ) {
		window.setTimeout( drawChartCamera, 100, null );
		return;
	}
	var ptr_camst = GetOnlineCameraCount();
	var onlineNum = ptr_camst.online;
	var offlineNum = ptr_camst.offline;
	var data = google.visualization.arrayToDataTable([
		['<?php echo $this->lang->line("admin.reports.task");?>', '<?php echo $this->lang->line("admin.reports.camera_health");?>'],
		[onlineNum + ' <?php echo $this->lang->line("admin.reports.online");?>',  ptr_camst.online],
		[offlineNum + ' <?php echo $this->lang->line("admin.reports.offline");?>', ptr_camst.offline],
	]);
			
	var options = {
		width: "50%",
		height: 240,
		title: '<?php echo $this->lang->line("admin.reports.camera_health");?>',
		colors: ['green', 'red' ]
	};
			
	var cameraChart = new google.visualization.PieChart(document.getElementById('cameraHealth'));
	cameraChart.draw(data, options);
}

	      //Draw Server Health	
function drawServerHealth(){
	if ( CheckAllInfoLoaded() == false ) {
		window.setTimeout( drawServerHealth, 100, null );
		return;
	}

	var ptr_serverst = GetOnlineServerCount();
	    	
	var data = google.visualization.arrayToDataTable([
		['<?php echo $this->lang->line("admin.reports.task");?>', '<?php echo $this->lang->line("admin.reports.serverhealth");?>'],
	    [ ptr_serverst.online + ' <?php echo $this->lang->line("admin.reports.online");?>',  ptr_serverst.online],
	    [ ptr_serverst.offline + ' <?php echo $this->lang->line("admin.reports.offline");?>', ptr_serverst.offline],
	]);
	
	var options = {
		width: "45%",
		height: 240,
		title: '<?php echo $this->lang->line("admin.reports.serverhealth");?>',
		colors: ['green', 'red' ]
	};
		
	var cameraChart = new google.visualization.PieChart(document.getElementById('serverHealth'));
	cameraChart.draw(data, options);
}

</script>
	</head>
	<body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
		<div class="container" style="max-width: 100%; width: 100%; height: 100%;position: relative;min-width: 150px; display: inline-block;">
			<div class="right-panel-header">
				<h2><?php echo $this->lang->line('admin.reports');?></h2>
				<p><?php echo $this->lang->line('admin.reports.detail');?></p>				
			</div>
			<ul id="reportingTab" class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#reportingDashboard" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.reports.dashboard');?></a></li>
			</ul>
			
			<!-- Tab panes -->
			<div class="tab-content">
			  <div class="tab-pane active" id="reportingDashboard">
			  	<div id="cameraHealth" style="display: inline-block;"></div>
				<div id="serverHealth" style="display: inline-block;"></div>
				<div id="lastOneDay" style="display: inline-block;"></div>
				<div id="storage" style="display: inline-block;"></div>
			  </div>
			</div>
		</div><!-- /.container -->
		
	</body>

</html>
