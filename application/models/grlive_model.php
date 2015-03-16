<?php
/*
 *************************************************************************
* @filename        : grlive_model.php
* @description    : Model of Live
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.26   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Grlive_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');
    }
    
    function AddNewLayout() {
    	$str_name = $_POST['name'];
    	$str_cameraids = $_POST['cameraids'];
    	$str_channelName = $_POST['channelname'];
    	$str_userid = $this->session->userdata('id');

    	$str_sql = "INSERT INTO gr_layouts(name, userId, cameraIds, channelName) VALUES (?,?,?,?)";
    	$this->db->query($str_sql, array($str_name, $str_userid, $str_cameraids, $str_channelName));
    	$str_id = $this->db->insert_id();
    	$result = array(
    			'id' => $str_id,
    			'name' => $str_name,
    			'cameraids' => $str_cameraids,
    			'channelName' => $str_channelName
    			);
    	return $result;
    }
    
    function DeleteLayout() {
    	$str_id = $_POST['fav_id'];
    	// $str_userid = $this->session->userdata('id');
    	$str_sql = "DELETE FROM gr_layouts WHERE id=?";
    	$this->db->query( $str_sql, array($str_id) );
    	return array('result'=>'success'); 
    }
    
    function UpdateLayoutName() {
    	$str_id = $_POST['fav_id'];
    	$str_name = $_POST['name'];
    	$str_sql = "UPDATE gr_layouts SET name=? WHERE id=?";
    	$this->db->query( $str_sql, array($str_name, $str_id));
    	return array('result'=>'success');
    }
    
    // Get own layouts
    function GetOwnLayouts() {
    	$str_userid = $this->session->userdata( 'id' );
    	$str_sql = "SELECT * FROM gr_layouts WHERE userId=? AND deletedYN='N'";
    	$result = $this->db->query( $str_sql, array($str_userid) )->result();
    	
    	return $result;
    }
    
    // Get shared layouts
    function GetSharedLayouts() {
    	$str_userid = $this->session->userdata( 'id' );
    	$str_sql = "SELECT * FROM gr_layouts WHERE userId<>? AND deletedYN='N' AND sharedYN='Y'";
    	$result = $this->db->query( $str_sql, array($str_userid) )->result();
    	 
    	return $result;
    }
}

/* End of file grlive_model.php */
/* Location: ./application/models/grlive_model.php */