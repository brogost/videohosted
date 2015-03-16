<?php 
/*
 *************************************************************************
 * @filename    : livescript.php
 * @description    : Live page javascript functions
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.26   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<?php $this->load->view('includes/globaljs');?>
<script type="text/javascript" src="<?php echo base_url();?>assets/angular/angular.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/angular/timer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/angular/hashKeyCopier.js"></script>
<?php $this->load->view('includes/liveController');?>
<script type="text/javascript">
// Variables of Context Menu
var m_channel_context = null;
var m_layout_context = null;
var m_layoutitem_context = null;
var m_layoutcamera_context = null;
// Variables of Tree
var m_tree_layout = null;
var m_tree_devices = null;
var m_cur_page_index = 0;

var m_cur_dragging = null;
var m_cur_selected_channel = null;

// Channel Layout
var DEFAULT_LAYOUTS = new Array();
DEFAULT_LAYOUTS['1x1'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 100%;height: 100%"><div class="channelDiv" channel_no="1"></div></td></tr></table>';
DEFAULT_LAYOUTS['1x5'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 66%;height: 66%" colspan="2" rowspan="2"><div class="channelDiv" channel_no="1"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="2"></div></td></tr><tr style="width: 100%;"><td style="width: 30%;height: 33%"><div class="channelDiv" channel_no="3"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="6"></div></td></tr></table>';
DEFAULT_LAYOUTS['2x1'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 100%;height: 50%"><div class="channelDiv" channel_no="1"></div></td></tr><tr style="width: 100%;"><td style="width: 100%;height: 50%"><div class="channelDiv" channel_no="2"></div></td></tr></table>';
DEFAULT_LAYOUTS['2x2'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 50%;height: 50%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 50%;height: 50%"><div class="channelDiv" channel_no="2"></div></td></tr><tr style="width: 100%;"><td style="width: 50%;height: 50%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 50%;height: 50%"><div class="channelDiv" channel_no="4"></div></td></tr></table>';
DEFAULT_LAYOUTS['2x3'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 33%;height: 50%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 33%;height: 50%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 33%;height: 50%"><div class="channelDiv" channel_no="3"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 50%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 33%;height: 50%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 33%;height: 50%"><div class="channelDiv" channel_no="6"></div></td></tr></table>';
DEFAULT_LAYOUTS['3x2'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 50%;height: 33%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 50%;height: 33%"><div class="channelDiv" channel_no="2"></div></td></tr><tr style="width: 100%;"><td style="width: 50%;height: 33%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 50%;height: 33%"><div class="channelDiv" channel_no="4"></div></td></tr><tr style="width: 100%;"><td style="width: 50%;height: 33%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 50%;height: 33%"><div class="channelDiv" channel_no="6"></div></td></tr></table>';
DEFAULT_LAYOUTS['3x3'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="3"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="6"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="8"></div></td><td style="width: 33%;height: 33%"><div class="channelDiv" channel_no="9"></div></td></tr></table>';
DEFAULT_LAYOUTS['4x3'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="3"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="6"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="8"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="9"></div></td></tr><tr style="width: 100%;"><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="10"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="11"></div></td><td style="width: 33%;height: 25%"><div class="channelDiv" channel_no="12"></div></td></tr></table>';
DEFAULT_LAYOUTS['4x4'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="4"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="6"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="8"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="9"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="10"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="11"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="12"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="13"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="14"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="15"></div></td><td style="width: 25%;height: 25%"><div class="channelDiv" channel_no="16"></div></td></tr></table>';
DEFAULT_LAYOUTS['5x4'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="4"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="6"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="8"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="9"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="10"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="11"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="12"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="13"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="14"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="15"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="16"></div></td></tr><tr style="width: 100%;"><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="17"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="18"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="19"></div></td><td style="width: 25%;height: 20%"><div class="channelDiv" channel_no="20"></div></td></tr></table>';
DEFAULT_LAYOUTS['5x5'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="5"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="6"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="8"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="9"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="10"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="11"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="12"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="13"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="14"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="15"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="16"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="17"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="18"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="19"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="20"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="21"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="22"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="23"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="24"></div></td><td style="width: 20%;height: 20%"><div class="channelDiv" channel_no="25"></div></td></tr></table>';
DEFAULT_LAYOUTS['6x5'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="5"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="6"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="8"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="9"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="10"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="11"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="12"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="13"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="14"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="15"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="16"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="17"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="18"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="19"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="20"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="21"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="22"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="23"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="24"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="25"></div></td></tr><tr style="width: 100%;"><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="26"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="27"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="28"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="29"></div></td><td style="width: 20%;height: 16%"><div class="channelDiv" channel_no="30"></div></td></tr></table>';
DEFAULT_LAYOUTS['6x6'] = '<table id="channelTable"><tr style="width: 100%;"><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="1"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="2"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="3"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="4"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="5"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="6"></div></td></tr><tr style="width: 100%;"><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="7"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="8"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="9"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="10"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="11"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="12"></div></td></tr><tr style="width: 100%;"><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="13"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="14"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="15"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="16"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="17"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="18"></div></td></tr><tr style="width: 100%;"><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="19"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="20"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="21"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="22"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="23"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="24"></div></td></tr><tr style="width: 100%;"><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="25"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="26"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="27"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="28"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="29"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="30"></div></td></tr><tr style="width: 100%;"><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="31"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="32"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="33"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="34"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="35"></div></td><td style="width: 16%;height: 16%"><div class="channelDiv" channel_no="36"></div></td></tr></table>';

ICON_RECORDING_ACTIVE 	= "<?php echo HTTP_IMAGES_PATH;?>recordingActive.png";
ICON_RECORDING_DISABLE	= "<?php echo HTTP_IMAGES_PATH;?>recording.png";
ICON_AUDIO_ACTIVE		= "<?php echo HTTP_IMAGES_PATH;?>audioActive.png";
ICON_AUDIO_DISABLE		= "<?php echo HTTP_IMAGES_PATH;?>audio.png";
ICON_PTZ_ACTIVE			= "<?php echo HTTP_IMAGES_PATH;?>ptzActive.png";
ICON_PTZ_DISABLE		= "<?php echo HTTP_IMAGES_PATH;?>ptz.png";
ICON_MOTION_ACTIVE		= "<?php echo HTTP_IMAGES_PATH;?>motionActive.png";
ICON_MOTION_DISABLE		= "<?php echo HTTP_IMAGES_PATH;?>motion.png";

var InitLiveTrees = function(nTab) {
	switch(nTab) {
	case 0:	//Device Tree
		m_cur_page_index = 0; // Channel layout
		
		break;
	case 1: // Favorites Tree
		m_cur_page_index = 1; // Favorite view
		
		break;
	case 2: // G-Map tree
		m_cur_page_index = 2; // GMap page
		
		fn_GmapTreeInit();
		break;
	}	
}

// Disable mouse right click event on main page
$(document).on('contextmenu', function (e) {
    return false;
});

// Detect mouse click event is right or not
function isRightClick(event) {
    var rightclick;
    if (!event) var event = window.event;
    if (event.which) rightclick = (event.which == 3);
    else if (event.button) rightclick = (event.button == 2);
    return rightclick;
}

var ICON_LIVE_CHANNEL_1X1 = "<?php echo HTTP_IMAGES_PATH; ?>ch_1x1.png";
var ICON_LIVE_CHANNEL_1X5 = "<?php echo HTTP_IMAGES_PATH; ?>ch_1x5.png";
var ICON_LIVE_CHANNEL_3X3 = "<?php echo HTTP_IMAGES_PATH; ?>ch_3x3.png";

var m_str_standard_channels = new Array(
		'1x1', '1x5', '2x1','2x2', '2x3', '3x2', '3x3', '4x3', '4x4', '5x4', '5x5', '6x5', '6x6');

var m_channel_info = new Array();
m_channel_info['1x1'] = { max_cameras:  1 };
m_channel_info['1x5'] = { max_cameras:  6 };
m_channel_info['2x1'] = { max_cameras:  3 };
m_channel_info['2x2'] = { max_cameras:  4 };
m_channel_info['2x3'] = { max_cameras:  6 };
m_channel_info['3x2'] = { max_cameras:  6 };
m_channel_info['3x3'] = { max_cameras:  9 };
m_channel_info['4x3'] = { max_cameras: 12 };
m_channel_info['4x4'] = { max_cameras: 16 };
m_channel_info['5x4'] = { max_cameras: 20 };
m_channel_info['5x5'] = { max_cameras: 25 };
m_channel_info['6x5'] = { max_cameras: 30 };
m_channel_info['6x6'] = { max_cameras: 36 };

var m_cur_channel_info = new Array();
m_cur_channel_info = { name: '2x3', assigned_cameras:"", camera_count: 0, max_cameras: 6 };

$(document).ready(function() {
	g_OnLoadPage();
	$("#channel_select").jqxDropDownList({ theme: 'energyblue',selectedIndex: 0, dropDownHorizontalAlignment: 'left', dropDownWidth: 155, width: 155, height: 25});
	for (var i = 0; i < m_str_standard_channels.length; i ++) {
		$("#channel_select").jqxDropDownList('addItem', m_str_standard_channels[i]);
	}

	$("#channel_select").jqxDropDownList('selectItem', '2x3');
	
	$("#channel_select").on('select', function(event) {
		var item = event.args.item;
		
		ChangeChannelLayout ( null, item.label );
	});

	g_LoadEngineInfo(LiveInitFunc);

	// Initialize object variables
	m_tree_devices = $("#deviceTree");
	g_curSelectedDeviceTree = $("#deviceTree");
	m_tree_layout = $("#favoriteTree");
	m_channel_context = $("#channelContextMenu").jqxMenu({ width: '180px', height: '64px', autoOpenPopup: false, mode: 'popup'});;
	m_layout_context = $("#layoutParentContextMenu").jqxMenu({ width: '180px', height: '32px', autoOpenPopup: false, mode: 'popup'});
	m_layoutitem_context = $("#layoutContextMenu").jqxMenu({ width: '180px', height: '82px', autoOpenPopup: false, mode: 'popup'});
	m_layoutcamera_context = $("#layoutCameraContextMenu").jqxMenu({ width: '60px', height: '30px', autoOpenPopup: false, mode: 'popup'});
	// Left Splitter configuration
	$('#liveMainSpliter').jqxSplitter({theme: 'energyblue', width: '100%', height: '100%', panels: [{ size: 300 }] });
	$('#leftSideSpliter').jqxExpander({toggleMode: 'none', showArrow: false, width: "100%", height: "100%", theme:'energyblue'});

	//Initialize Tab Container
	$("#tabContainer").jqxTabs({width: "100%", theme:'classic', initTabContent: InitLiveTrees});

	// Change tab event 
	$('#tabContainer').on('selected', function (event) {
        // displayEvent(event);
        if ($("#playerWnd").attr( 'isopened' ) == '1') {
    		$("#playerWnd").jqxWindow('close');
    	}
        switch (event.args.item) {
        case 0:
        	m_cur_page_index = 0; 
        	$("#channelContainer").show();
        	$("#itemContainer").hide();
        	$("#ptzContainer").show();
    		$("#ioContainer").show();
    		$("#channel_select").show();
            break;
        case 1:
        	m_cur_page_index = 1; 
        	$("#channelContainer").show();
        	$("#itemContainer").hide();
        	$("#ptzContainer").show();
    		$("#ioContainer").show();
    		$("#channel_select").show();
            break;
        case 2:
        	m_cur_page_index = 2;
        	$("#channelContainer").hide();
        	$("#itemContainer").show(); 
        	$("#ptzContainer").hide();
    		$("#ioContainer").hide();
    		$("#channel_select").hide();
            break;
        }
    });
	// Ptz controller
	$("#ptzContainer").jqxExpander( {theme:'energyblue', toggleMode: 'none', showArrow: false, width: "100%"});
	$("#ioContainer").jqxExpander( { theme:'energyblue', toggleMode: 'none',  showArrow: false, width: "100%"});

	m_tree_layout.jqxTree({ theme: 'energyblue', height: '100%', width: '100%', allowDrag: false, width: "100%"});
	m_tree_layout.jqxTree('addTo', {label: "Favorites", id: "favorite_root", icon: "<?php echo base_url();?>assets/images/favorites.png"}, null);
	m_tree_layout.css('visibility', 'visible');

	// Add new layout name window
	$('#addNewLayoutWnd').jqxWindow({
        theme: 'energyblue', maxHeight: 240, maxWidth: 280, minHeight: 30, minWidth: 250, height: 140, width: 270,
        resizable: false, isModal: true, modalOpacity: 0.3,
        okButton: $('#btnAdd'), cancelButton: $('#btnCancel'),
        initContent: function () {
            $('#btnAdd').jqxButton({ theme: 'energyblue', width: '65px' });
            $('#btnCancel').jqxButton({ theme: 'energyblue', width: '65px' });
            $('#btnAdd').focus();
            $('#addNewLayoutWnd').jqxWindow('close');
        }
    });

    // Rename layout window
    $("#renameLayoutWnd").jqxWindow({
    	theme: 'energyblue', maxHeight: 230, maxWidth: 320, minHeight: 30, minWidth: 180, height: 150, width: 210,
        resizable: false, isModal: true, modalOpacity: 0.3,
        okButton: $('#btnRename'), cancelButton: $('#btnRenameCancel'),
        initContent: function () {
            $('#btnRename').jqxButton({ theme: 'energyblue', width: '65px' });
            $('#btnRenameCancel').jqxButton({ theme: 'energyblue', width: '65px' });
            $('#btnAdd').focus();
            $('#renameLayoutWnd').jqxWindow('close');
        }
	});
	
    $("#newLayoutName").jqxInput({theme: 'energyblue', placeHolder: "<?php echo $this->lang->line('glive.newlayout.placeholder');?>", height: 25, width: 240, minLength: 1});
    $("#newLayoutReName").jqxInput({theme: 'energyblue', placeHolder: "<?php echo $this->lang->line('glive.newlayout.placeholder');?>", height: 25, width: 180, minLength: 1});
	$("#newLayout").unbind('click').bind('click', function() {
		m_layout_context.jqxMenu( 'close' );
		$('#addNewLayoutWnd').jqxWindow( 'open' );
	});
	$('#btnRename').unbind('click').bind('click', function() {
		var o_selectedItem = m_tree_layout.jqxTree( 'selectedItem' );
		var str_newname = $("#newLayoutReName").val();
		if (str_newname == '')
			return false;
		var str_favid = o_selectedItem.id.split("_")[1];
		var str_channelname = $("#"+o_selectedItem.id).attr('channel_name');
		m_tree_layout.jqxTree( 'updateItem', o_selectedItem, {label: str_newname + "(" + str_channelname + ")", icon: o_selectedItem.icon} );
		m_tree_layout.jqxTree( 'refresh' );
		$.ajax({
			url: "<?php echo base_url();?>grlive/updatelayout",
			type: "post",
			data: {fav_id: str_favid, name: str_newname},
			success: function ( result ) {
				$("#renameLayoutWnd").jqxWindow('close');
				$("#newLayoutReName").val('');
			}
		});
	});
	$("#layoutContextMenu").unbind('itemclick').on('itemclick', function (event) {
        var item = $.trim($(event.args).attr('cmd'));
        switch (item) {
        case 'apply':
            var o_selectedItem = m_tree_layout.jqxTree('selectedItem');
            if ( o_selectedItem == null )
                return;
            if (o_selectedItem.id == "favorite_root")
                return;
            var str_id = o_selectedItem.id;
            var int_apply = $("#"+str_id).attr('needapply') * 1;
            
            if (int_apply != 1)
                return;
            
            if ( m_cur_channel_info.assigned_cameras == '')
            {
                alert ('There is no camera to be assigned.');
                return;
            }
            m_tree_layout.jqxTree('updateItem', o_selectedItem, {label: str_id.split("_")[1] + "(" + m_cur_channel_info.name + ")", icon: o_selectedItem.icon});
            m_tree_layout.jqxTree( 'refresh' );
            $("#"+str_id).attr('needapply', '0'); 
            $.ajax ({
                url: "<?php echo base_url();?>grlive/addnewlayout",
                type: 'post',
                data: {name: str_id.split("_")[1], cameraids: m_cur_channel_info.assigned_cameras, channelname: m_cur_channel_info.name},
                success: function ( result ) {
                	$("#"+str_id).attr('id', 'Location_'+result.id); 
                	$("#"+str_id).attr('assignedcameras', m_cur_channel_info.assigned_cameras); 
                	$("#"+str_id).attr('channel_name', m_cur_channel_info.name);   	
                }
            });
			
            break;
        case 'delete':
        	var o_selectedItem = m_tree_layout.jqxTree('selectedItem');
            if ( o_selectedItem == null )
                return;
            if (o_selectedItem.id == "favorite_root")
                return;
            var str_id = o_selectedItem.id;
            var str_favid = str_id.split("_")[1];
            m_tree_layout.jqxTree('removeItem', o_selectedItem);
            $.ajax({
                url: "<?php echo base_url();?>grlive/deletelayout",
                type: "post",
                data: {fav_id: str_favid},
                success: function ( result ) {
                }
            });
            break;
        case 'rename':
        	$("#renameLayoutWnd").jqxWindow( 'open' );
            break;
        default:
        	break;
        }
	});
	LoadAllLayouts();
	$('#btnAdd').unbind('click').bind('click', function () {
		var tree_items = m_tree_layout.jqxTree( 'getItems' );
		var parent_element = tree_items[0].element;
		var str_name = $("#newLayoutName").val();
		if (str_name != '') {
			for (var i = 0; i < tree_items.length; i ++) {
				if ( tree_items[i].id == "Layout_:"+str_name || tree_items[i].label == str_name ) {
					alert ("Already exist same name!");
					return false;
				}
			}
			m_tree_layout.jqxTree( 'addTo', {label: str_name+" *", icon: "<?php echo base_url();?>assets/images/folder.png", id:"Layout_"+str_name}, parent_element); 
			m_tree_layout.jqxTree( 'refresh' );
			m_tree_layout.jqxTree( 'expandAll' );
			
			$("[id='Layout_" + str_name + "']").attr('needapply', '1');
			AttachFavoriteItemContextMenu();
		} 
		$('#addNewLayoutWnd').jqxWindow( 'close' );
		$("#newLayoutName").val('');

		
	});
    AttachFavoriteContextMenu();
    AttachFavoriteItemContextMenu();
	// Initialize G-Map
	$('#playerWnd').jqxWindow({
        theme: 'energyblue', resizable: false, showCollapseButton: false, maxHeight: 400, maxWidth: 700, minHeight: 200, minWidth: 200, height: PLAYERWND_HEIGHT, width: PLAYERWND_WIDTH,
        initContent: function () {                
            $('#playerWnd').jqxWindow('close');
        }
    });
    $('#playerWnd').on('open', function (event) {
        $('#playerWnd').attr('isopened', '1'); 
        $('#playerWnd').jqxWindow('focus');
	}); 
	$('#playerWnd').on('close', function (event) {
		$('#playerWnd').attr('isopened', '0');
		g_jqRemoveChildObject($("#windowContainer"));
	});
	
	CallbackResize = function(event) {
		if (m_cur_page_index == 0) {
			$("#deviceTree").height($("#liveMainSpliter").height() - $("#ptzContainer").height() - $("#ioContainer").height() - 70);
		} else if (m_cur_page_index == 1) {
			$("#favoriteTree").height($("#liveMainSpliter").height() - $("#ptzContainer").height() - $("#ioContainer").height() - 70);
		} else if (m_cur_page_index == 2) {
			var obj_container = $("#itemContainer");

			if ( obj_container.length == 0 )
		        return;

		    // Remove old items
		    // g_jqRemoveChildObject(obj_container);
		    // obj_container.find('.gmap_item').each(function () { $(this).remove(); });
		    
		    var obj_backimg = $("#gmap_back_img");    
		    if (obj_backimg.length == 0)
		        return;
		        
		    var int_backimg_pos_left = obj_backimg.get(0).offsetLeft; 
		    var int_backimg_pos_top = obj_backimg.get(0).offsetTop;
		    var int_backimg_width = obj_backimg.width();
		    var int_backimg_height = obj_backimg.height();

		    $(".gmap_item").each(function() {
		        var tmp_x = $(this).attr("posx") * 1;
		        var tmp_y = $(this).attr("posy") * 1;        
		        var t_int_posx = int_backimg_width * tmp_x * 1 / 100 - 12 + int_backimg_pos_left;
		        var t_int_posy = int_backimg_height * tmp_y * 1 / 100 - 12 + int_backimg_pos_top;
		        $(this).attr("style", "left:"+t_int_posx+"px; top:"+t_int_posy+"px; z-index: 1000;"); 
		    });
		}
	}

	$("#channelContainer").html( DEFAULT_LAYOUTS['2x3'] );
	m_cur_channel_info.name = '2x3';
	m_cur_channel_info.assigned_cameras = "";
	m_cur_channel_info.max_cameras = m_channel_info['2x3'].max_cameras;
	m_cur_channel_info.camera_count = 0;
	AttachChannelEventListener();

	$(".channelcontextmenu").unbind('itemclick').on('itemclick', function (event) {
        var item = $.trim($(event.args).attr('btn_cmd'));
        switch (item) {
            case "clearchannel":
                //var selectedItem = $('#menuDefaults').jqxTree('selectedItem');
                RemoveCameraFromChannel();
                break;
            case 'clearchannelall':
                RemoveAllCamerasFromChannel();
                break;
        }
        $("#channelContextMenu").jqxMenu('close');
	});
	$(window).resize();

	// Start polling thread
	var obj_maincontroller = $("[ng-controller='mainController']").scope();
	obj_maincontroller.$apply(function() {
		obj_maincontroller.timerRunning = true;		
	});
});


function EmptyAction() {
	
}
// Initialize Live panel
function LiveInitFunc() {
	// Device Tree Init
	m_tree_devices.jqxTree({ theme: 'energyblue', width: '100%', toggleMode: 'EmptyAction', allowDrag: true, allowDrop: false,
        dragStart: function (item) {
        	m_cur_dragging = item;
        }
    });
    
	g_GenerateDevicesTree(m_tree_devices, true);
	AttachDeviceTreeDblClickEvent();
	
	AttachDragDropEventListener();
	var obj_maincontroller = $("[ng-controller='mainController']").scope();
	obj_maincontroller.$apply(function() {
		obj_maincontroller.bTreeInitialized = true;		
	});
}

/**
 * Define Event Listeners Start - Added by KCH ^_^
 */
