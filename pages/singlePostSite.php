<?php
require __DIR__.'/../config/post.php';
require __DIR__.'/../config/postWithEmail.php';
require __DIR__.'/../config/comment.php';
require __DIR__.'/../config/user.php';
require __DIR__.'/../config/config.php';
require __DIR__.'/render.php';
$conn = new PDO('mysql:host='. DB_HOST .';dbname='. DB_DB, DB_USER, DB_PASS);
session_start();
if(!isset($_SESSION['id'])){
    $_SESSION['id'] = -1;
}
if($_SESSION['id'] == -1){
    header('Location: login.php');
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>
</head>
<body>
<?php
//saving comment to DB
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['newCommentContent']) && strlen($_POST['newCommentContent']) <= 140 && $_SESSION['id'] != -1){
                $newComment = new Comment;
                $newComment->setUserId($_SESSION['id']);
                $newComment->setPostId($_GET['id']);
                $newComment->setCreationDate(date('Y-m-d G:i:s', time()));
                $newComment->setContent($_POST['newCommentContent']);
                $newComment->saveToDB($conn);
                header("Location: http://" . "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            }
        }
//render post
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
        $id = $_GET['id'];
        $post = Post::loadPostById($conn, $id);
        $postAuthor = User::loadUserById($conn, $post->getUserId());
        $postInfo = [
            'email' => $postAuthor->getEmail(),
            'content' => $post->getContent(),
            'creationDate' => $post->getCreationDate(),
            'user_id' => $post->getUserId()
        ];
        echo render('templates/singlePostTemplate.html', $postInfo);
?>
   
    <h3>Komentarze:</h3>
    <form method="POST">
        <label>Comment on this</label>
        <input type="text" name="newCommentContent" id="newCommentContent" maxlength="140"/>
        <input type="submit" value="Send comment"/>
    </form>
<?php
//renderowanie komentarzy
        $postComments = Comment::loadAllCommentsByPostIdOrderByDate($conn, $id);
        foreach($postComments as $comment){
            $commentAuthor = User::loadUserById($conn, $comment->getUserId());
            $commentInfo = [
                'commentAuthorId'   => $comment->getUserId(),
                'commentAuthor'     => $commentAuthor->getEmail(),
                'creationDate'      => $comment->getCreationDate(),
                'content'           => $comment->getContent()
            ];
            echo render('templates/commentTemplate.html', $commentInfo);
        }
    }else{
        echo "Go back and select post to show!";
    }
}
?>
</body>
</html>