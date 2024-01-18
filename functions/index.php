<?php

function selectPostHome($origin_selection, $sort_selection, $order) {
    $username = readCookie('NomeUtente');
    $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT INTERAZIONI.Tipo THEN 1 END) AS DislikePost,
    FROM (POST LEFT JOIN INTERAZIONI ON POST.NrPost = INTERAZIONI.ElementId)";
    if($sort_selection == "comm") {
        $query .= " LEFT JOIN COMMENTI ON POST.NrPost = COMMENTI.NrPost";                
    }

    $decor = "SELECT INTERAZIONI.ElementID FROM (INTERAZIONI LEFT JOIN POST ON INTERAZIONI.ElementId = POST.NrPost)
    LEFT JOIN COMMENTI ON INTERAZIONI.ElementId = COMMENTI.NrCommento WHERE POST.Creatore";

    $query .= " WHERE POST.Creatore";
    switch($origin_selection) {
        case "strangers" {
            $condition .= " NOT IN ((SELECT Amico2 FROM AMICIZIA WHERE Amico1 = ?), (SELECT Followed FROM FOLLOW WHERE Follower = ?),
            (SELECT Bloccato FROM BLOCCO WHERE Bloccante = ?), ?)";
            break;
        }
        case "friends" {
            $condition .= " IN (SELECT Amico2 FROM AMICIZIA WHERE Amico1 = ?)";
            break;
        }
        case "followed" {
            $condition .= " IN (SELECT Followed FROM FOLLOW WHERE Follower = ?)";
            break;
        }
        case "mine" {
            $condition .= " = ?";
            break;
        }
    }

    $decor .= $condition;
    $decor .= " AND INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";

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
            $query .= " COUNT(COMMENTI.*)";                
            break;
        }
    }
    if ($order == true) {
        $query .= " DESC";
    }
    $stmt = $this->db->prepare($query);
    $deco = $this->db->prepare($decor);
    if ($origin_selection == "strangers") {
        $stmt->bind_param('ssss', $username, $username, $username, $username);
        $deco->bind_param('sssssi', $username, $username, $username, $username, $username, false);
        $deco->execute();
        $element_id_dislike = $deco->get_result();
        $deco->bind_param('sssssi', $username, $username, $username, $username, $username, true);
        $deco->execute();
        $element_id_like = $deco->get_result();
        } else {
        $stmt->bind_param('s', $username);
        $deco->bind_param('ssi', $username, $username, false);
        $deco->execute();
        $element_id_dislike = $deco->get_result();
        $deco->bind_param('ssi', $username, $username, true);
        $deco->execute();
        $element_id_like = $deco->get_result();
    }
    $stmt->execute();
    $post_list = $stmt->get_result();
}

?>