// Attach Device Tree Double Click Event
function AttachDeviceTreeDblClickEvent ()
{
	m_tree_devices.on("dblclick", function(event) {
		var oTarget = m_tree_devices.jqxTree('selectedItem');
		ProcessAddNewDevice( oTarget.id, null );
	});
}

// Attach Channel click event 
function AttachChannelEventListener() {
	$(".channelDiv").unbind('mousedown').bind('mousedown', function(event) {
		var rightClick = isRightClick(event) || $.jqx.mobile.isTouchDevice();
		if (rightClick == true) {
			// Attach Context menu event 
			var str_assigned = $(this).attr("assigned");
			if (str_assigned != "1")
				return true;
            var scrollTop = $(window).scrollTop();
            var scrollLeft = $(window).scrollLeft();
            m_channel_context.jqxMenu('open', parseInt(event.clientX) + scrollLeft, parseInt(event.clientY) + scrollTop);
        }
        // Attach Channel select event
        if ( m_cur_selected_channel != null ) {
            var str_cur_chno = m_cur_selected_channel.attr('channel_no');
            var str_chno = $(this).attr('channel_no');
            if (str_cur_chno == str_chno)
                return true;
        }
      	$(".channelDiv").css("border", "solid 1px gray");
        $(this).css("border", "solid 1px red");
        m_cur_selected_channel = $(this);
        
	});
}

