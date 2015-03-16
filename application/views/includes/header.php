<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo HTTP_IMAGES_PATH; ?>favicon.jpg">
    
    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
    <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
    <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
    <script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
    <script type="text/javascript" src="<?php echo HTTP_JS_PATH.'custom/clock.js';?>"></script>
    <script src="<?php echo HTTP_JS_PATH.'json3.min.js';?>"></script>
    <title><?php echo $this->lang->line('system.title');?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo HTTP_CSS_PATH; ?>bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH; ?>custom.css">
    <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH; ?>font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.energyblue.css';?>" media="screen">
    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.classic.css';?>" media="screen">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
      <script src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>   
    <![endif]-->
    
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo HTTP_JS_PATH; ?>das.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>bootstrap.js"></script>    
  </head>
<body>
    <?php
    $pg = isset($page) && $page != '' ?  $page :'home'  ;    
    ?>
<div id="menuContainer">
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container" style="width: auto">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url(); ?>"><span></span></a>
        </div>
        <div class="navbar-collapse collapse" style="padding-top: 5px;">
          <ul class="nav navbar-nav">
            <li <?php echo  $pg =='live' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>grlive" style="font-family: monospace, sans-serif, serif;font-size: 18px;"><i class="icon-eye-open"></i>&nbsp;<?php echo $this->lang->line('topmenu.live');?></a></li>
            <li <?php echo  $pg =='search' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>grsearch" style="font-family: monospace, sans-serif, serif;font-size: 18px;"><i class="icon-film"></i>&nbsp;<?php echo $this->lang->line('topmenu.search');?></a></li>
            <li <?php echo  $pg =='events' ? 'class="active"' : '' ?>><a href="<?php echo base_url(); ?>grevents" style="font-family: monospace, sans-serif, serif;font-size: 18px;"><i class="icon-warning-sign"></i>&nbsp;<?php echo $this->lang->line('topmenu.alert');?></a></li>
			<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo base_url(); ?>singout" style="font-family: monospace, sans-serif, serif;font-size: 18px;"><i class="icon-user"></i>&nbsp;<?php echo $this->lang->line('topmenu.account');?><span class="caret"></span></a>
				<ul class="dropdown-menu" roles="menu">
					<li>
						<a href="<?php echo base_url().'users/signout';?>"><i class="icon-off"></i>&nbsp;<?php echo $this->lang->line('topmenu.signout'); ?></a>
					</li>
					
				</ul>
			</li>
          </ul>
          
          <div class="navbar-right" style="margin-left: 5px">
          	<ul class="nav navbar-nav">
          		<li class="dropdown">
          			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
          			<?php
          			if ($this->session->userdata('site_lang') == 'portuguese') {?>
          				<img src="<?php echo HTTP_IMAGES_PATH.'pt.png';?>" style="width: 18px;" /></a>
          			<?php } else { ?>
          				<img src="<?php echo HTTP_IMAGES_PATH.'us.png';?>" style="width: 18px;" /></a>
          			<?php } ?>
          			<ul class="dropdown-menu">
          				<li><a href='<?php echo base_url(); ?>common/langswitch/switchLanguage/english'><img src="<?php echo HTTP_IMAGES_PATH.'us.png';?>" style="width: 24px;" />&nbsp;English</a></li>
          				<li><a href='<?php echo base_url(); ?>common/langswitch/switchLanguage/portuguese'><img src="<?php echo HTTP_IMAGES_PATH.'pt.png';?>" style="width: 24px;"/>&nbsp;Portuguese</a></li>
          			</ul>
          		</li>
          	</ul>
		</div>
			<div class="navbar-right clockbox">
				<div id="clockRow1"></div>
				<div id="clockRow2"></div>
			</div>
        </div><!--/.navbar-collapse -->
      </div>
    </div>
	<div id="subHeader-space">
	</div>      
	<div id="subHeader">
		
	<?php if ($pg == 'live') {?>
		<div id="liveSubHeader" >
			<input type="text" style="display: inline-block;margin-top: 5px;" placeholder="Search Camera" onkeyup="OnSearchCameras(this)">
			<div class="channel_select" id="channel_select" style="float: right;margin-right: 10px;">
				<ul>
					<li>2x1</li>
				</li>
			</div>
		</div>
	<?php } else { ?>
		<div id="">
			<input type="text" style="display: inline-block;margin-top: 5px;margin-left: 12px;" placeholder="Search Camera" onkeyup="OnSearchCameras(this)">
		</div>
	<?php } ?>
	
	</div>  
</div>
	<script type="text/javascript">
		cf_UpdateClock("clockRow1", "clockRow2");
	</script>
	
