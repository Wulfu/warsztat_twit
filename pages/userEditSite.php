<?php
require __DIR__.'/../config/config.php';
require __DIR__.'/../config/user.php';
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
    <form method="POST">
        <fieldset>
            <legend>Edit Your Profile</legend>
            <label>Change password</label>
            <p><input type="password" name="password" id="password" placeholder="Type Your new password" /></p>
            <p><input type="password" name="passwordRepeat" id="passwordRepeat" placeholder="Type Your new password again" /></p>
            <p><input type="submit" value="Edit password" /></p>
        </fieldset>
    </form>
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['password']) && isset($_POST['passwordRepeat']) && isset($_GET['id']) && $_POST['password'] === $_POST['passwordRepeat'] && $_SESSION['id'] === $_GET['id']){
        $actualUser = User::loadUserById($conn, $_SESSION['id']);
        $actualUser->setPass($_POST['password']);
        $actualUser->savetoDB($conn);
        if($actualUser->savetoDB($conn) === true){
            echo "<p>You successfully changed password!</p>";
        }else{
            echo "<p>Something went wrong, sorry about that.</p>";
        }
    }else{
        echo "Typed password must be the same.";
    }
}
?>
</body>
</html>