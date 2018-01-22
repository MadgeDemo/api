<?php
(defined('BASEPATH')) or exit('No direct script access allowed!');

/**
* 
*/
class UserTypes_model extends CI_Model
{
	
	function __construct()
	{
		parent:: __construct();
	}

	function get_userTypes()
	{
		$data = $this->db->get('user_types')->result();
		echo "<pre>";print_r($data);die();
	}
}
?>