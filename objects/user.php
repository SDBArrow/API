<?php
// 'user' object
class User{
 
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
    public function __construct($db){
        $this->conn = $db;
    }
 
// create new user record
function create(){
 
    // insert query
    $sql = "INSERT INTO " . $this->table_name . "SET firstname = ?, lastname = ?, email = ?, password = ?";
 
    // prepare the query
    $stmt = $this->conn->stmt_init(); 
    $stmt -> prepare($sql); 
 
    // sanitize
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
 
    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

    // bind the values
    $stmt -> bind_param('s',$this->firstname,$this->lastname,$this->email,$password_hash); 
 
    // execute the query, also check if query was successful
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
 
// emailExists() method will be here
}