<?php
// files needed to connect to database
include_once 'config/DBconnect.php';
include_once 'objects/user.php';

// get database connection (connect.php)
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

$user->id_car_set = "314";
$user->position_x = "1.8920861403688194";
$user->position_y = "-0.6740623915800827";
$user->position_z = "0";
$user->orientation_x = "0";
$user->orientation_y = "0";
$user->orientation_z = "-0.07039126483962273";
$user->orientation_w = "-0.9975194583737593";


// if jwt is not empty
if ($user->create_goalset()) {

}