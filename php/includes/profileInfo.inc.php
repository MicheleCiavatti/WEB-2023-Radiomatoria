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
         ORDER BY NrPost DESC;'
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

function getPosts($username) {
    $dbh = new Dbh;
    if ($username == null) {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM POST
             ORDER BY DataPost DESC
             LIMIT 20;'
        );
        if (!$s->execute()) {
            return false;
        }
    } else {
        $s = $dbh->connect()->prepare(
            'SELECT * 
             FROM POST P
             WHERE P.Creatore IN 
                (SELECT F.Followed
                 FROM FOLLOW F
                 WHERE F.Follower = ?)
             OR P.Creatore IN 
                (SELECT A.Amico1
                 FROM AMICIZIA A
                 WHERE A.Amico2 = ?)
             ORDER BY P.DataPost DESC
             LIMIT 10;'
        );
        if (!$s->execute([$username, $username])) {
            return false;
        }
    }
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    $posts = [];
    foreach ($result as $row) {
        $posts[] = array(
            'Creatore' => $row['Creatore'],
            'NrPost' => $row['NrPost'],
            'DataPost' => $row['DataPost'],
            'TestoPost' => $row['TestoPost'],
            'ImmaginePost' => $row['ImmaginePost'],
        );
    }
    if ($username != null) {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM POST
             WHERE CREATORE NOT IN 
                (SELECT F.Followed
                 FROM FOLLOW F
                 WHERE F.Follower = ?)
             AND CREATORE NOT IN
                (SELECT A.Amico1
                 FROM AMICIZIA A
                 WHERE A.Amico2 = ?)
             AND CREATORE != ?
             ORDER BY DataPost DESC
             LIMIT 3;'
        );
        if (!$s->execute([$username, $username, $username])) {
            return false;
        }
        $result = $s->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            error_log(print_r($row, true));
            array_unshift($posts, array(
                'Creatore' => $row['Creatore'],
                'NrPost' => $row['NrPost'],
                'DataPost' => $row['DataPost'],
                'TestoPost' => $row['TestoPost'],
                'ImmaginePost' => $row['ImmaginePost'],
            ));
        }
    }
    return $posts;
}

function isFriend($other) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM AMICIZIA
         WHERE Amico1 = ? AND Amico2 = ?;'
    );
    if (!$s->execute(array($_SESSION['NomeUtente'], $other))) {
        $s = null;
        header('location: ../profile.php?id=' . $other . '&error=stmtfailed');
        exit();
    }
    return $s->rowCount() > 0;
}

function friendshipRequested($other) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM NOTIFICHE
         WHERE Mandante = ? AND Ricevente = ? AND Richiesta = 1;'
    );
    if (!$s->execute(array($_SESSION['NomeUtente'], $other))) {
        $s = null;
        header('location: ../profile.php?id=' . $other . '&error=stmtfailed');
        exit();
    }
    return $s->rowCount() > 0;
}

function isFollowed($other) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM FOLLOW
         WHERE Follower = ? AND Followed = ?;'
    );
    if (!$s->execute(array($_SESSION['NomeUtente'], $other))) {
        $s = null;
        header('location: ../profile.php?id=' . $other . '&error=stmtfailed');
        exit();
    }
    return $s->rowCount() > 0;
}

function isBlocked($other) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM BLOCCO
         WHERE Bloccante = ? AND Bloccato = ?;'
    );
    if (!$s->execute(array($_SESSION['NomeUtente'], $other))) {
        $s = null;
        header('location: ../profile.php?id=' . $other . '&error=stmtfailed');
        exit();
    }
    return $s->rowCount() > 0;
}

function resetPropic($username) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'UPDATE UTENTI
         SET FotoProfilo = "../img/default.png"
         WHERE NomeUtente = ?;'
    );
    if (!$s->execute(array($username))) {
        $s = null;
        header('location: ../profile.php?id=' . $username . '&error=stmtfailed');
        exit();
    }
}


function getComments($creatorPost, $nrPost) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT COMMENTI.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikeCommento, COUNT(CASE WHEN NOT INTERAZIONI.Tipo THEN 1 END) AS DislikeCommento
         FROM COMMENTI LEFT JOIN INTERAZIONI ON (COMMENTI.NrPost = INTERAZIONI.ElementIdPost AND COMMENTI.Creatore = INTERAZIONI.ElementCreator AND COMMENTI.NrCommento = INTERAZIONI.ElementIdCommento)
         WHERE COMMENTI.Creatore = ? AND COMMENTI.NrPost = ?
         GROUP BY COMMENTI.Creatore, COMMENTI.NrPost, COMMENTI.NrCommento
         ORDER BY DataCommento DESC;'
    );
    if (!$s->execute(array($creatorPost, $nrPost))) {
        $s = null;
        header('location: ../profile.php?id=' . $creatorPost . '&error=stmtfailed');
        exit();
    }
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    if(!isset($result[0]['Creatore'])) {
        return null;
    }
    $comments = [];
    foreach ($result as $row) {
        $comments[] = array(
            'Creatore' => $row['Creatore'],
            'NrPost' => $row['NrPost'],
            'AutoreCommento' => $row['AutoreCommento'],
            'NrCommento' => $row['NrCommento'],
            'DataCommento' => $row['DataCommento'],
            'TestoCommento' => $row['TestoCommento'],
            'ImmagineCommento' => $row['ImmagineCommento'],
            'LikeCommento' => $row['LikeCommento'],
            'DislikeCommento' => $row['DislikeCommento']
        );
    }
    return $comments;
}