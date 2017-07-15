<?php
require __DIR__.'/../config/post.php';
require __DIR__.'/../config/postWithEmail.php';
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
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['post']) && $_SESSION['id'] > -1){
        $newPost = new Post;
        $newPost->setContent($_POST['post']);
        $newPost->setCreationDate(date('Y-m-d G:i:s', time()));
        $newPost->setUserId($_SESSION['id']);
        $newPost->saveToDB($conn);
    }
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
    <div>
        <form action="main.php" method="POST">
            <fieldset>
                <legend>Podziel się czymś:</legend>
                <p><input type="text" name="post" maxlength="140"/></p>
                <p><input type="submit" value="Tweet"/></p>
            </fieldset>
        </form>
    </div>
    <div>
        <h3>Recent Posts</h3>
        <?php
            $allPosts = PostWithEmail::loadAllPostsWithEmailOrderedByDate($conn);
            foreach($allPosts as $post){
                $postInfo = [
                    'id' => $post->getId(),
                    'email' => $post->getEmail(),
                    'content' => $post->getContent(),
                    'creationDate' => $post->getCreationDate(),
                    'user_id' => $post->getUserId()
                ];
                echo render('templates/singlePostTemplate.html', $postInfo);
            }
        ?>
    </div>
</body>
</html>
