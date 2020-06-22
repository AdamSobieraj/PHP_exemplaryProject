<?php
session_start();
require_once "connect.php";

if(!isset($_POST['login'])||!isset($_POST['haslo'])){
    header('Location: index.php');
    exit();
}

//Open connection - stara metoda z wyciszeniem @ błędu
$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if($polaczenie -> connect_errno!=0 ){
    echo "Error: ".$polaczenie->connect_errno."Opis: ".$polaczenie->connect_error;
}else{
    $login = $_POST['login'];//pobranie danych
    $haslo = $_POST['haslo'];

    //zabespieczenie przed wstrzykiwaniem sql
    $login = htmlentities($login, ENT_QUOTES, "UTF-8");//encje html
//    $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");

//    $sql = "SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo'";

    if($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
            mysqli_real_escape_string($polaczenie,$login)
//            mysqli_real_escape_string($polaczenie,$haslo)

        )
    )){
        $ilu_userów = $rezultat->num_rows;
        if($ilu_userów>0){
            $wiersz = $rezultat->fetch_assoc();

            //weryfikacja hasha
            if (password_verify($haslo, $wiersz['pass'])){
                //            $user = $wiersz['user'];

                $_SESSION['zalogowany'] = true;
                //sesyjna tablica asociacyjna
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['user'] = $wiersz['user'];
                $_SESSION['drewno'] = $wiersz['drewno'];
                $_SESSION['kamien'] = $wiersz['kamien'];
                $_SESSION['email'] = $wiersz['email'];

                unset($_SESSION['blad']);//usuń wywal z sesii
                $rezultat->close();//oczyść pobrane dane
                //przekierowanie
                header('Location: formPiekarni.php');
            }else{
                $_SESSION['blad'] = '<span style="color:#ff0000"> Nieprawidłowy login lub hasło!</span>';
                header('Location: index.php');
            }
        }else{//logowanie nie udane
                $_SESSION['blad'] = '<span style="color:#ff0000"> Nieprawidłowy login lub hasło!</span>';
            header('Location: index.php');
        }
    }

    //zamkniecie połączenia
    $polaczenie->close();
}



