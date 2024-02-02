<?php
require_once "../classes/dbh.classes.php";
$dbh = new Dbh;
if (isset($_GET['username']) && isset($_GET['other'])) {
    $user = $_GET['username'];
    $other = $_GET['other'];
    $s = $dbh->connect()->prepare(
        'DELETE FROM NOTIFICHE
         WHERE Mandante = ? AND Ricevente = ? AND Richiesta = 1;'
    );
    if (!$s->execute(array($other, $user))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    }
    $s = $dbh->connect()->prepare(
        'INSERT INTO AMICIZIA (Amico1, Amico2)
         VALUES (?, ?);'
    );
    if (!$s->execute(array($user, $other))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
    $s = $dbh->connect()->prepare(
        'INSERT INTO AMICIZIA (Amico2, Amico1)
         VALUES (?, ?);'
    );
    if (!$s->execute(array($user, $other))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
} else {
    error_log("Variabili non settate");
}