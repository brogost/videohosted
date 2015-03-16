<?php
/*
 *************************************************************************
 * @filename	: archive_server_defaults.php
 * @description	: see the archive server default server information
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.07.22   chanry         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<html>
	<head class="admin_body">
		<title></title>
	    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
	    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.bootstrap.css';?>" type="text/css" />
	    <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
	
		<link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
		<script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
	    <link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'bootstrap.css';?>" type="text/css" />
	    <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
	    
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'tab.js';?>"></script>
		<link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.ui-redmond.css';?>" media="screen">
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	    <script type="text/javascript">
		  $(document).ready(function(){
		
		  });	
		  function onDefaultsSave(){
			  var defaultsId = "";
			<?php if( $defaults[0]->id && $defaults[0]->id != "")?>
			  defaultsId = <?php echo $defaults[0]->id; ?>;
			  var description = $("#description").val();
			  var lanIpAddress = $("#lanIpAddress").val();
			  var wanIpAddress = $("#wanIpAddress").val();
			  var dnsName = $("#dnsName").val();

			  $.ajax({
				   url: "<?php echo base_url().'admin/defaults/save_archiveServerInfo/';?>",
		           cache : false,
		       	   dataType : "json",
		           type : "POST",
		           data : { defaultsId: defaultsId, description : description, lanIpAddress : lanIpAddress, wanIpAddress : wanIpAddress, dnsName : dnsName },
		           success: function(data) {
		        	   if(data.result == "success"){
		        		  	alert("Updated Successfully");
		               }else
			               alert("Failed!");
		           }
			});
		  }	
			 		     
   		 </script>
	</head>
	<body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
		<div class="container" style="width: 100%; max-width: 100%; height: 100%; min-width: 150px; display: inline-block;">
			<div class="right-panel-header row">
				<div class="col-sm-10">
					<h2>Archive Server Defaults</h2>
					<p>Predefined settings which are used to create archive servers in the system.</p>
				</div>		
				<div class="col-sm-2 margin-top-20">
					<button class="btn btn-info btn-sm" onclick="onDefaultsSave();">Apply</button>
				</div>		
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div class="panel panel-info margin-top-20">
				  		<div class="panel-heading">Active Scheduled Groups</div>	
						<div class="panel-body">
					  		<div class="form-horizontal">
					      		<div class="form-group form-group-sm">
					        		<label for="inputEmail3" class="col-sm-4 control-label">Description</label>
					        		<div class="col-sm-6">
					          			<input type="email" class="form-control" id="description" placeholder="description" value="<?php echo $defaults[0]->name;?>">
					        		</div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="inputPassword3" class="col-sm-4 control-label">LAN IP Address</label>
							        <div class="col-sm-6">
							        	<input type="text" class="form-control" id="lanIpAddress" placeholder="" value="<?php echo $defaults[0]->ipaddress;?>">
							        </div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="inputPassword3" class="col-sm-4 control-label">WAN IP Address</label>
							        <div class="col-sm-6">
							        	<input type="text" class="form-control" id="wanIpAddress" placeholder="" value="<?php echo $defaults[0]->wanipaddress;?>">
							        </div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="inputPassword3" class="col-sm-4 control-label" >DNS Name</label>
							        <div class="col-sm-6">
							        	<input type="text" class="form-control" id="dnsName" placeholder="" value="<?php echo $defaults[0]->dnsname;?>">
							        </div>
					      		</div>
	    					</div>
					  	</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<div class="panel panel-info margin-top-20">
				  		<div class="panel-heading">Email Notifications</div>	
					  	<div class="panel-body">
					  		<div class="form-horizontal">
					      		<div class="form-group form-group-sm">
					        		<label for="inputEmail3" class="col-sm-4 control-label">SMTP Server</label>
					        		<div class="col-sm-6">
					          			<input type="text" class="form-control" id="inputEmail3" placeholder="" value="<?php echo SMTP_SERVER; ?>" disabled />
					        		</div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="inputEmail3" class="col-sm-4 control-label">SMTP Port</label>
					        		<div class="col-sm-6">
					          			<input type="text" class="form-control" id="inputEmail3" placeholder="" value="<?php echo SMTP_PORT; ?>" disabled>
					        		</div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="inputEmail3" class="col-sm-4 control-label">SMTP Account</label>
					        		<div class="col-sm-6">
					          			<input type="text" class="form-control" id="inputEmail3" placeholder="" value="<?php echo SMTP_ACCOUNT; ?>" disabled>
					        		</div>
					      		</div>
					      		<div class="form-group form-group-sm">
					        		<label for="inputEmail3" class="col-sm-4 control-label">SMTP Password</label>
					        		<div class="col-sm-6">
					          			<input type="text" class="form-control" id="inputEmail3" placeholder="" value="<?php echo SMTP_PASSWORD; ?>" disabled>
					        		</div>
					      		</div>
		    				</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.container -->
	</body>

</html>