// Attach drag and drop event listener 
function AttachDragDropEventListener() {
	$(".draggable").jqxDragDrop({dropTarget:$(".channelDiv"), revert: true});
    $('.draggable').unbind('dropTargetEnter').bind('dropTargetEnter', function (event) {
        $(event.args.target).css('background-color', '#A1AEFD');
        //$(this).jqxDragDrop('dropAction', 'none');
    });
    $('.draggable').unbind('dropTargetLeave').bind('dropTargetLeave', function (event) {
        $(event.args.target).css('background-color', 'white');
        //$(this).jqxDragDrop('dropAction', 'copy');
    });

    $('.draggable').jqxDragDrop({ 
        onTargetDrop: function(data) { 
        	data.css('background-color', 'white');
        	if (m_cur_dragging != null) {
            	var str_selected = m_cur_dragging.id;
            	ProcessAddNewDevice( str_selected, data);
            	m_cur_dragging = null;
        	} 
        } 
    });
};

/**
 * End of Define Event Listeners
 */

/**
 * Channel Funcitons start
 */
// Processing Add camera action
function ProcessAddNewDevice(str_selected_id, obj_channel) {
	
	if ( str_selected_id.indexOf('Location') > -1 ) {
		var str_location_id = str_selected_id.split('_')[1];		 
		AssignCamerasToChannel( str_location_id, null, null, null );
	} else if ( str_selected_id.indexOf('Building') > -1 ) {
    	var str_tmp = str_selected_id.split('_')[1];
    	var str_location_id = str_tmp.split(':')[0];
    	var str_building_id = str_tmp.split(':')[1];
    	AssignCamerasToChannel( str_location_id, str_building_id, null, null);
	} else if ( str_selected_id.indexOf('Camera') > -1) {
    	var str_tmp = str_selected_id.split('_')[1];
    	var str_locationId = str_tmp.split(':')[0];
    	var str_cameraId = str_tmp.split(':')[1];
    	AssignCamerasToChannel( str_location_id, null, str_cameraId, obj_channel);
	}
}

