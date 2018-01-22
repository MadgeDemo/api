<?php
defined('BASEPATH') or exit('No direct access allowed!');

require APPPATH . 'libraries/REST_Controller.php';
// require 'Data.php';
/**
* 
*/
class Service extends REST_Controller
{
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('service_model');
	}

	public function index_get($id = null)
	{
		$response = $this->service_model->get_services($id);

		if ($response) {
			$this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
       } else {
           $this->response([
                    'status' => FALSE,
                    'message' => 'No Data Found'
                ], REST_Controller::HTTP_NOT_FOUND);
       }
		
	}
}
?>