<?php
// Switch Language 
// Added by Raccoon 2014-07-01
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class LangSwitch extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
	}

	function switchLanguage($language = "") 
	{
		$language = ($language != "") ? $language : 'english';
		$this->session->set_userdata('site_lang', $language);
		
		redirect(base_url());
	}
}