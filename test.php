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

$user->get_carset();