<?php
(defined('BASEPATH') or exit('No direct script access allowed!'));

require APPPATH . 'libraries/REST_Controller.php';
/**
* 
*/
class Cart extends REST_Controller
{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('cart_model');
	}

	public function index_get()
	{
		$email = trim($this->get('email'));
		if (empty($email)) $this->response([
		                    'status' => FALSE,
			                'message' => 'Incorrect information provided'
			            ], REST_Controller::HTTP_BAD_REQUEST);

		$cart = $this->cart_model->get_cart($email);

		if (empty($cart)) $this->response([
									'status' => FALSE,
									'message' => 'No services found in cart'
								], HTTP_NOT_FOUND);
		$this->response([
				'status' => TRUE,
				'message' => 'Cart data found',
				'data' => $cart
			], HTTP_OK);
	}

	public function index_post()
	{
		$email = trim($this->post('email'));
		$service = trim($this->post('service'));

		$cart = $this->cart_model->add_cart($email, $service);
		if (empty($cart)) $this->response([
		                    'status' => FALSE,
			                'message' => 'Unable to add service to cart'
			            ], REST_Controller::HTTP_BAD_REQUEST);

		$this->response([
				'status' => TRUE,
				'message' => 'Successfully added service to cart'
			], HTTP_OK);
	}

	public function checkout_post()
	{
		$email = trim($this->post('email'));

		$checkout = $this->cart_model->checkout($email);
		if (empty($checkout)) $this->response([
		                    'status' => FALSE,
			                'message' => 'Unable to complete order'
			            ], REST_Controller::HTTP_BAD_REQUEST);

		$this->response([
				'status' => TRUE,
				'message' => 'Successfully added order, move to payment',
				'data' => $checkout
			], HTTP_OK);
	}
}