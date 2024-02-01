<?php
require_once "../classes/dbh.classes.php";
$dbh = new Dbh;
if (isset($_GET['username']) && isset($_GET['other']) && 
isset($_GET['text']) && isset($_GET['read']) && isset($_GET['request'])) {
    $user = $_GET['username'];
    $other = $_GET['other'];
    $text = $_GET['text'];
    $read = $_GET['read'];
    $request = $_GET['request'];
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM NOTIFICHE
         WHERE Mandante = ? AND Ricevente = ?;'
    );
    if (!$s->execute(array($user, $other))) {
        error_log("Errore nell'esecuzione della query SELECT: " . print_r($s->errorInfo(), true));
    }
    $id = $s->rowCount() + 1;
    $s = $dbh->connect()->prepare(
        'INSERT INTO NOTIFICHE (IdNotifica, Mandante, Ricevente, TestoNotifica, Lettura, Richiesta)
         VALUES (?, ?, ?, ?, ?, ?);'
    );
    if(!$s->execute(array($id, $user, $other, $text, $read, $request))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
} else {
    error_log("Variabili non settate");
}