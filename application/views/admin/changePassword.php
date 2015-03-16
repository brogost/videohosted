<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
    
    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
    <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
    <script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>

	<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
	<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'json3.min.js';?>"></script>
	
	<link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.classic.css';?>" media="screen">
	
	<!-- Bootstrap core CSS -->
    <link href="<?php echo HTTP_CSS_PATH; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo HTTP_CSS_PATH; ?>signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo HTTP_JS_PATH; ?>html5shiv.js"></script>
      <script src="<?php echo HTTP_JS_PATH; ?>respond.min.js"></script>
    <![endif]-->
    <script>
	$(document).ready(function(){
		$('#changePwdForm').jqxValidator({
			 rules: [
			{ input: '#currentPassword', message: 'Current Password is required!', action: 'keyup, blur',  rule: 'required' },
			{ input: '#newPassword', message: 'New Password is required!', action: 'keyup, blur',  rule: 'required' },
            { input: '#newPassword', message: 'Your password must be between 4 and 12 characters!', action: 'keyup, blur', rule: 'length=4,12' },
            { input: '#conPassword', message: 'Confirm Password is required!', action: 'keyup, blur', rule: 'required' },
            {
                input: '#conPassword', message: 'Passwords doesn\'t match!', action: 'keyup, focus', rule: function (input, commit) {
                    // call commit with false, when you are doing server validation and you want to display a validation error on this field. 
                    if (input.val() === $('#newPassword').val()) {
                        return true;
                    }
                    return false;
                }
            }
            ]
		});
		$("button#pwdSubmit").click(function(e){
			e.preventDefault();
			if($('#changePwdForm').jqxValidator('validate')){
				$('#changePwdForm').submit();
			}
			return;
		});
	});
    </script>
  </head>

  <body>
    <div class="container">
    	<div class="row">
    		<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
		    	<form class="form-signin panel" id="changePwdForm" method="post" action="<?php echo base_url(); ?>admin/users/changePasswordSubmit">
		    		<a class="btn btn-default btn-sm margin-bottom-20" href="<?php echo base_url();?>admin/"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp;Return Dashboard</a>
		        	<div class="form-signin-heading">
		        		<h2>Change Your Password</h2>
		        	</div>
		        	<?php print_r($this->session->flashdata('msg')); ?>
		        	<div class="form-group">
						<label for="exampleInputEmail1">Current Password</label>
					    <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Current Password">
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1">New Password</label>
					    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New Password">
					</div>
					<div class="form-group">
						<label for="exampleInputPassword1">Confirm Password</label>
					    <input type="password" class="form-control" id="conPassword" name="conPassword" placeholder="Confirm Password">
					</div>
		        	<div class="row">	
		        		<div class="col-md-10 col-md-offset-1">
		        			<button class="btn btn-block btn-success" id="pwdSubmit">Submit and Return Dashboard</button>
		        		</div>	
		        	</div>
		      	</form>
		    </div>
      	</div>
    </div> <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>