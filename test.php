<?php
// required to decode jwt
include_once 'config/core.php';
include_once 'libs/php-jwt-main/src/BeforeValidException.php';
include_once 'libs/php-jwt-main/src/ExpiredException.php';
include_once 'libs/php-jwt-main/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-main/src/JWT.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$jwt="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NDYyNDAwODUsImV4cCI6MTY0NjI0MzY4NSwiaXNzIjoiaHR0cDovL3NpZ24tcmVnaXN0ZXIuaGVyb2t1YXBwLmNvbS8iLCJkYXRhIjp7ImlkIjo4NCwiZmlyc3RuYW1lIjoiTWlrZSIsImxhc3RuYW1lIjoiRGFsaXNheSIsImVtYWlsIjoibWlrZUBjb2Rlb2ZhbmluamEuY29tIn19.4ttrdcmvjoNmmZWs1f8kdJORu6Wo1XLHiUj3abg4gYc";

$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

echo $decoded->data;