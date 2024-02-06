<?php
require_once "../classes/dbh.classes.php";
$dbh = new Dbh;
if (isset($_GET['mail'])) {
    $s = $dbh->connect()->prepare(
        'SELECT Indizio
         FROM UTENTI
         WHERE IndirizzoMail = ?;'
    );
    if ($s === false) {
        error_log("Errore nella preparazione della query SELECT.");
        exit();
    }
    if(!$s->execute(array($_GET['mail']))) {
        error_log("Errore nell'esecuzione della query SELECT: " . print_r($s->errorInfo(), true));
        exit();
    }
    if($s->rowCount() > 0) {
        $result = $s->fetch(PDO::FETCH_NUM);
        print_r($result[0]);
    } else {
        print_r("Indirizzo e-mail digitato non presente in questo server");
    }
}  else {
    error_log("Variabili non settate");
}