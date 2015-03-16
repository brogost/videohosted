<?php 
/*
 *************************************************************************
 * @filename	: devices_location.php
 * @description	: Details of Location or Building
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.11   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<html>
    <head>
        <title></title>
        <link rel="shortcut icon" href="<?php echo HTTP_IMAGES_PATH; ?>favicon.jpg">
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
        <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
    
        <link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'bootstrap.css';?>" type="text/css" />
        <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
        
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
         <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'tab.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.ui-redmond.css';?>" media="screen">
        <script src="<?php echo HTTP_JS_PATH.'jquery.spin.js';?>"></script>    
        <!--  pnotify plugin loading-->
        <link rel="stylesheet" href="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.css" >
        <script type="text/javascript" src="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.js" ></script>
        <!-- bootstrap datepick plugin loading  -->
        <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'datepicker.css';?>" media="screen">
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap-datepicker.js';?>"></script>
        
        <!-- bootstrap datatable plugin loading  -->
        <link rel="stylehseet" href="<?php echo HTTP_CSS_PATH.'datepicker.css';?>" type="text/css">
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.dataTables.min.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'dataTables.bootstrap.js';?>"></script>
        
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'admin/location.js';?>"></script>
        <script type="text/javascript">
      //storage spin loading bar initial setting
      var obj_location = new LOCATION_STATUS();
      obj_location.setServerIp("<?php echo $locations->ipaddress;?>", "<?php echo $locations->webport;?>");
      obj_location.init();
        storageOpts = {
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
               
                //map description input value check
    			$("input#mapDescription").on('input', function () {
        			$(this).parents("div.form-group").eq(0).removeClass("has-error");
        			if ($(this).val() == "") {
        				$(this).parents("div.form-group").eq(0).addClass("has-error");
        			}
    			});
                var gmapId = '';
                $("#mapImageUpload").click(function(){
                	var description = $("#mapDescription").val();
                	$("#uploadDescription").val(description);
                	if(description == ""){
                	   $("input#mapDescription").parents("div.form-group").eq(0).addClass("has-error"); 
                    	return;
                    }
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
                
              
                $("img#mapImage").load(function() {
                });

                //distinguish tabs
                 $('#deviceLocationTab a').click(function(e) {
        			e.preventDefault();
        			if ($(this).attr("href") == "#locInformation") {
            			obj_location.init();
        			} else {
            			obj_location.unload();
        			}
        			if ($(this).attr("href") == "#mapSetUp") {
        				  // Initialize jqxDraggable
        				var imgObj = $("#mapSetUp").find("img#mapImage");
                        var locationId = <?php echo $locationId ?>;
                        $.ajax({
    			 			url: "<?php echo base_url().'admin/devices/get_locationMapInfo/';?>",
    			 		    cache : false,
    			 		    dataType : "json",
    			 		    type : "POST",
    			 		    data : { locationId : locationId },
    			 		    success: function(data) {
    			 		    	if(data.result == "success"){
    				 		    	var mapInfo = data.buildingGmapInfos;
    				 		        for( var i = 0 ; i < mapInfo.length ; i ++ ){
    				 		        		$("div.building-pins-wrap").find("img").eq(i).jqxDragDrop({restricter: mapWrapObj, dragZIndex: 999});
    				 		                var topPos = imgObj.offset().top + (mapInfo[i].posy * imgObj.height() / 100) - 32;
    				 		                var leftPos = imgObj.offset().left + (mapInfo[i].posx * imgObj.width() / 100) - 16;
    				 		                $("div.building-pins-wrap").find("img").eq(i).offset({ top: topPos, left: leftPos });
    				 		        }
    			 		        }else
    			 		        	errorAlert("Failed!");
    			 		   	}
    			 		});
        			}
        			$(this).tab('show');
                 });

                 //load sever storage infos
                 var target = document.getElementById('storageLoadingBar');
                 var spinner1 = new Spinner(storageOpts).spin(target);
                 $("div#loadingBarBackground").removeClass("hide");
                 $.ajax({
   				   url: "<?php echo 'http://'.$locations->ipaddress.':'.$locations->webport.'/grcenter.hardwareDevice.retrieveStorageDeviceInfo.nsf';?>",
   		           cache : false,
   		       	   dataType : "json",
   		           type : "POST",
   		           data : { },
   		           success: function(data) {
   	   		           if(data.result == "success") {
   	   		        	   var storageInfo = data.mData;
   	   		        	   var usageStatus = "";
   	   		        	   var tempHtml = "";
   	   		        	   var disabled = ''
   	   	   		           var selected = '';
   	   	   		           var allocationsHtml = '';
   	   	   		           for (var i = 0; i < storageInfo.nIndex.length; i ++) {
   	   	   	   		           if (storageInfo.nUsageStatus[i] == "-1") {
   	   	   	   		               usageStatus = "Not Use(Windows Installed)";
	   	   	   		               disabled = 'disabled';
	   	   	   		               selected = '';
   	   	   	   		           } else if (storageInfo.nUsageStatus[i] == "0") {
   	   	   	   		               usageStatus = "Not Use";
   	   	   	   		               disabled = '';
   	   	   	   		               selected = '';
   	   	   	   		           } else if (storageInfo.nUsageStatus[i] == "1") { 
   	   	   	   		               usageStatus = "Record";
	   	   	   		               disabled = '';
	   	   	   		               selected = 'selected';
	   	   	   		               allocationsHtml += "<option value='" + storageInfo.sDriverLetter[i] + "'>" + storageInfo.sDriverLetter[i] + "</option>";
   	   	   	   		           } else if (storageInfo.nUsageStatus[i] == "2") { 
   	   	   	   		               usageStatus = "Current Record";
   	   	   	   		               disabled = 'disabled';
   	   	   	   		               selected = 'selected';
   	   	   	   		           } else if (storageInfo.nUsageStatus[i] == "4") { 
   	   	   	   		               usageStatus = "Current Record(Default Path)";
   	   	   	   		               disabled = '';
   	   	   	   		               selected = '';
   	   	   	   		           }
   	   	   	   		           tempHtml += 
   	   	   	   	   		           "<tr><td>" + storageInfo.sDriverLetter[i] + "</td>" +
   	   	   	   	   		           "<td>" + storageInfo.sDriverFormat[i] + "</td>" +
   	   	   	   	   		           "<td>" + usageStatus + "</td>" + 
   	   	   	   	   		           "<td>" + storageInfo.dTotalSize[i] + "</td>" +
   	   	   	   	   		           "<td>" + storageInfo.dFreeSize[i] + "</td>" +
   	   	   	   	   		           "<td><select name='deviceUsageCombo' id='deviceUsage'" + disabled + "><option value='0'>Not Use</option><option value='1' " + selected + ">Record</option></select></td>";
   	   	   	   	   		       if (disabled == "") {
   	   	   	   	   	   		       tempHtml += "<input type='hidden' name='storageIndexOld' value='" + storageInfo.nStorageIndex[i] + "'>" + 
   	   	   	   	   	   		                   "<input type='hidden' name='userStatusOld' value='" + storageInfo.nUsageStatus[i] + "'>" +
   	   	   	   	   	   		                   "<input type='hidden' name='storageLetter' value='" + storageInfo.sDriverLetter[i] + "'>" +
   	   	   	   	   	   		                   "<input type='hidden' name='deviceUsageInfo' value='" + storageInfo.nUsageStatus[i] + "'>";
   	   	   	   	   		       }    
   	   	   	   	   		        tempHtml += "</tr>";

   	   	   		           }
   	   	   		           $("tbody#storageContent").html(tempHtml);

   	   	   		           $("select[name='allocationDrive']").append(allocationsHtml);
   	   	   		           $("div#loadingBarBackground").addClass("hide");
   	   	   		           spinner1.stop();
   	   	   		           var recycleHtml = '';
   	   	   		           for (var i = 0; i < data.recycleRateList.value.length; i ++) {
   	   	   	   		           if (data.nData.sRecycleRate ==  data.recycleRateList.value[i])
   	   	   	   		               recycleHtml += "<option value='" + data.recycleRateList.value[i] + "' selected>" + data.recycleRateList.code[i] + "</option>";
   	   	   	   		           else
   	   	   	   		               recycleHtml += "<option value='" + data.recycleRateList.value[i] + "'>" + data.recycleRateList.code[i] + "</option>";    
   	   	   		           }
   	   	   		           $("select[name='spaceRate']").html(recycleHtml);
           	   	   		   $("select#deviceUsage").change(function () {
                              $(this).parents("tr").eq(0).find('input[name="deviceUsageInfo"]').val($(this).val());
                           }); 
   	   		           }
   		           },
   		           error: function (data) {
   	   		           spinner1.stop();
   		           }
   				});
 				$("button#storagePropertyApply").click(function () {
 					$("form#storagePropertyForm").attr("action", "<?php echo 'http://'.$locations->ipaddress.':'.$locations->webport.'/grcenter.hardwareDevice.insertOrUpdateStorageProperty.nsf';?>");
 					$("form#storagePropertyForm").ajaxForm({
           			 success: function(data) {
           				if (data.result == "success") {
           					successAlert("<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>");
       				        window.location.reload();
       				        $("div#deviceStorage").addClass("active");
       				        
           				} else
           					errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
           				return; 
           			 } 
 				    }).submit();
 				});

 				$("a#recyclePropertyApply").click(function () {
 				   $("form#recyclePropertyForm").attr("action", "<?php echo 'http://'.$locations->ipaddress.':'.$locations->webport.'/grcenter.hardwareDevice.updateRecycleRate.nsf';?>");
 				   $("form#recyclePropertyForm").ajaxForm({
 	           			 success: function(data) {
     	           			if (data.result == "success")
     	           			    successAlert("<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>");
               				else
               					errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
               				return; 
 	           			 } 
 				    }).submit();
 				});
 				
 				$("a#allocationApply").click(function () {
  				   $("form#allocationForm").attr("action", "<?php echo 'http://'.$locations->ipaddress.':'.$locations->webport.'/grcenter.hardwareDevice.updateAllocationCmd.nsf';?>");
  				   $("form#allocationForm").ajaxForm({
  	           			 success: function(data) {
      	           			if (data.result == "success")
      	           			    successAlert("<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>");
                			else
                				errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
                				return; 
  	           			 } 
  				    }).submit();
  				});

  				//location detail save function
  				$("a#serverRestart").click(function() {
  	  				var str_url = "http://<?php echo $locations->ipaddress;?>:<?php echo $locations->webport;?>/grcenter.system.restartServer.nsf";
  	  				$.ajax({
  	  	  				url: str_url,
  	  	  				success: function ( result ) {
  	  	  				}
  	  				});
  				});
  				$("a#saveLocDetail").click(function () {
  					$("form#locDetailForm").attr("action", "<?php echo base_url();?>admin/devices/saveLocDetail");
   				    $("form#locDetailForm").ajaxForm({
   	           			 success: function(data) {
       	           			if (data.result == "success")
       	           			    successAlert("<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>");
                 			else
                 				errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
                 				return; 
   	           			 } 
   				    }).submit();
  				});
				 //datepicker
  				 $('#userFromDate,#userToDate,#systemFromDate, #systemToDate').datepicker({
  				    format: 'yyyy-mm-dd',
  			        "setDate": new Date(),
  			        "autoclose": true
  				});
  				 //event log search function
    			$("a#systemLogSearch").click(function () {
    				$("form#systemLogSearchForm").attr("action","<?php echo base_url();?>admin/devices/searchSystemLog");
        			$("form#systemLogSearchForm").ajaxForm({
            			success: function (data) {
                			var searchResult = data.result;
                			if (searchResult == 'failed')
                    			return;
                			var tempHtml = "";
                			var tempHtml1 = "<table class='table table-bordered table-condensed small' id='example'>" +
            			                    "<thead><tr>" +
                              "<th><?php echo $this->lang->line('admin.devices.logs.eventDateTime');?></th>" +
                              "<th><?php echo $this->lang->line('admin.devices.logs.message');?></th>" + 
                              "</tr></thead ><tbody id='systemLogResults'></tbody></table>";
                              $("div#systemLogResult").html(tempHtml1);
                			if (searchResult != null) {
                			    for (var i = 0; i < searchResult.length; i ++) {
                			        tempHtml += "<tr>" +
                			                    "<td>" + searchResult[i].eventTime + "</td>" +
                			                    "<td>" + searchResult[i].eventMessage + "</td>" +
                			                    "</tr>";
                			    }
                			    $("tbody#systemLogResults").html(tempHtml);
                			    //bootstrap data table;
                        			$('table#example').dataTable();
                        		
                			} 
            			}
            		}).submit();

    			});
            });

            // Search user events 
            function SearchUserEvent ( long_period ) {
                var obj_form = $("form#userEventFrom");
                var str_startdate = $("#userFromDate").val();
                var str_enddate = $("#userToDate").val();
               	obj_form.find("input[name='hid_start_date']").attr("value", str_startdate);
               	obj_form.find("input[name='hid_end_date']").attr("value", str_enddate);
               	obj_form.find("input[name='periodTime']").attr("value", long_period);
               	obj_form.ajaxForm({
        			success: function (data) {
            			var searchResult = data.result;
            			var tempHtml = "";
            			var tempHtml1 = "<table class='table table-bordered table-condensed small' id='example11'>" +
        			                    "<thead><tr>" +
                          "<th><?php echo $this->lang->line('admin.devices.logs.eventDateTime');?></th>" +
                          "<th><?php echo $this->lang->line('admin.devices.logs.message');?></th>" + 
                          "</tr></thead ><tbody id='userLogResults'></tbody></table>";
                          $("div#userLogResults").html(tempHtml1);
            			if (searchResult != null) {
            			    for (var i = 0; i < searchResult.eventtime.length; i ++) {
            			        tempHtml += "<tr>" +
            			                    "<td>" + searchResult.eventtime[i] + "</td>" +
            			                    "<td>" + searchResult.eventmessage[i] + "</td>" +
            			                    "</tr>";
            			    }
            			    $("tbody#userLogResults").html(tempHtml);
            			    //bootstrap data table;
                    			$('table#example11').dataTable();
                    		
            			} 
        			}
        		}).submit();
            }

            function OnUserEventSearchClick() {
        		// Get time period 
        		var str_url = "<?php echo 'http://'.$locations->ipaddress.':'.$locations->webport.'/grcenter.search.retrieveTimePeriod.nsf';?>" ;
        		 var obj_date = new Date();
       			 var int_year = obj_date.getUTCFullYear();
       			 var int_month = obj_date.getUTCMonth();
       			    
       			 if(int_month.toString().length == 1)
       			 {
       			 	int_month = "0" + int_month.toString();
       			 }
       			    
       			 var int_date = obj_date.getUTCDate();
       			       
       			 if(int_date.toString().length == 1)
       			 	int_date = "0" + intDate.toString();
       			    
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
       					 SearchUserEvent ( result.result.periodTime );
       				 },
       				 error: function ( err ) {
       					SearchUserEvent ( 0 );
       				 }
   			 	});
            }

            //pin position save  
            function onPositionSave(){
            	var buildingTopRate = [];
            	var buildingLeftRate = [];
            	var attr = 1;
            	
            	$("div.building-pins-wrap").find("img").each(function(){
            		var screenImageWidth = $("#mapImage").width();
                	var screenImageHeight = $("#mapImage").height();
                	
            		var pinBuildingLeftPos = $(this).offset().left - $("#mapImage").offset().left + 16;
            		var pinBuildingTopPos = $(this).offset().top - $("#mapImage").offset().top + 32;

            		buildingLeftRate[$(this).attr("id")] = 100 * (pinBuildingLeftPos / screenImageWidth).toFixed(2);
             		buildingTopRate[$(this).attr("id")] = 100 * (pinBuildingTopPos / screenImageHeight).toFixed(2);
            	});
            	
				var gmapId = $("img#mapImage").parent().attr("id");
				var locationId = <?php echo $locationId ?>;
				
				//image real size calculate 
            	var imgObj = $("img#mapImage"); // Get my img elem
            	var theImage = new Image();
            	theImage.src = imgObj.attr("src");
            	var imageRealWidth = theImage.width;
            	var imageRealHeight = theImage.height;

            	$.ajax({
 				   url: "<?php echo base_url().'admin/devices/save_loc_gmapPositionInfo/';?>",
 		           cache : false,
 		       	   dataType : "json",
 		           type : "POST",
 		           data : { buildingTopRate: buildingTopRate, buildingLeftRate : buildingLeftRate, gmapId : gmapId, locationId : locationId },
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


           // User Event Log : Added by KCH - keyword brown_ghost
           
        </script>
        <style>
        .datepicker table{
            font-size: 13px;
        }
        </style>
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
            <div class="right-panel-header row">
				<div class="col-sm-10">
					<h2><?php echo $this->lang->line('admin.location');?></h2>
					<p><?php echo $this->lang->line('admin.location.desc');?></p>
				</div>		
			</div>
            <ul id="deviceLocationTab" class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#locInformation" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.locInformation');?></a></li>
			    <li ><a href="#deviceStorage" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.storage');?></a></li>
			    <li><a href="#mapSetUp" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.map.setup');?></a></li>
			    <li><a href="#logs" role="tab" data-toggle="tab"><?php echo $this->lang->line('admin.devices.logs');?></a></li>
			</ul>
            <div class="tab-content">
                <div class="tab-pane active" id="locInformation">
                    <form class="form-horizontal margin-top-20" id="locDetailForm" method="post">
						<div class="form-group form-group-sm">
			        		<label for="deviceName" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.locationname');?></a></label>
					        <div class="col-sm-6">
					        	<input type="text" id="locationName" name="locationName" class="form-control required" value="<?php echo $locations->name;?>" />
					        </div>
			      		</div>
						
			      		<div class="form-group form-group-sm">
			        		<label for="deviceModel" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.locationip');?></label>
					        <div class="col-sm-6">
					            <input type="text" id="locationIp" name="locationIp" class="form-control required" value="<?php echo $locations->ipaddress;?>">
					        </div>
			      		</div>
			      		<div class="form-group form-group-sm">
			        		<label for="address" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.locationwebport');?></label>
					        <div class="col-sm-6">
					        	<input type="text" id="locationWebport" name="locationWebport" class="form-control required" value="<?php echo $locations->webport;?>">
					        </div>
			      		</div>
			      		<div class="form-group form-group-sm">
			        		<label for="userName" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.locationrtmpport');?></label>
					        <div class="col-sm-6">
					        	<input type="text" id="locationRtmpPort" name="locationRtmpPort" class="form-control" value="<?php echo $locations->rtmpport;?>">
					        </div>
			      		</div>
			      		<input type="hidden" name="locationId" id="locationId" value="<?php echo $locations->id;?>" />
			      		<div class="form-group form-group-sm">
			      		    <div class="col-sm-6 col-sm-offset-3 text-right">
			      		         <a href="#" class="btn btn-info" id="serverRestart"><?php echo $this->lang->line('btn.serverRestart');?></a>
			      		        <a href="#" class="btn btn-info" id="saveLocDetail"><?php echo $this->lang->line('btn.save');?></a>
			      		    </div>
			      		</div>
					</form>
					<div class="panel panel-info margin-top-20">
						<div class="panel-heading">Server Status</div>
						<div class="panel-body">
							<div class="col-md-6" id="cpuinfoDiv">
								<form class="form-horizontal" role="form">
									<div class="form-body">
										<h3 class="form-section">CPU Info</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Vendor:</label>
													<div class="col-md-9">
														<p class="form-control-static vendor"></p>
													</div>
												</div>
											</div>
											<!--/span-->
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Model:</label>
													<div class="col-md-9">
														<p class="form-control-static model"></p>
													</div>
												</div>
											</div>
											<!--/span-->
										</div>
										<!--/row-->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">System Usage:</label>
													<div class="col-md-9">
														<p class="form-control-static sysusage"></p>
													</div>
												</div>
											</div>		
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">User Usage:</label>
													<div class="col-md-9">
														<p class="form-control-static userusage"></p>
													</div>
												</div>
											</div>										
										</div>		
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Combind:</label>
													<div class="col-md-9">
														<p class="form-control-static combind"></p>
													</div>
												</div>
											</div>		
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Idle:</label>
													<div class="col-md-9">
														<p class="form-control-static idle"></p>
													</div>
												</div>
											</div>										
										</div>								
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Nice:</label>
													<div class="col-md-9">
														<p class="form-control-static nice"></p>
													</div>
												</div>
											</div>		
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Wait:</label>
													<div class="col-md-9">
														<p class="form-control-static wait"></p>
													</div>
												</div>
											</div>										
										</div>								
									</div>
								
								</form>
								
							</div>
							<div class="col-md-6" id="memInfoDiv">
								<form class="form-horizontal" role="form">
									<div class="form-body">
										<h3 class="form-section">Memory Info</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Total:</label>
													<div class="col-md-9">
														<p class="form-control-static total"></p>
													</div>
												</div>
											</div>
											<!--/span-->
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Used:</label>
													<div class="col-md-9">
														<p class="form-control-static used"></p>
													</div>
												</div>
											</div>
											<!--/span-->
										</div>
										<h3 class="form-section">Disk Info</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Total:</label>
													<div class="col-md-9">
														<p class="form-control-static dtotal"></p>
													</div>
												</div>
											</div>
											<!--/span-->
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label col-md-3">Used:</label>
													<div class="col-md-9">
														<p class="form-control-static dused"></p>
													</div>
												</div>
											</div>
											<!--/span-->
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
                </div>
                <div class="tab-pane" id="deviceStorage">
        		    <div id="storageLoadingBar"></div>
        		    <div id="loadingBarBackground" class="hide"></div>
        		    <div class="panel panel-info margin-top-20">
        		        <div class="panel-body">
        		            <h5><?php echo $this->lang->line('admin.devices.storage.property');?></h5>
        			  		<div class="table-responsive">
        			  		 <form id="storagePropertyForm" method="post">
        			  		  <input type="hidden" name="subKind" value="1">
                              <table class="table table-bordered table-condensed small">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('admin.devices.storage_property_form.Drive');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.storage_property_form.format');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.storage_property_form.currentUsage');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.storage_property_form.totalSize');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.storage_property_form.freeSize');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.storage_property_form.newUsage');?></th>
                                  </tr>
                                </thead>
                                <tbody id="storageContent">
                                </tbody>
                              </table>
                              </form>
                            </div>
                            <div class="col-md-12 col-sm-12 text-right">
                                <button class="btn btn-default" id="storagePropertyApply"><?php echo $this->lang->line('admin.button.apply');?></button>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info margin-top-20">
        		        <div class="panel-body">
        		            <h5><?php echo $this->lang->line('admin.devices.storage.recycleProperty');?></h5>
        		            <div class="col-md-12 col-sm-12">
        		                <form class="form-inline" id='recyclePropertyForm' method="post" role="form">
        		                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label"><?php echo $this->lang->line('admin.devices.rPForm.freeSpace');?> : &nbsp;</label>
                                        <select name="spaceRate" class='form-control' style="width: 100px;"></select>
                                    </div>
                                    <a class="btn btn-default" style="margin-left: 15px;" id="recyclePropertyApply"><?php echo $this->lang->line('admin.button.apply');?></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info margin-top-20">
        		        <div class="panel-body">
        		            <h5><?php echo $this->lang->line('admin.devices.storage.utility');?></h5>
        		            <div class="col-md-12 col-sm-12">
        		                <form class="form-inline" role="form" id='allocationForm' method="post">
        		                    <div class="form-group" >
                                        <label for="inputEmail3" class="control-label"><?php echo $this->lang->line('admin.devices.storage.selectDrive');?> : &nbsp;</label>
                                        <select class='form-control' name="allocationDrive"><option value=""><?php echo $this->lang->line('admin.devices.storage.allDrive');?></option></select>
                                    </div>
                                    <a class="btn btn-default" id="allocationApply" style="margin-left: 15px;"><?php echo $this->lang->line('admin.button.apply');?></a>
                                </form>
                            </div>
                        </div>
                    </div>    
			  	</div>
				<div class="tab-pane" id="mapSetUp">
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
        					      			<?php foreach($mapImages as $k => $v){?>
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
        			<?php if(count($buildingGmapInfo) > 0 ){?>
        				<?php foreach($buildingGmapInfo as $k => $v){?>
        					<img id="<?php echo $v->id;?>" src="<?php echo base_url(); ?>assets/images/mapbuilding.png" style="background-color: transparent;z-index: 99999; float: left;" />
        				<?php }?>
        			<?php }?>
        			</div>
        			<?php if ($locations != null) {?>
        			<div id="<?php echo $locations->mapId ?>" class="map-image-lg col-sm-12 col-md-12 panel panel-info">
        				<img id="mapImage" src="<?php echo base_url().'assets/uploads/gmap/'.$locations->image_path?>">
        			</div>
        			<?php } else {?>
        			<div id="<?php echo $mapImages[0]->id ?>" class="map-image-lg col-sm-12 col-md-12 panel panel-info">
        				<img id="mapImage" src="<?php echo base_url().'assets/uploads/gmap/'.$mapImages[0]->image_path?>">
        			</div>
        			<?php }?>
        		</div>
        		<div class="tab-pane" id="logs">
        		    <div class="panel panel-info margin-top-20">
        		        <div class="panel-heading"><?php echo $this->lang->line('admin.devices.logs.userLog');?></div>
        		        <div class="panel-body">
        		        <form id="userEventFrom" method="post" action="http://<?php echo $locations->ipaddress.':'.$locations->webport.'/grcenter.event.retrieveEvents.nsf';?>">
        		            <div class="form-horizontal row margin-bottom-10">
            		            <div class="col-sm-6">
                		            <div class="row form-group form-group-sm">
            			        		<label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.fromDate');?></a></label>
            					        <div class="col-sm-6">
            					        	<input type="text" id="userFromDate" name="userFromDate" class="form-control required" placeholder="From Date" />
            					        </div>
            			      		</div>
            			      		<div class="row form-group form-group-sm">
            			      		    <label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.eventType');?></a></label>
            			      		    <div class="col-sm-6">
            			      		        <select class="form-control" name="selBox">
            			      		            <option value="0"><?php echo $this->lang->line('gevents.systemevent.allevent');?></option>
                			      		        <option value="1"><?php echo $this->lang->line('gevents.systemevent.boot');?></option>
                			      		        <option value="2"><?php echo $this->lang->line('gevents.systemevent.shutdown');?></option>
                			      		        <option value="3"><?php echo $this->lang->line('gevents.systemevent.storage');?></option>
                			      		        <option value="4"><?php echo $this->lang->line('gevents.systemevent.motion');?></option>
                			      		        <option value="5"><?php echo $this->lang->line('gevents.systemevent.videoloss');?></option>
            			      		        </select>
            			      		    </div>
            			      		</div>
        			      		</div>
        			      		<div class="col-sm-6">
                		            <div class="form-group form-group-sm">
            			        		<label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.toDate');?></a></label>
            					        <div class="col-sm-6">
            					        	<input type="text" id="userToDate" name="userToDate" class="form-control required" placeholder="To Date" />
            					        </div>
            			      		</div>
            			      		<div class="row form-group form-group-sm">
            			      		    <label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.maxResult');?></a></label>
            			      		    <div class="col-sm-6">
                			      		    <select class="form-control" name="maxResult">
                			      		        <option value="100">100</option>
                			      		        <option value="200">200</option>
                			      		        <option value="300">300</option>
                			      		    </select>
                			      		</div>
            			      		</div>
        			      		</div>
        			      		<div class="col-sm-11 text-right">
        			      		    <span class="btn btn-info btn-sm" onclick="OnUserEventSearchClick();"><?php echo $this->lang->line('btn.search');?></span>
        			      		</div>
    			      		</div>
    			      		<input type="hidden" name="videoinindex" value="-1">
    			      		<input type="hidden" name="hid_start_date" value="">
    			      		<input type="hidden" name="hid_end_date" value="">
    			      		<input type="hidden" name="hid_start_time" value="00:00:00">
    			      		<input type="hidden" name="hid_end_time" value="23:59:59">
    			      		<input type="hidden" name="periodTime" value="">
    			      	 </form>
			      		    <div class="table-responsive">
        			  		 <div id="userLogResults" >
                              <table class="table table-bordered table-condensed small">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('admin.devices.logs.eventDateTime');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.logs.message');?></th>
                                  </tr>
                                </thead>
                                <tbody id="userLogResults">
                                </tbody>
                              </table>
                             </div>
                            </div>
        		        </div>
        		    </div>
        		    <div class="panel panel-info margin-top-20">
        		        <div class="panel-heading"><?php echo $this->lang->line('admin.devices.logs.systemLog');?></div>
        		        <div class="panel-body">
        		            <form class="form-horizontal row margin-bottom-10" id="systemLogSearchForm" method="post">
            		            <div class="col-sm-6">
                		            <div class="row form-group form-group-sm">
            			        		<label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.fromDate');?></a></label>
            					        <div class="col-sm-6">
            					        	<input type="text" id="systemFromDate" name="systemFromDate" class="form-control required" placeholder="From Date" />
            					        </div>
            			      		</div>
            			      		<div class="row form-group form-group-sm">
            			      		    <label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.eventType');?></a></label>
            			      		    <div class="col-sm-6">
            			      		       <table class="table table-bordered table-condensed small">
            					        	    <tr>
            					        	        <td><input type="checkbox" id="systemLogin" name="systemLogin"></td>
            					        	        <td><?php echo $this->lang->line('gevents.userevent.login');?></td>
            					        	    </tr>
            					        	    <tr>
            					        	        <td><input type="checkbox" id="systemLogout" name="systemLogout"></td>
            					        	        <td><?php echo $this->lang->line('gevents.userevent.logout');?></td>
            					        	    </tr>
            					        	    <tr>
            					        	        <td><input type="checkbox" id="systemloginFail" name="systemloginFail"></td>
            					        	        <td><?php echo $this->lang->line('gevents.userevent.loginfail');?></td>
            					        	    </tr>
            					        	    <tr>
            					        	        <td><input type="checkbox" id="systemModify" name="systemModify"></td>
            					        	        <td><?php echo $this->lang->line('gevents.userevent.modifysystem');?></td>
            					        	    </tr>
            					        	</table>
            			      		    </div>
            			      		</div>
        			      		</div>
        			      		<div class="col-sm-6">
                		            <div class="form-group form-group-sm">
            			        		<label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.toDate');?></a></label>
            					        <div class="col-sm-6">
            					        	<input type="text" id="systemToDate" name="systemToDate" class="form-control required" placeholder="To Date" />
            					        </div>
            			      		</div>
            			      		<div class="row form-group form-group-sm">
            			      		    <label for="deviceName" class="col-sm-4 control-label"><?php echo $this->lang->line('admin.devices.logs.maxResult');?></a></label>
            			      		    <div class="col-sm-6">
                			      		    <select class="form-control" id="maxResult" name="maxResult">
                			      		        <option value="100">100</option>
                			      		        <option value="200">200</option>
                			      		        <option value="300">300</option>
                			      		    </select>
                			      		</div>
            			      		</div>
        			      		</div>
        			      		<div class="col-sm-11 text-right">
        			      		    <a href="#" class="btn btn-info btn-sm" id="systemLogSearch"><?php echo $this->lang->line('btn.search');?></a>
        			      		</div>
    			      		</form>
			      		    <div class="table-responsive">
        			  		 <div id="systemLogResult" >
                              <table class="table table-bordered table-condensed small" id="example">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('admin.devices.logs.eventDateTime');?></th>
                                    <th><?php echo $this->lang->line('admin.devices.logs.message');?></th>
                                  </tr>
                                </thead>
                                <tbody id="systemLogResults">
                                    
                                </tbody>
                                
                              </table>
                              </form>
                            </div>
        		        </div>
        		    </div>
        		</div>
        	</div>	
        </div><!-- /.container -->
        <?php $this->load->view('admin/admin_globaljs');?>
    </body>
</html>

