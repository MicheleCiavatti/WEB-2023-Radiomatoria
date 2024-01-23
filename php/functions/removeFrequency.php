<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['f_to_remove'])) {
    $f = $_GET['f_to_remove'];
    $s = $dbh->connect()->prepare(
        'DELETE
         FROM BANDE
         WHERE NomeUtente = ? AND MHz BETWEEN ? AND ?;'
    );
    $lower = $f - 0.01;
    $upper = $f + 0.01;
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$s->execute(array($_GET['username'], $lower, $upper))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    } 
} else {
    error_log("Variabili non settate");
}



