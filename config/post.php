<?php

class Post{
    protected $id;
    protected $content;
    protected $creationDate;
    protected $userId;
    
    public function __construct() {
        $this->id = -1;
        $this->content = "";
        $this->creationDate = "";
        $this->userId = 0;
    }
    
    public function getId() {
        return $this->id;
    }
    public function getContent() {
        return $this->content;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }
    
    public function saveToDB(PDO $conn){
        if($this->id == -1){
            $stmt = $conn->prepare('INSERT INTO post(content, creationDate, user_id) VALUES (:content, :creationDate, :user_id)');
            $result = $stmt->execute(['content' => $this->content, 'creationDate' => $this->creationDate, 'user_id' => $this->userId]);
            if($result !== false){
                $this->id = $conn->lastInsertId();
                return true;
            }
        }else{
            $stmt = $conn->prepare('UPDATE post SET content=:content,creationDate=:creationDate WHERE id=:id');
            $result = $stmt->execute
                    ([
                        'content' => $this->content,
                        'creationDate' => $this->creationDate,
                        'id' => $this->getId() 
                    ]);
            if($result === true){return true;}
        }
        return false;
    }
    
    static public function loadPostById(PDO $conn, $id){
        $stmt = $conn->prepare('SELECT * FROM post WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if($result === true && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedPost = new post;
            $loadedPost->id = $row['id'];
            $loadedPost->setContent($row['content']);
            $loadedPost->setCreationDate($row['creationDate']);
            $loadedPost->setUserId($row['user_id']);
            
            
            return $loadedPost;
        }
        return null;
    }
    static public function loadAllPosts(PDO $conn){
        $stmt = 'SELECT * FROM post';
        $ret = [];
        $result = $conn->query($stmt);
        if($result !== false && $result->rowCount() > 0){
            foreach($result as $row){
                $loadedPost = new post;
                $loadedPost->id = $row['id'];
                $loadedPost->setContent($row['content']);
                $loadedPost->setCreationDate($row['creationDate']);
                $loadedPost->setUserId($row['user_id']);
                $ret[] = $loadedPost;
            }
        }
        return $ret;
    }
    static public function loadAllPostsOrderedByDate(PDO $conn){
        $stmt = 'SELECT * FROM post ORDER BY creationDate DESC';
        $ret = [];
        $result = $conn->query($stmt);
        if($result !== false && $result->rowCount() > 0){
            foreach($result as $row){
                $loadedPost = new post;
                $loadedPost->id = $row['id'];
                $loadedPost->setContent($row['content']);
                $loadedPost->setCreationDate($row['creationDate']);
                $loadedPost->setUserId($row['user_id']);
                $ret[] = $loadedPost;
            }
        }
        return $ret;
    }
    static public function loadAllPostsByUserId(PDO $conn, $id){
        $stmt = "SELECT * FROM post WHERE user_id=$id";
        $ret = [];
        $result = $conn->query($stmt);
        if($result !== false && $result->rowCount() > 0){
            foreach($result as $row){
                $loadedPost = new post;
                $loadedPost->id = $row['id'];
                $loadedPost->setContent($row['content']);
                $loadedPost->setCreationDate($row['creationDate']);
                $loadedPost->setUserId($row['user_id']);
                $ret[] = $loadedPost;
            }
        }
        return $ret;
    }
}