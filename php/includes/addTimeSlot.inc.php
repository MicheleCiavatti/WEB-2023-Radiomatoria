<?php
session_start();
if (isset($_POST['orainizio']) && isset($_POST['orafine'])) {
    $start = $_POST['orainizio'];
    $end = $_POST['orafine'];
    $uid = $_SESSION['NomeUtente'];
    
    if($start >= $end) {
        header('location: ../myProfile.php?error=start>time');
        exit();
    }

    require_once "../classes/dbh.classes.php";
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'INSERT INTO DISPONIBILITA (OraInizio, OraFine, Utente)
         VALUES (?, ?, ?);'
    );
    if (!$s->execute(array($start, $end, $uid))) {
        $s = null;
        header('location: ../myProfile.php?error=stmtfailed');
        exit();
    }
    header('location: ../myProfile.php?error=none');
}