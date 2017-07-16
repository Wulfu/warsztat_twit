<?php

class Comment{
    public $id;
    public $userId;
    public $postId;
    public $creationDate;
    public $content;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->postId = 0;
        $this->creationDate = "";
        $this->content = "";
    }
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getContent() {
        return $this->content;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    public function setPostId($postId) {
        $this->postId = $postId;
        return $this;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function saveToDB(PDO $conn){
        if($this->id == -1){
            $stmt = $conn->prepare('INSERT INTO comment (user_id, post_id, created_at, content) VALUES (:user_id, :post_id, :created_at, :content)');
            $result = $stmt->execute
                ([
                    'user_id' => $this->userId, 
                    'post_id' => $this->postId, 
                    'created_at' => $this->creationDate, 
                    'content' => $this->content
                ]);
                
            var_dump($result);
            var_dump($stmt);
            if($result !== false){
                $this->id = $conn->lastInsertId();
                return true;
            }
        }else{
            $stmt = $conn->prepare('UPDATE comment SET content=:content, creationDate=:creationDate WHERE id=:id');
            $result = $stmt->execute
                    ([
                        'content' => $this->content,
                        'creationDate' => $this->creationDate,
                        'id' => $this->getId() 
                    ]);
            if($result === true){
                return true;
            }
        }
        return false;
    }
    
    static public function loadCommentById(PDO $conn, $id){
        $stmt = $conn->prepare('SELECT * FROM comment WHERE id= :id');
        $result = $stmt->execute(['id' => $id]);
        
        if($result === true && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $loadedComment = new Comment;
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['user_id'];
            $loadedComment->postId = $row['post_id'];
            $loadedComment->creationDate = $row['created_at'];
            $loadedComment->content = $row['content'];
            
            return $loadedComment;
        }
        return null;
    }
    static public function loadAllCommentsByPostId(PDO $conn, $id){
        $stmt = "SELECT * FROM comment WHERE post_id=$id";
        $result = $conn->query($stmt);
        
        $ret = [];
        if($result !== false && $result->rowCount() > 0){
            foreach($result as $row){
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->postId = $row['post_id'];
                $loadedComment->creationDate = $row['created_at'];
                $loadedComment->content = $row['content'];
                
                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }
}