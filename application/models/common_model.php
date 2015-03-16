<?php
class Common_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function GenerateRndString($length = 32) {
		$str_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$str_rndstring = '';
		for ($i = 0; $i < $length; $i ++) {
			$str_rndstring .= $str_characters[rand(0, strlen($str_characters) - 1)];
		}
		
		return $str_rndstring;
	}
	
	function print_log( $str_log )
	{
		// open file
		$fd = fopen($config['log_path'], "a");
		// append date/time to message
		$str = "[" . date($config['log_date_format'], time()) . "] : " . $str_log;
		// write string
		fwrite($fd, $str . "\n");
		// close file
		fclose($fd);
	}
	
	function saveEventLog ($type, $sourceIndex, $sourceName, $msg) {
	    $userId = $this->session->userdata('id');
	    $str_sql = "INSERT INTO gr_eventlogs (eventType, eventSourceIndex, eventSourceName, eventTime, eventMessage, admin_id) 
	                VALUES (?,?,?,now(),?,?)";
	    $params = array (
	                    'eventType' 		=> $type,
	                    'eventSourceIndex' 	=> $sourceIndex,
	                    'eventSourceName' 	=> $sourceName,
	                    'eventMessage' 		=> $msg,
	                    'admin_id' 		=> $userId
	    );
	    
	    $result = $this->db->query( $str_sql, $params);
	}
}