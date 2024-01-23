<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['f_to_remove'])) {
    error_log("Variabili arrivate: " . $_GET['username'] . " e " . $_GET['f_to_remove']);
    $s = $dbh->connect()->prepare(
        'DELETE
         FROM BANDE
         WHERE NomeUtente = ? AND MHz = ?;'
    );
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$s->execute(array($_GET['username'], floatval($_GET['f_to_remove'])))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    } 
} else {
    error_log("Variabili non settate");
}



