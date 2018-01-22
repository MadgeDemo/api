<?php
// If you are using Composer (recommended)
//require 'vendor/autoload.php';

// If you are not using Composer
require("./sendgrid-php.php");

$from = new SendGrid\Email("Example User", "silverstonecustomerportal@gmail.com");
$subject = "Sending with SendGrid is Fun";
$to = new SendGrid\Email("Joshua Bakasa", "bakasajoshua09@gmail.com");
$content = new SendGrid\Content("text/plain", "and easy to do anywhere, even with PHP");
$mail = new SendGrid\Mail($from, $subject, $to, $content);
//
$apiKey = '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';
//$apiKey = getenv('SENDGRID_API_KEY');
$sg = new \SendGrid($apiKey);

$response = $sg->client->mail()->send()->post($mail);
echo $response->statusCode();
print_r($response->headers());
echo $response->body();
?>

