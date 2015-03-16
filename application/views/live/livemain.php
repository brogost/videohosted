<?php 
/*
 *************************************************************************
 * @filename        : livemain.php
 * @description    	: Live main page
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.26   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
$this->load->view('gmap/gmapscript'); 
$this->load->view('live/livescript');
?>
<style>
.liveinfoIcons {
	width: 20px;
	height: 20px;
	float: right;
}
.deviceTitle {
	color: white;
	font-size: 12px;
	font-weight: bold;
}
.channelOverlay {
	width: 100%;
    padding: 5px;
    position: absolute;
    z-index: 1001;
    text-align: left;
}
.livePlayer {
	z-index: 1001;
}
#itemContainer{
	width: 100%;
	height: 100%;         
	z-index: 100;
	top: 0px;
	left: 0px;
    overflow: hidden;
}
#rightContainer {
	width: 100%; 
	height: 100%; 
	display:inline;    
	z-index: 99;
    overflow: hidden;
}
#leftPaneContainer {
	width: 100%;
	height: 100%;
    overflow: hidden;
}
#leftPaneContainer td {
	position: relative;
    text-align: center;
}
.gmap_item {
	width: 24px;
	height: 24px;
	position: absolute;
	cursor: pointer;
}
#windowContainer {
    width: 100%;
    height: 100%;             
    overflow: hidden;
}

.channelDiv {
	border: 1px solid gray;
	width: 100%;
	height: 100%;
	z-index: 1000;
	position: relative;
}
#channelTable {
	width: 100%;
	height: 100%;
	margin: auto;
	border-spacing: 2px 1px;
	border-collapse: separate;
	z-index: 100;
}
#channelTable td {
	
}
#deviceTree{
	overflow: auto;
}
#liveSubHeader {
	margin-left: 10px;
	padding: 2px;
	border-left: 1px solid gray;
	height: 36px;
	display: block;
}

.channel.channel_selected {
	border: 1px solid red;
}
.btn_ptz {
	width: 48px;
	height: 48px;
	background-repeat: no-repeat;
	background-size: contain;
	cursor: pointer;
}
.btn_ptz_up {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_up.png');
}
.btn_ptz_upleft {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_ult.png');
}
.btn_ptz_upright {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_rup.png');
}
.btn_ptz_down {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_dn.png');
}
.btn_ptz_left {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_lt.png');
}
.btn_ptz_home {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_home.png');
}
.btn_ptz_right {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_rt.png');
}
.btn_ptz_dnleft {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_ldn.png');
}
.btn_ptz_down {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_dn.png');
}
.btn_ptz_dnright {
	background-image: url('<?php echo HTTP_IMAGES_PATH;?>ptz_rdn.png');
}
.btn_zoom_in {
	
}
/* Channel Button Classes */
.channelbtn.channel_1x1 {
	width: 32px;
	height: 32px;
	margin-left: 10px;
	background-image: url("<?php echo HTTP_IMAGES_PATH;?>ch_1x1.png");
	float:left;
	cursor: pointer;
}
.channelbtn.channel_1x1.selected {
	background-image: url("<?php echo HTTP_IMAGES_PATH;?>ch_1x1_selected.png");
}
.channelbtn.channel_1x5 {
	width: 32px;
	height: 32px;
	margin-left: 10px;
	background-image: url("<?php echo HTTP_IMAGES_PATH;?>ch_1x5.png");
	cursor: pointer;
	float:left;
}
.channelbtn.channel_1x5.selected {
	background-image: url("<?php echo HTTP_IMAGES_PATH;?>ch_1x5_selected.png");
}
.channelbtn.channel_3x3 {
	width: 32px;
	height: 32px;
	margin-left: 10px;
	background-image: url("<?php echo HTTP_IMAGES_PATH;?>ch_3x3.png");
	cursor: pointer;
	float:left;
}
.channelbtn.channel_3x3.selected {
	background-image: url("<?php echo HTTP_IMAGES_PATH;?>ch_3x3_selected.png");
}
#channel_select{
	margin-left: 10px;
	float: left;
	margin-top: 2px;
}

/* Channel Layout */
.channel_layout_container {
	border: 1px solid gray;
	width: 100%;
	height: 100%;
}
#channelContainer {
	width: 100%;
	height: 100%;
	text-align: center;
}
#leftPaneContainer {
	width: 100%;
	height: 100%;
}

#leftPaneContainer tr {
}
#leftPaneContainer td {
	vertical-align: top;	
}
</style>

