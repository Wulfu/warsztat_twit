<?php
class PostWithEmail extends Post{
    private $email;
    
    public function __construct(){
        parent::__construct();
        $this->email = "";
    }
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    static public function loadAllPostsWithEmailOrderedByDate($conn){
        $stmt = ('SELECT user.email as email, post.id as id, post.content as content, post.creationDate as creationDate,'
                . ' post.user_id as userId FROM post JOIN user ON user.id=post.user_id ORDER BY post.creationDate DESC');
        $result = $conn->query($stmt);
        $ret = [];
        if($result !== false && $result->rowCount() > 0){
            foreach($result as $row){
                $loadPostWithEmail = new PostWithEmail();
                $loadPostWithEmail->setEmail($row['email']);
                $loadPostWithEmail->setContent($row['content']);
                $loadPostWithEmail->setCreationDate($row['creationDate']);
                $loadPostWithEmail->id = $row['id'];
                $loadPostWithEmail->setUserId($row['userId']);
                $ret[] = $loadPostWithEmail;
            }
        }
        return $ret;
    }
    static public function loadAllUserPostsWithEmailOrderedByDate($conn, $id){
        $stmt = ("SELECT user.email as email, post.id as id, post.content as content, "
                ."post.creationDate as creationDate, post.user_id as userId FROM post JOIN user ON user.id=$id AND post.user_id=$id ORDER BY post.creationDate DESC");
        $result = $conn->query($stmt);
        $ret = [];
        if($result !== false && $result->rowCount() > 0){
            foreach($result as $row){
                $loadUserPostWithEmail = new PostWithEmail();
                $loadUserPostWithEmail->setEmail($row['email']);
                $loadUserPostWithEmail->setContent($row['content']);
                $loadUserPostWithEmail->setCreationDate($row['creationDate']);
                $loadUserPostWithEmail->id = $row['id'];
                $loadUserPostWithEmail->setUserId($row['userId']);
                $ret[] = $loadUserPostWithEmail;
            }
        }
        return $ret;
    }
}