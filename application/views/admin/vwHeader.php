<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo HTTP_IMAGES_PATH; ?>favicon.jpg">
    <title>GRCenter Admin Panel</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo HTTP_CSS_PATH; ?>bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
    <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
      <script src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
    <![endif]-->

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo HTTP_JS_PATH; ?>das.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>bootstrap.js"></script>    
    <script src="<?php echo HTTP_JS_PATH; ?>dropdown.js"></script>
    
     <link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
     <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
     <!-- pnotify js loading -->
     <link rel="stylesheet" href="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.css" >
     <script type="text/javascript" src="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.js" ></script>
  </head>
<body>
    <?php
    $pg = isset($page) && $page != '' ?  $page :'dash'  ;
    $this->load->view('admin/admin_globaljs');
    ?>

    <div class="navbar navbar-inverse navbar-fixed-top"  role="navigation">
    	<div class="navbar-inner">
	      <div class="container-fluid">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="<?php echo base_url(); ?>admin" style="color: #fff;"><?php echo $this->lang->line('admin.title');?></a>
	        </div>
	        <div class="navbar-collapse collapse">
	            <div class="input-group col-sm-offset-2 col-sm-3" style="float: left; margin-top: 8px;">
                  <div class="input-group-addon"><span class="glyphicon glyphicon-search"></span></div>
                  <form id="quickSearchForm" method="post" target="mainFrame">
                      <input type="text" placeholder="Search Camera..." id="quickSearchCamera" name="quickSearchCamera" class="form-control">
                  </form>
                </div>
	        	<div class="navbar-right" style="margin-top: 15px;">
	        		<div class="btn-group" id="adminProfile">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo $this->session->userdata('username');?> (<?php echo $this->session->userdata('now_group_name');?>) <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo base_url(); ?>admin/users/change_password"><?php echo $this->lang->line('admin.changepwd');?></a></li>
                          <li><a href="<?php echo base_url()?>admin/users/logout"><?php echo $this->lang->line('admin.logout');?></a></li>
                      </ul>
                    </div>
	        	</div>
	        </div>
	      </div>
	     </div>
    </div>
    <script>
    $('.dropdown-toggle').dropdown();
    </script>
