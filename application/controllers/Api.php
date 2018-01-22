<?php
defined('BASEPATH') or exit('No direct script access allowed!');

/**
* 
*/
class Api extends MX_Controller {

    public function index()
    {
        $this->load->view('rest_server');
    }
}
?>