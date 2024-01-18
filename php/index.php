<?php

function selectPostHome($origin_selection, $sort_selection, $order) {
    $username = readCookie('NomeUtente');
    $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONE.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT INTERAZIONE.Tipo THEN 1 END) AS DislikePost,
    CREAZIONE.NomeUtente AS UserPost FROM ((POST INNER JOIN CREAZIONE ON POST.NrPost = CREAZIONE.NrPost) LEFT JOIN INTERAZIONE ON POST.NrPost = INTERAZIONE.ElementId)";
    if($sort_selection == "comm") {
        $query .= " LEFT JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost";                
    }

    $decor = "SELECT INTERAZIONE.ElementID FROM INTERAZIONE WHERE (INTERAZIONE.ElementId IN (SELECT CREAZIONE.NrPost FROM CREAZIONE WHERE CREAZIONE.NomeUtente";

    $query .= " WHERE CREAZIONE.NomeUtente";
    switch($origin_selection) {
        case "strangers" {
            $condition .= " NOT IN ((SELECT NomeAmico, NomeSeguito,
            NomeBloccato FROM AMICIZIA, FOLLOW, BLOCCO WHERE NomeUtente = ?), ?)";
            break;
        }
        case "friends" {
            $condition .= " IN (SELECT NomeAmico FROM AMICIZIA WHERE NomeUtente = ?)";
            break;
        }
        case "followed" {
            $condition .= " IN (SELECT NomeSeguito FROM FOLLOW WHERE NomeUtente = ?)";
            break;
        }
        case "mine" {
            $condition .= " = ?";
            break;
        }
    }

    $decor .= $condition;
    $decor .= "), (SELECT SCRITTURA.NrCommento FROM SCRITTURA WHERE SCRITTURA.NomeUtente";
    $decor .= $condition;
    $decor .= ")) AND INTERAZIONE.NomeUtente = ? AND INTERAZIONE.Tipo = ?";

    $query .= $condition;
    $query .= " GROUP BY POST.NrPost ORDER BY";
    switch($sort_selection) {
        case "data" {
            $query .= " POST.DataPost";                
            break;
        }
        case "like" {
            $query .= " LikePost";                
            break;
        }
        case "comm" {
            $query .= " COUNT(CONTENUTO.NrCommento)";                
            break;
        }
    }
    if ($order == true) {
        $query .= " DESC";
    }
    $stmt = $this->db->prepare($query);
    $deco = $this->db->prepare($decor);
    if ($origin_selection == "strangers") {
        $stmt->bind_param('ss', $username, $username);
        $deco->bind_param('sssssi', $username, $username, $username, $username, $username, false);
        $deco->execute();
        $element_id_dislike = $deco->get_result();
        $deco->bind_param('sssssi', $username, $username, $username, $username, $username, true);
        $deco->execute();
        $element_id_like = $deco->get_result();
        } else {
        $stmt->bind_param('s', $username);
        $deco->bind_param('sssi', $username, $username, $username, false);
        $deco->execute();
        $element_id_dislike = $deco->get_result();
        $deco->bind_param('sssi', $username, $username, $username, true);
        $deco->execute();
        $element_id_like = $deco->get_result();
    }
    $stmt->execute();
    $post_list = $stmt->get_result();
}

?>