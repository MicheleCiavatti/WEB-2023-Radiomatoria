<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['receiver'])) {
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
    if($request != 0) {
        $text = "ti ha inviato una richiesta di amicizia";
    } else {
        $text = "è diventato un tuo follower";
    }
    $nid = rand(1,9999);
    $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICHE (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
    if(!$stmt->execute(array($receiver, $uid, $nid, $text, $request, 0))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
exit();//
}