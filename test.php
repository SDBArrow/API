<?php
// files needed to connect to database
include_once 'config/DBconnect.php';
include_once 'objects/user.php';

// get database connection (connect.php)
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);


// set product property values
$user->car_name = "123";
$user->car_ip = "456";
$user->car_port = "789";

$user->id = "89";
$user->create_cartset();