<?php
// 限制接收數據的來源以及類型
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Access-Control-Allow-Headers,Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header("Access-Control-Allow-Credentials: true");

//CORS 非簡單請求會先發送 預檢請求要回傳連線狀態
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Access-Control-Allow-Headers,Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

// 引入檔案
include_once 'config/DBconnect.php';
include_once 'objects/user.php';

// get database connection (connect.php)
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
$email_exists = $user->emailExists();
// 檢查有無空值後將資料加到資料庫

if ($email_exists == true) {
    // set response code
    http_response_code(404);

    // display message: user was created
    echo json_encode(array("message" => "Email重複"));
} elseif (!empty($user->firstname) && !empty($user->email) && !empty($user->password) && $user->create()) {

    // set response code
    http_response_code(200);

    // display message: user was created
    echo json_encode(array("message" => "註冊完成"));
} else {

    // set response code
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "註冊失敗"));
}
