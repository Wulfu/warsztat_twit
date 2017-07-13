<?php
require 'config/user.php';
require 'config/config.php';

        
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['userName']) && isset($_POST['email']) && isset($_POST['password'])){
        $userName = $_POST['userName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $newUser = new User;
        try{
            $conn = new PDO('mysql:host='. DB_HOST .';dbname='. DB_DB, DB_USER, DB_PASS);
            $newUser = new User;
            $newUser->setEmail($email);
            $newUser->setUserName($userName);
            $newUser->setPass($password);
            $newUser->saveToDB($conn);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
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
    <title>Zadanie 3 - formularze dodawania do bazy</title>
</head>
<body>
    <div>
        <form action="index.php" method="POST">
            <fieldset>
                <legend>Rejestracja użytkownika</legend>
                <label>Wprowadź imię:</label>
                <input type="text" name="userName"/>
                <label>Wprowadź e-mail:</label>
                <input type="email" name="email"/>
                <label>Wprowadź hasło:</label>
                <input type="password" name="password"/>
                <input type="submit"/>
            </fieldset>
        </form>
    </div>
</body>
</html>
