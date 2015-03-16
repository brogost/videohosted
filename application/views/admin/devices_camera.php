<?php 
/*
 *************************************************************************
 * @filename	: devices_camera.php
 * @description	: Details of Camera
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.26   Chanry      Initial
 * 		2014.10.15	 Gao		 Modify
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<html>
    <head>
        <title></title>
        <style>
        	#activeXTable { border:0px; border-color:#0000ff; border-style:solid; border-width:0px; z-index:2000; width:100%;height:100%; position: absolute; margin: 0px;padding:0px; width: 100%;}
        	#mDrawContainer { border: none;width: 500px; height: 271px; display: inline-block; position: relative; }
        	.motionDiv { position: absolute;border: 1px solid #FF0000; font-size: 0px; background-color: #FF0000;opacity: 0.3; }
        	.motionDiv.active { background-color: #FF0000;opacity: 0.8;border-color: #FFFFFF; }
        	.list-group-item.arealist { padding-top: 2px; padding-bottom: 2px; padding-left: 15px; cursor: pointer;}
        	.list-group.areanames { font-size: 12px; text-align: center;}
        	#areaList {height: 150px; display: inline-block; width: 100%; overflow: auto; };
        	.motionForm{ display: none; }
        </style>
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
        <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
    
        <link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'bootstrap.css';?>" type="text/css" />
         <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
         
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'tab.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'motion.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.bootstrap.css';?>" media="screen">
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'spin.js';?>"></script>
        <link rel="stylesheet" href="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.css" >
        <script type="text/javascript" src="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.js" ></script>  
        <script type="text/javascript">

        var fn_GetCurrentPage = function() {
            var str_page = 0;
            $("#deviceCameraTab").find("li").each(function(){
				if ( $(this).hasClass('active') ) {
					str_page = $(this).attr('page');
				}                
            });
        } 
        var g_bDrawSelect = false;
        var g_startPos = "";
        var g_curPos = "";
        var g_endPos = "";
        var colorTable = new Array(7*24);
        var g_SelectedColor = "";
        var g_ColorArray = new Array(12);
		var motionDetect = MOTIONDETECT_AREA("<?php echo $cameraInfo[0]->videoInIndex;?>");
		
		motionDetect.setServerIP( "<?php echo $location->ipaddress; ?>" );
		motionDetect.setHttpPort( "<?php echo $location->webport; ?>" );
		motionDetect.setBaseUrl( "<?php echo base_url(); ?>" );
        
		var g_CurrentPage = 0;
        scheduleOpts = {
  			  lines: 7, // The number of lines to draw
  			  length: 3, // The length of each line
  			  width: 3, // The line thickness
  			  radius: 3, // The radius of the inner circle
  			  corners: 1, // Corner roundness (0..1)
  			  rotate: 0, // The rotation offset
  			  direction: 1, // 1: clockwise, -1: counterclockwise
  			  color: '#333', // #rgb or #rrggbb or array of colors
  			  speed: 1, // Rounds per second
  			  trail: 60, // Afterglow percentage
  			  shadow: false, // Whether to render a shadow
  			  hwaccel: false, // Whether to use hardware acceleration
  			  className: 'spinner', // The CSS class to assign to the spinner
  			  zIndex: 2e9, // The z-index (defaults to 2000000000)
  			  top: '0px', // Top position relative to parent in px
  			  left: '100%', // Left position relative to parent in px
  			};
            $(document).ready(function() {            	
            	
                // Motion detection setting page
                $("#mSensitivity").jqxSlider({ theme: 'bootstrap', showButtons: false, value: 50, mode: 'fixed', width: '100%', min: 0, max: 255 });
                $("#mSensitivity").on("change", function(evt) {
                	$("#sensVal").html( evt.args.value );    
                	// $("#mProgressBar").css("width", evt.args.value + "%");
                });
                $("#mThreshold").jqxSlider({ theme: 'bootstrap', showButtons: false, value: 5, mode: 'fixed', width: '100%', min: 0, max: 100 });
                $("#mThreshold").on('change', function ( evt ) {
                    $("#thresVal").html( evt.args.value );
                    $("#mStandardBar").css('left', evt.args.value + "%");
                }); 
                
            	//map description input value check
            	$("input#mapDescription").on('input', function () {
        			$(this).parents("div.form-group").eq(0).removeClass("has-error");
        			if ($(this).val() == "") {
        				$(this).parents("div.form-group").eq(0).addClass("has-error");
        			}
    			});
                var gmapId = '';
                // Added by KCH
                $(".ptzurl").each(function() {
					$(this).attr("disabled", "disabled");
				});
				
				$('#ptzEnable').change(function(){
					var bool_checked = this.checked;
					
					$(".ptzurl").each(function() {
						$(this).attr("disabled", !bool_checked);
					});	
					$(this).attr('value', bool_checked == true?"1":"0");
				});

				$("#audioEnable").change(function () {
					var bool_checked = this.checked;
					$(this).attr('value', bool_checked==true?"1":"0");
				});
				/// End
                $("#mapImageUpload").click(function(){
                	var description = $("#mapDescription").val();
                	
                	if(description == ""){
                		 $("input#mapDescription").parents("div.form-group").eq(0).addClass("has-error");
                		 return;
                	}
                	
                	$("#uploadDescription").val(description);
	            		//var target = document.getElementById('loadingBar');
	            		//var spinner = new Spinner(opts).spin(target);
	            		//$( "#loadingBar" ).removeClass("hide");
            		$("input#fileUpload").parents("form").ajaxForm({
            			 success: function(data) {
            				if(data.result == "success"){
            					originFileName = data.origin_name;
            					gmapId = data.gmapId;
            					fullPath = "<?php echo base_url().'assets/uploads/gmap/';?>" + data.upload_data['file_name'];
            					var html = "<div class='map-image' id='"+ gmapId +"'><img src = '"+ fullPath +"' onclick='onShowMapImageDetail(this)'><p class='text-center'>"+ description +"</div>";
            					var currentWidth = $(".map-image-wrap").width();
            					$(".map-image-wrap").width(currentWidth + 120);
            					$(".map-image-wrap").append(html);
            					$("#mapDescription").val("");
            					$("#fileUpload").val("");
            					//spinner.stop();
            				}else if(data.result == 'exist'){
	            				infoAlert("exist");
            				}
            			 } 
            		}).submit();
                });
                var mapWrapObj = $('div.map-image-lg');
                
				//tab click function                
                $('#deviceCameraTab a').click(function(e){
        			e.preventDefault();
        			$(this).tab('show');
        			if ($(this).parent().attr('page') != 'motion') {
            			// $(".motionDiv").hide();
            			motionDetect.unload();
        			} else {
        				// $(".motionDiv").show();
        				motionDetect.init();
        			}
                	if($(this).attr("href") == "#parentMapPlacement"){
    					var imgObj = $("#parentMapPlacement").find("img#mapImage");
    					var cameraId = <?php echo $cameraId ?>;
    					$.ajax({
    			 			url: "<?php echo base_url().'admin/devices/get_cameraMapInfo/';?>",
    			 		    cache : false,
    			 		    dataType : "json",
    			 		    type : "POST",
    			 		    data : { cameraId : cameraId },
    			 		    success: function(data) {
    			 		    	if(data.result == "success"){
    				 		    	var mapInfo = data.cameraInfo;
    				 		        for( var i = 0 ; i < mapInfo.length ; i ++ ){
    				 		        		$("div.building-pins-wrap").find("img").eq(i).jqxDragDrop({restricter: mapWrapObj, dragZIndex: 999});
    				 		                var topPos = imgObj.offset().top + (mapInfo[i].posy * imgObj.height() / 100) - 32;
    				 		                var leftPos = imgObj.offset().left + (mapInfo[i].posx * imgObj.width() / 100) - 16;
    				 		                $("div.building-pins-wrap").find("img").eq(i).offset({ top: topPos, left: leftPos });
    				 		        }
    			 		        }else
    			 		        	errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
    			 		   	}
    			 		});
                	}
                });	

                //on save stram profile
                $("div#streamProfile").find("button#saveProfile").click(function(){
                	if($('#streamProfile').find('#streamProfileForm').jqxValidator('validate')){
                		$('#streamProfile').find('#streamProfileForm').ajaxForm({
               			 success: function(data) {
               				if(data.result == "success"){
                   				successAlert("<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>");
               					//spinner.stop();
               				}else if(data.result == 'exist'){
   	            				infoAlert("exist");
               				}
               			 } 
               			}).submit();
                	}
                });

                //retreive schedule informations
                //var target = document.getElementById('scheduleLoadingBar');
                //var spinner1 = new Spinner(scheduleOpts).spin(target);
                //$("div#loadingBarBackground").removeClass("hide");
                $.ajax({
		 			url: "<?php echo 'http://'.$location->ipaddress.':'.$location->webport.'/grcenter.hardwareDevice.retrieveSchedule.nsf';?>",
		 		    cache : false,
		 		    dataType : "json",
		 		    type : "POST",
		 		    data : { videoinindex : <?php echo $cameraInfo[0]->videoInIndex;?>},
		 		    success: function(data) {
		 		    	if (data.result == "success") {
		 		    		var strweek = data.nSchedule.recordscheduleweek;
		 		    		k = 0;
		 		    		for (var i = 1; i <= 7; i ++) {
		 		    			for (var j = 1; j <= 24; j ++) {
		 		    				if (strweek != null)
		 		    					colorTable[i*24+j-1] = strweek.substr(k, 1);
		 		    				else
		 		    					colorTable[i*24+j-1] = "1";
		 		    				k ++;
		 		    			}
		 		    		}
		 		    		$("select#recMode").val(data.nSchedule.recordmode);
		 		    		$("select#selPreMotion").val(data.nSchedule.premotion);
		 		    		$("select#selPostMotion").val(data.nSchedule.postmotion);
		 		    		if (data.nSchedule.premotion != '0') {
		 		    		    $("input#chkPreRecording").prop("checked", true);
		 		    		}
		 		    		if (data.nSchedule.postmotion != '0') {
		 		    		    $("input#chkPostRecording").prop("checked", true);
		 		    		}
		 		    		
		 		    		$("input#hiScheduleWeek").val(data.nSchedule.recordscheduleweek);
		 		    		$("input#hiSelPreMotion").val(data.nSchedule.premotion);
		 		    		$("input#hiSelPostMotion").val(data.nSchedule.postmotion);
		 		    		$("input#hiRecmode").val(data.nSchedule.recordmode);
		 		    		
		 		    		init(strweek);
		 		        } else
		 		        	errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
		 		   	}
		 		});
            });

            //pin position save  
            function onPositionSave(){
            	var cameraTopRate = [];
            	var cameraLeftRate = [];
            	var attr = 1;
            	
            	$("div.building-pins-wrap").find("img").each(function(){
            		var screenImageWidth = $("img#mapImage").width();
                	var screenImageHeight = $("img#mapImage").height();
                	
            		var pinCameraLeftPos = $(this).offset().left - $("img#mapImage").offset().left + 16;
            		var pinCameraTopPos = $(this).offset().top - $("img#mapImage").offset().top + 32;

            		cameraLeftRate[$(this).attr("id")] = 100 * (pinCameraLeftPos / screenImageWidth).toFixed(2);
            		cameraTopRate[$(this).attr("id")] = 100 * (pinCameraTopPos / screenImageHeight).toFixed(2);
            	});
            	
				var gmapId = $("img#mapImage").parent().attr("id");
				var buildingId = <?php echo $cameraInfo[0]->buildingId ?>;
				//image real size calculate 
            	var imgObj = $("img#mapImage"); // Get my img elem
            	var theImage = new Image();
            	theImage.src = imgObj.attr("src");
            	var imageRealWidth = theImage.width;
            	var imageRealHeight = theImage.height;

            	$.ajax({
 				   url: "<?php echo base_url().'admin/devices/save_bui_gmapPositionInfo/';?>",
 		           cache : false,
 		       	   dataType : "json",
 		           type : "POST",
 		           data : { cameraTopRate: cameraTopRate, cameraLeftRate : cameraLeftRate, gmapId : gmapId, buildingId : buildingId },
 		           success: function(data) {
 		        	   if(data.result == "success"){
 		        		  successAlert("<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>");
 		               }else
 		            	  errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
 		           }
 				});
            }
           function onShowMapImageDetail(obj){
               $("#mapImage").attr("src", $(obj).attr("src"));
               $("div.map-image-lg").attr("id", $(obj).parent().attr("id"));
           }
          
           function init (strweek) {
           	// Load Color Array
           	g_ColorArray['0'] = "#FFFFFF"; // No Record
           	g_ColorArray['1'] = "#40FF00"; // Always
           	g_ColorArray['2'] = "#0000FF"; // Motion
           	g_ColorArray['4'] = "#FF00FF"; // Event
           	g_ColorArray['8'] = "#00FFFF"; // Key-Frame
           	g_ColorArray['6'] = "#FFFF00"; // Motion + Event
           	g_ColorArray['A'] = "#C08000"; // Motion + Key-Frame
           	g_ColorArray['C'] = "#404080"; // Event + Key-Frame
           	g_ColorArray['B'] = "#C08080"; // Always + Motion + Key-Frame
           	g_ColorArray['D'] = "#FF0080"; // Always + Event + Key-Frame
           	g_ColorArray['E'] = "#408000"; // Motion + Event + Key-Frame
           	g_ColorArray['F'] = "#408080"; // Always + Motion + Event + Key-Frame

           	// Current Selected Color
           	g_SelectedColor = 1; 
           	document.getElementById("chkAlways").checked = true;
           	// Load Color Information to an array. 
           	fn_LoadColorTable(strweek);
           	fn_DrawTable();
           	
           }
           function fn_GetWeekSchedule() {
           	var retStr = "";
           	for (var i = 1; i <= 7; i ++)
           		for (var j = 1; j <= 24; j ++)
           			retStr += colorTable[i*24+j-1];
           	return retStr;
           }
           
           function fn_ApplyChanges() {
           	var scheduleweek;
           	var bChanges = false;
           	
           	scheduleweek = fn_GetWeekSchedule();
           	if (scheduleweek != document.getElementById("hiScheduleWeek").value) { 
           		bChanges = true;
           		document.getElementById("hiScheduleWeek").value = scheduleweek;
           	}
           	if (document.getElementById("selPreMotion").value != document.getElementById("hiSelPreMotion").value) {
           		bChanges = true; 
           		document.getElementById("hiSelPreMotion").value = document.getElementById("selPreMotion").value; 
           	}
           	if (document.getElementById("selPostMotion").value != document.getElementById("hiSelPostMotion").value) {
           		bChanges = true;
           		document.getElementById("hiSelPostMotion").value = document.getElementById("selPostMotion").value 
           	}
           	if (document.getElementById("recMode").value != document.getElementById("hiRecmode").value) {
           		bChanges = true; 
           		document.getElementById("hiRecmode").value = document.getElementById("recMode").value
           	}
           	if (bChanges) {
           		
           		$.ajax({
 				   url: "<?php echo 'http://'.$location->ipaddress.':'.$location->webport.'/grcenter.hardwareDevice.updateSchedule.nsf';?>",
 		           cache : false,
 		       	   dataType : "json",
 		           type : "POST",
 		           data : { videoinindex: document.getElementById("videoinindex").value, 
 		                   scheduleweek : fn_GetWeekSchedule(),
 		                      premotion : document.getElementById("selPreMotion").value,
 		                     postmotion : document.getElementById("selPostMotion").value,
 		                     recordmode : document.getElementById("recMode").value },
 		           success: function(data) {
 		        	   if(data.result == "success"){
 		        		  	successAlert("<?php echo $this->lang->line('admin.users.updateSuccess');?>");
 		               }else
 		            	  errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
 		           }
 				});
           	} else {
           	    infoAlert("Not changed any information");
           		return;
           	}
           	
           	
           }
           // Load Schedule
           function fn_DrawTable()
           {
           	for (var i = 1; i <= 7; i ++)
           		for (var j = 1; j <= 24; j ++)
           		{
           			document.getElementById(i+"-"+j).bgColor = g_ColorArray[colorTable[i*24+j-1]];
           		}
           }
           // Load Schedule Details
           function fn_LoadColorTable (weekValue) {
           	var strweek = weekValue;
           	k = 0;
           	for (var i = 1; i <= 7; i ++)
           		for (var j = 1; j <= 24; j ++) {
           			if (strweek != null)
           				colorTable[i*24+j-1] = strweek.substr(k, 1);
           			else
           				colorTable[i*24+j-1] = "1";
           			k ++;
           		}
           }
           function fn_callback_RetrieveSchedule(json, instance)
           {
           	infoAlert (json.recordscheduleweek[0].$text);
           }
           // Mathimatical Function to get Maximum and Minum value of two indexes
           function fn_GetMax(num1, num2) {
           	if (num1 * 1 >= num2 * 1)
           		return num1;
           	else 
           		return num2;
           }
           function fn_GetMin(num1, num2) {
           	if (num1 * 1 <= num2 * 1)
           		return num1;
           	else
           		return num2; 
           }
           // When mousemove, draw select range
           function fn_DrawSelectArea()
           {
           	
           	var oNorecord, oAlways, oEvent, oMotion, oKeyFrame;
           	oNorecord = document.getElementById("chkNoRecord");
           	oAlways = document.getElementById("chkAlways");
           	oEvent = document.getElementById("chkEvent");
           	oMotion = document.getElementById("chkMotion");
           	oKeyFrame = document.getElementById("chkKeyFrame");
           	if(!oNorecord.checked && !oAlways.checked && !oEvent.checked && !oMotion.checked && !oKeyFrame.checked)
           		return;
           	
           	var si, ei;
           	var sindi, sindj, eindi, eindj;
           	
           	if (g_bDrawSelect)
           	{
           		si = g_startPos.split("-");	// 1-1 1row, 1col
           		ei = g_curPos.split("-");
           		sindi = fn_GetMin(si[0], ei[0]);
           		eindi = fn_GetMax(si[0], ei[0]);
           		sindj = fn_GetMin(si[1], ei[1]);
           		eindj = fn_GetMax(si[1], ei[1]);
           		for (var i = 1; i <= 7; i ++)
           			for (var j = 1; j <= 24; j ++) {
           				if (i >= sindi && i <= eindi && j >= sindj && j<= eindj)
           					document.getElementById(i + "-" + j).bgColor = "#ACA9F2";
           				else
           					document.getElementById(i + "-" + j).bgColor = g_ColorArray[colorTable[i*24 + j - 1]];
           				
           				
           			}
           		
           	}
           }

           // Fill area with selected color
           function fn_FillArea()
           {
           	var si, ei;
           	var sindi, sindj, eindi, eindj;
           	
           	
           	si = g_startPos.split("-");
           	ei = g_endPos.split("-");
           	sindi = fn_GetMin(si[0], ei[0]);
           	
           	eindi = fn_GetMax(si[0], ei[0]);
           	sindj = fn_GetMin(si[1], ei[1]);
           	eindj = fn_GetMax(si[1], ei[1]);
           	for (var i = 1; i <= 7; i ++)
           		for (var j = 1; j <= 24; j ++) {
           			if (i >= sindi && i <= eindi && j >= sindj && j<= eindj) {				
           				if (g_SelectedColor != "-1") {
           					document.getElementById(i + "-" + j).bgColor = g_ColorArray[g_SelectedColor];
           					colorTable[i*24+j-1] = g_SelectedColor;
           				}
           			}
           			else {
           				var ind = colorTable[i*24+j-1];
           				document.getElementById(i + "-" + j).bgColor = g_ColorArray[ind];
           			}
           		}	
           	g_bDrawSelect = false;
           }

           // Select a time of all the weekday
           function fn_SelectWeek(ind) 
           {
           	for (var i = 1; i <= 7; i ++)
           	{
           		document.getElementById(i + "-" + ind).bgColor = g_ColorArray[g_SelectedColor];
           		colorTable[i*24+ind-1] = g_SelectedColor;
           	}
           }

           // Select 0~23 where you click the weekday
           function fn_SelectDay(ind)
           {
           	for (var i = 1; i <= 24; i ++)
           	{
           		document.getElementById(ind + "-" + i).bgColor = g_ColorArray[g_SelectedColor];
           		colorTable[ind*24+i-1] = g_SelectedColor;
           	}
           }

           // Select entire range
           function fn_SelectAll()
           {
           	for (var i = 1; i <= 7; i ++)
           		for (var j = 1; j <= 24; j ++) {
           			document.getElementById(i + "-" + j).bgColor = g_ColorArray[g_SelectedColor];
           			colorTable[i*24+j-1] = g_SelectedColor;
           		}
           }

           // Choose a color
           function fn_ClickCheckBox(obj)
           {
           	var oNorecord, oAlways, oEvent, oMotion, oKeyFrame;
           	oNorecord = document.getElementById("chkNoRecord");
           	oAlways = document.getElementById("chkAlways");
           	oEvent = document.getElementById("chkEvent");
           	oMotion = document.getElementById("chkMotion");
           	oKeyFrame = document.getElementById("chkKeyFrame");
           	if (obj.id != "chkNoRecord")
           	{
           		oNorecord.checked = false;
           	}
           	// No Record
           	if (oNorecord.checked)
           	{
           		oAlways.checked = false;
           		oEvent.checked = false;
           		oMotion.checked = false;
           		oKeyFrame.checked = false;
           		g_SelectedColor = "0";
           		return;
           	} 
           	// Always
           	if (oAlways.checked && !oEvent.checked && !oMotion.checked && !oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "1";
           	}
           	// Motion
           	else if (!oAlways.checked && !oEvent.checked && oMotion.checked && !oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "2";
           	}
           	// Event
           	else if (!oAlways.checked && oEvent.checked && !oMotion.checked && !oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "4";
           	}
           	// KeyFrame
           	else if (!oAlways.checked && !oEvent.checked && !oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "8";
           	}
           	// Motion + Event
           	else if (!oAlways.checked && oEvent.checked && oMotion.checked && !oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "6";
           	}
           	// Motion + KeyFrame
           	else if (!oAlways.checked && !oEvent.checked && oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = 'A';
           	}
           	// Event + KeyFrame
           	else if (!oAlways.checked && oEvent.checked && !oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "C";
           	}
           	// Always + Motion + KeyFrame
           	else if (oAlways.checked && !oEvent.checked && oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "B";
           	}
           	// Always + Event + KeyFrame
           	else if (oAlways.checked && oEvent.checked && !oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "D";
           	}
           	// Event + Motion + KeyFrame
           	else if (!oAlways.checked && oEvent.checked && oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "E";
           	}
           	// Always + Motion + Event + KeyFrame
           	else if (oAlways.checked && oEvent.checked && oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "F";
           	}
           	// Always + Motion
           	else if (oAlways.checked && !oEvent.checked && oMotion.checked && !oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "1";
           	}
           	// Always + Event
           	else if (oAlways.checked && oEvent.checked && !oMotion.checked && !oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "1";
           	} 
           	
           	// Always + KeyFrame
           	else if (oAlways.checked && !oEvent.checked && !oMotion.checked && oKeyFrame.checked) {
           		oNorecord.checked = false;
           		g_SelectedColor = "8";
           	}
           	else {
           		g_SelectedColor = "-1";
           	}
           }
           function fn_CopyTo()
           {
           		var scheduleWeek = fn_GetWeekSchedule();
           		var strUrl = "/securacle.setup.schedule.retrieveCameraNames.nsf?scheduleweek='" + scheduleWeek + "'&videoinindex="+document.getElementById("videoinindex").value;
           		var htmlparam = window.showModalDialog(strUrl, "Copy to", "dialogHeight: 450px; dialogWidth: 350px; dialogTop: 200px; dialogLeft: 400px; edge: Raised; center: Yes; help: No; resizable: No; status: No;");
           		// newWindow = window.open(strUrl,"","status,height=400,width=300")
           }
        </script>
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
        	<div class="right-panel-header row">
				<div class="col-sm-10">
					<h2><?php echo $this->lang->line('admin.devices.camera');?></h2>
					<p><?php echo $this->lang->line('admin.devices.camera.desc');?></p>
				</div>		
			</div>
        	<ul id="deviceCameraTab" class="nav nav-tabs" role="tablist">
				<li class="active" page='profile'><a href="#streamProfile" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.streamprofile');?></a></li>
			    <li page="motion"><a href="#motionDetection" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.motiondetection');?></a></li>
			    <li page="schedule"><a href="#scheduling" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.scheduling');?></a></li>
			    <li page="map"><a href="#parentMapPlacement" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.parentmapplacement');?></a></li>
			</ul>
			
			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="streamProfile">
					<div class="row">
						<div class="col-sm-12 col-md-12 text-right margin-top-20">
							<button id="saveProfile" class="btn btn-info"><?php echo $this->lang->line('btn.save');?></button>
						</div>
					</div>
					<form id="formAddNewCamera" style="overflow: hidden; margin: 10px;" action="http://<?php echo $location->ipaddress;?>:<?php echo $location->webport;?>/grcenter.hardwareDevice.insertCameraInfo.nsf" method="post">
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="panel panel-info margin-top-20">
							  		<div class="panel-heading"><?php echo $this->lang->line('admin.devices.streamprofile');?></div>	
										<div class="panel-body">
											<div class="form-horizontal" id="streamProfileForm">
												<div class="form-group form-group-sm">
									        		<label for="deviceName" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.devicename');?></label>
											        <div class="col-sm-6">
											        	<input type="text" id="deviceName" name="deviceName"  class="form-control required" value="<?php echo $cameraInfo[0]->videoInName;?>"/>
											        </div>
									      		</div>
												
									      		<div class="form-group form-group-sm">
									        		<label for="deviceModel" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.model');?></label>
											        <div class="col-sm-6">
											        	<?php echo $cameraInfo[0]->deviceModelName;?>
											        </div>
									      		</div>
									      		<div class="form-group form-group-sm">
									        		<label for="address" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.deviceipaddress'); ?></label>
											        <div class="col-sm-6">
											        	<input type="text" id="address" name="address" class="form-control required"  value="<?php echo $cameraInfo[0]->localAccessAddress;?>"/>
											        </div>
									      		</div>
									      		<div class="form-group form-group-sm">
									        		<label for="userName" class="col-sm-3 control-label"><?php echo $this->lang->line('general.username'); ?></label>
											        <div class="col-sm-6">
											        	<input type="text" id="userName" name="userName" class="form-control" value="<?php echo $cameraInfo[0]->userName;?>"/>
											        </div>
									      		</div>
									      		<div class="form-group form-group-sm">
									        		<label for="password" class="col-sm-3 control-label"><?php echo $this->lang->line('general.password'); ?></label>
											        <div class="col-sm-6">
											        	<input type="password" id="password" name="password" class="form-control" value="<?php echo $cameraInfo[0]->userPass;?>"/>
											        </div>
									      		</div>
									      		<div class="form-group form-group-sm">
									        		<label for="itcProtocol" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.interfacepro'); ?></label>
											        <div class="col-sm-6">
											        	<select id="itcProtocol" name="itcProtocol" class="form-control">
															<option value="0" >PSIA</option>
															<option value="1" >ONVIF</option>
															<?php // if ($cameraInfo[0]->itcProtocol == '1' ) { echo 'selected'; }?>
														</select>
											        </div>
									      		</div>
									      		<input type="hidden" name="firmwareVersion" value=""/>
									      		<input type="hidden" name="adminPort" value=""/>
									      		<input type="hidden" name="location" value=""/>
									      		<input type="hidden" name="dnsHostName" value=""/>
									      		<input type="hidden" name="dnsHostNameChk" value="off"/>
									      		<input type="hidden" name="hRtsp" value="80554"/>
									      		<input type="hidden" name="httpText" value=""/>
									      		<input type="hidden" name="rtspText" value=""/>
									      		<input type="hidden" name="macAddress" value=""/>
											</div>
						            	
								  	</div>
								</div>
							</div>
						</div>
					</form>
					<form id="addNewCamForm" action="<?php echo base_url().'admin/devices/add_camera';?>" method="post">
			        	<input type="hidden" name="videoIndex">
			        	<input type="hidden" name="videoInName">
			        	<input type="hidden" name="systemIndex">
			        	<input type="hidden" name="videoInName">
			        	<input type="hidden" name="liveToken">
			        	<input type="hidden" name="localAccessAddress">
			        	<input type="hidden" name="connectStatus" value="1">
			        	<input type='hidden' name='deviceModel' value=''>
			        	<input type="hidden" name="locationId" value="<?php echo $location->id;?>">
						<input type="hidden" name="buildingId" value="<?php echo $bid;?>">
						<!-- Devices Properties -->
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="panel panel-info margin-top-20">
							  		<div class="panel-heading"><?php echo $this->lang->line('admin.devices.deviceproperties');?></div>	
									<div class="panel-body">
										<div class="form-horizontal">
											<div class="form-group form-group-sm">
								        		<label for="audioEnable" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.audioenable');?></label>
										        <div class="col-sm-6">
										        	<input type="checkbox" id="audioEnable" name="audioEnable" value="<?php echo $properties->audioEnable;?>" style="margin-top: 10px" <?php if ($properties->audioEnable == '1') {echo 'checked=checked';}?>>
										        </div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzEnable" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzenable');?></label>
										        <div class="col-sm-6">
										        	<input type="checkbox" id="ptzEnable" name="ptzEnable" style="margin-top: 10px" value="<?php echo $properties->ptzEnable;?>" <?php if ($properties->ptzEnable == '1') {echo 'checked=checked';}?>>
										        </div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzUpUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzupurl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzUpUrl" name="ptzUpUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzupurl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzDownUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzdownurl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzDownUrl" name="ptzDownUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzdownurl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzLeftUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzlefturl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzLeftUrl" name="ptzLeftUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzlefturl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzRightUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzrighturl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzRightUrl" name="ptzRightUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzrighturl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzUpLeftUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzuplefturl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzUpLeftUrl" name="ptzUpLeftUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzuplefturl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzUpRightUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzuprighturl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzUpRightUrl" name="ptzUpRightUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzuprighturl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzDownLeftUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzdownlefturl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzDownLeftUrl" name="ptzDownLeftUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzdownlefturl');?>" value="">
								        		</div>
								      		</div>
								      		<div class="form-group form-group-sm">
								        		<label for="ptzDownRightUrl" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzdownrighturl');?></label>
								        		<div class="col-sm-6">
								          			<input type="text" class="form-control ptzurl" id="ptzDownRightUrl" name="ptzDownRightUrl" placeholder="<?php echo $this->lang->line('admin.devices.ptzdownrighturl');?>" value="">
								        		</div>
								      		</div>
								      	</div>
								  	</div>
								</div>
							</div>
						</div>
					</form>
			  	</div>
			  	<div class="tab-pane" id="motionDetection">
			  		<div class="panel panel-info margin-top-20">
			  			
				  		<div class="panel-heading"><?php echo $this->lang->line('admin.devices.motion.motionsetting'); ?></div>
				  		<div class="panel-body" id="motionSpinner">
				  			<div id="searching_spinner_center"></div>
				  		</div>
					  	<div class="panel-body motionForm" >					  		
					  		<div class="form-horizontal">
					  			<div class="row" style="text-align: center;">
					  				<div style="" id="mDrawContainer">
					  					<table id="activeXTable" style="height: 100%;"   cellpadding="0" cellspacing="0" border="1" 
					  					onmousedown="motionDetect.onPaintRectStart()" 
					  					onmousemove="motionDetect.onPaintRect()" 
					  					onmouseup="motionDetect.onPaintRectEnd()"  
					  					onmouseout="motionDetect.onPaintRectOut()"
					  					ondragstart = "return false;">
											<colgroup>
												<col width="10%"><col width="10%"><col width="10%"><col width="10%"><col width="10%"><col width="10%"><col width="10%"><col width="10%"><col width="10%"><col width="10%">
											</colgroup>
											<tr >
												<td ></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
											<tr >
												<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
											</tr>
										</table>
										<img src="" style="width: 100%; height: 100%;" id="VLCManager" onerror="motionDetect.errorloadingVideo();" onload="motionDetect.onloadVideo();">
					  				</div>
						      	</div>
				      		</div>
						</div>
					</div>
					<div class="panel panel-info margin-top-20 motionForm" >
				  		<table class="table" style="font-size: 12px">
				  			<tr>
				  				<td valign="top" width="45%">
				  					<div class="list-group areanames" >
									  <a href="#" class="list-group-item disabled">
									    <span><?php echo $this->lang->line('admin.devices.motion.areaname');?></span>
									  </a>
									  <div id="areaList">
									  </div>
									</div>
									<div class="margin-top-20" style="text-align: center">
										<button class="btn btn-default" onclick="motionDetect.onDelete();"><?php echo $this->lang->line('btn.delete');?></button>
									</div>
				  				</td>
				  				<td valign="top" width="50%">
				  					<form action="" class="form-inline" >
				  						<div class="row" style="margin: 0px">
							        		<label for="areaName" class="col-sm-4 " style="line-height: 30px"><?php echo $this->lang->line('admin.devices.motion.areaname');?></label>
									        <div class="col-sm-6">
									        	<input type="text" id="AreaNameText" name="AreaNameText" class="form-control" value="<?php echo $cameraInfo[0]->userPass;?>"/>
									        </div>
							      		</div>
							      		<div class="row" style="margin: 0px; margin-top: 15px;">
							      			<div class="progress" style="position: relative;">
							      				<div style="position:absolute;width: 2px; height: 100%;left: 20%; background-color: red;" id="mStandardBar"></div>							      				
											  	<div id="mProgressBar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
											  	</div>											  	
											</div>
							      		</div>
							      		<div class="row" style="margin: 0px; margin-top: 5px;">
							      			<label for="areaName" class="col-sm-2 " style="line-height: 30px"><?php echo $this->lang->line('admin.devices.motion.sensitivity');?></label>
									        <div class="col-sm-7">
									        	<div id='mSensitivity'></div>
									        </div> 			
									        <div class="col-sm-1"><span style="line-height: 30px" id="sensVal">50</span></div>			      			  
							      		</div>
							      		<div class="row" style="margin: 0px; margin-top: 5px;">
							      			<label for="areaName" class="col-sm-2 " style="line-height: 30px"><?php echo $this->lang->line('admin.devices.motion.threshold');?></label>
									        <div class="col-sm-7">
									        	<div id='mThreshold'></div>
									        </div> 			
									        <div class="col-sm-1"><span style="line-height: 30px" id="thresVal">20</span></div>			      			  
							      		</div>
							      		<div class="margin-top-20" style="text-align: center">
											<span class="btn btn-default " onclick="motionDetect.onUpdate();"><?php echo $this->lang->line('btn.update');?></span>
										</div>
				  					</form>
				  				</td>
				  			</tr>
				  			<tr>
				  				<td colspan="2" style="text-align: center"><button class="btn btn-success" onclick="motionDetect.onApply();"><?php echo $this->lang->line('btn.apply');?></button></td>
				  			</tr>
				  		</table>
					</div>
			  	</div>
			  	<div class="tab-pane dataTable_n" id="scheduling">
			  	    <form id="scheduleForm">
                    	<input type="hidden" name="videoinindex" id="videoinindex" value="<?php echo $cameraInfo[0]->videoInIndex;?>">
                    </form>
			  	    <div id="scheduleLoadingBar"></div>
        		    <div id="loadingBarBackground" class="hide"></div>
	  		        <div class="form-inline margin-bottom-20 margin-top-20">
    	  		        <div class="form-group">
    	  		            <label class='control-label'><?php echo $this->lang->line('admin.devices.camera.recordMode');?> : &nbsp;</label>
    	  		            <select class="form-control" id="recMode">
    	  		                <option value="0"><?php echo $this->lang->line('admin.devices.camera.noRecord');?></option>
                              	<option value="1"><?php echo $this->lang->line('admin.devices.camera.always');?></option>
                              	<option value="2"><?php echo $this->lang->line('admin.devices.camera.motion');?></option>
                              	<option value="3"><?php echo $this->lang->line('admin.devices.camera.event');?></option>
                              	<option value="4"><?php echo $this->lang->line('admin.devices.camera.keyFrame');?></option>
                              	<option value="5"><?php echo $this->lang->line('admin.devices.camera.customSchedule');?></option>
    	  		            </select>
    	  		        </div>
	  		        </div>
	  		        <div class="col-sm-5">
	  		            <div class="row">
	  		                <div class="panel panel-info">
    	  		                <h5 style="padding-left: 10px;"><?php echo $this->lang->line('admin.devices.camera.scheduleMode');?></h5>
    	  		                <hr style="margin-top: 0;">
    	  		                <div class="form-inline text-center margin-bottom-10">
    	  		                    <input type="checkbox" id='chkNoRecord' onclick="fn_ClickCheckBox(this);" /><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.noRecord');?></span>
    	  		                    <input type="checkbox" id='chkAlways' onclick="fn_ClickCheckBox(this);" /><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.always');?></span>
    	  		                    <input type="checkbox" id='chkMotion' onclick="fn_ClickCheckBox(this);" /><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.motion');?></span>
    	  		                </div>
    	  		                <div class="form-inline text-center margin-bottom-20">
    	  		                    <input type="checkbox" id='chkEvent' onclick="fn_ClickCheckBox(this);"/><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.event');?></span>
    	  		                    <input type="checkbox" id='chkKeyFrame' onclick="fn_ClickCheckBox(this);"/><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.keyFrame');?></span>
    	  		                </div>
	  		                </div>
	  		            </div>
	  		        </div>
	  		        <div class="col-sm-5 col-sm-offset-2">
	  		            <div class="row">
	  		                <div class="panel panel-info">
    	  		                <h5 style="padding-left: 10px;"><?php echo $this->lang->line('admin.devices.camera.prePostRecording');?></h5>
    	  		                <hr style="margin-top: 0; margin-bottom: 17px;">
    	  		                <div class="form-inline text-center margin-bottom-10">
    	  		                    <input id="chkPreRecording" type="checkbox" style="margin-right: 15px;"/><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.preRecording');?></span>
    	  		                    <select id="selPreMotion" class="form-control input-sm" style="margin-right: 15px;">
    	  		                        <option value="0" >0</option>
    		        					<option value="1" >1</option>
    		        					<option value="2" >2</option>
    		        					<option value="3" >3</option>
    		        					<option value="5" >5</option>
    	  		                    </select>
    	  		                    <span style="margin-right: 15px;"> Sec</span>
    	  		                </div>
    	  		                <div class="form-inline text-center margin-bottom-10">
    	  		                    <input id="chkPreRecording" type="checkbox" style="margin-right: 15px;"/><span style="margin-right: 15px;"> <?php echo $this->lang->line('admin.devices.camera.postRecording');?></span>
    	  		                    <select id="selPostMotion"" class="form-control input-sm" style="margin-right: 15px;">
    	  		                        <option value="0" >0</option>
    		        					<option value="1" >1</option>
    		        					<option value="5" >5</option>
    		        					<option value="10" >10</option>
    		        					<option value="20" >20</option>
    		        					<option value="30" >30</option>
    		        					<option value="60" >60</option>
    	  		                    </select>
    	  		                    <span style="margin-right: 15px;"> Sec</span>
    	  		                </div>
    	  		            </div>
	  		            </div>
	  		        </div>
	  		        <div class="col-sm-12">
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.noRecord');?></span>
	  		                <div class="schedule-color"></div>
	  		            </div>
	  		             <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.always');?></span>
	  		                <div class="schedule-color" style="background: #40ff00;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.motion');?></span>
	  		                <div class="schedule-color" style="background: blue;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.event');?></span>
	  		                <div class="schedule-color" style="background: #ff00ff;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20" >
	  		                <span><?php echo $this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #00ffff;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.motion')." + ".$this->lang->line('admin.devices.camera.event');?></span>
	  		                <div class="schedule-color" style="background: #ffff00;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.motion')." + ".$this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #c08000;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.event')." + ".$this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #404080;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.always')." + ".$this->lang->line('admin.devices.camera.motion')." + ".$this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #c08080;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.always')." + ".$this->lang->line('admin.devices.camera.event')." + ".$this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #ff0080;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.motion')." + ".$this->lang->line('admin.devices.camera.event')." + ".$this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #408000;"></div>
	  		            </div>
	  		            <div class="schedule-colors-wrap  margin-bottom-20">
	  		                <span><?php echo $this->lang->line('admin.devices.camera.always')." + ".$this->lang->line('admin.devices.camera.motion')." + ".$this->lang->line('admin.devices.camera.event')." + ".$this->lang->line('admin.devices.camera.keyFrame');?></span>
	  		                <div class="schedule-color" style="background: #408080;"></div>
	  		            </div>
	  		        </div>
	  		        <table style="border-style:solid;border: 1px;width:100%;text-align: center;)"  cellpadding="0" cellspacing="0" onkeydown="">
              		<?php 
              		$strWeek = array("Sun","Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
              		for ($i = 0; $i <= 7; $i ++)
              		{ ?><tr>
              			<?php for ($j = 0; $j <= 24; $j ++) {
              				if ($i == 0 && $j > 0) {
              			?> <td id="<?php echo $i . "-" . $j?>" style="background-color: '#EFEFEF';width:20px;" onclick="fn_SelectWeek(<?php echo $j; ?>)"><?php echo ($j - 1) ?></td> 
              			<?php } else if ($i > 0 && $j == 0) { ?>
              				<td id="<?php echo $i . "-" . $j?>" style="background-color: '#EFEFEF';width:36px;" onclick="fn_SelectDay(<?php echo $i; ?>)"><?php echo $strWeek[$i-1]; ?></td>  
              			<?php } else if ($i == 0 && $j == 0 ){?>
              				<td id="<?php echo $i . "-" . $j ?>" style=" background-color: '#EFEFEF';width:36px;" onclick="fn_SelectAll()">*</td>
              			<?php } else { ?>	
              				<td id="<?php echo $i . "-" . $j ?>" style="width:20px;" onmousedown="g_bDrawSelect=true;g_startPos=this.id;" onmousemove="g_curPos=this.id; fn_DrawSelectArea();" onmouseup="g_endPos=this.id;g_bDrawSelect=false;fn_FillArea();" ></td>  			
              			<?php }
              		} ?>
              		    </tr>
              		<?php } ?>
              		</table>
                    <div style="display:none;" id="changeValues">
                  		<input type="hidden" id="hiScheduleWeek" >
                  		<input type="hidden" id="hiSelPreMotion" >
                  		<input type="hidden" id="hiSelPostMotion" >
                  		<input type="hidden" id="hiRecmode" >
                  	</div>
                  	<div class="col-sm-12">
                  	    <div class="row text-right margin-top-20 margin-bottom-20">
                  	        <input class="btn btn-default" onclick="fn_ApplyChanges();" type="button" value="Apply"/>
                  	    </div>
                  	</div>
			  	</div>
			  	<div class="tab-pane" id="parentMapPlacement">
					<div class="panel panel-info margin-top-20">
				  		<div class="panel-heading"><?php echo $this->lang->line('admin.devices.map.setup');?></div>	
					  	<div class="panel-body">
					  		<div class="form-horizontal">
					  			<div class="row">
						  			<div class="col-sm-5 col-md-5">
								  		<div class="form-group form-group-sm margin-bottom-0">
							        		<label for="inputEmail3" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.map.name');?></label>
							        		<div class="col-sm-8">
							          			<input type="text" class="form-control" id="mapDescription" placeholder="" value="" />
							        		</div>
							      		</div>
							      	</div>
							      	<div class="col-sm-7 col-md-7">
								  		<div class="form-group form-group-sm margin-bottom-0">
							        		<label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.map.uploadimage');?></label>
							        		<div class="col-sm-6">
							          			<form id="fileUploadForm" class="attached-form" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/devices/uploadImage/" style="margin: 0">
			                                        <input type="file" class="form-control" name="fileUpload" id="fileUpload" style="height: auto;">                        
			                                        <input type="hidden" name="uploadDescription" id="uploadDescription" value="">
												</form>
							        		</div>
							        		<div class="col-sm-3">
							        			<button class="btn btn-default" id="mapImageUpload"><?php echo $this->lang->line('btn.upload');?></button>
							        		</div>
							      		</div>
							      	</div>
						      	</div>
						      	<hr class="small">
						      	<div class="row">
						      		<div class="col-sm-12 col-md-12 horizon-scroll">
							      		<div class="map-image-wrap" style="width: <?php echo count($mapImages) * 120;?>">
							      			<?php foreach ($mapImages as $k => $v) {?>
							      			<div class="map-image" id="<?php echo $v->id; ?>">
							      				<img src="<?php echo base_url().'assets/uploads/gmap/'.$v->image_path?>" onclick="onShowMapImageDetail(this)">
							      				<p class="text-center"><?php echo $v->name?></p> 
							      			</div>
							      			<?php }?>
							      		</div>
						      		</div>
						      	</div>
				      		</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12 text-right">
						<button class="btn btn-info btn-sm" onclick="onPositionSave();"><?php echo $this->lang->line('btn.saveposition');?></button>
					</div>
					<div class="building-pins-wrap" style="float:left;">
					<?php if(count($cameraInfo) > 0 ){?>
						<?php foreach($cameraInfo as $k => $v){ ?>
							<img id="<?php echo $v->id;?>" src="<?php echo base_url(); ?>assets/images/camera.png" style="background-color: transparent;z-index: 99999; float: left;width: 48px;" />
						<?php }?>
					<?php }?>
					</div>
					<?php if($cameraInfo != null){?>
					<div id="<?php echo $cameraInfo[0]->mapId ?>" class="map-image-lg col-sm-12 col-md-12 panel panel-info">
						<img id="mapImage" src="<?php echo base_url().'assets/uploads/gmap/'.$cameraInfo[0]->image_path?>">
					</div>
					<?php } else{?>
					<div id="<?php echo $mapImages[0]->id ?>" class="map-image-lg col-sm-12 col-md-12 panel panel-info">
						<img id="mapImage" src="<?php echo base_url().'assets/uploads/gmap/'.$mapImages[0]->image_path?>">
					</div>
					<?php }?>
				</div>
				<table id="AreaTable" class="dataTable_n" cellpadding="0" cellspacing="0" style="margin-top:-1;width:100%;">
										
									</table>	
			</div>
        </div><!-- /.container -->

        <?php $this->load->view('admin/admin_globaljs');?>
    </body>
</html>
