<?php
session_start();
require __DIR__.'/../config/user.php';
require __DIR__.'/../config/config.php';
require __DIR__.'/render.php';
$conn = new PDO('mysql:host='. DB_HOST .';dbname='. DB_DB, DB_USER, DB_PASS);
//definije zmienną sesion w której będę przetrzymywał id po zalogowaniu
if(!isset($_SESSION['id'])){
    $_SESSION['id'] = -1;
}   
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logowanie</title>
</head>
<body>
    <div>
        <form action="login.php" method="POST">
            <fieldset>
                <legend>Zaloguj się</legend>
                <label>Wprowadź email:</label>
                <input type="email" name="email"/>
                <label>Wprowadź hasło:</label>
                <input type="password" name="password"/>
                <input type="submit"/>
            </fieldset>
        </form>
        <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['email']) && isset($_POST['password'])){
                $loggedUser = User::loadUserByEmail($conn, $_POST['email']);
                if($loggedUser !== null){
                    if($loggedUser->verifyPass($_POST['password'])){
                        $_SESSION['id'] = $loggedUser->getId();
                        header("Location: main.php");
                    }else{
                        $data = ['info'=>'Wrong login or password, try again.'];
                        echo render('templates/wrong.html', $data);
                    }
                }else{
                    $data = ['info'=>'There is no such email in database.'];
                    echo render('templates/wrong.html', $data);
                }
            }
        }
        ?>
        <a href="register.php"><h3>Kliknij tutaj aby się zarejestrować</h3></a>
    </div>
</body>
</html>