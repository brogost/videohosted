<?php $this->load->view('includes/globaljs');?>
<script type="text/javascript">
var g_str_image_path = "<?php echo base_url().'assets/uploads/gmap/';?>";
var GMAP_BUILDING_ICON = "<?php echo HTTP_IMAGES_PATH.'mapbuilding.png';?>";
var GMAP_DEVICE_ICON = "<?php echo HTTP_IMAGES_PATH.'camera.png';?>"; 
var GMAP_DEVICE_DISCONNECTED = "<?php echo HTTP_IMAGES_PATH.'camera_disconnected.png';?>";
var GMAP_DEFAULT_IMAGE = "<?php echo HTTP_IMAGES_PATH.'default.png';?>";
var m_selected_location = -1;
var m_selected_building = -1;
var m_current_selected = 0; // 1 - location, 0: building, -1: not selected

var PLAYERWND_WIDTH = 400;
var PLAYERWND_HEIGHT = 300;

var m_bool_mouseover = false;

var m_selected_page = '';

var m_selected_Target = null;
var m_curposx = 0;
var m_curposy = 0;
   
function fn_GmapTreeInit() {
//	$("#feedExpander").jqxExpander({theme: 'energyblue', toggleMode: 'none', showArrow: false, width: "100%", height: "100%", 
//    	initContent: function () {
    		$('#menuGmap').jqxTree({  theme: 'energyblue', width: '280', allowDrag: false, allowDrop: false });
    		$('#menuGmap').css('visibility', 'visible');

    		for (var i = 0; i < g_EngineInfos.length; i ++) {
    			$('#menuGmap').jqxTree('addTo', {icon: ICON_DEVICES_LOCATION, expanded: true, label: g_EngineInfos[i].name, id: "Location_" + g_EngineInfos[i].id});    			
    	   	}
			var parentItems = $('#menuGmap').jqxTree('getItems');
    	   	for (var i = 0; i < g_BuildingInfos.length; i ++) {
    	   		var parentElement = g_GetTreeItemByID(parentItems, "Location_" + g_BuildingInfos[i].engineId);
    	   		$('#menuGmap').jqxTree('addTo', {icon: ICON_DEVICES_BUILDING, label: g_BuildingInfos[i].name, id: "Building_" + g_BuildingInfos[i].engineId + ":" + g_BuildingInfos[i].id}, parentElement );
    	   	}
    	   	
    		$('#menuGmap').on('select', function (event) {
    			var oTarget = $('#menuGmap').jqxTree('selectedItem'); 
    			
    			var str_id = oTarget.id;

    			if ($("#playerWnd").attr( 'isopened' ) == '1') {
    				$("#playerWnd").jqxWindow('close');
    			}

    			if (str_id == m_selected_page)
        			return;
                g_jqRemoveChildObject($("#rightContainer"));
    			m_selected_page = str_id;

    			if (str_id.indexOf('Location') > -1) {
        			var str_locid = str_id.split("_")[1];
        			$("#gmap_left_submit_form").find("input[name='locationId']").attr("value", str_locid);
        			$("#gmap_left_submit_form").find("input[name='buildingId']").attr("value", "-1");
        			m_current_selected = 1;
        			m_selected_location = str_locid;
        			m_selected_building = -1;
    			} else {
    				var str_locid = str_id.split("_")[1];
    				$("#gmap_left_submit_form").find("input[name='locationId']").attr("value", str_locid.split(":")[0]);
        			$("#gmap_left_submit_form").find("input[name='buildingId']").attr("value", str_locid.split(":")[1]);
        			m_current_selected = 0;
        			m_selected_location = str_locid.split(":")[0];
        			m_selected_building = str_locid.split(":")[1];
    			}
    			
    			$("#gmap_left_submit_form").ajaxSubmit({
        			success: function ( data ) {
        				if (data == "") 
                			return;
            			
            			var result = JSON.parse(data);
            			
                        var str_style = "width: 100%";
                        /* var obj_tmp_img = $("#gmap_clone");
                        obj_tmp_img.attr( "src", g_str_image_path + result.image_path);
                        var tmp_width = obj_tmp_img.width();
                        var tmp_height = obj_tmp_img.height();
                        var ocwidth = $("#rightContainer").width();
                        var ocheight = $("#rightContainer").height();
                        if (ocheight * 1 / ocwidth * 1 > tmp_height*1 / tmp_width*1)  {
                            str_style = "height: 100%";
                        } */
            			var str_img_html = "<img id='gmap_back_img' src='" + g_str_image_path + result.image_path + "'" + " style='"+str_style+"'>";
            			$("#rightContainer").children().each(function(){$(this).remove();});
            			$(str_img_html).appendTo($("#rightContainer"));
            			$("#gmap_back_img").error(function(){
            				document.getElementById("gmap_back_img").src = GMAP_DEFAULT_IMAGE;
            			});
            			$("#gmap_back_img").load(function() {
            				fn_LoadGMapItems();
            			});
            			
        			}
        		});
            });

    		// Select first location
    		$('#menuGmap').jqxTree('selectItem', parentItems[0].element);
        //}
//    });
}
function fn_SelectBuilding( str_bid ) {
    var ptr_building = g_GetBuildingInfoById( str_bid );
    var str_item_id = "Building_" + ptr_building.engineId + ":" + ptr_building.id;
    var tree_item = g_GetTreeItemByID($('#menuGmap').jqxTree('getItems'), str_item_id);
	$('#menuGmap').jqxTree('selectItem', tree_item.element);
}

