<?php 
/*
 *************************************************************************
 * @filename	: devices_camera.php
 * @description	: Details of Camera
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.26   Chanry         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
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
    
        <script type="text/javascript">
            $(document).ready(function() {

                //on save stram profile
                $("button#saveProfile").click(function(){
                	if($('#streamProfile').find('#streamProfileForm').jqxValidator('validate')){
                		$('#streamProfile').find('#streamProfileForm').ajaxForm({
               			 success: function(data) {
               				if(data.result == "success"){
                   				alert("success");
               					//spinner.stop();
               				}else if(data.result == 'exist'){
   	            				alert("exist");
               				}
               			 } 
               			}).submit();
                	}
                });
                $('#streamProfileForm').jqxValidator({
                    rules: [
                           { input: '#cameraName', message: 'Camera Name is required!', action: 'keyup, blur', rule: 'required' },
                           { input: '#cameraName', message: 'Camera Name must contain only letters!', action: 'keyup', rule: 'notNumber' }
                           ]
                });
            });

        </script>
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
        	<div class="right-panel-header row">
				<div class="col-sm-10">
					<h2><?php echo "Camera Defaults";//echo $this->lang->line('admin.location');?></h2>
					<p><?php echo "Camera Defaults predefine scheduling and recording settings that can be applied to locations and cameras.";//echo $this->lang->line('admin.location.desc');?></p>
				</div>		
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 text-right margin-top-20">
					<button id="saveProfile" class="btn btn-info">Save</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div class="panel panel-info margin-top-20">
				  		<div class="panel-heading">Stream Profile</div>	
						<div class="panel-body">
					  		<form class="form-horizontal" id="streamProfileForm" action="<?php echo base_url().'admin/devices/saveStreamProfile';?>" method="post">
					      		<div class="form-group form-group-sm">
					        		<label for="cameraName" class="col-sm-3 control-label">Camera Name</label>
					        		<div class="col-sm-6">
					          			<input type="text" class="form-control" id="cameraName" name="cameraName" placeholder="Camera Name" value="">
					        		</div>
					        		<div class="col-sm-2">
					        			<div class="checkbox">
								        	<label>
								            	<input type="checkbox" id="cameraCheckEnable" name="cameraCheckEnable" value="1"> Enable/Disable
								        	</label>
								        </div>
					        		</div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="cameraProvider" class="col-sm-3 control-label">Camera Provider</label>
							        <div class="col-sm-6">
							        	<select class="form-control" id="cameraProvider" name="cameraProvider">
							        		<option value="1">1</option>
							        	</select>
							        </div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="ipAddress" class="col-sm-3 control-label">IP Address</label>
							        <div class="col-sm-6">
							        	<input type="text" class="form-control" id="cameraIpAddress" name="cameraIpAddress" placeholder="000.000.000.000" value="">
							        </div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="imageType" class="col-sm-3 control-label">Image Type</label>
							        <div class="col-sm-6">
							        	<select class="form-control" id="imageType" name="imageType">
							        		<option value="1">H.264</option>
							        		<option value="2">MJPEG</option>
							        	</select>
							        </div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="frameRate" class="col-sm-3 control-label">Frame Rate</label>
							        <div class="col-sm-6">
							        	<select class="form-control" id="frameRate" name="frameRate">
							        		<option value="1">1fps</option>
							        		<option value="2">2fps</option>
							        		<option value="3">3fps</option>
							        	</select>
							        </div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="audioCheck" class="col-sm-3 control-label">Audio</label>
							        <div class="col-sm-6">
							        	<input type="checkbox" id="audioCheck" name="audioCheck" value="1"/>
							        </div>
					      		</div>
	    					</form>
					  	</div>
					</div>
				</div>
			</div>
        </div><!-- /.container -->
    </body>
</html>
