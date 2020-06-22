<?php
session_start();

    if(!isset($_SESSION['udanarejestracja'])){
        header('Location: index.php');
        exit();
    }else{
        unset($_SESSION['udanarejestracja']);
    }

    //czyszczenie
    if (isset($_SESSION['fr_nick']))unset($_SESSION['fr_nick']);
    if (isset($_SESSION['fr_email']))unset($_SESSION['fr_email']);
    if (isset($_SESSION['fr_haslo1']))unset($_SESSION['fr_haslo1']);
    if (isset($_SESSION['fr_haslo2']))unset($_SESSION['fr_haslo2']);
    if (isset($_SESSION['fr_regulaminm']))unset($_SESSION['fr_regulaminm']);

    //usuwanie zmiennych sesyjnych z błędami
    if (isset($_SESSION['e_nick']))unset($_SESSION['e_nick']);
    if (isset($_SESSION['e_email']))unset($_SESSION['e_email']);
    if (isset($_SESSION['e_haslo']))unset($_SESSION['e_haslo']);
    if (isset($_SESSION['e_regulaminm']))unset($_SESSION['e_regulaminm']);
    if (isset($_SESSION['e_boot']))unset($_SESSION['e_boot']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Piekarnia</title>
</head>
<body>
    <h1>Udana rejestracja</h1>

    <a href="index.php">Zaloguj się</a>
    <br/>
    <br/>

</body>
</html>