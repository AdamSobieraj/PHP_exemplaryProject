<?php
session_start();

if (isset($_POST['email'])){
    //udana walidacja
    $wszystko_Ok = true;

    //spr nicka
    $nick = $_POST['nick'];
    //spr długości nicka
    if (strlen($nick)<3 || strlen($nick)>20){
        $wszystko_Ok = false;
        $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków";
    }
    //testowanie nika na znaki alfanumeryczne
    if (ctype_alnum($nick)==false){
        $wszystko_Ok = false;
        $_SESSION['nick'] = "Nick może składać sie tylko z liter i cyfr(bez polskich znaków)";
    }

    //sprawdzenie poprawności adresu email
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false )||($emailB!=$email)){
        $wszystko_Ok=false;
        $_SESSION['e_email'] = "Podaj poprawny adres email";
    }

    //sprawdzenie hasła
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];

    if (strlen($haslo1)<8 || strlen($haslo1)>20){
        $wszystko_Ok = false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków";
    }

    if ($haslo1 != $haslo2){
        $wszystko_Ok = false;
        $_SESSION['e_haslo'] = "Hasła nie są identyczne";
    }

    //hashowanie
    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

    //akceptacja regulaminu
    if (!isset($_POST['regulamin'])){
        $wszystko_Ok = false;
        $_SESSION['e_regulamin'] = "Zakceptuj regulamin!";
    }

    //Akceptacja boota
    $boot_code = "6Le8eacZAAAAAAcJ4I0XJk_Zbndn5fcgvxhfNWsv";
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.
        $boot_code.'&response='.$_POST['g-recaptcha-response']);
    //JSON response
    $odpowiedz = json_decode($sprawdz);

    if ($odpowiedz -> success==false){
        $wszystko_Ok = false;
        $_SESSION['e_boot'] = "Potwierdz że nie jesteś bootem";
    }

    //Zapamietanie wprowadzonych danych
    $_SESSION['fr_nick'] = $nick;
    $_SESSION['fr_email'] = $email;
    $_SESSION['fr_haslo1'] = $haslo1;
    $_SESSION['fr_haslo2'] = $haslo2;
    if (isset($_POST['regulamin'])){
        $_SESSION['fr_regulaminm'] = true;
    }

    //baza połączenie
    require_once "connect.php";

    mysqli_report(MYSQLI_REPORT_STRICT);//wyłączenie błędu i przekazanie go na wyjątki

    try{
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie -> connect_errno!=0 ){
            throw new Exception(mysqli_connect_error());
        }else{
            //czy email istnieje
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
            if (!$rezultat)throw new Exception($polaczenie->error);
            $ile_takich_maili = $rezultat->num_rows;
            if ($ile_takich_maili>0){
                $wszystko_Ok = false;
                $_SESSION['e_email'] = "Do takiego emaila już jest przypisane konto";
            }

            //czy nick jest juz zarezerwowany
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
            if (!$rezultat)throw new Exception($polaczenie->error);
            $ile_takich_nickow = $rezultat->num_rows;
            if ($ile_takich_nickow>0){
                $wszystko_Ok = false;
                $_SESSION['e_nick'] = "Wybierz inny nick";
            }

            //koniec
            if ($wszystko_Ok == true){
                //walidacja udana
//                echo "udana walidacja";
//                exit();

                //wykonanie inserta
                if ($polaczenie->query("INSERT INTO uzytkownicy 
                            VALUES(NULL, '$nick', '$haslo_hash','$email',100,100,100,14)")){
                    $_SESSION['udanarejestracja'] = true;
                    header('Location:witamy.php');
                }else{
                    throw new Exception($polaczenie->error);
                }

            }

            $polaczenie ->close();
        }
    }catch (Exception $e){
        echo '<span style"color:red;">Błąd servera</span>';
//        echo '<br/>Informacja developera'.$e;
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Piekarnia</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .error
        {
            color: red;
            margin-top:10px ;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Zakładanie kont5a</h1>

<a href="rejestracja.php">Zarejestruj się</a>
<br/>
<br/>

<form method="post">

    <p>Nickname</p>
    <input type="text"  value="<?php
        if (isset($_SESSION['fr_nick'])){
            echo $_SESSION['fr_nick'];
            unset($_SESSION['fr_nick']);
        }
    ?>" name="nick"/>
    <br/><br/>
    <?php
    if (isset($_SESSION['e_nick'])){
        echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
        unset($_SESSION['e_nick']);
    }
    ?>

    <p>E-mail</p>
    <input type="text" name="email" value="<?php
    if (isset($_SESSION['fr_email'])){
        echo $_SESSION['fr_email'];
        unset($_SESSION['fr_email']);
    }
    ?>"/>
    <br/><br/>
    <?php
    if (isset($_SESSION['e_email'])){
        echo '<div class="error">'.$_SESSION['e_email'].'</div>';
        unset($_SESSION['e_email']);
    }
    ?>

    <p>hasło</p>
    <input type="password" name="haslo1" value="<?php
    if (isset($_SESSION['fr_haslo1'])){
        echo $_SESSION['fr_haslo1'];
        unset($_SESSION['fr_haslo1']);
    }
    ?>"/>
    <br/><br/>
    <?php
    if (isset($_SESSION['e_haslo'])){
        echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
        unset($_SESSION['e_haslo']);
    }
    ?>


    <p>hasło repeat</p>
    <input type="password" name="haslo2" value="<?php
    if (isset($_SESSION['fr_haslo2'])){
        echo $_SESSION['fr_haslo2'];
        unset($_SESSION['fr_haslo2']);
    }
    ?>"/>
    <br/><br/>

    <p>Akceptyuje regulamin</p>
    <label><input type="checkbox" name="regulamin"
    <?php
        if (isset($_SESSION['fr_regulaminm'])){
            echo "checked";
            unset($_SESSION['fr_regulaminm']);
        }
    ?>
    /></label>
    <br/><br/>
    <?php
    if (isset($_SESSION['e_regulamin'])){
        echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
        unset($_SESSION['e_regulamin']);
    }
    ?>

    <div class="g-recaptcha" data-sitekey="6Le8eacZAAAAAHzTCsonUmEGhVS6V8LZ7btXoFQe"></div>
    <br/>
    <?php
    if (isset($_SESSION['e_boot'])){
        echo '<div class="error">'.$_SESSION['e_boot'].'</div>';
        unset($_SESSION['e_boot']);
    }
    ?>

    <input type="submit" value="Zarejestruj się">

</form>

</body>
</html>