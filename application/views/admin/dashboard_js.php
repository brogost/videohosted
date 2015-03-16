<?php 
/*
 *************************************************************************
 * @filename	: dashboard_js.php
 * @description	: Javascript Functions of Dashboard page
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.10   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<script type="text/javascript">
// Define constant variables
var ICON_DEVICES_LOCATION = "<?php echo HTTP_IMAGES_PATH;?>regionalmap.jpg";
var ICON_DEVICES_BUILDING = "<?php echo HTTP_IMAGES_PATH;?>bluehouse.jpg";
var	ICON_DEVICES_CAMERA = "<?php echo HTTP_IMAGES_PATH;?>camera.jpg";
//Global variables -- Start

// Currently selected page name
var g_str_cur_page = "";

// Devices left tree object
var obj_menu_devices = null;
var contextLocationMenu = null;
var contextBuildingMenu = null;
var contextCameraMenu = null;
var contextCameraItemMenu = null;

// License left tree object
var obj_menu_license = null;
var contextLicenseMenu = null;
var contextLicenseItemMenu = null;

// Usere left tree object
var obj_menu_users = null;
var contextUsersMenu = null;
var contextUserItemMenu = null;
//Global variables -- End

//page Loading functions
$(document).ready(function() {
	g_OnLoadPage();

	// Set variable for each object
	obj_menu_devices = $('#menuDevices');
	obj_menu_license = $('#menuLicense');
	obj_menu_users = $('#menuUsers');
	
	//menu right bar hidden when click iframe
	$('iframe').load(function () {
		$(this).contents().find("body").on('click', function(event) {
		    $("#adminProfile").removeClass('open');
	    });
	});
	// Initialize Main Splitter - 300:*
	$('#mainSplitter').jqxSplitter({ theme: 'classic', width: '100%', height: '100%', panels: [{ size: 300 }] });

	// Initialize Left Panel 
	$("#navContainer").jqxNavigationBar({ theme: 'classic', height: "100%", width: "100%", expandMode: "singleFitHeight",
		initContent: function () {
		     //reporting(chanry) 
		    <?php if ($this->session->userdata('group_id') < 4) {?>
		    InitailizeReportingTree();
		    //
		    
			// Devices Start
			
			 InitializeDevicesTree();
			
		    // Devices End
		    
			// Licenses
			InitializeLicenseTree();
		    // Licenses end
			<?php } ?>
		    //**************************************************************************************************
		    // Users Managememt - Start
		    // Added by Raccoon on 2014-07-03
		    //**************************************************************************************************
		    InitializeUsersTree();

		    // Users Managememt - End
        }
    
	});	
	
    /********************************************************************************************************************************************************/
    // Devices Validation Setting start
    // Create jqxInput
    $("#txtLocationName").jqxInput({ theme: 'classic', width: '300px', height: '25px' });
    $("#txtLocationIP").jqxInput({ theme: 'classic', width: 300, height: 25});
    $("#txtLocationWEBPort").jqxInput({ theme: 'classic', width: '300px', height: '25px', value: '7001'});
    $("#txtLocationRTMPPort").jqxInput({ theme: 'classic', width: '300px', height: '25px', value: '1935'});
    $("#formAddNewLocation").jqxValidator({
        rules: [
                {
		        	input: "#txtLocationName", message: "Location name is required!", action: 'keyup, blur', rule: function (input, commit) {
		            	return input.val() != "";
		            }
		        },
		        {
		        	input: "#txtLocationIP", message: "Location IP address is required!", action: 'keyup, blur', rule: function (input, commit) {
		            	return input.val() != "";
		            }
		        },
		        {
		        	input: "#txtLocationWEBPort", message: "Location WEB port is required!", action: 'keyup, blur', rule: function (input, commit) {
		            	return input.val() != "";
		            }
		        },
		        {
		        	input: "#txtLocationRTMPPort", message: "Location RTMP port is required!", action: 'keyup, blur', rule: function (input, commit) {
		            	return input.val() != "";
		            }
		        }
        ], theme: 'classic'
    });

    $("#btnLocationAdd").click(function () {
    	if ($('#formAddNewLocation').jqxValidator('validate')) {
    		var treeItems = obj_menu_devices.jqxTree('getItems');
        	var firstItem = treeItems[0];
        	var firstItemElement = firstItem.element;
        	
    		$('#formAddNewLocation').ajaxSubmit({
            	success: function(data) {
                	var result = JSON.parse(data);
                	if (result.id == -1) {
                    	errorAlert("<?php echo $this->lang->line('admin.devices.msg.locationExists');?>");
                    	$('#wndAddNewLocation').jqxWindow('close');
                    	return;
                	}
                	infoAlert("<?php echo $this->lang->line('admin.devices.msg.locationAdded');?>");
                	obj_menu_devices.jqxTree('addTo', {icon:ICON_DEVICES_LOCATION, label: result.name, id: "Location_" + result.id}, firstItemElement);
                	$(document).find("li#Location_" + result.id).eq(0).attr("ipaddress", result.ipaddress);
                	$(document).find("li#Location_" + result.id).eq(0).attr("webport", result.webport);
                	$(document).find("li#Location_" + result.id).eq(0).attr("rtmpport", result.rtmpport);
                	// Important
                	attachDeviceContextMenu();
                	$('#formAddNewLocation').find('.needformat').each(function(){
                    	$(this).val('');
                	});
            		$('#wndAddNewLocation').jqxWindow('close');
            	}
        	});
    	}
    });

    // Building form
    $("#txtBuildingName").jqxInput({ theme: 'classic', width: '300px', height: '25px' });
    $("#formAddNewBuilding").jqxValidator({
        rules: [
                {
		        	input: "#txtBuildingName", message: "Building name is required!", action: 'keyup, blur', rule: function (input, commit) {
		            	return input.val() != "";
		            }
		        }
        ], theme: 'classic'
    });

    $("#btnBuildingAdd").click(function () {
    	if ($('#formAddNewBuilding').jqxValidator('validate')) {
    		var treeItem = obj_menu_devices.jqxTree('selectedItem');
			var str_pid = treeItem.id.split('_')[1];
			$('#formAddNewBuilding').attr('action', "<?php echo base_url().'admin/devices/add_building';?>");
			$('#formAddNewBuilding').find("input[name='locationId']").attr('value', str_pid);
    		$('#formAddNewBuilding').ajaxSubmit({
            	success: function(data) {
                	var result = JSON.parse(data);
                	if (result.id == -1) {
                    	errorAlert("<?php echo $this->lang->line('admin.devices.msg.buildingExists');?>");
                		$('#wndAddNewBuilding').jqxWindow('close');
                		return;
                	}
                	infoAlert("<?php echo $this->lang->line('admin.devices.msg.buildingAdded');?>");
                	obj_menu_devices.jqxTree('addTo', {icon: ICON_DEVICES_BUILDING, label: result.name, id: "Building_" + str_pid.split(":")[0] + ":" + result.id}, treeItem.element);
                	// Important
                	attachDeviceContextMenu();
                	$('#formAddNewBuilding').find('.needformat').each(function(){
                    	$(this).val('');
                	});
            		$('#wndAddNewBuilding').jqxWindow('close');
            	}
        	});
    	}
    });

    // Devices Validation Setting end
    // Groups Validation Setting Start
    // Create jqxInput
    $("#txtGroupName").jqxInput({ theme: 'classic', width: '300px', height: '25px' });
    $("#formAddGroup").jqxValidator({
        rules: [
                {
		        	input: "#txtGroupName", message: "Group name is required!", action: 'keyup, blur', rule: function (input, commit) {
		            	return input.val() != "";
		            }
		        }
        ], theme: 'classic'
    });

    // Users Management Validation Setting
    // Create jqxInput.
    $("#txtUserFirstName").jqxInput({ theme: 'classic', width: '300px', height: '25px' });
    $("#txtUserLastName").jqxInput({  theme: 'classic', width: '300px', height: '25px'});
    $("#txtUserLoginname").jqxInput({ theme: 'classic', width: '300px', height: '25px' });
    $("#txtUserEmail").jqxInput({ theme: 'classic', width: '300px', height: '25px' });
    // Create jqxPasswordInput.
    $("#txtUserPwd").jqxPasswordInput({ theme: 'classic', width: '300px', height: '20px', showStrength: true, showStrengthPosition: "right" });
    $("#txtUserConfirmPwd").jqxPasswordInput({ theme: 'classic', width: '300px', height: '20px' });
    
    // Create jqxValidator.
    $("#formAddUser").jqxValidator({
    	rules: [
        	{
            	input: "#txtUserFirstName", message: "First name is required!", action: 'keyup, blur', rule: function (input, commit) {
                	return input.val() != "" && input.val() != "First";
                }
            },
            {
            	input: "#txtUserLastName", message: "Last name is required!", action: 'keyup, blur', rule: function (input, commit) {
                	return input.val() != "" && input.val() != "Last";
                }
            },
            { input: '#txtUserEmail', message: 'Invalid e-mail!', action: 'keyup', rule: 'email' },
            { input: "#txtUserLoginname", message: "Username is required!", action: 'keyup, blur', rule: 'required' },
            { input: "#txtUserPwd", message: "Password is required!", action: 'keyup, blur', rule: 'required' },
            { input: "#txtUserConfirmPwd", message: "Password is required!", action: 'keyup, blur', rule: 'required' },
            {
            	input: "#txtUserConfirmPwd", message: "Passwords should match!", action: 'keyup, blur', rule: function (input, commit) {
                	var firstPassword = $("#txtUserPwd").jqxPasswordInput('val');
                    var secondPassword = $("#txtUserConfirmPwd").jqxPasswordInput('val');
                    return firstPassword == secondPassword;
                }
            }
        ]
	});
   	// Validate the Form.
   	$("#btnUserAdd").click(function () {
    	$('#formAddUser').jqxValidator('validate');
    });
    // Update the Add New User's content if the validation is successful.
    $('#formAddUser').on('validationSuccess', function (event) {
    	$('#formAddUser').find("input#userGroupId").val(<?php echo $this->session->userdata('group_id');?>);
    	$('#formAddUser').ajaxSubmit({
        	success: function(data) {
            	var result = JSON.parse(data);
            	if (result.id == -1) {
            		errorAlert("<?php echo $this->lang->line('admin.users.msg.userExists');?>");
            		$('#wndAddNewUser').jqxWindow('close');
            		return;
            	}
            	var treeItems = obj_menu_users.jqxTree('getItems');
            	var firstItem = treeItems[0];
            	var firstItemElement = firstItem.element;
            	successAlert("<?php echo $this->lang->line('admin.users.msg.userAdded');?>");
            	obj_menu_users.jqxTree('addTo', {icon:"<?php echo HTTP_IMAGES_PATH;?>users.jpg", label: result.name, id: result.id},firstItemElement);
            	// Important
            	attachUserContextMenu();
            	$('#wndAddNewUser').find('.needformat').each(function(){
                	$(this).val('');
            	});
        		$('#wndAddNewUser').jqxWindow('close');
        	}
    	});
    	
	});
	// Users Management Validation Setting End
    /*****************************************************************************************************************************************************/

    //when add camera different function by chanry
	    $("input[name=dnsHostNameChk]:checkbox").change(function(){
	    	 if($(this).is(":checked")) {
	            $("input#address").prop("disabled", true);
	            $("input#txtCameraIP").prop("disabled", false);
	         }else{
	        	 $("input#address").prop("disabled", false);
	        	 $("input#txtCameraIP").prop("disabled", true);
	         }
		});
	    $("input[name=httpRadio]:radio").change(function(){
	    	if($(this).is(":checked")) {
	    		$("input[name=hRtsp]:radio").prop("checked", false);
	    		$("input#httpText").prop("disabled", false);
	    		$("input#rtspText").prop("disabled", false);
	    	}
		});
	    $("input[name=hRtsp]:radio").change(function(){
	    	if($(this).is(":checked")) {
	    		$("input[name=httpRadio]:radio").prop("checked", false);
	    		$("input#httpText").prop("disabled", true);
	    		$("input#rtspText").prop("disabled", true);
	    	}
		});

		//get device model according provider information
		$("select#provider").change(function(){
			var providerId = $(this).val();
			$.ajax({
	 			url: "<?php echo base_url().'admin/devices/get_deviceModelInfo/';?>",
	 		    cache : false,
	 		    dataType : "json",
	 		    type : "POST",
	 		    data : { providerId : providerId },
	 		    success: function(data) {
	 		    	if(data.result == "success"){
	 		    		var deviceModelInfo = [];
	 		    		deviceModelInfo = data.deviceModel;
	 		    		var html = "";
	 		    		for(var i = 0 ; i < deviceModelInfo.length ; i ++){
		 		    		html += "<option value='" + deviceModelInfo[i].modelCode + "'" + ">" + deviceModelInfo[i].modelName + "</option>";
	 		    		}
	 		    		$("select#deviceModel").html( html );
	 		    		$("input#deviceModelName").val(deviceModelInfo[0].modelName);
	 		        }else
	 			    	errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
	 		   	}
	 		});
		});

		$("select#deviceModel").change(function(){
			$("input#deviceModelName").val($("#deviceModel option:selected").text());
		});
		///////////////
});
//pageLoading functions end

