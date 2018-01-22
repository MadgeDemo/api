<?php
defined('BASEPATH') or exit('No direct access allowed!');

require APPPATH . 'libraries/REST_Controller.php';
require 'Data.php';
/**
* 
*/
class Inventory extends REST_Controller
{
	
	function __construct()
	{
		parent:: __construct();

		$this->methods['items_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['items_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['items_delete']['limit'] = 50; // 50 requests per hour per user/key
        
	}

	public function items_get()
	{
		$Obj = new Data();
		$data = $Obj->itemList();

        $id = $this->get('No');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($data)
            {
                // Set the response and exit
                $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No data was found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = trim($id);

        // // Validate the id.
        // if ($id <= 0)
        // {
        // 	echo "<pre>";print_r('Bad Request apparently');die();
        //     // Invalid id, set the response and exit.
        //     $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        // }

        // // Get the user from the array, using the id as key for retrieval.
        // // Usually a model is to be used for this.

        $user = NULL;
        // echo "<pre>";print_r($data);die();
        if (!empty($data))
        {
            foreach ($data as $key => $value)
            {
                if (isset($value['No']) && $value['No'] === $id)
                {
                    $user = $value;
                }
            }
        }

        if (!empty($user))
        {
        	//This should be a test commit that
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
	}

    public function ledger_get()
    {
        $id = NULL;
        $data = NULL;

        $id = trim($this->get('No'));

        if ($id == NULL) {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $Obj = new Data();
        $data = $Obj->getUniqueItemLedger($id);

        if ($data) {
            // Set the response and exit
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No data was found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
}

?>