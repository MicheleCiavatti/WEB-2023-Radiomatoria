<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['receiver']) && isset($_GET['request'])) {
    $uid = $_GET['username'];
    $receiver = $_GET['receiver'];
    $request = $_GET['request'];

    $blockcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
    if(!$blockcheck->execute(array($receiver, $uid))) {
        $blockcheck = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $block = $blockcheck->fetch(PDO::FETCH_NUM);
    if($block[0] != 0) {
        header('location: ../profile.php?id=' . $uid . '&error=blocked');
        exit();
    }
    $repeatcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE Mandante = ? AND Richiesta = ?");
    if(!$repeatcheck->execute(array($uid, $request))) {
        $repeatcheck = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $repeat = $repeatcheck->fetch(PDO::FETCH_NUM);
    if($repeat[0] != 0) {
        header('location: ../profile.php?id=' . $uid . '&error=duplicate');
        exit();
    }
    if($request) {
        $text = "ti ha inviato una richiesta di amicizia";
    } else {
        $text = "è diventato un tuo follower";
    }
    do {
        $id = null;
        $nid = uniqid();
        $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE IdNotifica = ?");
        if(!$idcheck->execute(array($nid))) {
            $idcheck = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $id = $idcheck->fetch(PDO::FETCH_NUM);
    } while ($id[0] > 0);
    $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICA (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
    if(!$stmt->execute(array($receiver, $uid, $nid, $text, $request, false))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
}