// Add cameras to the current layout
function AssignCamerasToChannel ( str_location_id, str_building_id, str_camera_id, obj_target ) {
	if ( m_cur_channel_info.name == "" )
		return;
	
	var aChannelInfo = m_cur_channel_info;// m_channel_info[ m_str_current_layout ];
	if (aChannelInfo.max_cameras < aChannelInfo.camera_count) 
		return;
	var str_assigned = aChannelInfo.assigned_cameras;
	if (str_camera_id == null) {
		var ptr_cameras = g_GetDeviceInfosByLocationBuilding(str_location_id, str_building_id);
		if (ptr_cameras != null) {
			for (var i = 0; i < ptr_cameras.length; i ++) {
				var aCamera = ptr_cameras[i];
				var ptr_assigned = CheckAlreadyExistOnChannels ( str_assigned, aCamera.id );
				if (ptr_assigned.assigned == false) {
					
					if (aChannelInfo.camera_count == 0) {
						// m_channel_info[ m_str_current_layout ].assigned_cameras = aCamera.id;
						m_cur_channel_info.assigned_cameras = aCamera.id;
					} else {
						//m_channel_info[ m_str_current_layout ].assigned_cameras += ":" + aCamera.id;
						m_cur_channel_info.assigned_cameras += ":" + aCamera.id;
					}
					
					AddCameraToChannel ( aCamera, null );
					// m_channel_info[ m_str_current_layout ].camera_count ++;
					m_cur_channel_info.camera_count ++;
					
					// if (m_channel_info[ m_str_current_layout ].camera_count >= m_channel_info[ m_str_current_layout ].max_cameras)
					if (m_cur_channel_info.camera_count >= m_cur_channel_info.max_cameras)
						break;
				}
			}
		}
				
	} else {
		var aCamera = g_GetDeviceInfoById(str_camera_id);

		if (aCamera == null ) 
			return;

		var ptr_assigned = CheckAlreadyExistOnChannels ( str_assigned, aCamera.id );
		
		if (ptr_assigned.assigned == false) {
			if (aChannelInfo.camera_count == 0) {
				// m_channel_info[ m_str_current_layout ].assigned_cameras = aCamera.id;
				m_cur_channel_info.assigned_cameras = aCamera.id;
			} else {
				// m_channel_info[ m_str_current_layout ].assigned_cameras += ":" + aCamera.id;
				m_cur_channel_info.assigned_cameras += ":" + aCamera.id;
			}
			
			AddCameraToChannel ( aCamera, obj_target );
			// m_channel_info[ m_str_current_layout ].camera_count ++;
			m_cur_channel_info.camera_count ++;
		}
	}
	
}