<div id='mainContainer'>	
    <div id="liveMainSpliter">    	
        <div class="splitter-panel">
        	<table id="leftPaneContainer">
        		<tr style="">
        			<td>
        				<div style="border: none;" id="leftSideSpliter">
        					<div class="jqx-hideborder">
		                        <?php echo $this->lang->line('glive.explorer');?>
			                </div>
			                <div id="tabContainer" style="border: none;overflow: hidden;">
			                	<ul>
					                <li style="margin-left: 2px;" id="tabDevices">
										<?php echo $this->lang->line('glive.devices');?>
					                </li>
					                <li id="tabFavorites">
					                	<?php echo $this->lang->line('glive.favorites');?>
					                </li>
					                <li id="tabEmap">
					                	<?php echo $this->lang->line('glive.emap');?>
					                </li>
					            </ul>
				                <div class="content-container">
				                	<div id='deviceTree' style="border: none;margin-top: 3px;">
				                	</div>
				                </div>
				                <div class="content-container">
				                	<div id='favoriteTree' style="border: none; margin-top: 3px;overflow: hidden;">
				                	</div>
				                </div>
			    				<div class="content-container">
			    					<div  style="border: none;overflow: hidden;" id='menuGmap'></div>
			            		</div>
							</div>
			        	</div>			
        			</td>
        		</tr>
        		<tr style="height: 0px;">
        			<td>
        				<div id="ptzContainer" >
        					<div class="jqx-hideborder" style="cursor: pointer; width: 100%;text-align: left;" onclick='OnClickPtzPanel();'>
		                        <?php echo $this->lang->line('glive.ptz');?>
			                </div>
			                <div style="text-align: center">
			                	<TABLE id="ptzControlPanel" style="margin: 10px;margin-left: 30px;display: none" isvisible="0">
									<TR>
										<TD>
											<TABLE>
												<TR>
													<TD>
														<div class="btn_ptz btn_ptz_upleft"></div>
													</TD>
													<TD>
														<div class="btn_ptz btn_ptz_up"></div>
													</TD>
													<TD>
														<div class="btn_ptz btn_ptz_upright"></div>
													</TD>
												</TR>
												<TR>
													<TD>
														<div class="btn_ptz btn_ptz_left"></div>
													</TD>
													<TD>
														<div class="btn_ptz btn_ptz_home"></div>
													</TD>
													<TD>
														<div class="btn_ptz btn_ptz_right"></div>
													</TD>
												</TR>
												<TR>
													<TD>
														<div class="btn_ptz btn_ptz_dnleft"></div>
													</TD>
													<TD>
														<div class="btn_ptz btn_ptz_down"></div>
													</TD>
													<TD>
														<div class="btn_ptz btn_ptz_dnright"></div>
													</TD>
												</TR>
											</TABLE>
										</TD>
										<TD>
											<TABLE style="height: 100%;margin-left: 20px">
												<TR style="height: 48px;vertical-align: middle;">
													<TD style="height: 48px;vertical-align: middle;text-align: center;"><img src="<?php echo HTTP_IMAGES_PATH;?>zoom.png" style="width: 48px;cursor: pointer;"></TD>
												</TR>
												<TR style="height: 48px;vertical-align: middle;">
													<TD style="height: 48px;vertical-align: middle;text-align: center;">
														<?php echo $this->lang->line('glive.digitalzoom');?>
													</TD>
												</TR>
												<TR style="height: 48px;vertical-align: middle;">
													<TD style="height: 48px;vertical-align: middle;text-align: center;"><img src="<?php echo HTTP_IMAGES_PATH;?>zoom-out.png" style="width: 48px;cursor: pointer;"></TD>
												</TR>
											</TABLE>
										</TD>
									</TR>
								</TABLE>
			                </div>
        				</div>
        			</td>
        		</tr>
        		<tr style="height: 0px;">
        			<td>
        				<div id="ioContainer">
        					<div class="jqx-hideborder" style="cursor: pointer; width: 100%;text-align: left;" onclick='OnClickIOControlPanel();'>
		                        <?php echo $this->lang->line('glive.io');?>
			                </div>
			                <div id="ioControlPanel" isvisible="0" style="display: none">
			                </div>
			        	</div>
        			</td>
        		</tr>        		
        	</table>
            
        </div>
        <div class="splitter-panel">               
           <div id="channelContainer" style="border: 1px solid gray;"></div>
           <div id="itemContainer" style="display: none">
       			<table style="width: 100%; height: 100%" >
	       			<tr>
	       				<td valign="middle">
	       					<div id="rightContainer">
	       						
	       					</div>
	       				</td>
	       			</tr>
	       		</table>
       		</div>
        </div>
    </div>
