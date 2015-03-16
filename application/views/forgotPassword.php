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
    		<div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
		    	<form class="form-inline form-signin panel" method="post" action="<?php echo base_url(); ?>users/forgot_emailSubmit">
		    		<a class="btn btn-info btn-sm margin-bottom-20" href="<?php echo base_url();?>"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp;Goto Log In</a>
		    		<?php print_r($this->session->flashdata('msg')); ?>
		    		<div class="form-signin-heading">
		        		<h2>Please input your Email Address</h2>
		        	</div>
		        	<div class="form-group col-md-8 col-md-offset-1 text-center">
				    	<div class="input-group col-md-12">
				      		<input class="form-control" type="email" placeholder="Enter email" name="emailAddress" id="emailAddress">
				    	</div>
				  	</div>
		        		<button type="submit" class="btn btn-success">Submit</button>
		      	</form>
		    </div>
      	</div>
    </div> <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>