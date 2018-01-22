<?php
(defined('BASEPATH')) or exit('No direct script access allowed!');

/**
* 
*/
class Users_model extends CI_Model
{
	
	function __construct()
	{
		parent:: __construct();
	}

	function get_userTypes($id = null)
	{
		if ($id) $this->db->where('id',$id);
		$this->db->where('status', 1);
		$response = $this->db->get('user_types')->result();
		
		$data = array();
		foreach ($response as $key => $value) {
			$data[] = array(
						'id' => $value->id,
						'name' => $value->name
					);
		}

       return $data;
	}

	function get_Usertype($usertype=null)
	{
		$this->db->where('name',$usertype);
		$res = $this->db->get('usertype')->result_array();
		return $res[0]['ID'];
	}

	function sign_up($data=null)
	{
		return $this->db->insert('users',$data);
	}

	function authenticate($data=null)
	{
		$login = ['email' => $data['email'], 'password' => $data['password']];
		$this->db->where($login);
		$user = $this->db->get('users')->result();
		if (empty($user)) {
			return null;
		} else {
			foreach ($user as $key => $value) {
				// if (empty($value->firstLogin)) {
					return [
									'email' => $value->email,
									'name' => $value->name,
									'usertype' => self::get_userTypes($value->user_type_id)
								];;
				// } else {
				// 	if (empty($data['code'])) return "code";

				// 	if ($data['code'] != $value->firstLogin) return "wrong code";

				// 	$this->db->where($login);
				// 	$update = $this->db->update('users', ['firstLogin' => null]);
				// 	if ($update) return [
				// 					'email' => $value->email,
				// 					'name' => $value->name,
				// 					'usertype' => self::get_userTypes($value->user_type_id)
				// 				];
				// }
			}
		}
	}

	function checkUser($email=null,$number=null)
	{
		if ($number==null) {
			$this->db->where('email',$email);
		}else {
			$data = array('email'=>$email,'No'=>$number);
			$this->db->where($data);
		}
		
		return $this->db->get('users');
	}

	function activate($email=null,$No=null,$code=null)
	{
		$condition = array(
					'email' => $email,
					'No' => $No
				);
		$this->db->where($condition);
		return $this->db->update('users', array('firstTimeLogin'=>NULL));
	}

	function resetPassword($data)
	{
		$condition = array(
				"No" => $data['No'],
				"email" => $data['email']
			);
		$this->db->set('resetCode', $data['resetCode']);
		$this->db->where($condition);
		return $this->db->update('users');
	}

	function reset($code,$password)
	{
		$this->db->where('resetCode', $code);
		$data = $this->db->get('users')->result();

		if ($data) {
			$this->db->where('resetCode', $code);
			$this->db->set('password', $password);
			$this->db->set('resetCode', NULL);
			$this->db->update('users');
		}

		return $data;

	}

	function getAdmin()
	{
		$this->db->where('usertype', 1);
		$admin = $this->db->get('users')->result();

		return $admin[0]->email;
	}

	function getSalesPeoples()
	{
		// $data = json_decode(file_get_contents(base_url('assets/services/data.json')));
		// $data = json_encode($data->data);
		$data = $this->salesPeople('02');
		
		return $data;
	}

	function items()
	{
		return $this->itemList();
	}
}