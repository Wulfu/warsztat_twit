<?php
class User{
    private $id;
    private $email;
    private $username;
    private $hashed_password;
    
    public function __construct(){
        $this->id = -1;
        $this->email = "";
        $this->hashed_password = "";
    }
    
    public function getId(){
        return $this->id;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }
    public function setUsername($username) {
        $this->username = $username;
    }

    public function verifyPass($password){
        password_verify($password, $this->hashed_password);
    }
    public function setPass($password){
        $this->hashed_password = password_hash($password, PASSWORD_BCRYPT,['cost'=>11]);
        return $this;
    }
    public function saveToDB(PDO $conn){
        if($this->id == -1){
            $stmt = $conn->prepare('INSERT INTO user (email,username,hashed_password) VALUES (:email, :username, :pass)');
            $result = $stmt->execute(
                    ['email' => $this->email, 'username' => $this->username, 'pass' => $this->hashed_password]
            );
            if($result !== false){
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }
}
