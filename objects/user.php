<?php
// 'user' object
class User
{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create new user record
    function create()
    {

        // insert query
        $sql = "INSERT INTO " . $this->table_name . " SET firstname=?, lastname=?, email=?, password=?";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        // 消毒  strip_tags可不做 htmlspecialchars一定要做。 strip_tags：去掉 HTML 及 PHP 的標籤(html語法) ; htmlspecialchars，將特殊字元轉成 HTML 格式 防止http連接攻擊
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // 密碼加密
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // 帶入參數
        $stmt->bind_param('ssss', $this->firstname, $this->lastname, $this->email, $password_hash);

        // execute the query, also check if query was successful
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // check if given email exist in the database
    function emailExists()
    {

        // query to check if email exists
        $sql = "SELECT id, firstname, lastname, password FROM " . $this->table_name . " WHERE email=? LIMIT 0,1";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        // 消毒 
        $this->email = htmlspecialchars(strip_tags($this->email));

        // 帶入參數
        $stmt->bind_param('s', $this->email);

        // execute the query
        $stmt->execute();
        $stmt->bind_result($this->id, $this->firstname, $this->lastname, $this->password);

        // if email exists, assign values to object properties for easy access and use for php sessions
        if ($stmt->fetch()) {
            return true;
        }
        return false;
    }
    // update a user record
    public function update()
    {

        $password_set = !empty($this->password);
        $userdata_set = !empty($this->firstname) && !empty($this->lastname);

        // 判斷密碼是否需要被更新
        if ($password_set) {
            $sql = "UPDATE " . $this->table_name . " SET password =? WHERE id =?";
        } else if ($userdata_set) {
            $sql = "UPDATE " . $this->table_name . " SET firstname =?, lastname =? WHERE id =?";
        }

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        //判斷是改密碼還改資料
        if ($password_set) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT); // 加密密碼，並帶入值
            $stmt->bind_param('ss', $password_hash, $this->id);
        } else if($userdata_set) {
            // sanitize
            $this->firstname = htmlspecialchars(strip_tags($this->firstname));
            $this->lastname = htmlspecialchars(strip_tags($this->lastname));
            // unique ID of record to be edited
            $stmt->bind_param('sss', $this->firstname, $this->lastname, $this->id);
        }

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function send_email()
    {
        // query to check if email exists
        $sql = "SELECT firstname, lastname FROM " . $this->table_name . " WHERE email=? LIMIT 0,1";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        // 消毒 
        $this->email = htmlspecialchars(strip_tags($this->email));

        // 帶入參數
        $stmt->bind_param('s', $this->email);

        // execute the query
        $stmt->execute();
        $stmt->bind_result($this->firstname, $this->lastname);

        if ($stmt->fetch()) {
            require 'vendor/autoload.php'; // If you're using Composer (recommended)
            $send_email = getenv('email');
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($send_email, "AIMMA_AGV"); //寄件人資訊
            $email->setSubject("AIMMA_AGV PASSWORD RESET");
            $email->addTo($this->email, $this->firstname . $this->lastname);
            $email->addContent("text/plain", "AIMMA_AGV PASSWORD RESET");
            $email->addContent(
                "text/html",
                "<strong>請看副檔</strong>"
            );
            //發送email
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage() . "\n";
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}
