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
    public function getUsername(){
        return $this->username;
    }
    
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }
    public function setUsername($username) {
        $this->username = $username;
    }

    public function verifyPass($password){
        if(password_verify($password, $this->hashed_password) === true){
            return true;
        }else{
            return false;
        }
    }
    public function setPass($password){
        $this->hashed_password = password_hash($password, PASSWORD_BCRYPT,['cost'=>11]);
        return $this;
    }
    public function saveToDB(PDO $conn){
        if($this->id == -1){
            $stmt = $conn->prepare('INSERT INTO user (email,username,hashed_password) VALUES (:email, :username, :pass)');
            $result = $stmt->execute(['email' => $this->email, 'username' => $this->username, 'pass' => $this->hashed_password]);
            if($result !== false){
                $this->id = $conn->lastInsertId();
                return true;
            }
        }else{
            $stmt = $conn->prepare(
                    'UPDATE user SET username=:username,email=:email,hashed_password=:hashed_password WHERE id=:id'
                    );
            $result = $stmt->execute
                    ([   'username' => $this->getUsername(),
                        'email' => $this->getEmail(),
                        'hashed_password' => $this->hashed_password,
                        'id' => $this->getId()
                    ]);
            if($result === true){return true;}
        }
        return false;
    }
    
    public function deleteUser(PDO $conn){
        if($this->id != -1){
            $stmt = $conn->prepare('DELETE FROM user WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);
            if($result === true){
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }
    
    static public function loadUserById(PDO $conn, $id){
        $stmt = $conn->prepare('SELECT * FROM user WHERE id= :id');
        $result = $stmt->execute(['id' => $id]);
        
        if($result === true && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->email = $row['email'];
            $loadedUser->hashed_password = $row['hashed_password'];
            
            return $loadedUser;
        }
        return null;
    }
    static public function loadUserByEmail(PDO $conn, $email){
        $stmt = $conn->prepare('SELECT * FROM user WHERE email= :email');
        $result = $stmt->execute(['email' => $email]);
        
        if($result === true && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->email = $row['email'];
            $loadedUser->hashed_password = $row['hashed_password'];
            
            return $loadedUser;
        }
        return null;
    }
    static public function loadAllUsers(PDO $conn){
        $sql = "SELECT * FROM user";
        $ret = [];
        
        $result = $conn->query($sql);
        if($result !== false && $result->rowCount() != 0){
            foreach($result as $row){
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->email = $row['email'];
                $loadedUser->hashed_password = $row['hashed_password'];
                
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }
}
