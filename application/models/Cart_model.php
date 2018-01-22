<?php
(defined('BASEPATH') or exit('No direct script access allowed!'));

/**
* 
*/
class Cart_model extends CI_Model
{
	
	function __construct()
	{
		parent:: __construct();
	}

	function get_user($email = null)
	{
		if (empty($email)) return null;

		$this->db->where('email', $email);
		$user = $this->db->get('users')->result();

		return $user[0]->id;
	}

	function get_service($service = null)
	{
		if (empty($service)) return null;

		$this->db->where('id', $service);
		$resp = $this->db->get('services')->result();

		if (empty($resp)) return null;

		return $resp[0];
	}

	function get_cart($email = null)
	{
		$usr = self::get_user($email);
		if (empty($usr)) return null;

		$this->db->where(['user_id' => $usr, 'status' => 1]);
		return $this->db->get('cart')->result();
	}

	function add_cart($email = null, $service = null)
	{
		$usr = self::get_user($email);	
		if (empty($usr)) return null;

		$srv = self::get_service($service);
		if (empty($srv)) return null;

		$data = [
					'user_id' => $usr,
					'service_id' => $srv->id,
					'value' => $srv->value
				];

		return $this->db->insert('cart', $data);
	}

	function checkout($email = null)
	{
		if (empty($email)) return null;
		$usr = self::get_user($email);	
		if (empty($usr)) return null;

		$this->db->where('user_id', $usr);
		$cart = $this->db->get('cart')->result();
		if (empty($cart)) return null;

		$price = 0;
		$data = [];
		foreach ($cart as $key => $value) {
			$data[] = [
						'user_id' => $value->user_id,
						'service_id' => $value->service_id,
						'value' => $value->value
					];
			// $services[]
			$price += $value->value;
		}

		$this->db->insert_batch('transactions', $data);

		$this->db->where('user_id', $usr);
		$this->db->update('cart', ['status' => 0]);

		return $price;
	}
}
?>													