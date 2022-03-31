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

// required to encode json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-main/src/BeforeValidException.php';
include_once 'libs/php-jwt-main/src/ExpiredException.php';
include_once 'libs/php-jwt-main/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-main/src/JWT.php';
include_once 'libs/php-jwt-main/src/Key.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// files needed to connect to database
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
$user->carname = $data->car_name;
$user->carip = $data->car_ip;
$user->carport = $data->car_port;
// get posted data

// get jwt
$jwt = isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if ($jwt) {
    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        $user->id = $decoded->data->id;

        if (!empty($user->id) && !empty($user->car_name) && !empty($user->car_ip) && !empty($user->car_port) && $user->create_cartset()) {

            // set response code
            http_response_code(200);

            // show user details
            echo json_encode(array(
                "code" => "61",
                "message" => "儲存成功",
                "data" => $decoded->data
            ));
        }
    }

    // if decode fails, it means jwt is invalid
    catch (Exception $e) {

        // set response code
        http_response_code(401);

        // tell the user access denied  & show error message
        echo json_encode(array(
            "code" => "42",
            "message" => "驗證失敗",
            "error" => $e->getMessage()
        ));
    }
} // show error message if jwt is empty
else {

    // set response code
    http_response_code(401);

    // tell the user access denied
    echo json_encode(array(
        "code" => "62",
        "message" => "儲存失敗"
    ));
}
