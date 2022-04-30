<?php

// files needed to connect to database
include_once 'config/DBconnect.php';
include_once 'objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

$user->id = "94";

if ($test = $user->get_permissions()) {

    // set response code
    http_response_code(200);

    // show user details
    echo json_encode(array(
        "code" => "81",
        "message" => "獲取成功",
        "data" => $test
    ));
} else {
    http_response_code(404);

    // show user details
    echo json_encode(array(
        "code" => "85",
        "message" => "權限不夠",
        "data" => $return_data
    ));
}
