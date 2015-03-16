<?php 
/*
 *************************************************************************
 * @filename	: devices_building.php
 * @description	: View - Details of Building
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
        
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
        <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.ui-redmond.css';?>" media="screen">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.css" >
        <script type="text/javascript" src="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.js" ></script>
        <script type="text/javascript">
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
                	
                	if(description == ""){
                		 $("input#mapDescription").parents("div.form-group").eq(0).addClass("has-error");
                    	return;
                    }
                	
                	$("#uploadDescription").val(description);
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
	            				errorAlert("exist");
            				}
            			 } 
            		}).submit();
                });

                // Initialize jqxDraggable
                var mapWrapObj = $('div.map-image-lg');
                $("img#mapImage").load(function(){
                	var imgObj = $("img#mapImage");
                    var buildingId = <?php echo $buildingId ?>;
                    $.ajax({
			 			url: "<?php echo base_url().'admin/devices/get_buildingMapInfo/';?>",
			 		    cache : false,
			 		    dataType : "json",
			 		    type : "POST",
			 		    data : { buildingId : buildingId },
			 		    success: function(data) {
			 		    	if(data.result == "success"){
				 		    	var mapInfo = data.cameraGmapInfos;
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
                });
                	<?php
                		$attr = 0;
                		foreach($buildingCameraGmapInfo as $k => $v){
                	?>
                	$("div.building-pins-wrap").find("img").eq(<?php echo $attr; ?>).jqxDragDrop({restricter: mapWrapObj, dragZIndex: 999});
                	var topPos = $("#mapImage").offset().top + (<?php echo $v->posy; ?> * $("#mapImage").height() / 100) - 32;
                	var leftPos = $("#mapImage").offset().left + (<?php echo $v->posx; ?> * $("#mapImage").width() / 100) - 16;
                	$("div.building-pins-wrap").find("img").eq(<?php echo $attr; ?>).offset({ top: topPos, left: leftPos });
                	<?php
                			$attr ++;
						}
                	?>
            });

            //pin position save  
            function onPositionSave(){
            	var cameraTopRate = [];
            	var cameraLeftRate = [];
            	var attr = 1;
            	
            	$("div.building-pins-wrap").find("img").each(function(){
            		var screenImageWidth = $("#mapImage").width();
                	var screenImageHeight = $("#mapImage").height();
                	
            		var pinCameraLeftPos = $(this).offset().left - $("#mapImage").offset().left + 16;
            		var pinCameraTopPos = $(this).offset().top - $("#mapImage").offset().top + 32;

            		cameraLeftRate[$(this).attr("id")] = 100 * (pinCameraLeftPos / screenImageWidth).toFixed(2);
            		cameraTopRate[$(this).attr("id")] = 100 * (pinCameraTopPos / screenImageHeight).toFixed(2);
            	});
            	
				var gmapId = $("img#mapImage").parent().attr("id");
				var buildingId = <?php echo $buildingId ?>;
				
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
 		        		  "<?php echo $this->lang->line('admin.devices.msg.applySuccess');?>"
 		               }else
 			               errorAlert("<?php echo $this->lang->line('admin.devices.msg.failed');?>");
 		           }
 				});
            }
           function onShowMapImageDetail(obj){
              
               $("#mapImage").attr("src", $(obj).attr("src"));
               $("div.map-image-lg").attr("id", $(obj).parent().attr("id"));
           }
        </script>
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
            <div class="right-panel-header row">
				<div class="col-sm-10">
					<h2><?php echo $this->lang->line('admin.devices.building');?></h2>
					<p><?php echo $this->lang->line('admin.devices.building.desc');?></p>
				</div>		
			</div>			
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
					        		<label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('admin.devices.map.uploadimage'); ?></label>
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
			<?php if(count($buildingCameraGmapInfo) > 0 ){?>
				<?php foreach($buildingCameraGmapInfo as $k => $v){?>
					<img id="<?php echo $v->id;?>" src="<?php echo base_url(); ?>assets/images/camera.png" style="background-color: transparent;z-index: 99999; float: left; width: 48px" />
				<?php }?>
			<?php }?>
			</div>
			<?php if($buildings != null){?>
			<div id="<?php echo $buildings->mapId ?>" class="map-image-lg col-sm-12 col-md-12 panel panel-info">
				<img id="mapImage" src="<?php echo base_url().'assets/uploads/gmap/'.$buildings->image_path?>">
			</div>
			<?php } else{?>
			<div id="<?php echo $mapImages[0]->id ?>" class="map-image-lg col-sm-12 col-md-12 panel panel-info">
				<img id="mapImage" src="<?php echo base_url().'assets/uploads/gmap/'.$mapImages[0]->image_path?>">
			</div>
			<?php }?>
        </div><!-- /.container -->
        <?php $this->load->view('admin/admin_globaljs');?>
    </body>
</html>