function fn_SelectDevice(str_cid)
{
    $("#playerWnd").jqxWindow('close');
    m_bool_mouseover = false;
    var external_window = g_OpenExternalWindow("http://google.com", "test");    
}

function fn_LoadGMapItems() {
	var obj_container = $("#itemContainer");
	// Remove old items
	// g_jqRemoveChildObject(obj_container);
	obj_container.find('.gmap_item').each(function () { $(this).remove(); });
	var obj_backimg = $("#rightContainer").find("img");
	var int_backimg_pos_left = obj_backimg.get(0).offsetLeft; 
	var int_backimg_pos_top = obj_backimg.get(0).offsetTop;
	var int_backimg_width = obj_backimg.width();
	var int_backimg_height = obj_backimg.height();     

	var obj_clone = $("#gmap_clone");
	var int_posx = 0;
	var int_posy = 0;
	if (m_current_selected == 1) // Location
	{
		for (var i = 0; i < g_BuildingInfos.length; i ++) {
			if (g_BuildingInfos[i].engineId == m_selected_location) {
				var obj_bitem = obj_clone.clone(true);
				int_posx = int_backimg_width * g_BuildingInfos[i].posx * 1 / 100 - 12 + int_backimg_pos_left;
				int_posy = int_backimg_height * g_BuildingInfos[i].posy * 1 / 100 - 12 + int_backimg_pos_top;
				obj_bitem.attr("isbuilding", "1");
                obj_bitem.attr("id", "");
                obj_bitem.attr("class", "gmap_item");
				obj_bitem.attr("title", g_BuildingInfos[i].name);
				obj_bitem.attr("src", GMAP_BUILDING_ICON);
                obj_bitem.attr("posx", g_BuildingInfos[i].posx);   
                obj_bitem.attr("posy", g_BuildingInfos[i].posy); 
				obj_bitem.attr("buildingid", g_BuildingInfos[i].id);
				obj_bitem.attr("style", "left:"+int_posx+"px;top:"+int_posy+"px;z-index: 1000;"); 
				obj_bitem.on('click', function(){fn_SelectBuilding( $(this).attr('buildingid') );});
				obj_bitem.prependTo($("#rightContainer")); 
			}
		}
        for (var i = 0; i < g_DeviceInfos.length; i ++) {
            if (g_DeviceInfos[i].engineId == m_selected_location && g_DeviceInfos[i].buildingId == '-1') {
                var obj_bitem = obj_clone.clone(true);
                int_posx = int_backimg_width * g_DeviceInfos[i].posx * 1 / 100 - 12 + int_backimg_pos_left;
                int_posy = int_backimg_height * g_DeviceInfos[i].posy * 1 / 100 - 12 + int_backimg_pos_top;
                obj_bitem.attr("isbuilding", "0");
                obj_bitem.attr("id", "");
                obj_bitem.attr("posx", g_DeviceInfos[i].posx);   
                obj_bitem.attr("posy", g_DeviceInfos[i].posy); 
                obj_bitem.attr("title", g_DeviceInfos[i].name);
                obj_bitem.attr("class", "gmap_item");
                obj_bitem.attr("src", GMAP_DEVICE_ICON);
                obj_bitem.attr("deviceid", g_DeviceInfos[i].id);
                obj_bitem.attr("style", "left:"+int_posx+"px;top:"+int_posy+"px;z-index: 1000;"); 
                obj_bitem.on('click', function(){fn_SelectDevice($(this).attr('deviceid'));});
                obj_bitem.prependTo($("#rightContainer")); 
            }
        }
	} else {    
        for (var i = 0; i < g_DeviceInfos.length; i ++) {
            if (g_DeviceInfos[i].buildingId == m_selected_building) {
                var obj_bitem = obj_clone.clone(true);
                int_posx = int_backimg_width * g_DeviceInfos[i].posx * 1 / 100 - 12 + int_backimg_pos_left;
                int_posy = int_backimg_height * g_DeviceInfos[i].posy * 1 / 100 - 12 + int_backimg_pos_top;
                obj_bitem.attr("isbuilding", "0");
                obj_bitem.attr("id", "");
                obj_bitem.attr("posx", g_DeviceInfos[i].posx);   
                obj_bitem.attr("posy", g_DeviceInfos[i].posy); 
                obj_bitem.attr("class", "gmap_item");
                obj_bitem.attr("title", g_DeviceInfos[i].name);
                obj_bitem.attr("src", GMAP_DEVICE_ICON);
                obj_bitem.attr("deviceid", g_DeviceInfos[i].id);
                obj_bitem.attr("style", "left:"+int_posx+"px;top:"+int_posy+"px;z-index: 1000;"); 
                obj_bitem.attr("onmouseover", "fn_DeviceMouseOver(event)");
                obj_bitem.attr("onmouseout", "m_bool_mouseover=false;");
                obj_bitem.on('click', function(){
                	if ($("#playerWnd").attr( 'isopened' ) == '1') {
        				$("#playerWnd").jqxWindow('close');
        			}
                    fn_SelectDevice($(this).attr('deviceid'));
                });
                obj_bitem.prependTo($("#rightContainer")); 
            }
        }
    }
}

