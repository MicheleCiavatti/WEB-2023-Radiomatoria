<?php

function selectPostHome($origin_selection, $sort_selection, $order) {
    $query = "SELECT * FROM POST LEFT JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost INNER JOIN COMMENTO ON
    CONTENUTO.NrCommento = COMMENTO.NrCommento LEFT JOIN INTERAZIONE ON POST.NrPost = INTERAZIONE.NrPost WHERE POST.UserPost";
    switch($origin_selection) {
        case "strangers" {
            $query .= " NOT IN ((SELECT NomeAmico, NomeSeguito,
            NomeBloccato FROM AMICIZIA, FOLLOW, BLOCCO WHERE NomeUtente = ?), ?)";
            break;
        }
        case "friends" {
            $query .= " IN (SELECT NomeAmico FROM AMICIZIA WHERE NomeUtente = ?)";
            break;
        }
        case "followed" {
            $query .= " IN (SELECT NomeSeguito FROM FOLLOW WHERE NomeUtente = ?)";
            break;
        }
        case "mine" {
            $query .= " = ?";
            break;
        }
    }
    $decor = "SELECT INTERAZIONE.ElementID FROM (" + $query + ") WHERE INTERAZIONE.NomeUtente = ? AND INTERAZIONE.Tipo = ?";
    $stmt = $this->db->prepare($decor);
    $stmt->bind_param('si', readCookie('NomeUtente'), false);
    $stmt->execute();
    $element_id_dislike = $stmt->get_result();
    $stmt->bind_param('si', readCookie('NomeUtente'), true);
    $stmt->execute();
    $element_id_like = $stmt->get_result();
    switch($sort_selection) {
        case "data" {
            $query .= " ORDER BY DataPost";                
            break;
        }
        case "like" {
            $query .= " GROUP BY NrPost HAVING INTERAZIONE.Tipo = true ORDER BY COUNT(INTERAZIONE.Tipo)";                
            break;
        }
        case "comm" {
            $query .= " GROUP BY NrPost ORDER BY COUNT(CONTENUTO.NrCommento)";                
            break;
        }
    }
    if ($order == true) {
        $query .= " DESC";
    }
    $stmt = $this->db->prepare($query);
    if ($origin_selection == "strangers") {
        $stmt->bind_param('ss', readCookie('NomeUtente'), readCookie('NomeUtente'));
    } else {
        $stmt->bind_param('s', readCookie('NomeUtente'));
    }
    $stmt->execute();
    $post_list = $stmt->get_result();   //aggiungere like e dislike a post e commenti
    decorate($element_id_like, $element_id_dislike);
}

?>