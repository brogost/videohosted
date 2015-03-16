<?php 
/*
 *************************************************************************
 * @filename        : eventsmain.php
 * @description    : Events main page
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.09.20   Jimm         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */ 
 $this->load->view('events/eventsscript');
?>
<div id="mainContainer">
    <div id="leftSplitter">
        <div id="deviceTree">
            
        </div>
        <div>    
            <div id="rightSplitter">
                <div>
                	<div id="mainTable">
                	</div>
                </div>
                <div>
                    <div id="docking1">
                    	<div>
                    		<div id="window0" style="height: 65px;">
		                    	<div>
		                    		<span><?php echo $this->lang->line('gevents.selectdate'); ?></span>
		                    	</div>
		                    	<div style="overflow: hidden">
		                        	<div id='rangeDateTime' style="">
		                        	</div>
		                        </div>
	                        </div>
                        </div>                        
                    </div>    
                    <div id="docking2">
                    	<div>
                    		<div id="window1" style= "height: 180px">
                    			<div>
                    				<span><?php echo $this->lang->line('gevents.systemevent.title');?></span>
                    			</div>
                    			<div style="overflow: hidden">
                    				<div id="selAll">
			                    		<span><?php echo $this->lang->line('gevents.systemevent.allevent');?></span>
			                        </div>
			                        <div id="selMotion">
			                        	<span><?php echo $this->lang->line('gevents.systemevent.motion');?></span>
			                        </div>
			                        <div id="selBoot">
			                        	<span><?php echo $this->lang->line('gevents.systemevent.boot');?></span>
			                        </div>
			                        <div id="selShutdown">
			                        	<span><?php echo $this->lang->line('gevents.systemevent.shutdown');?></span>
			                        </div>
			                        <div id="selVideoloss">
			                        	<span><?php echo $this->lang->line('gevents.systemevent.videoloss');?></span>
			                        </div>
			                        <div id="selStorageevent">
			                        	<span><?php echo $this->lang->line('gevents.systemevent.storage');?></span> 
			                        </div>
                    			</div>
                    		</div>
                    	</div>
                    </div>
                    
                    <div id = "docking3" style="display: none">
                    	<div>
                    		<div id="window2" style="height: 135px">
                    			<div>
                    				<span><?php echo $this->lang->line('gevents.userevent.title');?></span>
                    			</div>
                    			<div style="overflow: hidden">
                    				<div id="selLogin">
                    					<span><?php echo $this->lang->line('gevents.userevent.login');?></span>
                    				</div>
                    				<div id="selLogout">
                    					<span><?php echo $this->lang->line('gevents.systemevent.logout');?></span>
                    				</div>
                    				<div id="selLoginfail">
                    					<span><?php echo $this->lang->line('gevents.systemevent.loginfail');?></span>
                    				</div>
                    				<div id="selModifySystem">
                    					<span><?php echo $this->lang->line('gevents.systemevent.modifysystem');?></span>
                    				</div>
                    			</div>
                    		</div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>