// add camera to the favorite view
function AddCamerasToFavoriteView (str_assigned) {
	var ptr_cameraids = str_assigned.split( ":" );
	for ( var i = 0; i < ptr_cameraids.length; i ++) {
		var tmp_camera = null;
		for ( var j = 0; j < g_DeviceInfos.length; j ++ ) {
			if ( g_DeviceInfos[j].id == ptr_cameraids[i] ) {
				tmp_camera = g_DeviceInfos[j];
				break;
			}
		}
		if ( tmp_camera != null ) {
			if (m_cur_channel_info.camera_count == 0) {
				// m_channel_info[ m_str_current_layout ].assigned_cameras = aCamera.id;
				m_cur_channel_info.assigned_cameras = tmp_camera.id;
			} else {
				//m_channel_info[ m_str_current_layout ].assigned_cameras += ":" + aCamera.id;
				m_cur_channel_info.assigned_cameras += ":" + tmp_camera.id;
			}
			
			AddCameraToChannel ( tmp_camera, null );
			// m_channel_info[ m_str_current_layout ].camera_count ++;
			m_cur_channel_info.camera_count ++;
			
			// if (m_channel_info[ m_str_current_layout ].camera_count >= m_channel_info[ m_str_current_layout ].max_cameras)
			if (m_cur_channel_info.camera_count > m_cur_channel_info.max_cameras)
				break;
		}
	}
}

