<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
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
  </head>

  <body>
    <div class="container">
    	<div class="row">
    		<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
		    	<form class="form-signin panel" method="post" action="<?php echo base_url(); ?>users/login">
		      		<?php print_r($this->session->flashdata('msg')); ?>
		        	<div class="form-signin-heading">
		        		<h2>Sign In</h2>
		        	</div>
		        	<div class="input-group margin-bottom-20">
                    	<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
		        		<input type="text" class="form-control" placeholder="Username" name="username" autofocus>
		        	</div>
		        	<div class="input-group margin-bottom-20">
                    	<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
		        		<input type="password" class="form-control" placeholder="Password" name="password">
		        	</div>
		        	<div class="checkbox">
                        <label>
                          <input type="checkbox" name='tecSupport'> Technical Support
                        </label>
                    </div>
                    <div class="form-group margin-bottom-20 hide">
		        		<textarea class="form-control" placeholder="Description" name="tecDescription"></textarea>
		        	</div>
		        	<div class="input-group margin-bottom-20">
		        		<a href="<?php echo base_url()?>users/forgot_password">Forgot Password?</a>
		        	</div>
		        	<div class="row">	
		        	<div class="col-md-10 col-md-offset-1">
		        		<button class="btn btn-block btn-success" type="submit">Sign In</button>
		        	</div>	
		        	</div>
		      	</form>
		    </div>
      	</div>
    </div> <!-- /container -->
    <script src="<?php echo HTTP_JS_PATH; ?>jquery.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>custom/login_page.js"></script>
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>