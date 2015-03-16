<?php
/*
 *************************************************************************
* @filename        : grevents_model.php
* @description    : Model of Events 
*------------------------------------------------------------------------
* VER  DATE         AUTHOR      DESCRIPTION
* ---  -----------  ----------  -----------------------------------------
* 1.0  2014.07.26   KCH         Initial
* ---  -----------  ----------  -----------------------------------------
* GRCenter Web Client
*************************************************************************
*/
class Grevents_model extends CI_Model {

    function __construct()
    {
        parent::__construct();

        $this->load->model('common_model');
    }
}

/* End of file grevents_model.php */
/* Location: ./application/models/grevents_model.php */