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
$user->carname = "123";
$user->carip = "456";
$user->carport = "789";

$user->id = "89";
$user->create_cartset();