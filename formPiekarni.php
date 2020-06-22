<?php
session_start();

    if (!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
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
<form action="order.php" method="post">

    <?php
        echo "<p></p><a href=logout.php>Wyloguj się!</a></p>";
        echo "<p>Witaj ".$_SESSION['user']." ile zamawiasz";
        echo "<br/>";
        echo "<p>Email usera ".$_SESSION['email'];
    ?>

    <p>Ile pączków (0,99 PLN/szt): </p>
    <input type="text" name="paczkow"/>
    <br/><br/>
    <p>Ile grzebieni (1,29 PLN/szt): </p>
    <input type="text" name="grzebieni"/>
    <br/><br/>
    <input type="submit" value="Wyślij zamówienie"/>

</form>
</body>
</html>