// Add camera to the specific channel
function AddCameraToChannel ( ptr_camera, obj_target ) {
	// var obj_channels = $(".channelDiv");
	if ( m_cur_channel_info.name == "") 
		return;
	if (obj_target != null) {
		var str_assigned = obj_target.attr("assigned");
	
		if ( str_assigned != "1") {
			var ptr_engine = g_GetEngineInfoById( ptr_camera.engineId );
			var str_url = "rtmp://" + ptr_engine.hostName + ":" + ptr_engine.rtmpport + "/live/" + ptr_camera.livetoken; 
			// var str_player_html = g_GeneratePlayer( str_url, "100%", "100%");
			// var str_player_html = g_GeneratePlayer( "rtmp://" + ptr_engine.hostName + ":" + ptr_engine.rtmpport, ptr_camera.livetoken  + "/live", "100%", "100%");
			var str_player_html = g_GenerateMJPEGPlayer(ptr_engine.hostName, ptr_camera.videoinindex);
			$(str_player_html).appendTo( obj_target );
			obj_target.attr("assigned", "1");
			obj_target.attr("assignedid", ptr_camera.id);
			var obj_overlay = obj_target.find('.channelOverlay');
			var str_span_title = "<span class='deviceTitle'>" + ptr_camera.name + "</span>";
			
			var str_liveinfoIcons = "";
		
			if (ptr_camera.liveInfo.recording == 'A') {
				str_liveinfoIcons = "<img id='liveRecordingSt' src='" + ICON_RECORDING_ACTIVE + "' class='liveinfoIcons'>";
			} else {
				str_liveinfoIcons = "<img id='liveRecordingSt' src='" + ICON_RECORDING_DISABLE + "' class='liveinfoIcons'>";
			} 

			if (ptr_camera.liveInfo.audio == 'A') {
				str_liveinfoIcons += "<img id='liveAudioSt' src='" + ICON_AUDIO_ACTIVE + "' class='liveinfoIcons'>";
			} else {
				str_liveinfoIcons += "<img id='liveAudioSt' src='" + ICON_AUDIO_DISABLE + "' class='liveinfoIcons'>";
			}

			if ( ptr_camera.liveInfo.ptz == 'A') {
				str_liveinfoIcons += "<img id='livePtzSt' src='" + ICON_PTZ_ACTIVE + "' class='liveinfoIcons'>";
			} else {
				str_liveinfoIcons += "<img id='livePtzSt' src='" + ICON_PTZ_DISABLE + "' class='liveinfoIcons'>";
			}

			if (ptr_camera.liveInfo.motion == 'A') {
				str_liveinfoIcons += "<img id='liveMotionSt' src='" + ICON_MOTION_ACTIVE + "' class='liveinfoIcons'>";
			} else {
				str_liveinfoIcons += "<img id='liveMotionSt' src='" + ICON_MOTION_DISABLE + "' class='liveinfoIcons'>";
			}
			
			$(str_span_title).appendTo(obj_overlay);
			$(str_liveinfoIcons).appendTo( obj_overlay );
		} 
	} else {
		var b_assigned = false;
		$(".channelDiv").each(function() {
			var str_assigned = $(this).attr("assigned");
			if (str_assigned != "1" && b_assigned == false ) {
				var ptr_engine = g_GetEngineInfoById( ptr_camera.engineId );
				var str_url = "rtmp://" + ptr_engine.hostName + ":" + ptr_engine.rtmpport + "/live/" + ptr_camera.livetoken; 
				// var str_player_html = g_GeneratePlayer( str_url, "100%", "100%");
				// var str_player_html = g_GeneratePlayer( "rtmp://" + ptr_engine.hostName + ":" + ptr_engine.rtmpport + "/live", ptr_camera.livetoken, "100%", "100%");
				var str_player_html = g_GenerateMJPEGPlayer(ptr_engine.hostName, ptr_camera.videoinindex);
				$(str_player_html).appendTo( $(this) );
				$(this).attr("assigned", "1");
				$(this).attr("assignedid", ptr_camera.id);
				var obj_overlay = $(this).find('.channelOverlay');
				var str_span_title = "<span class='deviceTitle'>" + ptr_camera.name + "</span>";
				var str_liveinfoIcons = "";

				if (ptr_camera.liveInfo.recording == 'A') {
					str_liveinfoIcons = "<img id='liveRecordingSt' src='<?php echo HTTP_IMAGES_PATH;?>recordingActive.png' class='liveinfoIcons'>";
				} else {
					str_liveinfoIcons = "<img id='liveRecordingSt' src='<?php echo HTTP_IMAGES_PATH;?>recording.png' class='liveinfoIcons'>";
				} 
				if (ptr_camera.liveInfo.audio == 'A') {
					str_liveinfoIcons += "<img id='liveAudioSt' src='<?php echo HTTP_IMAGES_PATH;?>audioActive.png' class='liveinfoIcons'>";
				} else {
					str_liveinfoIcons += "<img id='liveAudioSt' src='<?php echo HTTP_IMAGES_PATH;?>audio.png' class='liveinfoIcons'>";
				}

				if ( ptr_camera.liveInfo.ptz == 'A') {
					str_liveinfoIcons += "<img id='livePtzSt' src='<?php echo HTTP_IMAGES_PATH;?>ptzActive.png' class='liveinfoIcons'>";
				} else {
					str_liveinfoIcons += "<img id='livePtzSt' src='<?php echo HTTP_IMAGES_PATH;?>ptz.png' class='liveinfoIcons'>";
				}

				if (ptr_camera.liveInfo.motion == 'A') {
					str_liveinfoIcons += "<img id='liveMotionSt' src='<?php echo HTTP_IMAGES_PATH;?>motionActive.png' class='liveinfoIcons'>";
				} else {
					str_liveinfoIcons += "<img id='liveMotionSt' src='<?php echo HTTP_IMAGES_PATH;?>motion.png' class='liveinfoIcons'>";
				}

				$(str_span_title).appendTo(obj_overlay);
				$(str_liveinfoIcons).appendTo( obj_overlay );
				b_assigned = true;
			}
		});
	}
}

