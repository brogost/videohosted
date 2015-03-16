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
    </head>
    <body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
        <div class="container" style="max-width: 100%; width: 100%;height: 100%;min-width: 500px;float: left; width: 100%;">
        <h4>Quick Search Result</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Camera Name</th>
                    <th>Location Name</th>
                    <th>Video Live Url</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($searchResult as $k => $v) {?>
            <tr>
                <td><a href="<?php echo base_url();?>admin/devices/camera_detail/<?php echo $v->id;?>" target="mainFrame"><?php echo $v->videoInName;?></a></td>
                <td><a href="<?php echo base_url();?>admin/devices/camera_detail/<?php echo $v->id;?>" target="mainFrame"><?php echo $v->location_name;?></a></td>
                <td><a href="<?php echo base_url();?>admin/devices/camera_detail/<?php echo $v->id;?>" target="mainFrame"><?php echo $v->videoLiveURL;?></a></td>
            <?php } ?>
            </tbody>
        </table>
        </div><!-- /.container -->
    </body>
</html>