function fn_OpenPlayerWnd() {
    if ( m_bool_mouseover == false )
        return;
    
    var oTarget = m_selected_Target;
    var str_deviceid = $(oTarget).attr('deviceid');
    var oPlayerWnd = $("#playerWnd");

    var bIsOpened = $("#playerWnd").attr( 'isopened' );
    var str_org_deviceid = $("#playerWnd").attr("deviceid"); 
    if (bIsOpened == '1' && str_org_deviceid == str_deviceid) {
        return;
    }

    g_jqRemoveChildObject($("#windowContainer"));

    oPlayerWnd.attr('deviceid', str_deviceid);

    // Load player
    var ptr_device = g_GetDeviceInfoById(str_deviceid);
    if (ptr_device == null)
    {
        return;
    }
    var ptr_location = g_GetEngineInfoById(ptr_device.engineId);
    
    if (ptr_location == null) {
        return;
    }
    var str_liveurl = "rtmp://" + ptr_location.hostName + ":" + ptr_location.rtmpport + "/live/" + ptr_device.livetoken;
    
    var str_player = g_GeneratePlayer("rtmp://" + ptr_location.hostName + ":" + ptr_location.rtmpport + "/live", ptr_device.livetoken, "100%", "100%");
    $(str_player).appendTo($("#windowContainer"));
    
    // Move player window
    var int_posx = m_curposx - PLAYERWND_WIDTH - 12;
    
    if (m_curposx + PLAYERWND_WIDTH > g_WndWidth) {
        int_posx = g_WndWidth - PLAYERWND_WIDTH; 
    } else if (int_posx < 0) {
        int_posx = m_curposx;
    }
    var int_posy = m_curposy - PLAYERWND_HEIGHT ;
    if ( int_posy < 0 ) {
        int_posy = m_curposy;
    } 
    oPlayerWnd.css("left", int_posx);
    oPlayerWnd.css("top", int_posy);

    // Open player window
    if (bIsOpened == '0') {
        $("#playerWnd").jqxWindow('open');
    }
}

function fn_DeviceMouseOver(e) {
    m_bool_mouseover = true;
    m_selected_Target = e.target;
    m_curposx = e.screenX;
    m_curposy = e.screenY;
    var pageX = e.pageX;
    var pageY = e.pageY;
    if (pageX === undefined) {
        pageX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
        pageY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    }

    m_curposx = pageX;
    m_curposy = pageY;
    window.setTimeout("fn_OpenPlayerWnd()", 500);
}
</script>