// Remove camera id from assigned camera ids string 
function RemoveAssignedCameraId ( str_assigned_cameras, str_camera_id ) {
	var ptr_assigned = str_assigned_cameras.split(":");
	var result = "";
	var int_count = 0;
	for (var i = 0; i < ptr_assigned.length; i ++) {
		if ( ptr_assigned[i] != str_camera_id ) {
			if (int_count == 0 ) {
				result += ptr_assigned[i];
			} else {
				result += ":" + ptr_assigned[i];
			}
			int_count ++;
		}
	} 

	return result;
}

// Clear channel 
function RemoveCameraFromChannel ( ) {
	if (m_cur_selected_channel == null)
		return;
	var str_assigned = m_cur_selected_channel.attr("assigned");
	if (str_assigned == "0")
		return;
	var str_assignedid = m_cur_selected_channel.attr("assignedid");
	m_cur_selected_channel.attr("assigned", "0");
	m_cur_selected_channel.attr("assignedid", "");
	g_jqRemoveChildObject( m_cur_selected_channel);
	// Change Channel Info - assigned_cameras
	var str_assigned = m_cur_channel_info.assigned_cameras;
	m_cur_channel_info.assigned_cameras = RemoveAssignedCameraId(str_assigned, str_assignedid);//str_newassigned;
	m_cur_channel_info.camera_count --;
	
	bool_removed = true;
	return;
}

// Clear All 
function RemoveAllCamerasFromChannel() {
	var obj_channels = $(".channelDiv");
	if (obj_channels.length == 0)
		return;
	obj_channels.each(function(){
		$(this).attr("assigned", "0");
		$(this).attr("assignedid", "");
		g_jqRemoveChildObject( $(this) );
	});
	m_cur_channel_info.assigned_cameras = "";
	m_cur_channel_info.camera_count = 0;
	m_cur_selected_channel = null;
}

// Check if camera exist on current layout
function CheckAlreadyExistOnChannels ( str_assigned, str_camera_id ) {
	var result = { assigned: false, ind: 0, assigned_cameras: ""};
	if (str_assigned == "") {
		result.assigned_cameras = str_camera_id;
		return result;
	}

	var ptr_assigned = str_assigned.split(":");

	result.assigned_cameras = str_assigned;
	for (var i = 0; i < ptr_assigned.length; i ++) {
		if (ptr_assigned[i] == str_camera_id) {
			result.assigned = true;
			result.ind = i;
			return result;
		}
	}
	return result;
}

