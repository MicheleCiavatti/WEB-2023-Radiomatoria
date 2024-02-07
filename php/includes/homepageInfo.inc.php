<?php
require_once __DIR__ . "/../classes/dbh.classes.php";

function getPosts($username, $profile) {
    $dbh = new Dbh;
    if ($username == null) {
        $s = $dbh->connect()->prepare(
            'SELECT P.*, COUNT(CASE WHEN I.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT I.Tipo THEN 1 END) AS DislikePost
             FROM POST P LEFT JOIN INTERAZIONI I ON P.NrPost = I.ElementId AND P.Creatore = I.Creatore
             GROUP BY P.Creatore, P.NrPost
             ORDER BY DataPost DESC
             LIMIT 20;'
        );
    } else if($profile == true) {
        $s = $dbh->connect()->prepare(
            'SELECT P.*, COUNT(CASE WHEN I.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT I.Tipo THEN 1 END) AS DislikePost
             FROM POST P LEFT JOIN INTERAZIONI I ON P.NrPost = I.ElementId AND P.Creatore = I.Creatore
             WHERE P.Creatore IN 
                ((SELECT F.Followed
                 FROM FOLLOW F
                 WHERE F.Follower = ?) UNION
                (SELECT A.Amico1
                 FROM AMICIZIA A
                 WHERE A.Amico2 = ?))
                 OR P.Creatore = ?
             GROUP BY P.Creatore, P.NrPost
             ORDER BY P.DataPost DESC
             LIMIT 20;'
        );
    } else {
        $s = $dbh->connect()->prepare(
            'SELECT P.*, COUNT(CASE WHEN I.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT I.Tipo THEN 1 END) AS DislikePost
             FROM POST P LEFT JOIN INTERAZIONI I ON P.NrPost = I.ElementId AND P.Creatore = I.Creatore
             WHERE P.Creatore NOT IN 
             ((SELECT F.Followed
                 FROM FOLLOW F
                 WHERE F.Follower = ?) UNION
                (SELECT A.Amico1
                 FROM AMICIZIA A
                 WHERE A.Amico2 = ?))
                 AND NOT P.Creatore = ?
             GROUP BY P.Creatore, P.NrPost
             ORDER BY DataPost DESC
             LIMIT 20;'
        );
    }
    if (!$s->execute(array($username, $username, $username))) {
        return false;
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
            'LikePost' => $row['LikePost'],
            'DislikePost' => $row['DislikePost'],
        );
    }
    return $posts;
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

function getComments($creatorPost, $nrPost) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT C.*, COUNT(CASE WHEN R.Tipo THEN 1 END) AS LikeCommento, COUNT(CASE WHEN NOT R.Tipo THEN 1 END) AS DislikeCommento
         FROM COMMENTI C LEFT JOIN REAZIONI R ON (C.NrPost = R.ElementIdPost AND C.Creatore = R.Creatore AND C.NrCommento = R.ElementIdCommento)
         WHERE C.Creatore = ? AND C.NrPost = ?
         GROUP BY C.Creatore, C.NrPost, C.NrCommento
         ORDER BY C.DataCommento DESC;'
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

function isLiked($username, $creatorPost, $nrPost, $nrCommento) {
    $dbh = new Dbh;
    if(!$nrCommento) {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM INTERAZIONI
             WHERE Creatore = ? AND ElementId = ? AND Interagente = ? AND Tipo = 1;' // Tipo 1 = like
        );
        error_log('creatorPost: ' . $creatorPost . ' nrPost: ' . $nrPost . ' username: ' . $username);
        if (!$s->execute(array($creatorPost, $nrPost, $username))) {
            $s = null;
            header('location: ../home.php?error=stmtfailed');
            exit();
        }
    } else {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM REAZIONI
             WHERE Creatore = ? AND ElementIdPost = ? AND ElementIdCommento = ? AND Reagente = ? AND Tipo = 1;'
        );
        error_log('creatorPost: ' . $creatorPost . ' nrPost: ' . $nrPost . ' nrCommento: ' . $nrCommento . ' username: ' . $username);
        if (!$s->execute(array($creatorPost, $nrPost, $nrCommento, $username))) {
            $s = null;
            header('location: ../home.php?error=stmtfailed');
            exit();
        }
    }
    return $s->rowCount() > 0;
}

function isDisliked($username, $creatorPost, $nrPost, $nrCommento) {
    $dbh = new Dbh;
    if(!$nrCommento) {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM INTERAZIONI
             WHERE Creatore = ? AND ElementId = ? AND Interagente = ? AND Tipo = 0;' // Tipo 0 = dislike
        );
        error_log('creatorPost: ' . $creatorPost . ' nrPost: ' . $nrPost . ' username: ' . $username);
        if (!$s->execute(array($creatorPost, $nrPost, $username))) {
            $s = null;
            header('location: ../home.php?error=stmtfailed');
            exit();
        }
    } else {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM REAZIONI
             WHERE Creatore = ? AND ElementIdPost = ? AND ElementIdCommento = ? AND Reagente = ? AND Tipo = 0;'
        );
        error_log('creatorPost: ' . $creatorPost . ' nrPost: ' . $nrPost . ' nrCommento: ' . $nrCommento . ' username: ' . $username);
        if (!$s->execute(array($creatorPost, $nrPost, $nrCommento, $username))) {
            $s = null;
            header('location: ../home.php?error=stmtfailed');
            exit();
        }
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
        header('location: ../home.php?error=stmtfailed');
        exit();
    }
}
