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
$user->id_car_set = $data->id_car_set;
$user->position_x = $data->pose->position->x;
$user->position_y = $data->pose->position->y;
$user->position_z = $data->pose->position->z;
$user->orientation_x = $data->pose->orientation->x;
$user->orientation_y = $data->pose->orientation->y;
$user->orientation_z = $data->pose->orientation->z;
$user->orientation_w = $data->pose->orientation->w;

// get posted data

// get jwt
$jwt = isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if ($jwt) {
    // if decode succeed, show user details
    try {

        // 解碼JWT
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        if (!empty($user->id_car_set) && !empty($user->position_x) && !empty($user->position_y) && !empty($user->position_z) && $user->create_goalset()) {

            // set response code
            http_response_code(200);
            echo json_encode(array(
                "code" => "91",
                "message" => "儲存成功",
            ));
        }else{

            // set response code
            http_response_code(401);
            echo json_encode(array(
                "code" => "92",
                "message" => "儲存失敗",
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
            "message" => "登入憑證時效過期，請重新登入",
            "error" => $e->getMessage()
        ));
    }
} // show error message if jwt is empty
else {

    // set response code
    http_response_code(401);

    // tell the user access denied
    echo json_encode(array(
        "code" => "43",
        "message" => "登入驗證失敗，請重新登入"
    ));
}
