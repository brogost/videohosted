<?php
/*
 *************************************************************************
* @filename		: users.php
* @description	: Controller of Users ( front-end )
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.24   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct() {
		parent::__construct();
	
		$this->load->model('users_model');
		
		$this->load->helper('string');
	}
	
	public function index() {
		if ($this->session->userdata('is_front_login')) {
			redirect('home');
		} else {
			$this->load->view('login');
		}
	}
	
	public function login() {
	
		if ($this->session->userdata('is_front_login') == 1) {
			redirect('home');
		} else {
			$user = $_POST['username'];
			$password = $_POST['password'];
			$techDescription = $_POST['tecDescription'];
			if (isset($_POST['tecSupport'])) {
			    $techSupported = 1;
			    if ($techDescription == "") {
			        $this->session->set_flashdata('msg', '<div class="alert alert-danger">Please Input Description.</div>');
			        redirect('users');
			    }
			} else $techSupported = 0;
			    
			$result = $this->users_model->CheckFrontUserLogin($user, $password, $techSupported, $techDescription);
	
			if($result > 0) 
			{
				redirect('grlive');
			}
			else
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-danger">Username or Password is Incorrect.</div>');
				redirect('users');
			}
		}
	}
    public function forgot_password(){
    	$this->load->view('forgotPassword');
    }
    public function forgot_emailSubmit(){
    	$emailAddress = $_POST['emailAddress'];
    	$tempPwd = random_string('alnum', 8);
    	
    	$result = $this->users_model->CheckFrontUserResetPwd($emailAddress, $tempPwd);
    	if($result){
	    	$config = Array(
	    			'protocol' => 'smtp',
	    			'smtp_host' => 'smtp.gmail.com',
	    			'smtp_port' => 465,
	    			'smtp_crypto' => 'ssl',
	    			'smtp_user' => 'jenistar90@gmail.com',
	    			'smtp_pass' => 'smilejeni20!4',
	    			'mailtype'  => 'html',
	    			'charset'   => 'iso-8859-1'
	    	);
	    	$this->load->library('email', $config);
	    	$this->email->set_newline("\r\n");
	    	
	    	$this->email->from("Support Team", "grcenter");
	    	$this->email->to("$emailAddress");
	    	
	    	$msgContent = "Your account Password has reset.<br>Password: ".$tempPwd;
	    	$this->email->subject('Email Reset');
	    	$this->email->message("$msgContent");
	    	 
	    	$sendResult = $this->email->send();
	    	if($sendResult)
	    		$this->session->set_flashdata('msg', '<div class="alert alert-success">Your password has reset.Please check your email address.</div>');
    	}else
    		$this->session->set_flashdata('msg', '<div class="alert alert-danger">Your email address is incorrect.</div>');
    	redirect('users/forgot_password');
    	
    }
    public function signout() {
        $this->users_model->signout();
        $this->load->view('login');
    }
    
}

/* End of file groups.php */
/* Location: ./application/controllers/admin/groups.php */