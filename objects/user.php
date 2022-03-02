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

        // if password needs to be updated
        $password_set = !empty($this->password) ? ", password = :password" : "";

        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email
                {$password_set}
            WHERE id = :id";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // hash the password before saving to database
        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
