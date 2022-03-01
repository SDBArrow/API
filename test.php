<?php
include_once 'config/connect.php';
$database = new Database();
$db = $database->getConnection();
$sql = "insert into users (firstname) value ('2');";
$db->query($sql); 
$db -> close();