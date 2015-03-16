<?php 
/*
 *************************************************************************
 * @filename        : searchmain.php
 * @description    : Search main page
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.26   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */ 
 $this->load->view('search/searchscript');
?>
<style>
#rightContainer { width: 100%;height: 100%; overflow: auto; padding: 5px; }
#rightContainer img { margin: 2px; }
#leftContainer { width: 100%; height: 100%; }
#tab1, #tab2, #tab3 { width: 100%; height: 100%; }
.calendar_recorded { color: red; font-weight: bold; }
.detailsearch-td-time { width: 150px; text-align: center; }
.detailsearch-td-action { width: 180px; text-align: center; }
</style>
<div id="mainContainer">
	<div id="searchMainSpliter">
		<div class="splitter-panel">
			<div id="tabContainer">
				<ul>
					<li><?php echo $this->lang->line('gsearch.search');?></li>
					<li><?php echo $this->lang->line('gsearch.thumbnails');?></li>
					<li><?php echo $this->lang->line('gsearch.libraries');?></li>
				</ul>
				<div id="tab1">
					<div class="splitter-panel">
						<div id="deviceTree1">
						</div>
					</div>
					<div class="splitter-panel">
						<div id="search_calendar">
						</div>
					</div>
				</div>			
				<div id="tab2">
					<div class="splitter-panel">
						<div id="deviceTree2">
						</div>
					</div>
					<div class="splitter-panel">
						<div id="thumbnail_calendar">
						</div>
					</div>
				</div>			
				<div id="tab3">
					
				</div>
			</div>
		</div>
		<div class="splitter-panel" style="overflow: auto;">
			<div id="pageDetailSearch">
				<div style="text-align: center; padding-top: 20px;" id="detailSearchPlayer">
				</div>
				<div style="overflow-y: scroll">
					<table class="table table-striped table-condensed" id="searchDetailTable">
						<thead>
							<tr>
								<th style="text-align: center"><?php echo $this->lang->line('gsearch.initialtime');?></th>
								<th></th>
								<th style="text-align: center"><?php echo $this->lang->line('gsearch.actionsevent');?></th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 0;
							for ( $i = 0; $i < 24; $i ++) { 
						?>
							<tr valign="middle">
								<td valign="middle" class="detailsearch-td-time">
								<?php 
								$str_time = '';
								if ( $i == 0 ) { 
									echo "24:00 AM"; 
								} else if ( $i < 12) {
									echo $i.":00 AM";
								} else if ( $i == 12) {
									echo "12:00 PM"; 
								} else if ( $i > 12 ) { 
									echo ($i * 1 - 12).":00 PM"; 
								}
								?>
								</td>
								<td valign="middle" class="timeline" time-hour="<?php echo $i<10?'0'.$i:$i;?>">
									<div class="timeline-slider"></div>
								</td>
								<td class="detailsearch-td-action">
									<button class="btn btn-success" onclick = "OnViewDetail(this);">View</button>
									<button class="btn btn-success" onclick = "OnDoExport(this);">Export</button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
						
				</div>
			</div>
			<div id="pageThumgnailSearch" style="width: 100%;">
				
			</div>
			<div id = "pageLibraries" style="width: 100%;display: none">
				
			</div>
		</div>
	</div>
</div>
<div id="descWnd" style="display: none">
	<div>
		<img width="14" height="14" src="<?php echo HTTP_IMAGES_PATH;?>help.png" alt="" />
        <?php echo $this->lang->line('gsearch.description');?>
	</div>
	<div>
		<div>
        	<?php echo $this->lang->line('gsearch.description.desc');?>
		</div>
		<div>
			<input type="text" id="txtDescription" style="margin-top: 10px; margin-left: 5px;">
		</div>
        <div>
        	<div style="float: right; margin-top: 15px;">
            	<input type="button" id="btnOk" value="<?php echo $this->lang->line('btn.ok');?>" style="margin-right: 10px" />
                <input type="button" id="btnCancel" value="<?php echo $this->lang->line('btn.cancel');?>" />
			</div>
    	</div>
	</div>
</div>
<div ng-app="PollingApp" style="display: none">
	<div ng-init="timerType = 'Polling Server'" ng-controller="mainController" id="timerctrl">
    	<ul>
    		<li ng-repeat="engine in engineInfos" on-last-repeat>	    	
    			<div ng-init="timerType='Check ConnectStatus'; serverIndex=$index; locationId=engine.id; hostName=engine.hostName; webport=engine.webport; " ng-controller="locationController" style="display: none">
    				<timer interval="3000" />
    			</div>
    			<div ng-init="timerType='Check Time Period'; serverIndex=$index; locationId=engine.id; hostName=engine.hostName; webport=engine.webport; " ng-controller="timeperiodController" style="display: none">
    				<timer interval="10000" />
    			</div>
    		</li>
    	</ul>
		<timer interval="5000" style="display: none"/>	
    </div>
</div>