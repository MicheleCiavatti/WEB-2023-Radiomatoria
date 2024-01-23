<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_POST['username']) && isset($_POST['start']) && isset($_POST['end'])) {
    $s = $dbh->connect()->prepare(
        'DELETE 
         FROM DISPONIBILITA
         WHERE NomeUtente = ? AND OraInizio = ? AND OraFine = ?;'
    );
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if (!$s->execute(array($_POST['username'], $_POST['start'], $_POST['end']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    }
}