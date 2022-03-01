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
 
// set product property values
$user->firstname = "Mike";
$user->lastname = "Dalisay";
$user->email = "mike@codeofaninja.com";
$user->password = "555";
 
// create the user
if(
    !empty($user->firstname) &&
    !empty($user->email) &&
    !empty($user->password) 
){
 
    // set response code
    http_response_code(200);
 
    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}