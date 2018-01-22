<?php
defined('BASEPATH') or exit('No direct access allowed!');

require APPPATH . 'libraries/REST_Controller.php';
require 'Data.php';
/**
* 
*/
class Users extends REST_Controller
{
	
	function __construct()
	{
		parent:: __construct();

		$this->methods['items_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['items_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['items_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('users_model');
	}

    public function types_get($id = null)
    {
       $resp = $this->users_model->get_userTypes($id);

       if ($resp) {
           $this->response($resp, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
       } else {
           $this->response([
                    'status' => FALSE,
                    'message' => 'No Data Found'
                ], REST_Controller::HTTP_NOT_FOUND);
       }
       
    }

	public function create_post()
    {
        $name = trim($this->post('name'));
        $email = trim($this->post('email'));
        $phone = trim($this->post('phone'));
        $type = trim($this->post('type'));
        $password = parent::Hash($this->post('password'));
        $confirm_password = parent::Hash($this->post('confirm_password'));

        if ($password != $confirm_password)  $this->response([
                                                'status' => FALSE,
                                                'message' => 'Passwords do not match'
                                            ], REST_Controller::HTTP_NOT_FOUND);

        $type = $this->users_model->get_userTypes($type);
        
        if ($type == null) $this->response([
                                        'status' => FALSE,
                                        'message' => 'User Type could not be found'
                                    ], REST_Controller::HTTP_NOT_FOUND);
        
        if (!empty($this->users_model->checkUser($email)->result())) $this->response([
                                                                                'status' => FALSE,
                                                                                'message' => 'User already exists!'
                                                                            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        if (empty($name) || empty($phone) || empty($email) || empty($password)) $this->response([
                        'status' => FALSE,
                        'message' => 'Data not provided!'
                    ], REST_Controller::HTTP_NOT_ACCEPTABLE);

        $register = $this->users_model->sign_up([
                                                'name' => $name,
                                                'email' => $email,
                                                'password' => $password,
                                                'phone' => $phone,
                                                'user_type_id' => $type[0]['id'],
                                                'firstLogin' => rand()
                                                ]);

        if ($register) {
            $this->response([
                'status' => TRUE,
                'message' => 'User successfully created.',
                'data' => ['email' => $email, 'name' => $name, 'usertype' => $type],
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'User registration failed'
            ], REST_Controller::HTTP_GONE);
        }

        
    }

    public function login_post()
    {
        $email = trim($this->post('email'));
        $password = parent::Hash(trim($this->post('password')));
        $code = null;
        if (!empty($this->post('code'))) $code = $this->post('code');

        $data = ['email' => $email,
                'password' => $password,
                'code' => $code];
        
        $checkUser = $this->users_model->authenticate($data);

        // if ($checkUser == "code") $this->response([
        //                                     'message' => 'This is your first login. Please provide the reset code that was sent to your email!',
        //                                     'status'  => FALSE,
        //                                 ], REST_Controller::HTTP_UNAUTHORIZED);

        // if ($checkUser == "wrong code") $this->response([
        //                 'message' => 'You have provided the wrong code. Check and try again',
        //                 'status'  => FALSE,
        //             ], REST_Controller::HTTP_UNAUTHORIZED);

        if (empty($checkUser)) $this->response([
                                    'message' => 'Wrong username or password!',
                                    'status'  => FALSE
                                ], REST_Controller::HTTP_UNAUTHORIZED);
        
        $this->response([
                    'message' => 'Login successful',
                    'data' => $checkUser,
                    'status'  => 1
                ], REST_Controller::HTTP_OK);
    }

    function activate_post()
    {
        $email = $this->post('email');
        $No = $this->post('No');
        $code = $this->post('code');
        $type = $this->post('type');

        $update = $this->users_model->activate($email,$No,$code);

        if ($update) {
            $data = array(
                    'sNo' => $No,
                    'semail' => $email,
                    'suser' => $type,
                    "sisLoggedIn"    => TRUE
                );
            $this->response([
                'apiStatus' => TRUE,
                'message' => 'Account successfully activated!',
                'data' => $data,
                'status'  => 1
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'apiStatus' => FALSE,
                'message' => 'An error occured while attempting to activate your account. Please try again later!',
                'status'  => 0
            ], REST_Controller::HTTP_GONE);
        }
    }

    function find_get()
    {
        $user = array();
        $users = array();
        
        $role = $this->get('role');
        $No = $this->get('No');
        $Obj = new Data();

        if ($role == 1) {
            $users = $Obj->salesPeople($No);
            if ($users) {
                $role = 'employee';
                $names = explode(' ', $users->Name);
                $size = sizeof($names);
                if ($size>2) {
                    $first = $names[0];
                    $middle = $names[1];
                    $last = $names[2];
                } else {
                    $first = $names[0];
                    $middle = NULL;
                    $last = $names[1];
                }
                $No = $users->Code;
                $email = '';
            }
        } else {
            $users = $Obj->getUserInfoNav($No);
            if ($users) {
                $role = 'customer';
                $names = explode(' ', $users->Name);
                $size = sizeof($names);
                if ($size>2) {
                    $first = $names[0];
                    $middle = $names[1];
                    $last = $names[2];
                } else {
                    $first = $names[0];
                    $middle = NULL;
                    $last = $names[1];
                }
                $No = $users->No;
                $email = $users->E_Mail;
            }
        }
        if ($users) {
            $user = array(  'RegNo' => $No,
                        'fName' => $first,
                        'mName' => $middle,
                        'lName' => $last,
                        'email' => $email,
                        'role' => $role);
            $this->response($user, REST_Controller::HTTP_OK);
        }else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No data was found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    function forgot_post()
    {
        $Obj = new Data();
        if (!isset($_POST)) {
            $this->response([
                'status' => FALSE,
                'message' => 'Incorrect information provided'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $email = $this->post('email');
            $number = $this->post('number');
            
            $checkUser = $this->users_model->checkUser($email,$number)->result();

            if ($checkUser) {
                $resetCode = rand(0,99999);
                $data = array(
                        "email" => $email,
                        "No" => $number,
                        "resetCode" => $resetCode,
                        "Name" => $checkUser[0]->firstName." ".$checkUser[0]->lastName
                    );
                
                $update = $this->users_model->resetPassword($data);
                if ($update) {
                    $message = "Hello,<br/>
                                You have requested a password change on the Silverstone Web Portal. <br/> 
                                Use the credentials below to login.<br/><br/>

                                Reset Code: ".$data['resetCode']."<br/>
                                Username: ".$data['email']."<br/><br/>

                                Regards,<br/>
                                Silverstone Customer Portal.";
                    $data = array(
                                'Name' => $data['Name'],
                                'subject' => 'Silverstone Tyres Password Reset' ,
                                'userEmail' => $data['email'],
                                'message' => $message
                            );
                    $send = $Obj->sendEmail($data);
                    // $send = $this->sendResetEmail($email,$resetCode);
                    if ($send) {
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Account successfully updated. A reset code has been sent to your email.'
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'apistatus' => TRUE,
                            'message' => 'Account successfully updated with an error sending the reset code. Try again later!',
                            'status' => 2
                        ], REST_Controller::HTTP_PARTIAL_CONTENT);
                    }
                } else {
                    $this->response([
                            'status' => FALSE,
                            'message' => 'An error occured while reseting the account'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                }
                
            } else {
                $this->response([
                            'status' => FALSE,
                            'message' => 'No user exists with the provided details'
                        ], REST_Controller::HTTP_UNAUTHORIZED);
            }
            
            // $data = array();
        }
    }

    function reset_post()
    {
        $code = $this->post('reset');
        $password = trim(sha1($this->config->item('salt_phrase').$this->post('password').$this->config->item('hash_phrase')));
        $resp = $this->users_model->reset($code,$password);
        if ($resp) {
            $this->response([
                'status' => TRUE,
                'message' => 'Account successfully updated. A reset code has been sent to your email.',
                'data' => $resp
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'An error occured while reseting the account'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }

    function test_post()
    {
        $Obj = new Data();

        print_r($Obj->sendEmail($this->post('email'),$this->post('code')));
    }
}

?>
