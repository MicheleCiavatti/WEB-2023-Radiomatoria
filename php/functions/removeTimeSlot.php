<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['start']) && isset($_GET['end'])) {
    $uid = $_GET['username'];
    $end = date("H:i:s", strtotime($_GET['end']));
    $start = date("H:i:s", strtotime($_GET['start']));
    error_log("uid: " . $uid . " start: " . $start . " end: " . $end);
    $s = $dbh->connect()->prepare(
        'DELETE 
         FROM DISPONIBILITA
         WHERE Utente = ? AND OraInizio = ? AND OraFine = ?;'
    );
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if (!$s->execute(array($uid, $start, $end))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    }
} else {
    error_log("Variabili non settate");
}