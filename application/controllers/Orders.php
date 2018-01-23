<?php
(defined('BASEPATH') or exit('No direct script access allowed!'));

require APPPATH . 'libraries/REST_Controller.php';

/**
* 
*/
class Orders extends REST_Controller
{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('orders_model');
	}

	public function index_get()
	{
		$email = trim($this->get('email'));
		$order = trim($this->get('order'));

		if (empty($email)) $this->response([
										'status' => FALSE,
                    					'message' => 'No Data Found'
									], HTTP_BAD_REQUEST);

		$orders = $this->orders_model->get_orders($email, $order);

		if (empty($orders)) $this->response([
					                'status' => FALSE,
					                'message' => 'No data was found'
					            ], REST_Controller::HTTP_NOT_FOUND);

		$this->response([
					'status' => TRUE,
	                'message' => 'Data found',
	                'data' => $orders
	            ], REST_Controller::HTTP_OK);
	}
}
?>