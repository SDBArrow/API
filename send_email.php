<?php
$email = new \SendGrid\Mail\Mail(); 
$email->setFrom($send_email, "dogmission"); //寄件人資訊
$email->setSubject($timestart." ~ ".$timeend." 工作檢核");
$email->addTo($user_email, $user_name);
$email->addContent("text/plain", $timestart."~".$timeend." 工作檢核");
$email->addContent(
    "text/html", "<strong>請看副檔</strong>"
);
//附件檔案
$file_encoded = base64_encode(file_get_contents("https://dogmission.herokuapp.com/record.pdf"));
$email->addAttachment(
    $file_encoded,
    "application/pdf",
    "record.pdf",
    "attachment"
);
//發送email
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}