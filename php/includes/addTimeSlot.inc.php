<?php
session_start();
if (isset($_POST['orainizio']) && isset($_POST['orafine'])) {
    $start = $_POST['orainizio'];
    $end = $_POST['orafine'];
    $uid = $_SESSION['NomeUtente'];
    $stringHeader = "location: ../profile.php?id=" . $uid;
    if($end <= $start) {
        header($stringHeader . '&error=endbeforestart');
        exit();
    }
    
    require_once "../classes/dbh.classes.php";
    $dbh = new Dbh;
    $stmt = $dbh->connect()->prepare("SELECT OraInizio, OraFine FROM DISPONIBILITA WHERE Utente = ?");
    if(!$stmt->execute(array($uid))) {
        $stmt = null;
        header($stringHeader . '&error=stmtfailed');
        exit();
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $end1 = $end;
    if($start > $end) {
        $end1 += 12;
    }
    foreach($result as $interval) {
        $end2 = $interval[1];
        if($interval[0] > $interval[1]) {
            $end2 += 12;
        }
        if (($start < $interval[0] && $interval[0] < $end1) || ($interval[0] < $start && $start < $end2)
        || $interval[0] == $start || $interval[1] == $end) {
            header('location: ../profile.php?id=' . $uid . '&error=overlap');
            exit();
        }
    }
    $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM DISPONIBILITA WHERE OraInizio = ? AND Utente = ?");
    if(!$stmt->execute(array($start, $uid))) {
        $stmt = null;
        header('location: ../profile.php?id = ' . $uid . '&error=stmtfailed');
        exit();
    }
    $result = $stmt->fetch(PDO::FETCH_NUM);
    $stmt = $dbh->connect()->prepare("INSERT INTO DISPONIBILITA (OraInizio, OraFine, Utente) VALUES (?, ?, ?)");
    if(!$stmt->execute(array($start, $end, $uid))) {
        $stmt = null;
        header('location: ../profile.php?id = ' . $uid . '&error=stmtfailed');
        exit();
    }

    header('location: ../profile.php?id=' . $uid .'&error=none');
}