//Global Functions -- Start
	// disable the default browser's context menu.
	 $(document).on('contextmenu', function (e) {
	     if ($(e.target).parents('.jqx-tree').length > 0) {
	         return false;
	     }
	     return true;
	 });
	
	 // Check mouse click is right button
	 function isRightClick(event) {
	     var rightclick;
	     if (!event) var event = window.event;
	     if (event.which) rightclick = (event.which == 3);
	     else if (event.button) rightclick = (event.button == 2);
	     return rightclick;
	 }
	
	 // Get Tree Item by id 
	 function GetTreeItemByID(ptr_items, str_id) {
	     var ret_item = null;
	     
	     for (var i = 0; i < ptr_items.length; i ++) {
	         if (ptr_items[i].element.id == str_id) {
	             ret_item = ptr_items[i];
	             break;
	         }
	     }
	
	     return ret_item;
	 }
	 function InitailizeReportingTree(){
		$('#menuReporting').jqxTree({  theme: 'classic', height: '100%', width: '100%', allowDrag:false, allowDrop: false });
		$('#menuReporting').css('visibility', 'visible');
		$("#menuReporting").find("li").each(function(){
			$(this).unbind('click').on('click', function(event) {
	        	var oTarget = $("#menuReporting").jqxTree('selectedItem');
	        	var oRightClick = isRightClick(event);
	        	if (!oRightClick && oTarget != null) {
		        	var str_cur_page = "Reporting_" + oTarget.id;
	        		if (g_str_cur_page != str_cur_page) {
		                document.getElementById("mainFrame").src = "<?php echo base_url().'admin/reporting/reporting_detail';?>";
		                
		                g_str_cur_page = str_cur_page;
	        		}
	        	}
		    });
		});
	}	
		
	 // Attach Context Menu to Devices Tree
	 function attachDeviceContextMenu() {
	    	// open the location context menu when the user presses the mouse right button.
	    	$("#menuDevices li").unbind('mousedown').mousedown( function (event) {
	    		
	        	var oTarget = $(event.target).parents('li:first')[0];
	        	var oRightClick = isRightClick(event);
	        	if (oRightClick && oTarget != null) {
	            	obj_menu_devices.jqxTree("selectItem", oTarget);
	            	var scrollTop = $(window).scrollTop();
	            	var scrollLeft = $(window).scrollLeft();
	            	oTargetObj = obj_menu_devices.jqxTree("getItem", oTarget);
	            	if ( oTargetObj.id == 'devicesroot' ) {
	            		contextBuildingMenu.jqxMenu('close');
	            		contextCameraMenu.jqxMenu('close');
	            		contextCameraItemMenu.jqxMenu('close');
	            		<?php if ($this->session->userdata('group_id') == 2) { ?>
	            		    contextLocationMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	            		<?php }?>
	            	} else if (oTargetObj.id.indexOf('Location') > -1 && oTargetObj.level == 1) {
	            		contextCameraMenu.jqxMenu('close');
	            		contextCameraItemMenu.jqxMenu('close');
	            		<?php if ($this->session->userdata('group_id') == 2) { ?>
	            		contextLocationMenu.jqxMenu('close');
	            		<?php } ?>
	            		contextBuildingMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	                } else if (oTargetObj.id.indexOf('Building') > -1 && oTargetObj.level == 2) {
	                	contextCameraItemMenu.jqxMenu('close');
	                	<?php if ($this->session->userdata('group_id') == 2) { ?>
	            		contextLocationMenu.jqxMenu('close');
	            		<?php } ?>
	            		contextBuildingMenu.jqxMenu('close');
	                	contextCameraMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	                } else if ( oTarget.id.indexOf('Camera') > -1 ) {
	                	<?php if ($this->session->userdata('group_id') == 2) { ?>
	            		contextLocationMenu.jqxMenu('close');
	            		<?php } ?>
	            		contextBuildingMenu.jqxMenu('close');
	            		contextCameraMenu.jqxMenu('close');
	                	contextCameraItemMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	                }
	                return false;
	            }
	        });
	    	$("#menuDevices li").unbind('click').on('click', function (event) {
	    		var oTarget = obj_menu_devices.jqxTree('selectedItem');
	        	var oRightClick = isRightClick(event);
	        	if (!oRightClick && oTarget != null) {
		        	var str_cur_page = "Devices_" + oTarget.id;
		        	var str_tmp_ids = oTarget.id.split(":");
	        		if (g_str_cur_page != str_cur_page) {
		        		if (str_tmp_ids[0] == 'devicesroot') {
		                	document.getElementById("mainFrame").src = "about:blank";
		                } else{
		                	if ( oTarget.id.indexOf('Location') > -1 ){
			                	//if (oTarget.level == 1)
		                		document.getElementById("mainFrame").src = "<?php echo base_url().'admin/devices/location_detail/';?>"+oTarget.id.split('_')[1];
			                } else if (oTarget.id.indexOf('Building') > -1){
			                	var str_tmp_pid = oTarget.id.split('_')[1];
			                	document.getElementById("mainFrame").src = "<?php echo base_url().'admin/devices/building_detail/';?>"+str_tmp_pid.split(":")[1];				                
		                	} else if (oTarget.id.indexOf('Camera') > -1 ) {
		                		document.getElementById("mainFrame").src = "<?php echo base_url().'admin/devices/camera_detail/';?>"+oTarget.id.split('_')[1];
		                	}
		                }
		                g_str_cur_page = str_cur_page;
	        		}
	        	}
	    	});
		}
	
	function OnDeviceTreeDragStart(item) {
		if (item.id == 'devicesroot' || item.id.indexOf('Location') > -1 || item.id.indexOf('Building') > -1 ) {
			return false; 
		}
		
		// return true;
	}

	function OnDropTreeDragEnd(item, dropItem, args, dropPosition, tree) {		
		if ( dropItem.id == 'devicesroot') { 	// if drop target is root, return false 
			return false;
		}
		return false;
	}
		
	function InitializeDevicesTree() {
		// Devices Start
		obj_menu_devices.jqxTree({  theme: 'classic', height: '100%', width: '100%',
			dragStart: OnDeviceTreeDragStart,
            dragEnd: OnDropTreeDragEnd
		});
		obj_menu_devices.css('visibility', 'visible');
		 <?php if ($this->session->userdata('group_id') == 2) {?>
		contextLocationMenu = $("#contextLocations").jqxMenu({ theme: 'classic', width: '140px',  height: '30px', autoOpenPopup: false, mode: 'popup' });
		<?php } ?>
		contextBuildingMenu = $("#contextBuildings").jqxMenu({ theme: 'classic', width: '140px',  height: '58px', autoOpenPopup: false, mode: 'popup' });
		contextCameraMenu = $("#contextCameras").jqxMenu({ theme: 'classic', width: '140px',  height: '58px', autoOpenPopup: false, mode: 'popup' });
		contextCameraItemMenu = $("#contextCameraItem").jqxMenu({ theme: 'classic', width: '140px',  height: '30px', autoOpenPopup: false, mode: 'popup' });
		
		attachDeviceContextMenu();

		$(".devicecontextmenu").unbind('itemclick').on('itemclick', function (event) {
	        var item = $.trim($(event.args).attr('btn_cmd'));
	        switch (item) {
	        <?php if ($this->session->userdata('group_id') == 2) {?>
	            case "addlocation":
	                var selectedItem = obj_menu_devices.jqxTree('selectedItem');
	                if (selectedItem != null) {
	                	// Get selected item on the left tree
		                var selectedItem = obj_menu_devices.jqxTree('selectedItem');
		                if (selectedItem != null) {
			                // Check Add New Location window is loaded or not 
			                var isInitialized = $('#wndAddNewLocation').attr('initialized');
			                if (isInitialized == 'loaded') {	// If Add New User window is loaded, open it 
			                	$('#wndAddNewLocation').jqxWindow('open');
			                } else {
			                	$('#wndAddNewLocation').attr("style","");

			                	// Initialize Add New User window
			                	$('#wndAddNewLocation').jqxWindow({
			                		theme: 'classic', maxHeight: 450, maxWidth: 350, minHeight: 30, minWidth: 250, height: 270, width: 340,
			                        resizable: false, isModal: true, modalOpacity: 0.3,
			                        cancelButton: $('#btnLocationCancel'), draggable: false,
			                        initContent: function () {
			                            $('#btnLocationAdd').jqxButton({ width: '65px' });
			                            $('#btnLocationCancel').jqxButton({ width: '65px' });
			                            $('#btnLocationAdd').focus();
			                        }
			                    });
			                    // Close all validator labels when main window closed
			                	$('#wndAddNewLocation').on('close', function() {
			                		$("#formAddNewLocation").jqxValidator('hide');
			                	});
			                	// Set initialized variable
			                	$('#wndAddNewLocation').attr('initialized', 'loaded');
			                	
			                }
		                }
	                }
	                break;
	            case "removelocation":
	                var selectedItem = obj_menu_devices.jqxTree('selectedItem');
	                if (selectedItem != null) {
		                var str_id = selectedItem.id;
	                    $.ajax({
		                    url: "<?php echo base_url().'admin/devices/delete_location/';?>" + str_id.split("_")[1],
		                    success: function(data) {
			                    obj_menu_devices.jqxTree('removeItem', selectedItem);
			                    if (g_str_cur_page == ("Devices_" + str_id)) {
			                    	document.getElementById("mainFrame").src = "about:blank";
			                    	g_str_cur_page = "";
			                    }
			                    // Important
			                    attachDeviceContextMenu();
			                    successAlert ("<?php echo $this->lang->line('admin.devices.msg.deletedevice.success');?>");
		                    }
	                    });
	                }
	                break;
	                <?php } if ($this->session->userdata('group_id') < 4) {?>
	            case "addbuilding":
	                var selectedItem = obj_menu_devices.jqxTree('selectedItem');
	                if (selectedItem != null) {
	                    var str_pid = selectedItem.id.split('_')[1];
	                    
	                    var isInitialized = $('#wndAddNewBuilding').attr('initialized');
		                if (isInitialized == 'loaded') {	// If Add New User window is loaded, open it 
		                	$('#wndAddNewBuilding').jqxWindow('open');
		                } else {
		                	$('#wndAddNewBuilding').attr("style","");

		                	// Initialize Add New User window
		                	$('#wndAddNewBuilding').jqxWindow({
		                		theme: 'classic', maxHeight: 450, maxWidth: 350, minHeight: 30, minWidth: 250, height: 150, width: 340,
		                        resizable: false, isModal: true, modalOpacity: 0.3,
		                        cancelButton: $('#btnBuildingCancel'), draggable: false,
		                        initContent: function () {
		                            $('#btnBuildingAdd').jqxButton({ width: '65px' });
		                            $('#btnBuildingCancel').jqxButton({ width: '65px' });
		                            $('#btnBuildingAdd').focus();
		                        }
		                    });
		                    // Close all validator labels when main window closed
		                	$('#wndAddNewBuilding').on('close', function() {
		                		$("#formAddNewBuilding").jqxValidator('hide');
		                	});
		                	// Set initialized variable
		                	$('#wndAddNewBuilding').attr('initialized', 'loaded');
		                }
	                }
	                break;
	            case "removebuilding":
	                var obj_selecteditem = obj_menu_devices.jqxTree('selectedItem');
	                
	                if (obj_selecteditem != null) {
	                	var str_id = obj_selecteditem.id;
	                    $.ajax({
		                    url: "<?php echo base_url().'admin/devices/delete_building/';?>" + str_id.split('_')[1].split(":")[1],
		                    success: function(data) {
			                    var str_buildingid = obj_selecteditem.id;
			                    $("[id='" + str_buildingid + "']").remove();    
			                    if (g_str_cur_page == ("Devices_" + str_id)) {
			                    	document.getElementById("mainFrame").src = "about:blank";
			                    	g_str_cur_page = "";
			                    }
			                    // Important
			                    attachDeviceContextMenu();
			                    successAlert("<?php echo $this->lang->line('admin.devices.msg.deletedevice.success');?>");
		                    }
	                    });
	                }
	                break;
	            case "addcamera":
		            // Added by KCH
		            var treeItem = obj_menu_devices.jqxTree('selectedItem');
					var str_pid = treeItem.id.split('_')[1];
					var str_locationId = str_pid.split(":")[0];
					var str_buildingId = str_pid.split(":")[1];
					document.getElementById("mainFrame").src = "<?php echo base_url();?>admin/devices/go_adddevice?lid=" + str_locationId + "&bid=" + str_buildingId;
		            // Commented by KCH
	                /* var selectedItem = obj_menu_devices.jqxTree('selectedItem');
	                if (selectedItem != null) {
	                	var selectedItem = obj_menu_devices.jqxTree('selectedItem');
		                if (selectedItem != null) {
		                    var str_pid = selectedItem.id.split('_')[1];
		                    var isInitialized = $('#wndAddNewCamera').attr('initialized');
			                if (isInitialized == 'loaded') {	// If Add New User window is loaded, open it 
			                	$('#wndAddNewCamera').jqxWindow('open');
			                } else {
			                	$('#wndAddNewCamera').attr("style","");

			                	// Initialize Add New User window
			                	$('#wndAddNewCamera').jqxWindow({
			                		theme: 'classic', maxHeight: 450, maxWidth: 950, minHeight: 30, minWidth: 250, height: "auto", width: 760,
			                        resizable: false, isModal: true, modalOpacity: 0.3,
			                        cancelButton: $('#btnCameraCancel'), draggable: false,
			                        initContent: function () {
			                            $('#btnCameraAdd').jqxButton({ width: '65px' });
			                            $('#btnCameraCancel').jqxButton({ width: '65px' });
			                            $('#btnCameraAdd').focus();
			                        }
			                    });
			                	$('#wndAddNewCamera').css;
			                    // Close all validator labels when main window closed
			                	$('#wndAddNewCamera').on('close', function() {
			                		$("#formAddNewCamera").jqxValidator('hide');
			                	});
			                	// Set initialized variable
			                	$('#wndAddNewCamera').attr('initialized', 'loaded');
			                }
		                }
	                } */
	                break;
	            case "removecamera":
	            	var selectedItem = obj_menu_devices.jqxTree('selectedItem');
	            	var selectedLocationObj = $(document).find("#Location_"+selectedItem.parentId.split("_")[1].split(":")[0]);
	                if (selectedItem != null) {
	                	var str_id = selectedItem.id.split("_")[1];
	                    $.ajax({
		                    url: "<?php echo base_url().'admin/devices/delete_camera/';?>",
		                    cache : false,
				 		    dataType : "json",
				 		    type : "POST",
				 		    data : { str_id : str_id },
		                    success: function(data) {
		                    	if(data.result == "success"){
				                    var systemIndex = data.systemIndexId;
				                    obj_menu_devices.jqxTree('removeItem', selectedItem);
				                    if (g_str_cur_page == ("Devices_" + str_id)) {
				                    	document.getElementById("mainFrame").src = "about:blank";
				                    	g_str_cur_page = "";
				                    }
				                    // Important
				                    attachDeviceContextMenu();

				                    //send api to remove from nvr server
							    	var serverIpAddress = selectedLocationObj.attr("ipaddress");
							    	var webPort = selectedLocationObj.attr("webport");
							    	
							    	$.ajax({
							 			url: "http://"+ serverIpAddress + ":" + webPort + "/grcenter.hardwareDevice.removeCameraInfo.nsf",
							 		    cache : false,
							 		    dataType : "json",
							 		    type : "POST",
							 		    data : { systemIndex : systemIndex },
							 		    success: function(data) {
							 		    	if(data.success == "success"){
								 		    	successAlert("<?php echo $this->lang->line('admin.devices.msg.deletedevice.success');?>");
							 		    	}else
							 		    		errorAlert("<?php echo $this->lang->line('admin.devices.msg.deletedevice.failed');?>");
							 		   	}
							 		});
			                    }else
				                    errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
		                    }
	                    });
	                }
	                break;
	                <?php } ?>
	        }
	    });
	    // Devices tree expand event 
		obj_menu_devices.unbind('expand').on('expand', function(event) {
		    // Check users tree is initialized or not 
		    var str_initialized = obj_menu_devices.attr('initialized');
		    if (str_initialized == 'loaded') {
		    } else {	// If not initialized, load all child items from database
			    // Display resource loading animation
			    $(this).find('.resourceloading').css('display','inline');
			    $(this).find('ul').eq(0).find('ul').remove();
			    $.ajax({
				    url: "<?php echo base_url().'admin/devices/get_all_locations'; ?>",
				    type: 'POST',
				    success: function(data) {
					    var result = JSON.parse(data);
				    	var treeItems = obj_menu_devices.jqxTree('getItems');
		            	var firstItem = treeItems[0];
		            	var firstItemElement = firstItem.element;
		            	
						var ptr_locations = result['locations'];
						if ((ptr_locations.length*1) < 1 || !ptr_locations.length) {
							obj_menu_devices.find('.resourceloading').css('display','none');
			            	attachDeviceContextMenu();
			            	obj_menu_devices.attr('initialized', 'loaded');
							return;
						}
						<?php if ($this->session->userdata('group_id') < 4) { ?>
		            	for (var i = 0; i < ptr_locations.length; i ++) {
		            	    
    			            	var str_parent = "";
    			            	var str_icon = ICON_DEVICES_LOCATION;
    		            		obj_menu_devices.jqxTree(
    				            		'addTo', 
    				            		{ icon: str_icon, label: ptr_locations[i].name, id: "Location_"+ptr_locations[i].id },
    				            		firstItemElement
    				            		);
    		            		$("#Location_" + ptr_locations[i].id).attr("ipaddress", ptr_locations[i].ipaddress);
    		                	$("#Location_" + ptr_locations[i].id).attr("webport", ptr_locations[i].webport);
    		                	$("#Location_" + ptr_locations[i].id).attr("rtmpport", ptr_locations[i].rtmpport);
						}
		            	treeItems = obj_menu_devices.jqxTree('getItems');
		            	var ptr_buildings = result['buildings'];
		            	for (var i = 0; i < ptr_buildings.length; i ++) {
		            		var str_parent = "Location_"+ptr_buildings[i].locationId;
		            		var str_icon = ICON_DEVICES_BUILDING;
		            		var ptr_parent = GetTreeItemByID(treeItems, str_parent);
		            		if ( ptr_parent != null ) { 
		            			obj_menu_devices.jqxTree(
				            		'addTo', 
				            		{ icon: str_icon, label: ptr_buildings[i].name, id: "Building_" +ptr_buildings[i].locationId + ":" + ptr_buildings[i].id },
				            		ptr_parent.element
				            		);
		            		}
		            	}
		            	treeItems = obj_menu_devices.jqxTree('getItems');
		            	var ptr_cameras = result['cameras'];
		            	for (var i = 0; i < ptr_cameras.length; i ++) {
		            		var str_parent = "";
		            		if (ptr_cameras[i].buildingId * 1 == -1) {
		            			str_parent = "Location_" + ptr_cameras[i].locationId;
			            	} else {
			            		str_parent = "Building_" + ptr_cameras[i].locationId + ":" + ptr_cameras[i].buildingId
			            	}
		            		var str_icon = ICON_DEVICES_CAMERA;
		            		var ptr_parent = GetTreeItemByID(treeItems, str_parent);
		            		if (ptr_parent == null ) {
			            		ptr_parent = firstItem;
		            		}
	            			obj_menu_devices.jqxTree(
				            		'addTo', 
				            		{ icon: str_icon, label: ptr_cameras[i].videoInName, id: "Camera_" + ptr_cameras[i].id },
				            		ptr_parent.element
				            		);
		            	}
		            	<?php } ?>
		            	obj_menu_devices.find('.resourceloading').css('display','none');
		            	attachDeviceContextMenu();
		            	obj_menu_devices.attr('initialized', 'loaded');
				    },
				    error: function(data) {
					    
				    }
				});
		    	
		    }
	    });

	}

	// Initialize Groups Tree
	function InitializeLicenseTree() {
		obj_menu_license.jqxTree({  theme: 'classic', height: '100%', width: '100%',allowDrag:false, allowDrop: false,
			dragStart: function (item) {
				return false;
            },
            dragEnd: function (item, dropItem, args, dropPosition, tree) {
				return false;
            }
        });
		
		obj_menu_license.css('visibility', 'visible');
		var str_license_initialized = obj_menu_license.attr('initialized');
		if (str_license_initialized == 'loaded') {
	    } else {	// If not initialized, load all child items from database
	    	
	    }
	    
		//contextLicenseMenu = $("#contextLicense").jqxMenu({ theme: 'classic', width: '140px',  height: '30px', autoOpenPopup: false, mode: 'popup' });
		//contextLicenseItemMenu = $("#contextLicenseItem").jqxMenu({ theme: 'classic', width: '150px',  height: '30px', autoOpenPopup: false, mode: 'popup' });

		attachLicenseContextMenu();

		//$(".licensecontextmenu").unbind('itemclick').on('itemclick', function (event) {
	      //  var item = $.trim($(event.args).attr('btn_cmd'));
	    //});
	}
	 // Attach Context Menu to Groups Tree
	 function attachLicenseContextMenu() {
			obj_menu_license.find("li").each(function(){
				$(this).unbind('mousedown').mousedown( function (event) {
		        	var oTarget = $(event.target).parents('li:first')[0];
		        	
		        	var oRightClick = isRightClick(event);
		        	//if (oRightClick && oTarget != null) {
		        	//	obj_menu_license.jqxTree("selectItem", oTarget);
		            ///	var scrollTop = $(window).scrollTop();
		           // 	var scrollLeft = $(window).scrollLeft();
		           // 	if ( oTarget.id == 'licenseroot' ) {
		            //		contextLicenseMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
		            //		contextLicenseItemMenu.jqxMenu('close');
		            //	} else {
		            //		contextLicenseMenu.jqxMenu('close');
		            //		contextLicenseItemMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
		            //    }
		             //   return false;
		            // }*/
		        });
				$(this).unbind('click').on('click', function(event) {
		        	var oTarget = obj_menu_license.jqxTree('selectedItem');
		        	var oRightClick = isRightClick(event);
		        	if (!oRightClick && oTarget != null) {
			        	var str_cur_page = "License_" + oTarget.id;
		        		if (g_str_cur_page != str_cur_page) {
			        		if (oTarget.id == 'licenseroot') {
			                	document.getElementById("mainFrame").src = "<?php echo base_url().'admin/license/';?>";
			                } else {
			            }
			                g_str_cur_page = str_cur_page;
		        		}
		        	 }
			    });
			});
		}


	 // Initialize Users Tree
	function InitializeUsersTree() {
		// Left side bar users tree 
		obj_menu_users.jqxTree({  theme: 'classic', height: '100%', width: '100%',allowDrag:false, allowDrop: false});
		obj_menu_users.css('visibility', 'visible');

		// Users tree context menu of Root item
		contextUsersMenu = $("#contextUsers").jqxMenu({ theme: 'classic', width: '140px',  height: '30px', autoOpenPopup: false, mode: 'popup' });
		// Users tree context menu of Child items
		contextUserItemMenu = $("#contextUserItem").jqxMenu({ theme: 'classic', width: '140px',  height: '30px', autoOpenPopup: false, mode: 'popup' });

		// Attach context menu - Call on load Users tab
		attachUserContextMenu();

		// Users context menu click event 
		$(".usercontextmenu").unbind('itemclick').on('itemclick', function (event) {
	        var item = $.trim($(event.args).attr('btn_cmd'));
	        switch (item) {
	            case "adduser":	// Add new user 
		            // Get selected item on the left tree
	                var selectedItem = obj_menu_users.jqxTree('selectedItem');
	                if (selectedItem != null) {
		                // Check Add New User window is loaded or not 
		                var isInitialized = $('#wndAddNewUser').attr('initialized');
		                if (isInitialized == 'loaded') {	// If Add New User window is loaded, open it 
		                	$('#wndAddNewUser').jqxWindow('open');
		                } else {
		                	$('#wndAddNewUser').attr("style","");

		                	// Initialize Add New User window
		                	$('#wndAddNewUser').jqxWindow({
		                		theme: 'classic', maxHeight: 450, maxWidth: 350, minHeight: 30, minWidth: 250, height: 400, width: 340,
		                        resizable: false, isModal: true, modalOpacity: 0.3,
		                        cancelButton: $('#btnUserCancel'), draggable: false,
		                        initContent: function () {
		                            $('#btnUserAdd').jqxButton({ width: '65px' });
		                            $('#btnUserCancel').jqxButton({ width: '65px' });
		                            $('#btnUserAdd').focus();
		                        }
		                    });
		                    // Close all validator labels when main window closed
		                	$('#wndAddNewUser').on('close', function() {
		                		$("#formAddUser").jqxValidator('hide');
		                	});
		                	// Set initialized variable
		                	$('#wndAddNewUser').attr('initialized', 'loaded');
		                	
		                }
	                }
	                break;
	            case "removeuser":	// Remove selected user
	                var selectedItem = obj_menu_users.jqxTree('selectedItem');
	                if (selectedItem != null) {
	                    var str_id = selectedItem.id;
	                    $.ajax({
		                    url: "<?php echo base_url().'admin/users/delete_user/';?>" + str_id,
		                    type: "GET",
		                    success: function(data) {
			                    obj_menu_users.jqxTree('removeItem', selectedItem);
			                    if (g_str_cur_page == ("Users_" + str_id)) {
			                    	document.getElementById("mainFrame").src = "about:blank";
			                    	g_str_cur_page = "";
			                    }
			                    successAlert("<?php echo $this->lang->line('admin.users.msg.userRemoved');?>");
			                    attachUserContextMenu();
		                    }
						});	                    
	                }
	                break;
	        }
	    });
		obj_menu_users.unbind('click').on('click', function(event) {
			var str_initialized = obj_menu_users.attr('initialized');
		    if (str_initialized == 'loaded') {
		    	event.preventDefault();
		    	event.stopPropagation();
		    	return false;
		    }
		});
		// Users tree expand event 
	    obj_menu_users.unbind('expand').on('expand', function(event) {
		    // Check users tree is initialized or not 
		    var str_initialized = obj_menu_users.attr('initialized');
		    if (str_initialized == 'loaded') {
		    } else {	// If not initialized, load all child items from database
			    // Display resource loading animation
			    $(this).find('.resourceloading').css('display','inline');
			    $(this).find('ul').eq(0).find('ul').remove();

			    $.ajax({
				    url: "<?php echo base_url().'admin/users/get_all_users'; ?>",
				    type: 'post',
				    success: function(data) {
					    var result = JSON.parse(data);
				    	
		            	if (result == null) {
		            		obj_menu_users.find('.resourceloading').css('display', 'none');
		            		obj_menu_users.attr('initialized', 'loaded');
			            	return;
		            	}
		            	var treeItems = obj_menu_users.jqxTree('getItems');
		            	var firstItem = treeItems[0];
		            	var firstItemElement = firstItem.element;
		            	
		            	for (var i = 0; i < result.length; i ++) {
		            		obj_menu_users.jqxTree('addTo', {icon:"<?php echo HTTP_IMAGES_PATH;?>users.jpg", label: result[i].user_name, id: result[i].id},firstItemElement);
		            	}
		            	obj_menu_users.find('.resourceloading').css('display', 'none');
					    obj_menu_users.attr('initialized', 'loaded');

					    // Attach context menu event for each child item
					    attachUserContextMenu();
				    },
				    error: function(data) {
					    
				    }
				});
		    	
		    }
	    });
	}

	// Attach context menu to the items - Define
	// Important : When add or remove a child item, you must call this function for context menu
	function attachUserContextMenu() {
    	// open the location context menu when the user presses the mouse right button.
    	obj_menu_users.find("li").each(function() {
	    	$(this).unbind('mousedown').on('mousedown', function (event) {
	        	var oTarget = $(event.target).parents('li:first')[0];
	        	
	        	var oRightClick = isRightClick(event);
	        	if (oRightClick && oTarget != null) {
	        		obj_menu_users.jqxTree("selectItem", oTarget);

	            	var scrollTop = $(window).scrollTop();
	            	var scrollLeft = $(window).scrollLeft();
	            	if ( oTarget.id == 'usersroot' ) {// If Root 
	            		contextUsersMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	            		contextUserItemMenu.jqxMenu('close');
	            	} else { // Child
	            		contextUsersMenu.jqxMenu('close');
	            		contextUserItemMenu.jqxMenu('open', parseInt(event.clientX) + 5 + scrollLeft, parseInt(event.clientY) + 5 + scrollTop);
	                }
	                return false;
	            }
	        });
	        $(this).unbind('click').on('click', function(event) {
	        	var oTarget = obj_menu_users.jqxTree('selectedItem');
	        	var oRightClick = isRightClick(event);
	        	if (!oRightClick && oTarget != null) {
		        	var str_cur_page = "Users_" + oTarget.id;
	        		if (g_str_cur_page != str_cur_page) {
		        		if (oTarget.id == 'usersroot') {
		                	document.getElementById("mainFrame").src = "about:blank";
		                } else {
		                	document.getElementById("mainFrame").src = "<?php echo base_url().'admin/users/user_detail/';?>"+oTarget.id;
		                }
		                g_str_cur_page = str_cur_page;
	        		}
	        	}
		    });
    	});
	}

	function NewDeviceAdded ( obj_data ) {
		var treeItem = obj_menu_devices.jqxTree('selectedItem');
		obj_menu_devices.jqxTree('addTo', {icon:ICON_DEVICES_CAMERA, label: obj_data.name, id: "Camera_" + obj_data.id}, treeItem.element);
     	// Important
     	attachDeviceContextMenu();
	}
//Global Functions -- End
</script>