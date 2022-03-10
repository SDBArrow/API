<?php
require 'vendor/autoload.php'; // If you're using Composer (recommended)
$send_email = getenv('email');
$email = new \SendGrid\Mail\Mail();
$email->setFrom($send_email, "AIMMA_AGV"); //寄件人資訊
$email->setSubject("AIMMA_AGV PASSWORD RESET");
$email->addTo("j25889651556@gmail.com","楊子弘");
$email->addContent("text/plain", "AIMMA_AGV PASSWORD RESET");
$email->addContent(
    "text/html",
    "<strong>請看副檔</strong>"
);
//發送email
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
