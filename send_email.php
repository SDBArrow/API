<?php
$send_email = getenv('email');
$email = new \SendGrid\Mail\Mail();
$email->setFrom($send_email, "AIMMA_AGV"); //寄件人資訊
$email->setSubject("AIMMA_AGV PASSWORD RESET");
$email->addTo("測試2");
$email->addContent("text/plain", "測試1");
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
