<?php

class Dbh {

    public function connect() {
        try {
            $username = "root";
            $password = "";
            $dbh = new PDO('mysql:host=localhost;dbname=longlight', $username, $password);
            return $dbh;
        } catch(PDOException $e) {
            echo 'Error!; ' . $e->getMessage() . '<br/>';
            die();
        }
    }

    public function access($NomeUtente, $FotoProfilo, $Indirizzo, $Città, $DataNascita, $IndirizzoMail, $Indizio) {
        $time = time() + (86400 * 30); //30 days
        $_SESSION['NomeUtente'] = $NomeUtente;
        $_SESSION['FotoProfilo'] = $FotoProfilo;
        $_SESSION['Indirizzo'] = $Indirizzo;
        $_SESSION['Città'] = $Città;
        $_SESSION['DataNascita'] = $DataNascita;
        $_SESSION['IndirizzoMail'] = $IndirizzoMail;
        $_SESSION['Indizio'] = $Indizio;
        setcookie('NomeUtente', $NomeUtente, $time, "/");
        setcookie('FotoProfilo', $FotoProfilo, $time, "/");
        setcookie('Indirizzo', $Indirizzo, $time, "/");
        setcookie('Città', $Città, $time, "/");
        setcookie('DataNascita', $DataNascita, $time, "/");
        setcookie('IndirizzoMail', $IndirizzoMail, $time, "/");
        setcookie('Indizio', $Indizio, $time, "/");
    }
}