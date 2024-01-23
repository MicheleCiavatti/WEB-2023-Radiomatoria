<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_COOKIE['NomeUtente'])) {
    $username = $_COOKIE['NomeUtente'];
    $origin_selection = $_GET['origin_selection'];
    $sort_selection = $_GET['sort_selection'];
    $order = $_GET['order'];

    $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT
    INTERAZIONI.Tipo THEN 1 END) AS DislikePost FROM (POST LEFT JOIN INTERAZIONI ON POST.NrPost = INTERAZIONI.ElementId)";
    if($sort_selection == "comm") {
        $query .= " LEFT JOIN COMMENTI ON POST.NrPost = COMMENTI.NrPost";                
    }

    $decor = "SELECT INTERAZIONI.ElementID FROM (INTERAZIONI LEFT JOIN POST ON INTERAZIONI.ElementId = POST.NrPost)
    LEFT JOIN COMMENTI ON INTERAZIONI.ElementId = COMMENTI.NrCommento";

    switch($origin_selection) {
        case "strangers":
            $condition = " WHERE POST.Creatore NOT IN ((SELECT Amico2 FROM AMICIZIA WHERE Amico1 = ?), (SELECT Followed FROM FOLLOW WHERE Follower = ?),
            (SELECT Bloccato FROM BLOCCO WHERE Bloccante = ?), ?)";
            break;
        case "friends":
            $condition = " WHERE POST.Creatore IN (SELECT Amico2 FROM AMICIZIA WHERE Amico1 = ?)";
            break;
        case "followed":
            $condition = " WHERE POST.Creatore IN (SELECT Followed FROM FOLLOW WHERE Follower = ?)";
            break;
        case "mine":
            $condition = " WHERE POST.Creatore = ?";
            break;
        case "all":
            default:
                $condition = "";
                break;
    }

    if($condition == "") {
        $decor .= " WHERE";
    } else {
        $decor .= $condition;
        $decor .= " AND";
        $query .= $condition;
    }

    $decor .= " INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";
    $query .= " GROUP BY POST.NrPost ORDER BY";
    switch($sort_selection) {
        case "like":
            $query .= " LikePost";                
            break;
        case "comm":
            $query .= " COUNT(COMMENTI.*)";                
            break;
        case "data":
            default:
                $query .= " POST.DataPost";                
                break;
    }
    if ($order == true) {
        $query .= " DESC";
    }
    $stmt = $dbh->connect()->prepare($query);
    if($stmt == false) {
        error_log("Errore nella preparazione della query SELECT.");
        exit;
    }
    $deco = $dbh->connect()->prepare($decor);
    if($stmt == false) {
        error_log("Errore nella preparazione della query SELECT.");
        exit;
    }
    if ($origin_selection == "strangers") {
        if(!$stmt->execute(array($username, $username, $username, $username))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($stmt->errorInfo(), true));
            exit;
        } 
        if(!$stmt->execute(array($username, $username, $username, $username))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($stmt->errorInfo(), true));
            exit;
        }
        if(!$deco->execute(array($username, $username, $username, $username, $username, false))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($deco->errorInfo(), true));
            exit;
        }
        $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
        if(!$deco->execute(array($username, $username, $username, $username, $username, true))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($deco->errorInfo(), true));
            exit;
        }
        $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
    } else {
        if(!$stmt->execute(array($username))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($stmt->errorInfo(), true));
            exit;
        }
        if(!$deco->execute(array($username, false))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($stmt->errorInfo(), true));
            exit;
        }
        $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
        if(!$deco->execute(array($username, true))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($deco->errorInfo(), true));
            exit;
        }
        $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
    }
    $post_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array($post_list, $element_id_like, $element_id_dislike);
} else {
    header('location: ../../login.html?error=needtologin');
}