<?php
require_once "../classes/dbh.classes.php";
session_start();
if (isset($_POST['frequency'])) {
    $f = $_POST['frequency'];
    $uid = $_SESSION['NomeUtente'];
    $headerString = 'location: ../profile.php?id=' . $uid;
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM BANDE
         WHERE MHz = ? AND NomeUtente = ?;'
    );
    if (!$s->execute(array($f, $uid))) {
        $s = null;
        header($headerString . '&error=stmtfailed');
        exit();
    }
    if ($s->rowCount() > 0) {
        $s = null;
        header($headerString . '&error=duplicate');
        exit();
    }
    $s = $dbh->connect()->prepare(
        'INSERT INTO BANDE (NomeUtente, MHz)
         VALUES (?, ?);'
    );
    
    if (!$s->execute(array($uid, $f))) {
        $s = null;
        header($headerString . '&error=stmtfailed');
        exit();
    }
    header($headerString);
}