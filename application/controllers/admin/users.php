<?php
/*
 *************************************************************************
* @filename		: users.php
* @description	: Controller of Users
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.08   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        //if (!$this->session->userdata('is_admin_login')) {
        //    redirect('admin/');
       // }
        
        $this->load->model('users_model');
    }
	
    public function login() {
    
    	if ($this->session->userdata('is_admin_login')) {
    		redirect('admin/dashboard');
    	} else {
    		$user = $_POST['username'];
    		$password = $_POST['password'];
    
    		$result = $this->users_model->checkLogIn($user, $password);

    		if($result > 0)
    			redirect('admin/dashboard');
    		else{
    			$this->session->set_flashdata('msg', '<div class="alert alert-danger">Username or Email is Incorrect.</div>');
    			redirect('admin/');
    		}
    	}
    }
    
    public function change_password(){
    	$loggedInId = $this->session->userdata('id');
    	//$result = $this->users_model->GetUserDetail($uid);
    	//$data['userinfo'] = $result;
    	$this->load->view('admin/changePassword');
    }
    /* now logged in user change password function  */
    public function changePasswordSubmit(){
    	$currentPassword = $_POST['currentPassword'];
    	$newPassword = $_POST['newPassword'];
    	
    	$result = $this->users_model->changeAdminPassword($currentPassword, $newPassword);
    	
    	if(!$result){
    		$this->session->set_flashdata('msg', '<div class="alert alert-danger">Current Password Incorrect.</div>');
    		redirect('admin/users/change_password');
    	}else{
    		//$this->session->set_flashdata('msg', '<div class="alert alert-success">Password Updated Successfully.</div>');
    		redirect('admin/');
    	}
    		redirect('admin/');
    }
	public function logout(){
	
    	$this->users_model->signout();
        redirect('admin/');

	}
	
    public function index() {
        $arr['page'] = 'user';
        $this->load->view('admin/vwManageUser',$arr);
    }

    public function add_user() {
        $result = $this->users_model->AddNewUser();
        die(json_encode($result));
    }

     public function edit_user() {
        $this->users_model->UpdateUser();
        die("");
    }
    
     public function block_user() {

     }
    
    public function delete_user($uid) {
        $result = $this->users_model->DeleteUser($uid);
        if ($result) 
        	die('success');
        else
        	die('failed');
	}
	
	public function get_all_users() {
		$result = $this->users_model->GetAllUsers();
		
		if ($result) 
			die(json_encode($result));
		else
			die('null');
	}
	
	public function user_detail($uid) {
		$result = $this->users_model->GetUserDetail($uid);
		if ($this->session->userdata('group_id') < 3 && $this->session->userdata('group_id') > 0) {
		    $locations = $this->users_model->GetAllLocations($uid);
		    $data['locations'] = $locations;
		}
		$data['userinfo'] = $result;
		$this->load->view('admin/userdetail', $data);
	}
	/*assign location to users  */
	public function assign_servers () {
	    $result = $this->users_model->assignServers();
	    header('Content-Type: application/json');
	    echo json_encode($result);
	}
	
	/* user detail page user password change function  */
	public function changePassword() {
	     $result = $this->users_model->userChangePassword();
	    header('Content-Type: application/json');
	    echo json_encode($result);
	}
	
}

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */
