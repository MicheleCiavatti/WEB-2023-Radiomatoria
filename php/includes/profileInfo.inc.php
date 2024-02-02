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

function selectPostProfile($username, $relation_selection, $sort_selection, $order) {
    $dbh = new Dbh;
    $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT INTERAZIONI.Tipo THEN 1 END) AS DislikePost
    FROM (POST LEFT JOIN INTERAZIONI ON POST.NrPost = INTERAZIONI.ElementIdPost AND POST.Creatore = INTERAZIONI.ElementCreator AND INTERAZIONI.ElementIdCommento IS NULL)
    LEFT JOIN COMMENTI ON POST.NrPost = COMMENTI.NrPost AND POST.Creatore = COMMENTI.Creatore";

    switch($relation_selection) {
        case "create":
            $condition = " WHERE POST.Creatore = ?";
            break;
        case "like":
            case "dislike":
                $condition = " WHERE INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";
                break;
        case "comment":
            $condition = " WHERE COMMENTI.Creatore = ?";
            break;
        case "none":
            default:
                $condition = "";
                break;
    }

    $query .= $condition;
    $query .= " GROUP BY POST.NrPost";
    switch($sort_selection) {
        case "data":
            $query .= " ORDER BY DataPost";                
            break;
        case "like":
            $query .= " HAVING INTERAZIONI.Tipo = ? ORDER BY COUNT(INTERAZIONI.Tipo)";  
            break;
        case "comm":
            $query .= " ORDER BY COUNT(COMMENTI.NrCommento)";                
            break;
        case "none":
            default:
                $order = false;
                break;
    }
    if ($order == true) {
        $query .= " DESC";
    }
    $stmt = $dbh->connect()->prepare($query);

    if(isset($_SESSION['NomeUtente'])) {
        $decor = "SELECT CONCAT(INTERAZIONI.ElementIdPost, '_', INTERAZIONI.ElementCreator, '_', INTERAZIONI.ElementIdCommento) FROM
        (INTERAZIONI LEFT JOIN POST ON INTERAZIONI.ElementIdPost = POST.NrPost AND INTERAZIONI.ElementCreator = POST.Creatore AND INTERAZIONI.ElementIdCommento IS NULL)
        LEFT JOIN COMMENTI ON INTERAZIONI.ElementIdPost = COMMENTI.NrPost AND INTERAZIONI.ElementCreator = COMMENTI.Creatore AND INTERAZIONI.ElementIdCommento = COMMENTI.NrCommento";
        $decor .= $condition;
        $decor .= " AND INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";
        $deco = $dbh->connect()->prepare($decor);
        $element_id_like = [];
        $element_id_dislike = [];
    }
            
    switch($relation_selection) {
        case "like": 
            if(isset($_SESSION['NomeUtente'])) {
                if(!$deco->execute(array($utente['NomeUtente'], true, $_SESSION['NomeUtente'], false))) {
                    $deco = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
                $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                if(!$deco->execute(array($utente['NomeUtente'], true, $_SESSION['NomeUtente'], true))) {
                    $deco = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
                $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
            }
            if($sort_selection == "like") {
                if(!$stmt->execute(array($utente['NomeUtente'], true, true))) {
                    $stmt = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
            } else {
                if(!$stmt->execute(array($utente['NomeUtente'], true))) {
                    $stmt = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
            }
            break;
        case "dislike":
            if(isset($_SESSION['NomeUtente'])) {
                if(!$deco->execute(array($utente['NomeUtente'], false, $_SESSION['NomeUtente'], false))) {
                    $deco = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
                $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                if(!$deco->execute(array($utente['NomeUtente'], false, $_SESSION['NomeUtente'], true))) {
                    $deco = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
                $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
            }
            if($sort_selection == "like") {
                if(!$stmt->execute(array($utente['NomeUtente'], false, true))) {
                    $stmt = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
            } else {
                if(!$stmt->execute(array($utente['NomeUtente'], false))) {
                    $stmt = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
            }
            break;
        case "create":
            case "comment":
                if(isset($_SESSION['NomeUtente'])) {
                    if(!$deco->execute(array($utente['NomeUtente'], $_SESSION['NomeUtente'], false))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                    if(!$deco->execute(array($utente['NomeUtente'], $_SESSION['NomeUtente'], true))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
                }
                if($sort_selection == "like") {
                    if(!$stmt->execute(array($utente['NomeUtente'], true))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                } else {
                    if(!$stmt->execute(array($utente['NomeUtente']))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                }
                break;
        case "none":
            default:
                if(isset($_SESSION['NomeUtente'])) {
                    if(!$deco->execute(array($_SESSION['NomeUtente'], false))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                    if(!$deco->execute(array($_SESSION['NomeUtente'], true))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
                }
                if($sort_selection == "like") {
                    if(!$stmt->execute(array(true))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                } else {
                    if(!$stmt->execute()) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                }
                break;
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!isset($result[0]['Creatore'])) {
        return array(null, null, null);
    }
    $post_list = [];
    foreach ($result as $row) {
        $post_list[] = array(
            'Creatore' => $row['Creatore'],
            'NrPost' => $row['NrPost'],
            'DataPost' => $row['DataPost'],
            'TestoPost' => $row['TestoPost'],
            'ImmaginePost' => $row['ImmaginePost'],
            'LikePost' => $row['LikePost'],
            'DislikePost' => $row['DislikePost']
        );
    }
    return array($post_list, $element_id_like, $element_id_dislike);
}

function getComments($creatorPost, $nrPost) {
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT COMMENTI.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikeCommento, COUNT(CASE WHEN NOT INTERAZIONI.Tipo THEN 1 END) AS DislikeCommento
         FROM COMMENTI LEFT JOIN INTERAZIONI ON (COMMENTI.NrPost = INTERAZIONI.ElementIdPost AND COMMENTI.Creatore = INTERAZIONI.ElementCreator AND COMMENTI.NrCommento = INTERAZIONI.ElementIdCommento)
         WHERE COMMENTI.Creatore = ? AND COMMENTI.NrPost = ?
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
    