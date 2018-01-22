<?php
	require_once('phpMailer/PHPMailerAutoload.php');

	class LoadMailer extends PHPMailer{
		public function __construct(){
			parent::__construct();
		}
	}
?>
