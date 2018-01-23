<?php
(defined('BASEPATH') or exit('No direct script access allowed!'));

/**
* 
*/
class Service_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function get_services($id = null)
	{
		if ($id) $this->db->where('id',$id);
		$this->db->where('status', 1);
		$response = $this->db->get('services')->result();
		
		$data = array();
		foreach ($response as $key => $value) {
			$data[] = array(
							'ID' => $value->id,
							'name' => $value->name,
							'description' => $value->description,
							'price' => $value->value
						);
		}

		return $data;

	}
}
?>