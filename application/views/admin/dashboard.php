<?php
/*
 *************************************************************************
 * @filename	: dashboard.php
 * @description	: Dashboard of Admin Panel
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.06.30   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<?php $this->load->view('admin/vwHeader');?>
<script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>

<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'json3.min.js';?>"></script>

<link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.classic.css';?>" media="screen">

<?php $this->load->view('includes/globaljs');?>
<?php $this->load->view('admin/dashboard_js'); ?>
<script type="text/javascript">

</script>
<div style="padding-top:52px"></div>
<div id="mainContainer" >
	<div id="mainSplitter">
		<!-- Left Navigation Area Start -->
		<div class="splitter-panel">
			<div id='navContainer'>
				<!-- Reporting navigation area start -->
				 <?php if ($this->session->userdata('group_id') < 4) {?>
				<div><?php echo $this->lang->line('admin.reporting');?></div>
			    <div class="jqx-hideborder jqx-hidescrollbars">
			    	<div style="visibility: hidden; border: none;" id="menuReporting">
						<ul>
                    		<li item-expanded='true'>
                        		<img style='float: left; margin-right: 2px;' src='<?php echo HTTP_IMAGES_PATH;?>regionalmap.jpg' />
                        		<span item-title="true"><?php echo $this->lang->line('admin.reporting.cameraaccess');?></span>
                        	</li>
                        </ul>
					</div>
				</div><!-- Reporting navigation area end -->
				<!-- License navigation area start -->
				<div>
					<?php echo $this->lang->line('admin.license.title');?>
				</div>
				<div class="jqx-hideborder jqx-hidescrollbars">
					<div style="visibility: hidden; border: none;" id="menuLicense">
						<ul>
                    		<li item-expanded='true' id="licenseroot">
                        		<img style='float: left; margin-right: 2px;' src='<?php echo HTTP_IMAGES_PATH;?>treeicongroups.jpg' />
                        		<span item-title="true"><?php echo $this->lang->line('admin.license.title');?></span>
                        	</li>
                        </ul>
					</div>
				</div><!-- License navigation area end -->
				<!-- Devices navigation area start -->
				<div>
					<?php echo $this->lang->line('admin.devices.title');?>
				</div>
				<div class="jqx-hideborder jqx-hidescrollbars">
					<div style="visibility: hidden; border: none;" id="menuDevices">
						<ul>
                    		<li item-expanded='false' id="devicesroot">
                        		<img style='float: left; margin-right: 2px;' src='<?php echo HTTP_IMAGES_PATH;?>treeiconsysdefault.jpg' />
                        		<img src='<?php echo HTTP_IMAGES_PATH;?>WebResource.gif' class="resourceloading"/>
                        		<span item-title="true"><?php echo $this->lang->line('admin.devices.globaldevices');?></span>
                        		<ul>
                        			<li><img style="float: left; margin-right: 5px;" src="<?php echo HTTP_IMAGES_PATH;?>LoadingProgressBar.gif"/></li>
                        		</ul>
                        	</li>
                        </ul>
					</div>
				</div><!-- Devices navigation area end -->
				
				<?php } ?>
				<!-- Users navigation area start -->
				<div>
					<?php echo $this->lang->line('admin.users');?>
				</div>
				<div class="jqx-hideborder jqx-hidescrollbars">
					<div style="visibility: hidden; border: none;" id="menuUsers">
						<ul>
                    		<li item-expanded='false' id="usersroot">
                        		<img style='float: left; margin-right: 2px;' src='<?php echo HTTP_IMAGES_PATH;?>users.jpg' />
                        		<img src='<?php echo HTTP_IMAGES_PATH;?>WebResource.gif' class="resourceloading"/>
                        		<span item-title="true"><?php echo $this->lang->line('admin.users');?></span>
                        		<ul>
                        			<li><img style="float: left; margin-right: 5px;" src="<?php echo HTTP_IMAGES_PATH;?>LoadingProgressBar.gif"/></li>
                        		</ul>
                        	</li>
                        </ul>
					</div>
				</div><!-- Users navigation end -->
			</div>
		</div><!-- Left Navigation Area End -->
		<!-- Right Side Area Start -->
		<div class="splitter-panel">
			<iframe style="width: 100%; height: 100%;" src = "about:blank" id="mainFrame" name="mainFrame"></iframe>
		</div><!-- Right Side Area End -->
	</div>
	<!-- Context Menu Start -->
	
	<!-- Devices - Location Parent Context Menu Start -->
	<?php if ($this->session->userdata('group_id') == 2) {?>
	<div id="contextLocations" class="devicecontextmenu">
		<ul>
			<li btn_cmd='addlocation'><?php echo $this->lang->line('admin.devices.context.addnewlocation');?></li>
		</ul>
	</div><!-- Devices - Location Parent Context Menu End -->
	<?php } ?>
	<?php if ($this->session->userdata('group_id') < 4) {?>
	<!-- License Context Menu Start -->
	<!-- <div id="contextLicense" class="licensecontextmenu">
		<ul>
			<li btn_cmd=""></li>
		</ul>
	</div>
	<div id="contextLicenseItem" class="licensecontextmenu">
		<ul>
			<li btn_cmd=""></li>
		</ul> -->
	</div><!-- License Context Menu End -->
	<!-- Devices - Locations Context Menu Start -->
	<div id="contextBuildings" class="devicecontextmenu">
		<ul>
			<li btn_cmd='addbuilding'><?php echo $this->lang->line('admin.devices.context.addnewbuilding');?></li>
			<li btn_cmd='removelocation'><?php echo $this->lang->line('btn.remove');?></li>
		</ul>
	</div><!-- Devices - Locations Context Menu End -->
	<!-- Devices - Buildings context menu start -->
	<div id="contextCameras" class="devicecontextmenu">
		<ul>
			<li btn_cmd="addcamera"><?php echo $this->lang->line('admin.devices.context.addnewdevice');?></li>
			<li btn_cmd="removebuilding"><?php echo $this->lang->line('btn.remove');?></li>
		</ul>
	</div><!-- Devices - Buildings context menu end -->
	<!-- Devices - Camera item context menu start -->
	<div id="contextCameraItem" class="devicecontextmenu">
		<ul>
			<li btn_cmd="removecamera"><?php echo $this->lang->line('btn.remove');?></li>
		</ul>
	</div><!-- Devices - Camera item context menu end -->
	
	<?php } ?>
	<!-- Users Context Menu start -->
	<div id="contextUsers" class="usercontextmenu">
		<ul>
			<li btn_cmd="adduser"><?php echo $this->lang->line('admin.users.adduser');?></li>
		</ul>
	</div>
	<div id="contextUserItem" class="usercontextmenu">
		<ul>
			<li btn_cmd="removeuser"><?php echo $this->lang->line('admin.users.removeuser');?></li>
		</ul>
	</div>
	<!-- Users Context Menu end -->
	<!-- Context Menu End -->
	
	<!-- Dialog Area Start -->
	<div id="dlgWndContainer">
		<!-- Add New Location for Devices Start -->
		<div id="wndAddNewLocation" style="display: none">
			<div>
				<?php echo $this->lang->line('admin.devices.locationaddnew');?>
			</div>
			<div>
				<div>
	            	<form id="formAddNewLocation" style="overflow: hidden; margin: 10px;" action="<?php echo base_url().'admin/devices/add_location';?>" method="post">
	            		 <table>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.devices.locationname');?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtLocationName" name="txtLocationName" class="needformat"/>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.devices.locationip');?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtLocationIP" name="txtLocationIP" class="needformat"/>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.devices.locationwebport');?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtLocationWEBPort" name="txtLocationWEBPort" class="needformat" value="7001"/>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.devices.locationrtmpport');?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtLocationRTMPPort" name="txtLocationRTMPPort" class="needformat" value="1935"/>
		                        </td>
		                    </tr>
		                </table>
	            	</form>
	            </div>
	            <div style="margin-top: 15px;float: right;margin-right: 5px;">
	            	<input type="button" id="btnLocationAdd" value="<?php echo $this->lang->line('btn.add');?>" style="margin-right: 10px" />
	                <input type="button" id="btnLocationCancel" value="<?php echo $this->lang->line('btn.cancel');?>" />
	            </div>
			</div>
		</div><!-- Add New Location for Devices End -->
		<!-- Add New Building for Devices Start -->
		<div id="wndAddNewBuilding" style="display: none">
			<div>
				<?php echo $this->lang->line('admin.devices.buildingaddnew');?>
			</div>
			<div>
				<div>
	            	<form id="formAddNewBuilding" style="overflow: hidden; margin: 10px;" action="<?php echo base_url().'admin/devices/add_building';?>" method="post">
	            		<input type="hidden" name="locationId" value="">
	            		 <table>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.devices.buildingname');?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtBuildingName" name="txtBuildingName" class="needformat"/>
		                        </td>
		                    </tr>
		                </table>
	            	</form>
	            </div>
	            <div style="margin-top: 15px;float: right;margin-right: 5px;">
	            	<input type="button" id="btnBuildingAdd" value="<?php echo $this->lang->line('btn.add');?>" style="margin-right: 10px" />
	                <input type="button" id="btnBuildingCancel" value="<?php echo $this->lang->line('btn.cancel');?>" />
	            </div>
			</div>
		</div><!-- Add New Building for Devices End -->
		<!-- Add New Camera for Devices Start -->
		<div id="wndAddNewCamera" style="display: none">
			
		</div><!-- Add New Camera for Devices End -->
		<!-- Add New Group Window Start -->
		<div id="wndAddNewGroup" style="display: none">
			<div><?php echo $this->lang->line('admin.groups.groupaddnew');?></div>
			<div>
				<div>
	            	<form id="formAddGroup" style="overflow: hidden; margin: 10px;" action="<?php echo base_url().'admin/groups/add_group';?>" method="post">
	            		 <table>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.groups.groupname');?>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtGroupName" name="txtGroupName" class="needformat"/>
		                        </td>
		                    </tr>
		                </table>
	            	</form>
	            </div>
	            <div style="margin-top: 15px;float: right;margin-right: 5px;">
	            	<input type="button" id="btnGroupAdd" value="<?php echo $this->lang->line('btn.add');?>" style="margin-right: 10px" />
	                <input type="button" id="btnGroupCancel" value="<?php echo $this->lang->line('btn.cancel');?>" />
	            </div>
			</div>
		</div><!-- Add New Group Window End -->
		
		<!-- Add New User Window Start -->
		<div id="wndAddNewUser" style="display: none">
			<div>
				<?php echo $this->lang->line('admin.users.addnewuser');?>(<?php echo $this->session->userdata('group_name')?>)
			</div>
	        <div>
	        	<div>
	            	<form id="formAddUser" style="overflow: hidden; margin: 10px;" action="<?php echo base_url().'admin/users/add_user';?>" method="post">
	            		 <table>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.label.firstname');?></td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtUserFirstName" name="txtUserFirstName" class="needformat"/>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.label.lastname');?></td>
		                    </tr>
		                    <tr>
		                        <td>
		                            <input id="txtUserLastName" name="txtUserLastName" class="needformat"/>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.users.loginname');?></td>
		                    </tr>
		                    <tr>
		                        <td colspan="2">
		                            <input id="txtUserLoginname" name="txtUserLoginname" class="needformat" />
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.users.emailaddress');?></td>
		                    </tr>
		                    <tr>
		                        <td colspan="2">
		                            <input id="txtUserEmail" name="txtUserEmail" class="needformat" />
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.users.createpwd');?></td>
		                    </tr>
		                    <tr>
		                        <td colspan="2">
		                            <input id="txtUserPwd" type="password" name="txtUserPwd" class="needformat"/>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td colspan="2"><?php echo $this->lang->line('admin.users.confirmpwd');?></td>
		                    </tr>
		                    <tr>
		                        <td colspan="2">
		                            <input id="txtUserConfirmPwd" type="password" class="needformat" />
		                        </td>
		                    </tr>
		                    <input type="hidden" id="userGroupId" name="userGroupId" />
		                </table>
	            	</form>
	            </div>
	            <div style="margin-top: 15px;float: right;margin-right: 5px;">
	            	<input type="button" id="btnUserAdd" value="<?php echo $this->lang->line('btn.add');?>" style="margin-right: 10px" />
	                <input type="button" id="btnUserCancel" value="<?php echo $this->lang->line('btn.cancel');?>" />
	            </div>
	        </div>
	    </div><!-- Add New User Window End -->
    </div>
	<!-- Dialog Area end -->
</div>
<?php $this->load->view('admin/vwFooter');?>
<?php $this->load->view('admin/admin_globaljs');?>