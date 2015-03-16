<?php
/*
 *************************************************************************
* @filename        : grsearch_model.php
* @description    : Model of Search 
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.26   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Grsearch_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');
    }
    
    public function GetLibraries() {
    	$str_userid 	= $this->session->userdata('id');
    	$str_masterid 	= $this->session->userdata('masterid'); 
    	$str_sql 		= "SELECT 
								gl.*
								, gu.user_name username,
								(SELECT 
									CONCAT('http://',gl.ipaddress, ':', gl.webport, '/uploads/',gl.videoFile) downurl
								FROM 
									gr_videoins gv,
									gr_locations gl
								WHERE 
									gv.locationId=gl.id
								AND
									gv.id=gl.deviceId) downurl
							FROM 
								gr_libraries gl
							LEFT JOIN 
								gr_users gu 
							ON 
								gl.userId=gu.id
							AND
    							gl.userId=?";
    	$ret_array 	= $this->db->query ( $str_sql, array ($str_userid) )->result();
    	return $ret_array;
    }
    
    public function AddNewLibrary()
    {
    	$str_userid 	= $this->session->userdata('id');
    	$str_masterid 	= $this->session->userdata('masterid');
    	$str_filename 	= $_POST['filename'];
    	$str_devicename	= $_POST['devicename'];
    	$str_deviceId	= $_POST['deviceid'];
    	$str_description= $_POST['description'];
    	$str_lcname		= $_POST['locationname'];
    	$str_sql 		= "INSERT INTO gr_libraries(userId, adminId, deviceName, deviceId, exportDate, description, locationName, videoFile) VALUES (?,?,?,?,CURRENT_TIMESTAMP(), ?,?,?)";
    	$this->db->query( $str_sql, array($str_userid, $str_masterid, $str_devicename, $str_deviceId, $str_description, $str_lcname, $str_filename) );
    	$result = array('result'=>'success', 'errmsg'=>'');
    	return $result;
    }
}

/* End of file grsearch_model.php */
/* Location: ./application/models/grsearch_model.php */