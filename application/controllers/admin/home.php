<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function index() {
        if ($this->session->userdata('is_admin_login')) {
            redirect('admin/dashboard');
       } else {
        	$this->load->view('admin/login');
       }
    }

    public function logout() {
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('user_type');
        $this->session->unset_userdata('is_admin_login');   
        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        redirect('admin/home', 'refresh');
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
    /* quick search camera */
    public function quickSearchCamera() {
        $searchValue = $_POST['quickSearchCamera'];
        
        $searchResult = $this->users_model->quickSearchCamera($searchValue);
        $data['searchResult'] = $searchResult;
        $this->load->view('admin/quickSearchCamera', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */