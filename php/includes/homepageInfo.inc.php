<?php
require_once __DIR__ . "/../classes/dbh.classes.php";

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
        header('location: ../profile.php?id=' . $creatorPost . '&error=stmtfailed');
        exit();
    }
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    $comments = [];
    foreach ($result as $row) {
        $comments[] = array('AutoreCommento' => $row['AutoreCommento'], 
                            'DataCommento' => $row['DataCommento'], 
                            'TestoCommento' => $row['TestoCommento'], 
                            'ImmagineCommento' => $row['ImmagineCommento']);
    }
    return $comments;
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
        header('location: ../home.php?error=stmtfailed');
        exit();
    }
}

function getNotifications($username) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT COUNT(*) AS N_Notifiche
         FROM NOTIFICHE
         WHERE Ricevente = ?;'
    );
    if (!$s->execute(array($username))) {
        $s = null;
        header('location: ../notifiche.php?id=' . $username . '&error=stmtfailed');
        exit();
    }
    return $s->fetch()['N_Notifiche'];
}