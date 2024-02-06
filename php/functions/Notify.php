<?php
require_once "../classes/dbh.classes.php";
function notify($sender, $receiver, $text, $request, $read = 'no post') {
    if ($sender != $receiver) {
        error_log("Notifica: " . $sender . " -> " . $receiver . " : " . $text . " Richiesta: " . $request . " Lettura: " . $read);
        $dbh = new Dbh;
        $s = $dbh->connect()->prepare(
            'SELECT MAX(IdNotifica) AS MaxNotifica
             FROM NOTIFICHE
             WHERE Mandante = ? AND Ricevente = ?;'
        );
        if(!$s->execute(array($sender, $receiver))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($s->errorInfo(), true));
        }
        $nid = $s->fetch()['MaxNotifica'] + 1;
        $s = $dbh->connect()->prepare(
            'INSERT INTO NOTIFICHE (Mandante, Ricevente, IdNotifica, TestoNotifica, Richiesta, Lettura)
             VALUES (?, ?, ?, ?, ?, ?);'
        );
        if(!$s->execute(array($sender, $receiver, $nid, $text, $request, $read))) {
            error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
        }
    }
}

function removeNotification($sender, $receiver, $nid, $request = 0) {
    $dbh = new Dbh;
    if ($request == 1) {
        $s = $dbh->connect()->prepare(
            'DELETE FROM NOTIFICHE
             WHERE Mandante = ? AND Ricevente = ? AND Richiesta = 1;'
        );
        if(!$s->execute(array($sender, $receiver))) {
            error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
        }
    } else {
        $s = $dbh->connect()->prepare(
            'DELETE FROM NOTIFICHE
             WHERE Mandante = ? AND Ricevente = ? AND IdNotifica = ?;'
        );
        if(!$s->execute(array($sender, $receiver, $nid))) {
            error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
        }
    }
}