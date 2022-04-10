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

    public $id_car_set;
    public $car_name;
    public $car_ip;
    public $car_port;

    public $id_goal_set;
    public $goal_name;
    public $position_x;
    public $position_y;
    public $position_z;
    public $orientation_x;
    public $orientation_y;
    public $orientation_z;
    public $orientation_w;


    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 創建新用戶
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
    // 用email檢查用戶是否存在
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
    // 更新資料
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
        } else if ($userdata_set) {
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
    // 寄送信件
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
                "<strong>你的密碼是：</strong>" . $this->password
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
    // 寄送信件
    public function change_password()
    {
        $stmt = $this->conn->stmt_init();
        $sql = "UPDATE " . $this->table_name . " set password=? where email=? ";
        $stmt->prepare($sql);
        $this->password = htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT); // 加密密碼，並帶入值
        $stmt->bind_param('ss', $password_hash, $this->email);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // 創建車子資料
    public function create_carset()
    {
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
    }
    // 讀取車子資料
    public function get_carset()
    {
        // query to check if email exists
        $sql = "SELECT id_car_set, car_name, car_ip, car_port FROM car_set WHERE id_user=?";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        // 帶入參數
        $stmt->bind_param('s', $this->id);
        $arr = array();
        // execute the query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                /*
                foreach ($row as $r) {
                    echo "$r ";
                }*/
                array_push($arr, $row);
                //echo "\n";
            }
            return $arr;
        }
        return false;
    }
    // 刪除車子資料
    public function delete_carset()
    {
        // query to check if email exists
        $sql = "DELETE FROM car_set WHERE id_user=? and id_car_set=?";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        // 帶入參數
        $stmt->bind_param('ss', $this->id, $this->id_car_set);
        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // 創建goal資料
    public function create_goalset()
    {
        // insert query
        $sql = "INSERT INTO goal_set SET id_car_set=?, goal_name=?, position_x=?, position_y=?, position_z=?, orientation_x=?, orientation_y=?, orientation_z=?, orientation_w=?";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);
        $this->goal_name = htmlspecialchars(strip_tags($this->goal_name));
        // 帶入參數
        $stmt->bind_param('sssssssss', $this->id_car_set, $this->goal_name, $this->position_x, $this->position_y, $this->position_z, $this->orientation_x, $this->orientation_y, $this->orientation_z, $this->orientation_w);

        // execute the query, also check if query was successful
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // 讀取goal資料
    public function get_goalset()
    {
        // query to check if email exists
        $sql = "SELECT goal_name, position_x, position_z, orientation_x, orientation_y, orientation_z, orientation_w FROM goal_set WHERE id_car_set=?";

        // 初始化stat 防sql injection
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql);

        // 帶入參數
        $stmt->bind_param('s', $this->id_car_set);
        $arr = array();
        // execute the query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                /*
                    foreach ($row as $r) {
                        echo "$r ";
                    }*/
                array_push($arr, $row);
                //echo "\n";
            }
            return $arr;
        }
        return false;
    }
}
