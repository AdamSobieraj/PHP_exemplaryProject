<?php
session_start();

    if(isset($_SESSION['zalogowany'])&&($_SESSION['zalogowany']==true)){
        header('Location: formPiekarni.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Piekarnia</title>
</head>
<body>
    <h1>Zamówienie online</h1>

    <a href="rejestracja.php">Zarejestruj się</a>
    <br/>
    <br/>

    <form action="zaloguj.php" method="post">

        <p>Login</p>
        <input type="text" name="login"/>
        <br/><br/>
        <p>Hasło</p>
        <input type="password" name="haslo"/>
        <br/><br/>
        <input type="submit" value="Zaloguj się"/>

    </form>

    <?php
        if (isset($_SESSION['blad'])){
            echo $_SESSION['blad'];
        }
    ?>
</body>
</html>