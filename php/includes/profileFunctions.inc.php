<?php
session_start();
require_once __DIR__ . "/../classes/dbh.classes.php";
function profileAccess($username) {
    $dbh = new Dbh;
    $stmt = $dbh->connect()->prepare("SELECT UTENTI.* FROM UTENTI WHERE UTENTI.NomeUtente = ?");
    if(!$stmt->execute(array($username))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $dbh->connect()->prepare("SELECT BANDE.MHz FROM BANDE WHERE BANDE.NomeUtente = ? ORDER BY BANDE.MHz");
    if(!$stmt->execute(array($username))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $frequenze = [];
    foreach ($result as $row) {
        $frequenze[] = $row[0];
    }

    $stmt = $dbh->connect()->prepare("SELECT DISPONIBILITA.OraInizio, DISPONIBILITA.OraFine FROM DISPONIBILITA WHERE DISPONIBILITA.Utente = ? ORDER BY DISPONIBILITA.OraInizio");
    if(!$stmt->execute(array($username))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $orari = [];
    foreach ($result as $row) {
        $orari[] = array($row[0], $row[1]);
    }

    $stmt = $dbh->connect()->prepare("SELECT AMICIZIA.Amico2, UTENTI.FotoProfilo FROM AMICIZIA INNER JOIN UTENTI ON AMICIZIA.Amico2 = UTENTI.NomeUtente WHERE AMICIZIA.Amico1 = ?");
    if(!$stmt->execute(array($username))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $amici = [];
    foreach ($result as $row) {
        $amici[] = array($row[0], $row[1]);
    }

    $stmt = $dbh->connect()->prepare("SELECT FOLLOW.Followed, UTENTI.FotoProfilo FROM FOLLOW INNER JOIN UTENTI ON FOLLOW.Followed = UTENTI.NomeUtente WHERE FOLLOW.Follower = ?");
    if(!$stmt->execute(array($username))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $seguiti = [];
    foreach ($result as $row) {
        $seguiti[] = array($row[0], $row[1]);
    }

    $stmt = $dbh->connect()->prepare("SELECT BLOCCO.Bloccato, UTENTI.FotoProfilo FROM BLOCCO INNER JOIN UTENTI ON BLOCCO.Bloccato = UTENTI.NomeUtente WHERE BLOCCO.Bloccante = ?");
    if(!$stmt->execute(array($username))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $bloccati = [];
    foreach ($result as $row) {
        $bloccati[] = array($row[0], $row[1]);
    }

    return array($utente, $frequenze, $orari, $amici, $seguiti, $bloccati);
}