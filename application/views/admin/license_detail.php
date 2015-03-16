<?php 
/*
 *************************************************************************
 * @filename	: license_detail.php
 * @description	: View - Detail of License
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
                var gmapId = '';
                $("#uploadFile").click(function(){
                	
            		$("input#fileUpload").parents("form").ajaxForm({
            			 success: function(data) {
            				if (data.result == "success") {
            					successAlert("Uploaded Successfully!");
            					window.location.reload();
            					//spinner.stop();
            				} else if(data.result == 'not_allowed') {
	            				infoAlert("This file Type not Allowed");
            				} else errorAlert ("Upload failed");
            			 } 
            		}).submit();
                });

              
            });

        </script>
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
            <div class="right-panel-header row">
				<div class="col-sm-10">
					<h2><?php echo $this->lang->line('admin.license.title');?></h2>
					<p><?php echo $this->lang->line('admin.license.index.shortDesc');?></p>
				</div>		
			</div>	
			<div class="panel panel-info margin-top-20">
		  		<div class="panel-heading"><?php echo $this->lang->line('admin.license.title');?></div>	
			  	<div class="panel-body">
			  	    <table class="table table-bordered table-condensed small">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('admin.license.index.issueDate');?></th>
                                <th><?php echo $this->lang->line('admin.license.index.liveEnable');?></th>
                                <th><?php echo $this->lang->line('admin.license.index.searchEnable');?></th>
                                <th><?php echo $this->lang->line('admin.license.index.alertEnable');?></th>
                                <th><?php echo $this->lang->line('admin.license.index.totalCount');?></th>
                                <th><?php echo $this->lang->line('admin.license.index.allowedUsers');?></th>
                          </tr>
                        </thead>
                        <tbody id="licenseContent">
                        <?php
                            foreach ($licenses as $key => $value) {
                                if ($value->liveEnable == 0) {
                                    $liveIsEnable = "No";
                                } else $liveIsEnable = "Yes";
                                if ($value->searchEnable == 0) {
                                    $searchIsEnable = "No";
                                } else $searchIsEnable = "Yes";
                                if ($value->alertEnable == 0) {
                                    $alertIsEnable = "No";
                                } else $alertIsEnable = "Yes";
                        ?>
                            <tr>
                                <td><?php echo $value->issueDate;?></td>
                                <td><?php echo $liveIsEnable;?></td>
                                <td><?php echo $searchIsEnable;?></td>
                                <td><?php echo $alertIsEnable;?></td>
                                <td><?php echo $value->totalCount;?></td>
                                <td><?php echo $value->allowedUsers;?></td>
                            </tr>
                        <?php 
                            }
                            if ($licenses == null ) {?>
                               <tr>
                                   <td colspan="6"><?php echo $this->lang->line('admin.license.index.noData');?></td></tr>
                        <?php } ?>
                        
                        </tbody>
                    </table>
			  	</div>
			</div>		
			<div class="panel panel-info margin-top-20">
		  		<div class="panel-heading"><?php echo $this->lang->line('admin.license.index.uploadNew');?></div>	
			  	<div class="panel-body">
			  		<div class="form-horizontal">
			  			<div class="clearfix">
					  		<div class="form-group form-group-sm margin-bottom-0">
					  		    <div class="col-sm-6">
				          			<form id="fileUploadForm" class="attached-form" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/license/uploadLicense/" style="margin: 0">
                                        <input type="file" class="form-control" name="fileUpload" id="fileUpload" style="height: auto;">                        
                                        <input type="hidden" name="uploadDescription" id="uploadDescription" value="">
									</form>
				        		</div>
				        		<div class="col-sm-3">
				        			<button class="btn btn-default" id="uploadFile"><?php echo $this->lang->line('btn.upload');?></button>
				        		</div>
				      		</div>
				      	</div>
		      		</div>
				</div>
			</div>
        </div><!-- /.container -->
        <?php $this->load->view('admin/admin_globaljs');?>
    </body>
</html>