//Change Layout
function ChangeChannelLayout( obj_parent, str_layout ) {
	// if (m_cur_channel_info.name == str_layout)
	//	return;

	// m_str_current_layout = str_layout;
	m_cur_channel_info.name = str_layout;
	m_cur_channel_info.max_cameras = m_channel_info[str_layout].max_cameras;
	m_cur_channel_info.assigned_cameras = "";
	m_cur_channel_info.camera_count = 0;
	
	var str_layout_obj = DEFAULT_LAYOUTS[str_layout];
	g_jqRemoveChildObject($("#channelContainer"));
	
	$(str_layout_obj).appendTo($("#channelContainer"));

	// Set Channel No
	var i_chno = 0;
	$(document).find(".channelDiv").each(function(){
		$(this).attr("channel_no", i_chno);
		$(this).attr("assigned", "0");
		i_chno ++;
	});

	AttachDragDropEventListener();
	AttachChannelEventListener();
	
	//AttachChannelClickEvent();
}

/**
 * End of Channel Functions
 */


 function LoadAllLayouts() {
 	$.ajax({
 		url: "<?php echo base_url(); ?>grlive/getalllayouts",
 		type: "POST",
 		success: function (data) {
 			var result = JSON.parse( data );
 			//var ownlayouts = result.ownlayouts;
 			//var sharedlayouts = result.sharedlayouts;
 			var tree_items = m_tree_layout.jqxTree('getItems');
 			// Add own cameras			
 			if (result) {
 				var parent_element = tree_items[0];
 				for ( var i = 0; i < result.length; i ++ ) {
 					var aLayout = result[i];
 					var str_shared = "";
 					if (aLayout.shared == 'Y') {
 						str_shared = "(Shared)";
 					}
 					m_tree_layout.jqxTree('addTo', {id: "Layout_" + aLayout.id, label: aLayout.name + "(" + aLayout.channelName + ")", icon: "<?php echo base_url();?>assets/images/folder.png"}, parent_element.element);
 					$( "#Layout_" + aLayout.id ).attr( "channel_name", aLayout.channelName );
 					$( "#Layout_" + aLayout.id ).attr( "assignedcameras", aLayout.cameraIds );
 					//if (aLayout.cameraIds != '')
 						//AddCamerasToLayout( "Layout_" + aLayout.id, aLayout.cameraIds.split(":"));
 				}
 			}			
 			// Add other users shared cameras
 			/* if ( sharedlayouts ) {
 				var parent_element = g_GetTreeItemByID(tree_items, "sharedLayouts");
 				for (var i = 0; i < sharedlayouts.length; i ++) {
 					var aLayout = sharedlayouts[i];
 					m_tree_layout.jqxTree('addTo', {id: "Layout_" + aLayout.id, label: aLayout.name}, parent_element);
 					$( "#Layout_" + aLayout.id ).attr( "channel_name", aLayout.channelName );
 					if (aLayout.cameraIds != '')
 						AddCamerasToLayout( "Layout_" + aLayout.id, aLayout.cameraIds.split(":"));
 				}
 			} */
 		}
 	});
 }
 
// Context menu for layout configuration
function AttachFavoriteContextMenu() {
	$("#favoriteTree").on('mousedown', function(event){
		var rightClick = isRightClick(event) || $.jqx.mobile.isTouchDevice();
		var oTarget = $(event.target).parents('li:first')[0];
		if ( oTarget == undefined )
			return false;
        if (rightClick && oTarget.id.indexOf('favorite') > -1) {
            var scrollTop = $(window).scrollTop();
            var scrollLeft = $(window).scrollLeft();
            m_layoutitem_context.jqxMenu('close');
            m_layout_context.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
            // return false;
        } else if (!rightClick && oTarget.id.indexOf('Layout') > -1) {
        	var oTarget = $(event.target).parents('li:first')[0];
        	var str_channelname = oTarget.getAttribute('channel_name');
        	var str_assigned = oTarget.getAttribute( 'assignedcameras' );
        	if ( str_channelname != undefined ) {
        		ChangeChannelLayout ( null, str_channelname );
        		AddCamerasToFavoriteView( str_assigned );
        		AttachFavoriteItemContextMenu();
        	}
        }        
	});
}

function AttachFavoriteItemContextMenu() {
	$(document).find('#favorite_root').find('li').each (function(){
		$(this).unbind('mousedown').bind('mousedown', function(event){
			var rightClick = isRightClick(event) || $.jqx.mobile.isTouchDevice();
			var oTarget = $(event.target).parents('li:first')[0];
	        if (rightClick) {
	            var scrollTop = $(window).scrollTop();
	            var scrollLeft = $(window).scrollLeft();
	            m_layout_context.jqxMenu( 'close' );
	            m_layoutitem_context.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	            // return false;
	        }
		});
	});
}

function OnClickPtzPanel() {
	var m_isvisible = $("#ptzControlPanel").attr("isvisible");
	if (m_isvisible == "0") {
		$("#ptzControlPanel").attr("isvisible", "1");
		$("#ptzControlPanel").show();
	} else {
		$("#ptzControlPanel").attr("isvisible", "0");
		$("#ptzControlPanel").hide();
	}
	$(window).resize();
}

function OnClickIOControlPanel() {
	var m_isvisible = $("#ioControlPanel").attr("isvisible");
	if (m_isvisible == "0") {
		$("#ioControlPanel").attr("isvisible", "1");
		$("#ioControlPanel").show();
	} else {
		$("#ioControlPanel").attr("isvisible", "0");
		$("#ioControlPanel").hide();
	}
	$(window).resize();
}
</script>