<?php


$id="";
$car_name="";
$car_ip="";
$id="";
// insert query
$sql = "INSERT INTO car_set SET id_user=?, car_name=?, car_ip=?, car_port=?";

// 初始化stat 防sql injection
$stmt = $this->conn->stmt_init();
$stmt->prepare($sql);

// 消毒  strip_tags可不做 htmlspecialchars一定要做。 strip_tags：去掉 HTML 及 PHP 的標籤(html語法) ; htmlspecialchars，將特殊字元轉成 HTML 格式 防止http連接攻擊
$this->id = htmlspecialchars(strip_tags($this->id));
$this->car_name = htmlspecialchars(strip_tags($this->car_name));
$this->car_ip = htmlspecialchars(strip_tags($this->car_ip));
$this->car_port = htmlspecialchars(strip_tags($this->car_port));

// 帶入參數
$stmt->bind_param('ssss', $this->id, $this->car_name, $this->car_ip, $this->car_port);

// execute the query, also check if query was successful
if ($stmt->execute()) {
    return true;
}
return false;
