<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['receiver']) && isset($_GET['text'])) {
    $uid = $_GET['username'];
    $receiver = $_GET['receiver'];
    $text = $_GET['text'];
    $request = $_GET['request'];
    $blockcheck = $dbh->connect()->prepare("SELECT * FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
    if(!$blockcheck->execute(array($receiver, $uid))) {
        $blockcheck = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    if($blockcheck->rowCount() > 0) {
        header('location: ../profile.php?id=' . $uid . '&error=blocked');
        exit();
    }
    $repeatcheck = $dbh->connect()->prepare("SELECT * FROM NOTIFICHE WHERE Mandante = ? AND Ricevente = ? AND Richiesta = ? AND TestoNotifica = ?");
    if(!$repeatcheck->execute(array($uid, $receiver, $request, $text))) {
        $repeatcheck = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    if($repeatcheck->rowCount() > 0) {
        header('location: ../profile.php?id=' . $uid . '&error=repeated');
        exit();
    }
    $idcheck = $dbh->connect()->prepare("SELECT * FROM NOTIFICHE WHERE IdNotifica = ?");
    if($idcheck === false) {
        error_log("Errore nella preparazione della query SELECT.");
    }
    do {
        $nid = rand(1,999);
        if(!$idcheck->execute(array($nid))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
        }
    } while ($idcheck->rowCount() > 0);
    $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICHE (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
    if(!$stmt->execute(array($receiver, $uid, $nid, $text, $request, 0))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
}
