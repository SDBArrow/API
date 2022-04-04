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

// files needed to connect to database
include_once 'config/DBconnect.php';
include_once 'objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$user->password = substr(str_shuffle($str), 0, 8);
$user->email = $data->email;
$email_help = $user->send_email();

// 引入生成 json web token 的library
include_once 'config/core.php';
include_once 'libs/php-jwt-main/src/BeforeValidException.php';
include_once 'libs/php-jwt-main/src/ExpiredException.php';
include_once 'libs/php-jwt-main/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-main/src/JWT.php';

// 如果信件寄件成功
if ($email_help && !empty($user->email)) {
    if($user->change_password()){
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "Email 已寄送",
                "code" => "51",
            )
        );
    }else{
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "資料庫錯誤",
                "code" => "53",
            )
        );
    }
} elseif ($email_help == false) {
    http_response_code(404);
    echo json_encode(
        array(
            "message" => "Email 不存在",
            "code" => "52",
        )
    );
} else {
    http_response_code(401);
    echo json_encode(
        array(
            "message" => "connect failed",
            "code" => "01",
        )
    );
}
