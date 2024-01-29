<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['start'])) {
    $s = $dbh->connect()->prepare(
        'DELETE 
         FROM DISPONIBILITA
         WHERE Utente = ? AND OraInizio = ?;'
    );
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if (!$s->execute(array($_GET['username'], $_GET['start']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    }
} else {
    error_log("Variabili non settate");
}