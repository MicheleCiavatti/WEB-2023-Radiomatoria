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

        $stmt = $dbh->connect()->prepare(
            'SELECT *
             FROM POST
             WHERE Creatore = ?
             ORDER BY DataPost DESC;'
        );
        if (!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $post = [];
        foreach ($result as $row) {
            $post[] = array($row[0], $row[1], $row[2], $row[3], $row[4]);
        }
    
        return array($utente, $frequenze, $orari, $amici, $seguiti, $bloccati, $post);
    }

function isFriend($user, $other) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM AMICIZIA
         WHERE Amico1 = ? AND Amico2 = ?;'
    );
    if (!$s->execute(array($user, $other))) {
        $s = null;
        header('location: ../profile.php?id=' . $other . '&error=stmtfailed');
        exit();
    }
    return $s->rowCount() > 0;
}

function isFollowed($user, $other) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM FOLLOW
         WHERE Follower = ? AND Followed = ?;'
    );
    if (!$s->execute(array($user, $other))) {
        $s = null;
        header('location: ../profile.php?id=' . $other . '&error=stmtfailed');
        exit();
    }
    return $s->rowCount() > 0;
}

function getComments($creatorPost, $nrPost) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM COMMENTI
         WHERE Creatore = ? AND NrPost = ?
         ORDER BY DataCommento DESC;'
    );
    if (!$s->execute(array($creatorPost, $nrPost))) {
        $s = null;
        header('location: ../profile.php?id=' . $_SESSION['NomeUtente'] . '&error=stmtfailed');
        exit();
    }
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    $comments = [];
    foreach ($result as $row) {
        $comments[] = array($row['Creatore'], $row['DataCommento'], $row['TestoCommento']);
    }
    return $comments;
}
    