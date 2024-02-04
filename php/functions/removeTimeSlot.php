<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['start'])) {
    $uid = $_GET['username'];
    $start = date("H:i:s", strtotime($_GET['start']));
    $s = $dbh->connect()->prepare(
        'DELETE 
         FROM DISPONIBILITA
         WHERE Utente = ? AND OraInizio = ?;'
    );
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if (!$s->execute(array($uid, $start))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    }
} else {
    error_log("Variabili non settate");
}