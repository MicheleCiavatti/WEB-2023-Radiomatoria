<?php
require_once "../classes/dbh.classes.php";
$dbh = new Dbh;
if (isset($_GET['username']) && isset($_GET['other'])) {
    $user = $_GET['username'];
    $other = $_GET['other'];
    $s = $dbh->connect()->prepare(
        'INSERT INTO AMICIZIA (Amico1, Amico2)
         VALUES (?, ?);'
    );
    if (!$s->execute(array($user, $other))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
    if (!$s->execute(array($other, $user))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
} else {
    error_log("Variabili non settate");
}