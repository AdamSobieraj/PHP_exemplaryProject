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
    <title>Podsumowanie zamówienia</title>
</head>
<body>

<?php

$poaczkow = $_POST['paczkow'];
$grzebieni = $_POST['grzebieni'];
$suma = 0.99 * $poaczkow + 1.29*$grzebieni;

echo<<<END
    <h2>Podsumowanie zamowienia</h2>
    <table border = "1" cellpadding = "10" cellspacing="0"
    <tr>
        <td>Pączek (0,99 PLN/szt)</td><td>$poaczkow</td>
    </tr>
    <tr>
        <td>Grzebień (1,29 PLN/szt)</td><td>$grzebieni</td>
    </tr>
    <tr>
        <td>SUMA</td><td>$suma PLN</td>
    </tr>
    </table>
    <br><a href="index.php">Powrót do strony głównej</a>
    
    
END;

?>

</body>
</html>