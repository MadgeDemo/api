<?php
defined('BASEPATH') or exit('No direct script access allowed!');
/**
* 
*/
class Data extends CI_Controller
{
	
	public function __construct()
	{
		log_message('info', 'Data Class Initialized');
	}

	//getItemList
	public function itemList(){
		$webServiceUrl = webServiceUrl; 
		$client = new SoapClient($webServiceUrl);
		try {
			$res = $client->getItemCard();
		} catch (SoapFault $e) {
			$res = "Error: {$e->faultstring}";
		}
		
		$rawData = json_decode($res->getItemCardResult);
		// echo "<pre>";print_r($rawData);die();
		$data = array();
		foreach ($rawData as $key => $value) {
			$data[] = array(
						'Key' => $value->Key,
						'No' => $value->No,
						'Description' => $value->Description,
						'Inventory' => $value->Inventory,
						'Unit_Price' => $value->Unit_Price,
						'Reserved_Quantity' => $value->Reserved_Qty_on_Sales_Orders,
						'Brand' => $value->Item_Category_Code,
						'Rim' => $value->RIM,
						'Pattern' => $value->Pattern,
						'Category' => $value->Shortcut_Dimension_4_Code
			);
		}
		return $data;
	}
	//getItemList

	//getItemLedger
	public function getUniqueItemLedger($itemNo){
		$webServiceUrl = webServiceUrl; 
		
		$client = new SoapClient($webServiceUrl);
		try {
			$res = $client->getLedgerEntries(array("itemNo"=>$itemNo));
		} catch (SoapFault $e) {
		    $res = "Error: {$e->faultstring}";
		    //$res = 3;
		}
		
		$responce = json_decode($res->getLedgerEntriesResult);
		$data  =  array();
		foreach ($responce as $key => $value) {
			$lc = $value->locationCode;
            $qty = round($value->qty);
            $arrayOfStorageLocationsNotToDisplay =  ["HO", "Head Office", "HEAD OFFICE", "Display", "DISPLAY", "Customer Corner", "CUSTOMER CORNER","CUSTCORNER","Speedy", "SPEEDY","G4S"];
			
			if (!in_array($lc, $arrayOfStorageLocationsNotToDisplay)) {
				$data[] = array(
							'locationCode' => $lc,
							'qty' => $qty
				);
			}
		}

		return $data;
	}
	//getItemLedger

	//get sales people 
	public function salesPeople($saleNo){
		$client = new SoapClient(webServiceUrl);
		try{
			$res = $client->getSpecificSales(array("salesNo"=>$saleNo));
		} catch (SoapFault $e) {
			$res = "Error: {$e->faultstring}";
		}
		return json_decode($res->getSpecificSalesResult);
	}
	//get sales people

	//Specific User info
	public function getUserInfoNav($custNo){
		$webServiceUrl = webServiceUrl; 
		
		$client = new SoapClient($webServiceUrl);
		try {
			$res = $client->getSpecificCustomer(array("custNo"=>$custNo));
		} catch (SoapFault $e) {
		    $res = "Error: {$e->faultstring}";
		}
		// print_r($res);die();
		return json_decode($res->getSpecificCustomerResult);
	}
	//Specific User info


	function sendEmail($data=null)
		{
			if ($data==null) {
				return json_encode(
					array('error' => array('message'=> 'No data for the recepient provided!','status'=>FALSE)
				));
			} else {
				require APPPATH . 'libraries/sendgrid/sendgrid-php.php';
				// require("../../libraries/sendgrid/sendgrid-php.php");
				// echo "<pre>";print_r($data);die();x
				$from = new SendGrid\Email("Silverstone Tyres", "silverstonecustomerportal@gmail.com");
				$subject = $data['subject'];
				$to = new SendGrid\Email($data['Name'], $data['userEmail']);
				$content = new SendGrid\Content("text/plain", $data['message']);
				$mail = new SendGrid\Mail($from, $subject, $to, $content);
				//
				$apiKey = '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';
				//$apiKey = getenv('SENDGRID_API_KEY');
				$sg = new \SendGrid($apiKey);

				$response = $sg->client->mail()->send()->post($mail);

				// echo $response->statusCode();
				// print_r($response->headers());
				// echo $response->body();
				// die();
				if ($response->statusCode()!=202) {
					return FALSE;
				} else {
					return TRUE;
				}
			}
			
			
			// //SEND USER EMAIL
			// 	$reciepientemail = $userEmail;
			// 	$sendersEmail = 'silverstonecustomerportal@gmail.com';
			// 	$sendersEmailPass = 'abc123**';
				
			// 	$message = "Hello,<br/>
			// 	You have been registered as a user on the Silverstone Web Portal. <br/> This will give you access to the inventory list.<br/>
			// 	You will be prompted to change your password during your first login. 
			// 	Use the credentials below to login.<br/><br/>

			// 	First Time Login Code: ".$firstTimeLoiginID."<br/>
			// 	Username: ".$reciepientemail."<br/><br/>
			// 	Link: http://hzapps.silverstone.co.ke:8089/silverstonecustomerportal/login/employeelogin <br/>
			// 	Local Link: http://10.10.10.139/sscustomerportal/ (use this if you are accessing the site from a device in the silverstone network)

			// 	Regards,<br/>
			// 	Silverstone Customer Portal.";

			// 	$FromFName = "Silverstone";
			// 	$FromLName = "Customer Portal";
			// 	$subject = "Welcome to the Silverstone Customer Portal";

			// 	return $this->phpMailerSendMail($reciepientemail,$sendersEmail,$sendersEmailPass,$message,$FromFName,$FromLName,$subject);
		}

		// //send Mail
		public function phpMailerSendMail($reciepientemail,$sendersEmail,$sendersEmailPass,$message,$FromFName,$FromLName,$subject){
			//SMTP needs accurate times, and the PHP time zone MUST be set
			//This should be done in your php.ini, but this is how to do it if you don't have access to that
			date_default_timezone_set('Etc/UTC');
			//$this->load->library('email');
			//require_once('../libraries/loadMailer.php');
			//$this->load->library('LoadMailer');
			//Create a new PHPMailer instance
			$mail = new PHPMailer;

			//Tell PHPMailer to use SMTP
			$mail->isSMTP();

			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;

			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';

			//Set the hostname of the mail server
			$mail->Host = 'smtp.gmail.com';
			// use
			// $mail->Host = gethostbyname('smtp.gmail.com');
			// if your network does not support SMTP over IPv6

			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$mail->Port = 465;

			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'ssl';

			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;

			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = $sendersEmail;

			//Password to use for SMTP authentication
			$mail->Password = $sendersEmailPass;

			//Set who the message is to be sent from
			$mail->setFrom($sendersEmail, $FromFName." ".$FromLName);

			//Set an alternative reply-to address
			$mail->addReplyTo($sendersEmail, $FromFName." ".$FromLName);

			//Set who the message is to be sent to
			$mail->addAddress($reciepientemail, 'Silverstone Customer Portal User');
			
			//Set the subject line
			$mail->Subject = $subject;

			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			// file_get_contents('contents.html'), dirname(__FILE__)
			$mail->msgHTML($message);

			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';

			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.png');

			//send the message, check for errors
			if (!$mail->send()) {
			    // echo "Mailer Error: " . $mail->ErrorInfo;
			    $resp = false;	
			} else {
			    $resp = true;	
			}
			return $resp;
		}
		//send Mail
}
?>