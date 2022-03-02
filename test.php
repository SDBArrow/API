<?php
// files needed to connect to database
include_once 'config/connect.php';
include_once 'objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
$user->email = "mike@codeofaninja.com";

if($email_exists = $user->emailExists()){
}else{
    echo "error";
}