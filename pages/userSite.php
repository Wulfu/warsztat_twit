<?php
require __DIR__.'/../config/post.php';
require __DIR__.'/../config/postWithEmail.php';
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
<!doctype html>
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
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($_GET['id']) && is_numeric($_GET['id'])){
                $showedUserId = $_GET['id'];
                $showedUser = User::loadUserById($conn, $showedUserId);
                $emailData = ['email' => $showedUser->getEmail()];
                echo render('templates/showedUserSite.html', $emailData);
                $showedUserPosts = PostWithEmail::loadAllUserPostsWithEmailOrderedByDate($conn, $showedUserId);
                foreach($showedUserPosts as $post){
                    $postInfo = [
                        'id' => $post->getId(),
                        'email' => $post->getEmail(),
                        'content' => $post->getContent(),
                        'creationDate' => $post->getCreationDate(),
                        'user_id' => $post->getUserId()
                    ];
                    echo render('templates/singlePostTemplate.html', $postInfo);
                }
            }
        }
    ?>
</body>
</html>