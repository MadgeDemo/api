<?php
(defined('BASEPATH') or exit('No direct script access allowed!'));

/**
* 
*/
class Orders_model extends CI_Model
{
	
	function __construct()
	{
		parent:: __construct();
	}

	function get_orders($email = null, $order = null)
	{
		if (empty($email)) return null;

		$this->db->select(['services.name as service', 'services.description', 'transactions.value', 'transactions.created_date', 'users.name as user', 'users.email', 'transactions.id as transactionsID', 'services.id as serviceID']);
		$this->db->from('transactions');
		$this->db->join('services', 'services.id = transactions.service_id');
		$this->db->join('users', 'transactions.user_id = users.id');
		$this->db->where('email', $email);
		if (!empty($order)) $this->db->where('transactions.id', $order);
		$query = $this->db->get()->result();

		if (empty($query)) return null;

		return $query;
	}
}
?>