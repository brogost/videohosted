<?php
/*
 *************************************************************************
* @filename		: users_model.php
* @description	: Model of Users
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.08   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Users_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('common_model');
	}
	
	function GetAllUsers() {
	    $groupId = $this->session->userdata('group_id');
		$str_sql = "SELECT id, user_name, status FROM gr_users WHERE deletedYN='N' AND isadmin='0' AND groupId=$groupId ";
		return $this->db->query($str_sql)->result();
	}
	
	function AddNewUser() {
		$str_firstname = $_POST['txtUserFirstName'];
		$str_lastname = $_POST['txtUserLastName'];
		$str_loginname = $_POST['txtUserLoginname'];
		$str_password = $_POST['txtUserPwd'];
		$group_id = $_POST['userGroupId'];
		$str_emailaddr = isset($_POST['txtUserEmail'])?$_POST['txtUserEmail']:'';
		//user name email check
		$str_sql = "select * from gr_users where user_name = ? or email = ?";
		$params = array(
		                'user_name' => $str_loginname,
		                'email' => $str_emailaddr
		            );
		$resultUser = $this->db->query($str_sql, $params)->result();
		if (count($resultUser) > 0) {
		    $result['id'] = -1;
		    return $result;
		}
		$str_salt = $this->common_model->GenerateRndString();
		$str_pwd = md5($str_salt.$str_password);
		$adminId = $this->session->userdata('id');
		$str_sql = "INSERT INTO gr_users (user_name, password, first_name, last_name, email, signup_date, salt, groupId, adminId) 
		            VALUES ('".$str_loginname."', '".$str_pwd."', '".$str_firstname."', '".$str_lastname."', '".$str_emailaddr."', CURRENT_TIMESTAMP(),'".$str_salt."', '".$group_id."', '".$adminId."')";
		$this->db->query($str_sql);
		
		$str_id = $this->db->insert_id();
		
		$result['id'] = $str_id;
		$result['name'] = $str_loginname;
		//event logs
		$loggedUserName = $this->session->userdata('username');
		$eventMsg  = "One User(Name:".$str_loginname.") added by ".$loggedUserName;
		$this->common_model->saveEventLog(4, $str_id, "user", $eventMsg);
		
		return $result;
	}
	
	//check login 
	function checkLogIn($user, $password) {
		$salt = $this->GetSalt($user);//'5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
		if (!$salt)
			return false;
		
		$enc_pass  = md5($salt.$password);
		
		$sql = "SELECT * FROM gr_users WHERE user_name = ? AND password = ? AND deletedYN='N' AND groupId IN(1,2,3) LIMIT 1";
		$val = $this->db->query($sql,array($user, $enc_pass ));
		
		if ($val->num_rows) {
			$groupName = '';
			foreach ($val->result_array() as $recs => $res) {
			    $groupName = $this->GetGroupById($res['groupId']);
			    $nowGroupName = $this->GetGroupById($res['groupId'] - 1);
				$this->session->set_userdata(array(
						'id' => $res['id'],
						'username' => $res['user_name'],
						'email' => $res['email'],
						'is_admin_login' => true,
						'user_type' => $res['isadmin'],
				        'group_name' => $groupName,
				        'group_id' => ($res['groupId'] + 1),
				        'now_group_name' => $nowGroupName    
						)
				);
			}
			//event logs
			$loggedUserName = $res['user_name'];
			$eventMsg  = "User(Name:".$loggedUserName.") Logged in (group: ".$groupName.")";
			$this->common_model->saveEventLog(1, $res['id'], "user", $eventMsg);
			
			return $this->UpdateLoginTime($this->session->userdata('id'));
			
		}else{
		    $loggedUserName = $res['user_name'];
		    $eventMsg  = "User(Name:".$user.") log in failed";
		    $this->common_model->saveEventLog(3, '-1', "user", $eventMsg);
			return false;
		}
	}
	
	function CheckFrontUserLogin($user, $password, $techSupported, $techDescription) {
		$salt = $this->GetSalt($user);//'5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
		if (!$salt)
			return false;
		
		$enc_pass  = md5($salt.$password);
		if ($techSupported == 0)
		    $sql = "SELECT * FROM gr_users WHERE user_name = ? AND password = ? AND deletedYN='N' AND groupId=4 LIMIT 1";
		else { 
		    $sql = "SELECT * FROM gr_users WHERE user_name = ? AND password = ? AND deletedYN='N' AND groupId IN(1,2,4) LIMIT 1";
		}
		$val = $this->db->query($sql,array($user, $enc_pass ));
		
		if ($val->num_rows) {
			$groupName = '';
			foreach ($val->result_array() as $recs => $res) {
				$groupName = $this->GetGroupById($res['groupId']);
				$this->session->set_userdata(array(
						'id' 		=> $res['id'],
						'username' 	=> $res['user_name'],
						'masterid' 	=> $res['adminId'],
						'groupid'	=> $res['groupId'],
						'email' => $res['email'],
						'is_front_login' => true
					)
				);
			}
			//event logs
			$loggedUserName = $res['user_name'];
			
			if ($techSupported == 0) {
    			$eventMsg  = "Front User(Name:".$loggedUserName.") Logged in (group: ".$groupName.")";
			} else {
			    $eventMsg  = "Admin User(Name:".$loggedUserName.") Logged in Front Side(group: ".$groupName.") for ".$techDescription;
			}
			$this->common_model->saveEventLog(1, $res['id'], "user", $eventMsg);
			
			return $this->UpdateLoginTime($this->session->userdata('id'));
		}else{
		    $loggedUserName = $res['user_name'];
		    $eventMsg  = "Front User(Name:".$user.") log in failed";
		    $this->common_model->saveEventLog(3, '-1', "user", $eventMsg);
			return false;
		}
	}
	
	function UpdateLoginTime($uid) { 
		$str_sql = "UPDATE gr_users SET signin_date=CURRENT_TIMESTAMP() WHERE id=?";
		$this->db->query($str_sql, array($uid));
		return true;
	}
	
	function GetUserDetail( $str_uid ) {
		$str_sql = "SELECT gu.id, gu.user_name, gu.first_name, gu.last_name, gu.email, gu.signin_date, gu.signup_date, gu.changepwd_date, gu.groupId, gu.status, gu.blocked_date, gg.name AS group_name 
		              FROM gr_users gu, gr_groups gg WHERE gu.groupId = gg.id AND gu.id=?";
		$params = array('uid'=>$str_uid);
		$result = $this->db->query($str_sql, $params);
		
		return $result->result();
	}
	
	function DeleteUser($str_uid) {
		$str_sql = "UPDATE gr_users SET deletedYN='Y' WHERE id=?";
		$params = array('uid'=>$str_uid);
		$this->db->query($str_sql, $params);
		
		//event logs
		$loggedUserName = $this->session->userdata('username');
		$eventMsg  = "One User Removed by ".$loggedUserName;
		$this->common_model->saveEventLog(4, $str_uid, "user", $eventMsg);
		
		return true;
	}
	function assignServers () {
        $locationLists = $_POST['locationIds'];
        $str_uid = $_POST['userid'];
        
        $str_sql = "UPDATE gr_locations SET adminId = '-1' WHERE adminId = $str_uid";
        $result = $this->db->query($str_sql);
        if ($locationLists != "") {
            $str_sql = "UPDATE gr_locations SET adminId = '".$str_uid."' WHERE id IN ($locationLists)";
            $result = $this->db->query($str_sql);
	    }
	    //event logs
	    $loggedUserName = $this->session->userdata('username');
	    if ($locationLists != "")
	        $eventMsg  = "Location assigned to user by ".$loggedUserName;
	    else $eventMsg  = "Location removed from user by ".$loggedUserName;
	    $this->common_model->saveEventLog(4, $str_uid, "user", $eventMsg);
        $data['result'] = 'success'; 
        return $data;
	}
	function UpdateUser() {
		$str_uid = $_POST['userid'];
		
		$str_sql = "";
		
			$str_sql = "UPDATE gr_users SET first_name=?, last_name=?, email=? WHERE id=?";
			$params = array(
					'first_name' => $_POST['firstname'],
					'last_name' => $_POST['lastname'],
					'email' => $_POST['emailaddr'],
					'uid' => $str_uid
			        );
			$this->db->query($str_sql, $params);
		//event logs
		$loggedUserName = $this->session->userdata('username');
		$eventMsg  = "User Info Name Changed by ".$loggedUserName;
		$this->common_model->saveEventLog(4, $str_uid, "user", $eventMsg);
		
		return true;			
	}
	//user change password functin 
	function userChangePassword () {
	    $str_pwd = $_POST['newpwd'];
	    $str_uid = $_POST['userid'];
	    $str_name = $_POST['username'];
	    $str_salt = $this->GetSalt($str_name);
	    if ($str_pwd != "") {
	        $str_sql = "UPDATE gr_users SET password=?, changepwd_date=CURRENT_TIMESTAMP() WHERE id=?";
	        $params = array(
	                        'password'=> md5($str_salt.$str_pwd),
	                        'uid' => $str_uid
	        );
	        $this->db->query($str_sql, $params);
	        $data['result'] = 'success';
	        //event logs
	        $loggedUserName = $this->session->userdata('username');
	        $eventMsg = "User Info Password Changed by ".$loggedUserName;
	        $this->common_model->saveEventLog(4, $str_uid, "user", $eventMsg);
	    } else $data['result'] = 'failed';
	    
	    return $data;
	}
	function GetSalt($str_uid) {
		$str_sql = "SELECT salt FROM gr_users WHERE user_name=? LIMIT 1";
		$params = array('uid'=>$str_uid);
		$result = $this->db->query($str_sql, $params)->result();

		if ($result) 
			return $result[0]->salt;
		else 
			return null;
	}
    /*  nav bar global page current user change password function*/
	function changeAdminPassword($currentPassword, $newPassword){
		$loggedInId = $this->session->userdata('id');
		
		$str_sql = "SELECT salt FROM gr_users WHERE id=? LIMIT 1";
		$params = array('id'=>$loggedInId);
		$salt = $this->db->query($str_sql, $params)->result();
		$str_salt = $salt[0]->salt;
		$str_sql = "SELECT * FROM gr_users WHERE id=? AND password=? LIMIT 1";
		$params = array('id'=>$loggedInId,
						'password'=>md5($str_salt.$currentPassword)
						);
		$passExist = $this->db->query($str_sql, $params)->result();
		if(count($passExist)>0){
			$str_sql = "UPDATE gr_users SET password=? WHERE id=?";
			$params = array('password'=>md5($str_salt.$newPassword),
							'id'=>$loggedInId
							);
			$result = $this->db->query($str_sql, $params);
			//event logs
			$loggedUserName = $this->session->userdata('username');
			$eventMsg = "Current logged in User Info Password Changed by himself";
			$this->common_model->saveEventLog(2, $loggedInId, "user", $eventMsg);
			
			return $result;
		}else 
			return false;
		
	}
	
	function CheckFrontUserResetPwd($emailAddress, $tempPwd) {
		$str_sql = "SELECT salt FROM gr_users WHERE email=? LIMIT 1";
		$params = array('email'=>$emailAddress);
		$str_salt = $this->db->query($str_sql, $params)->result();
		if(count($str_salt) > 0){
			$str_sql = "UPDATE gr_users SET password=? WHERE email=?";
			$params = array('password'=>md5($str_salt[0]->salt.$tempPwd),
					'email'=>$emailAddress
			);
			$result = $this->db->query($str_sql, $params);
			return $result;
		}else 
			return false;
	}
    function signout() {
        $str_uid = '';
        $str_uid = $this->session->userdata('id');
        if ($str_uid) {
            // $str_sql = "UPDATE gr_users SET signout_date=CURRENT_TIMESTAMP() WHERE id=?";
            // $this->db->query($str_sql, array($str_uid));
            //event logs
            $loggedUserName = $this->session->userdata('username');
            $eventMsg = "User (Name: $loggedUserName) Logged Out";
            $this->common_model->saveEventLog(2, $str_uid, "user", $eventMsg);
            
            $this->session->sess_destroy();
        }
        return true;
    }
    
    function quickSearchCamera($searchValue) {
        $str_sql = "SELECT gv.*,gr.name as location_name FROM gr_videoins gv, gr_locations gr WHERE gv.locationId = gr.id AND gv.deletedYN = 'N' AND gv.videoInName LIKE '%".$searchValue."%'";
//         $params = array('id'=>$loggedInId,
//                         'password'=>md5($str_salt[0]->salt.$currentPassword)
//         );
        $result = $this->db->query($str_sql)->result();
        return $result;
    }
    
    function GetGroupById ($gId) {
        $groupId = $gId + 1;
        $str_sql = "SELECT * FROM gr_groups WHERE deletedYN = 'N' AND id = ?";
        $params = array('id'=>$groupId);
        $result = $this->db->query($str_sql, $params)->result();
        return $result ? $result[0]->name:null;
    }
    
    //get all locations 
    function GetAllLocations ($uId) {
        $str_sql = "SELECT * FROM gr_locations WHERE deletedYN = 'N' AND (adminId = '-1' OR adminId = '".$uId."')";
        $result = $this->db->query($str_sql)->result();
        return $result;
    }
    
}

/* End of file users_model.php */
/* Location: ./application/models/admin/users_model.php */