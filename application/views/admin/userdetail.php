<?php
/*
 *************************************************************************
 * @filename	: userdetail.php
 * @description	: Details of a User
 *------------------------------------------------------------------------
 * VER  DATE         AUTHOR      DESCRIPTION
 * ---  -----------  ----------  -----------------------------------------
 * 1.0  2014.06.30   KCH         Initial
 * ---  -----------  ----------  -----------------------------------------
 * GRCenter Web Client
 *************************************************************************
 */
?>
<html>
	<head class="admin_body">
		<title></title>
	    <link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.base.css';?>" type="text/css" />
	    <script type="text/javascript" src="<?php echo HTTP_JQX_JS_PATH.'jquery-1.10.2.min.js';?>"></script>
	    
		<link href="<?php echo HTTP_CSS_PATH; ?>fam-icons.css" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo HTTP_CSS_PATH.'bootstrap.css';?>" type="text/css" />
		<link href="<?php echo HTTP_CSS_PATH; ?>custom.css" rel="stylesheet">
		<script type="text/javascript" src="<?php echo HTTP_JQX_JSW_PATH.'jqx-all.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'bootstrap.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'tab.js';?>"></script>
		<script type="text/javascript" src="<?php echo HTTP_JS_PATH.'jquery.form.js';?>"></script>
		<link rel="stylesheet" href="<?php echo HTTP_JQX_CSS_PATH.'jqx.ui-redmond.css';?>" media="screen">
		<link rel="stylesheet" href="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.css" >
        <script type="text/javascript" src="<?php echo base_url()?>assets/pnotify/pnotify.custom.min.js" ></script>
		<script type="text/javascript">
			var isBlocked = "<?php echo $userinfo[0]->status;?>";
			var str_curgids = "<?php echo $userinfo[0]->groupId;?>";
			var lst_gids = new Array();
			$(document).ready( function(){
				//assign servers to user
				$("a#assignServer").on('click', function(event) {
					var strLocIds = "";
					var objList = $("div#assignLocations").find("input:checkbox:checked");
					for( var i = 0 ; i < objList.size(); i ++ ){
						strLocIds += objList.eq(i).val();
						if( i != objList.size() - 1)
							strLocIds += ",";
					}
					var userid = <?php echo $userinfo[0]->id;?>;
					$.ajax({
					    url: "<?php echo base_url();?>admin/users/assign_servers",
		    	        dataType : "json",
		    	        type : "POST",
		    	        data : { userid: userid, locationIds: strLocIds},
		    	        success : function(data){	
		    	        	if( data.result == "success" ){	
		    	        		successAlert("<?php echo $this->lang->line('admin.users.msg.assignServers');?>");
		    	        	}
		    	        }
					});
				});
				$("#newpwd").jqxPasswordInput({ theme: 'classic', width: '200px', height: '20px', showStrength: true, showStrengthPosition: "right" });
			    $("#confirmpwd").jqxPasswordInput({ theme: 'classic', width: '200px', height: '20px' });
				$('#form_changepwd').jqxValidator({
					rules: [
						{ input: '#newpwd', message: 'Your password must be between 4 and 12 characters!', action: 'keyup, blur', rule: 'length=4,12' },                       
						{ input: '#confirmpwd', message: 'Password is required!', action: 'keyup, blur', rule: 'required' },
						{ input: '#confirmpwd', message: 'Passwords doesn\'t match!', action: 'keyup, focus', rule: function (input, commit) {
								// call commit with false, when you are doing server validation and you want to display a validation error on this field. 
								if (input.val() === $('#newpwd').val()) {                                   
									return true;                               
								}
								return false;
							}
						}
					],
					theme: 'ui-redmond'
				});
				$("a#btnUpdateUser").on('click', function() {
					
					//if( objList.size() == 0 ) {alert("<?php // echo $this->lang->line('admin.users.selectUserDelete');?>"); return;}
					
					$("#formupdateuser").ajaxSubmit({
						success: function() {
							successAlert("<?php echo $this->lang->line('admin.users.updateSuccess');?>");
						}
					});
					
				});

				$("div#assignLocations").find("input[type='checkbox']").click(function () {
					// $("#btnApply").jqxButton('disabled', false);
				});
				//change password form click function
				$("a#changePassword").click(function () {
					var str_pwd = $("#newpwd").val();
					if (str_pwd != "") {
						if ($('#form_changepwd').jqxValidator('validate') == false) return;
						$("#userpwd").attr('value', str_pwd);
					}
					$("form#form_changepwd").ajaxSubmit({
						success: function() {
							successAlert("<?php echo $this->lang->line('admin.users.updateSuccess');?>");
						}
					});
				});
				//
				$("#newpwd").on('input', function () {
					$("a#changePassword").removeAttr('disabled');
					if ($("#newpwd").val() == "") 
						$("a#changePassword").attr('disabled','disabled');
				});
			    //bootstrap tab change function 
				 $('#userTab a').click(function(e){
        			e.preventDefault();
        			$(this).tab('show');
				 });
			});
		    
			/*function EnableApplyButton() {
				if (isBlocked != 'A')
					return;
				var b_changed = false;

				if ($("#newpwd").val() != "")
					b_changed = true;
				
				$(".needupdate").each(function() {
					if (b_changed == true) return;
					var str_org = $(this).attr('orgval');
					var str_curval = $(this).val();
					if (str_curval != str_org) {
						b_changed = true;
						return;
					}
				});
				
				if (b_changed) {
					$("#btnApply").jqxButton('disabled', false);
				} else {
					$("#btnApply").jqxButton('disabled', true);
				}
			}*/
		</script>
	</head>
	<body style="overflow: auto;padding-top: 20px;padding-left: 2%;padding-right: 2%;">
		<div class="container" style="width: 100%;height: 100%;position: relative;min-width: 150px;">
			<div class="right-panel-header row">
    			<div class="col-sm-10">
    				<h2><?php echo $this->lang->line('admin.users');?></h2>
    				<p><?php echo $this->lang->line('admin.users.desc');?></p>
    			</div>
			    <div class="col-sm-2">
			    </div>
			</div>
			<ul id="userTab" class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#userDetail" role="tab" data-toggle="tab">User Detail</a></li>
			    <li><a href="#userPermission" role="tab" data-toggle="tab">Permission</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="userDetail">
        			<?php if ($this->session->userdata('group_id') < 3 && $this->session->userdata('group_id') > 0) {?>
        			<div class="panel panel-info margin-top-20">
        				<div class="panel-heading">
        					<?php echo $this->lang->line('admin.users.assignServers');?>
        				</div>
        				<div class="panel-body">
        				    <div class="checkbox" id="assignLocations">
        				    <?php 
        				        if ($locations == null) {
                                    echo $this->lang->line('admin.users.noServerAssign');
                                }
        				        foreach ($locations as $key => $value) {
        				            if ($value->adminId == "-1"){
                                        $checked = "";
                                    }else $checked = "checked";
        				    ?>
        				        <label style="margin-left: 20px;"><input type="checkbox" <?php echo $checked; ?> value="<?php echo $value->id;?>" /><?php echo $value->name;?></label>
        				    <?php }?>
        				    </div>
        				    <div class="col-md-12 col-sm-12 text-right">
                                <a href="#" class="btn btn-info" id="assignServer">Apply</a>
                            </div>    
        				</div>
        			</div>
        			<?php } ?>
        			<div class="panel panel-info margin-top-20">
        				<div class="panel-heading">
        					<?php echo $this->lang->line('admin.users.userinformation');?>
        				</div>
        				<div class="panel-body">
        					<form class="users_subform" method="post" id="formupdateuser" action="<?php echo base_url().'admin/users/edit_user';?>">
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.usergroup');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<span style="font-weight: bold"><?php echo $userinfo[0]->group_name;?></span>
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.username');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<span style="font-weight: bold"><?php echo $userinfo[0]->user_name;?></span>
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.label.firstname');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<input name="firstname" type="text" value="<?php echo $userinfo[0]->first_name;?>" class="needupdate"  orgval="<?php echo $userinfo[0]->first_name;?>">
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.label.lastname');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<input name="lastname" type="text" value="<?php echo $userinfo[0]->last_name;?>" class="needupdate"  orgval="<?php echo $userinfo[0]->last_name;?>">
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.emailaddress');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<input name="emailaddr" type="text" value="<?php echo $userinfo[0]->email;?>" class="needupdate" style="width: 100%;" orgval="<?php echo $userinfo[0]->email;?>">
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.createdon');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<?php echo $userinfo[0]->signup_date;?>
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.lastlocked');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<?php echo $userinfo[0]->blocked_date;?>
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.lastlogin');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<?php echo $userinfo[0]->signin_date;?>
        							</div>
        						</div>
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<span><?php echo $this->lang->line('admin.users.lastpwdChanged');?></span>
        							</div>
        							<div class="users_rowcontent">
        								<?php echo $userinfo[0]->changepwd_date;?>
        							</div>
        						</div>
        						<input type="hidden" name="userid" value="<?php echo $userinfo[0]->id;?>" id="userid">
        						<div class="col-md-12 col-sm-12 text-right">
                                    <a href="#" class="btn btn-info" id="btnUpdateUser">Apply</a>
                                </div>
        					</form>
        				</div>
        			</div>
        			<div class="panel panel-info margin-top-20">
        				<div class="panel-heading">
        					<?php echo $this->lang->line('admin.users.changepwd');?>
        				</div>
        				<div class="panel-body">
        					<form class="users_subform" id="form_changepwd" method="post" action="<?php echo base_url();?>admin/users/changePassword">
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<?php echo $this->lang->line('admin.users.newpwd');?>
        							</div>
        							<div class="users_rowcontent">
        								<input type="password" id="newpwd" name="newpwd" value="" >
        							</div>
        						</div>						
        						<div class="users_row">
        							<div class="users_rowtitle">
        								<?php echo $this->lang->line('admin.users.confirmpwd');?>
        							</div>
        							<div class="users_rowcontent">
        								<input type="password" id="confirmpwd" name="confirmpwd" value="">
        							</div>
        						</div>
        						<input type="hidden" name="userid" value="<?php echo $userinfo[0]->id;?>" id="userid">
        						<input type="hidden" name="username" value="<?php echo $userinfo[0]->user_name;?>" id="userid">
        						<div class="col-md-12 col-sm-12 text-right">
                                    <a class="btn btn-info" href="#" id="changePassword" disabled>Apply</a>
                                </div>
        					</form>
        				</div>
        			</div>
        		</div>
        		<div class="tab-pane" id="userPermission">
        		   <div class="panel panel-info margin-top-20">
        				<div class="panel-heading">
        					<?php echo $this->lang->line('admin.users.accessProperty');?>
        				</div>
        				<div class="panel-body">
        				    <div class="table-responsive">
        			  		 <form id="accessPropertyForm" method="post">
                              <table class="table table-bordered table-condensed small">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('admin.users.property');?></th>
                                    <th><?php echo $this->lang->line('admin.users.enable');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('admin.users.search');?></td>
                                        <td>
                                        <select>
                                            <option value="1">Enable</option>
                                            <option value="0">Disable</option>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('admin.users.live');?></td>
                                        <td>
                                        <select>
                                            <option value="1">Enable</option>
                                            <option value="0">Disable</option>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('admin.users.setup');?></td>
                                        <td>
                                        <select>
                                            <option value="1">Enable</option>
                                            <option value="0">Disable</option>
                                        </select>
                                        </td>
                                    </tr>
                                </tbody>
                              </table>
                              </form>
                            </div>
        				</div>
        			</div>
        			<div class="panel panel-info margin-top-20">
        				<div class="panel-heading">
        					<?php echo $this->lang->line('admin.users.accessCamera');?>
        				</div>
        				<div class="panel-body">
        				    <div class="table-responsive">
        			  		 <form id="accessPropertyForm" method="post">
                              <table class="table table-bordered table-condensed small">
                                <thead>
                                  <tr>
                                    <th><?php echo $this->lang->line('admin.users.access');?></th>
                                    <th><?php echo $this->lang->line('admin.users.cameras');?></th>
                                    <th><?php echo $this->lang->line('admin.users.live');?></th>
                                    <th><?php echo $this->lang->line('admin.users.ptz');?></th>
                                    <th><?php echo $this->lang->line('admin.users.events');?></th>
                                    <th><?php echo $this->lang->line('admin.users.search');?></th>
                                    <th><?php echo $this->lang->line('admin.users.aviExport');?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    </tr>
                                </tbody>
                              </table>
                              </form>
                            </div>
        				</div>
        			</div>
        		</div>
        	</div>
		</div><!-- /.container -->
		<?php $this->load->view('admin/admin_globaljs');?>
	</body>
</html>
