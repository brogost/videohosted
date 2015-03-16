<?php 
	$this->load->view('gmap/gmapscript');
?>
<style>
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
table {
	width: 100%;
	height: 100%;
    overflow: hidden;
}
td {
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
</style>
<div id='mainContainer' >
	<div id="gmapSplitter">
    	<div class="splitter-panel">
    		<div style="border: none;" id="feedExpander">
    			<div class="jqx-hideborder">
                        <?php echo $this->lang->line('gmap.rootdir');?>
                </div>
             	<div class="jqx-hideborder jqx-hidescrollbars">
    				<div style="visibility: hidden; border: none;" id='menuGmap'>
                		
            		</div>
            	</div>
            </div>
        </div>
        <div class="splitter-panel">       		
       		<div id="itemContainer">
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
        	Player
        </span>
    </div>
    <div id="windowContainer">
    </div>
</div>