</div>
<div id = "channelContextMenu" class="channelcontextmenu">
	<ul>
		<li id="clearChannel" btn_cmd="clearchannel"><img src="<?php echo HTTP_IMAGES_PATH.'clearch.png';?>" style="width: 20px;margin-right: 3px"><a href="#"><?php echo $this->lang->line('glive.clearchannel');?></a></li>
		<li id="clearChannelall" btn_cmd="clearchannelall"><img src="<?php echo HTTP_IMAGES_PATH.'clearall.png';?>" style="width: 20px;margin-right: 3px"><a href="#"><?php echo $this->lang->line('glive.clearchannelall');?></a></li>
	</ul>
</div>
<div id='layoutParentContextMenu' class="layoutparentcontext">
	<ul>
		<li id="newLayout"><img src="<?php echo HTTP_IMAGES_PATH.'folderIcon.png';?>"><a href="#"><?php echo $this->lang->line('glive.newlayout');?></a></li>
	</ul>
</div>
<div id="layoutContextMenu" class="layoutcontextmenu">
	<ul>
		<li cmd="apply"><span class=""></span><a href="#"><?php echo $this->lang->line('btn.apply');?></a></li>
		<li cmd="rename"><span class=""></span><a href="#"><?php echo $this->lang->line('btn.rename');?></a></li>
		<li cmd="delete"><span class=""></span><a href="#"><?php echo $this->lang->line('btn.delete');?></a></li>
		
	</ul>
</div>
<div id="layoutCameraContextMenu" class="layoutcameramenu">
	<ul>
		<li><span class="glyphicon glyphicon-remove"></span><a href="#"><?php echo $this->lang->line('btn.remove');?></a></li>
	</ul>
</div>
<div id="addNewLayoutWnd" style="display: none">
	<div>
		<img width="14" height="14" src="<?php echo HTTP_IMAGES_PATH;?>help.png" alt="" />
        <?php echo $this->lang->line('glive.addnewlayout');?>
	</div>
	<div>
		<div>
        	<?php echo $this->lang->line('glive.addnewlayout.desc');?>
		</div>
		<div>
			<input type="text" id="newLayoutName" style="margin-top: 10px; margin-left: 5px;">
		</div>
        <div>
        	<div style="float: right; margin-top: 15px;">
            	<input type="button" id="btnAdd" value="<?php echo $this->lang->line('btn.ok');?>" style="margin-right: 10px" />
                <input type="button" id="btnCancel" value="<?php echo $this->lang->line('btn.cancel'); ?>" />
			</div>
    	</div>
	</div>
</div>
<div id="renameLayoutWnd" style="display: none">
	<div>
		<img width="14" height="14" src="<?php echo HTTP_IMAGES_PATH;?>help.png" alt="" />
        <?php echo $this->lang->line('glive.renamelayout');?>
	</div>
	<div>
		<div>
        	<?php echo $this->lang->line('glive.renamelayout.desc');?>
		</div>
		<div>
			<input type="text" id="newLayoutReName" style="margin-top: 10px; margin-left: 5px;">
		</div>
        <div>
        	<div style="float: right; margin-top: 15px;">
            	<input type="button" id="btnRename" value="<?php echo $this->lang->line('btn.ok');?>" style="margin-right: 10px" />
                <input type="button" id="btnRenameCancel" value="<?php echo $this->lang->line('btn.cancel');?>" />
			</div>
    	</div>
	</div>
</div>

<form id="gmap_left_submit_form" method="post" style="display: none" action="<?php echo base_url().'grgmap/loadmapinfo'?>">
	<input type="hidden" name="locationId">
	<input type="hidden" name="buildingId">
</form>

<img src="#" style="display: none" id="gmap_clone"/>

<div id="playerWnd" style="display: none" deviceid='' isopened='0'>
	<div id="windowHeader">
		<span>
        	<img src="<?php echo HTTP_JQX_IMAGE_PATH;?>movie.png" alt="" style="margin-right: 15px" />
        </span>
        <span>
        	<?php echo $this->lang->line('glive.player');?>
        </span>
    </div>
    <div id="windowContainer">
    </div>
</div>
<div ng-app="PollingApp" style="display: none">
	<div ng-init="timerType = 'Polling Server'" ng-controller="mainController" id="timerctrl">
    	<ul>
    		<li ng-repeat="engine in engineInfos" on-last-repeat>	    	
    			<div ng-init="timerType='Check ConnectStatus'; serverIndex=$index; locationId=engine.id; hostName=engine.hostName; webport=engine.webport; " ng-controller="locationController" style="display: none">
    				<timer interval="3000" />
    			</div>
    		</li>
    	</ul>
		<timer interval="5000" style="display: none"/>	
    </div>
    
</div>