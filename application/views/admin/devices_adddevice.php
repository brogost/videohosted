<?php
/********************************************************************************************
 * Page				: Admin panel - Add new device
 * Author			: KCH
 * ------------------------------------------------------------------------------------------
 * File Name		: devices_adddevice.php
 * Description		: View of add new device 
 * Date				: Aug 08, 2014 7:09:26 PM
 * Version			: 1.0
 ********************************************************************************************/
 ?>
 <html>
    <head>
        <title></title>
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
        <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
    
        <link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
        <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'bootstrap.css';?>" type="text/css" />
        <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
        
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'tab.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.ui-redmond.css';?>" media="screen">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.css" >
        <script type="text/javascript" src="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.js" ></script>
        <script type="text/javascript">
		
        	var str_serverip = "<?php echo $location->ipaddress;?>";
        	var str_webport = "<?php echo $location->webport;?>";
            $(document).ready(function() {
				$(".ptzurl").each(function() {
					$(this).attr("disabled", "disabled");
				});
				

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
			 		        }
			 		   	}
			 		});
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

				// Button test connect event 
				$("#btnTestConnect").click(function() {
					var str_ipaddress = $("#address").val();
					if (str_ipaddress == '') {
						errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.invalidip');?>");
						return;
					}
					var obj_submit = $("#testConnectionForm");
					obj_submit.find('input[name="ipAddr"]').attr('value', str_ipaddress);
					obj_submit.ajaxSubmit({
						success: function ( result ) {
							if ( result.success == "success" ) {
								successAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.connectsuccess');?>");
								return;
							} else {
								errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.connectfail');?>");
								return;
							}
						},
						error : function ( err ) {
							errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.connectfail');?>");
							return;
						}
					});						
				});

				$("#btnAddCamera").click( function () {
					var bool_valid = true;
					$(".required").each(function () {
						if ($(this).val() == '') {
							bool_valid = false;
							return;
						}
					});
					
					if (bool_valid == false) {
						errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.invalidinput');?>");
						return;
					}
					$("#btnAddCamera").text("<?php echo $this->lang->line('btn.waitPlease');?>");
					$("#formAddNewCamera").ajaxSubmit({
						success: function ( result ) {
							if (result.success == 'success') {
								var obj_videoin = result.videoin;
								var str_videoinindex = obj_videoin.videoinindex;
								var str_systemIndex = obj_videoin.systemindex;
								var str_systemname = obj_videoin.systemname;
								var str_livetoken = "stream_" + str_videoinindex;
								var str_localaccess = obj_videoin.localAccess;

								var obj_submit = $("#addNewCamForm");
								obj_submit.find("input[name='videoIndex']").attr('value', str_videoinindex);								
								obj_submit.find("input[name='systemIndex']").attr('value', str_systemIndex);								
								obj_submit.find("input[name='liveToken']").attr('value', str_livetoken);
								obj_submit.find("input[name='videoInName']").attr('value', str_systemname);
								obj_submit.find("input[name='deviceModel']").attr('value', $("input#deviceModelName").val());
								obj_submit.find("input[name='localAccessAddress']").attr( 'value', str_localaccess );

								obj_submit.ajaxSubmit({
									success: function ( result ) {
										$("#btnAddCamera").text("<?php echo $this->lang->line('btn.save');?>");
										successAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.success'); ?>");
										top.NewDeviceAdded( result.result );
									},
									error: function ( err ) {
										$("#btnAddCamera").text("<?php echo $this->lang->line('btn.save');?>");
										errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.failed'); ?>");
									}
								});								
							} else {
								$("#btnAddCamera").text("<?php echo $this->lang->line('btn.save');?>");
								errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.failed'); ?>");
							}
						},
						error: function ( errors ) {
							$("#btnAddCamera").text("<?php echo $this->lang->line('btn.save');?>");
							errorAlert ("<?php echo $this->lang->line('admin.devices.msg.adddevice.failed'); ?>");
						}
					});
				});
				

				$("select#deviceModel").change(function(){
					$("input#deviceModelName").val($("#deviceModel option:selected").text());
				});
            });

        </script>
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
        	<div class="right-panel-header row">
				<div class="col-sm-10">
					<h2><?php echo $this->lang->line('admin.devices.addnewdevice'); ?></h2>
					<p><?php echo $this->lang->line('admin.devices.addnewdevice.desc'); ?></p>
				</div>		
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 text-right margin-top-20">
					<button id="btnTestConnect" class="btn btn-info"><?php echo $this->lang->line('btn.connect');?></button>
					<button id="btnAddCamera" class="btn btn-info"><?php echo $this->lang->line('btn.save');?></button>
				</div>
			</div>
			<form id="formAddNewCamera" style="overflow: hidden;" action="http://<?php echo $location->ipaddress;?>:<?php echo $location->webport;?>/grcenter.hardwareDevice.insertCameraInfo.nsf" method="post">
				
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="panel panel-info margin-top-20">
					  		<div class="panel-heading"><?php echo $this->lang->line('admin.devices.streamprofile');?></div>	
								<div class="panel-body">
									<div class="form-horizontal">
										<div class="form-group form-group-sm">
							        		<label for="deviceName" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.devicename');?></label>
									        <div class="col-sm-6">
									        	<input type="text" id="deviceName" name="deviceName"  class="form-control required"/>
									        </div>
							      		</div>
										<div class="form-group form-group-sm">
							        		<label for="provider" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.provider');?></label>
									        <div class="col-sm-6">
									        	<select name="provider" id="provider" class="form-control">
												<?php foreach($providers as $k => $v){
													echo "<option value='".$v->providerIndex."'>".$v->providerName."</option>";		
												}?>
												</select>
									        </div>
							      		</div>
							      		<div class="form-group form-group-sm">
							        		<label for="deviceModel" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.model');?></label>
									        <div class="col-sm-6">
									        	<select name="deviceModel" id="deviceModel" class="form-control">
												<?php foreach($modelInfos as $k => $v){
													echo "<option value='".$v->modelCode."'>".$v->modelName."</option>";		
												}?>
												</select>
									        </div>
							      		</div>
							      		<div class="form-group form-group-sm">
							        		<label for="address" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.deviceipaddress'); ?></label>
									        <div class="col-sm-6">
									        	<input type="text" id="address" name="address" class="form-control required"  value=""/>
									        </div>
							      		</div>
							      		<div class="form-group form-group-sm">
							        		<label for="userName" class="col-sm-3 control-label"><?php echo $this->lang->line('general.username'); ?></label>
									        <div class="col-sm-6">
									        	<input type="text" id="userName" name="userName" class="form-control"/>
									        </div>
							      		</div>
							      		<div class="form-group form-group-sm">
							        		<label for="password" class="col-sm-3 control-label"><?php echo $this->lang->line('general.password'); ?></label>
									        <div class="col-sm-6">
									        	<input type="password" id="password" name="password" class="form-control"/>
									        </div>
							      		</div>
							      		<div class="form-group form-group-sm">
							        		<label for="itcProtocol" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.interfacepro'); ?></label>
									        <div class="col-sm-6">
									        	<select id="itcProtocol" name="itcProtocol" class="form-control">
													<option value="0">PSIA</option>
													<option value="1">ONVIF</option>
												</select>
									        </div>
							      		</div>
							      		<input type="hidden" id="deviceModelName" name="deviceModelName" value="<?php echo $modelInfos[0]->modelName;?>" />
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
								        	<input type="checkbox" id="audioEnable" name="audioEnable" value="0" style="margin-top: 10px">
								        </div>
						      		</div>
						      		<div class="form-group form-group-sm">
						        		<label for="ptzEnable" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.ptzenable');?></label>
								        <div class="col-sm-6">
								        	<input type="checkbox" id="ptzEnable" name="ptzEnable" value="0" style="margin-top: 10px">
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
        </div><!-- /.container -->
        <form style="display: none" id="testConnectionForm" action="http://<?php echo $location->ipaddress.':'.$location->webport;?>/grcenter.hardwareDevice.connectTest.nsf" method="post">
        	<input type='hidden' name='ipAddr'>
        </form>
        <?php $this->load->view('admin/admin_globaljs');?>
    </body>
</html>