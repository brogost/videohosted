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
?>

<!DOCTYPE html>
<html ng-app="demoApp">
<head>
    <title id="Description"></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/demo/jqwidgets/styles/jqx.base.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/demo/jqwidgets/styles/jqx.energyblue.css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/demo/scripts/angular.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/angular/timer.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/angular/hashKeyCopier.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/demo/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/demo/jqwidgets/jqx-all.js"></script>
    <?php $this->load->view('includes/deviceTreeController');?>
</head>
<body>

    <div ng-init="timerType = 'Polling Server'" ng-controller="deviceTreeController">
    <jqx-tree jqx-width="'300px'" jqx-height="'300px'">
    	<ul>
    		<li ng-repeat="engine in engineInfos" on-last-repeat>	    	
    			<span>Server Info : {{engine.id}}, {{engine.name}}, {{engine.ipaddress}}, {{engine.webport}}, {{engine.rtmpport}}</span>
    			<ul ng-repeat="building in buildings | filter:{engineId: engine.id}">
    				<li id="building_{{building.engineId}}:{{building.id}}">
    					<span>{{building.name}}</span>
    					<ul ng-repeat="device in devices | filter:{engineId: engine.id, buildingId: building.id}">
							<li id="Camera_{{engine.id}}:{{device.id}}">{{device.name}}</li>
						</ul>
    				</li>
    			</ul>
    			
    		</li>
    	</ul>
		
    </jqx-tree>
    	
        <timer interval="5000" style="display: none"/>	
    </div>
</body>
</html>