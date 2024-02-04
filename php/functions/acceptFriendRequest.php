<?php
require_once "../classes/dbh.classes.php";
require_once "Notify.php";
$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['other'])) {
    $receiver = $_GET['username'];
    $sender = $_GET['other'];
    removeNotification($sender, $receiver, 0, 1);
    $s = $dbh->connect()->prepare(
        'INSERT INTO AMICIZIA (Amico1, Amico2)
         VALUES (?, ?);'
    );
    if (!$s->execute(array($receiver, $sender))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
    $s = $dbh->connect()->prepare(
        'INSERT INTO AMICIZIA (Amico2, Amico1)
         VALUES (?, ?);'
    );
    if (!$s->execute(array($receiver, $sender))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
    notify($receiver, $sender, "L'utente " . $receiver . " ha accettato la tua richiesta di amicizia", 0, 0);
} else {
    error_log("Variabili non settate");
}