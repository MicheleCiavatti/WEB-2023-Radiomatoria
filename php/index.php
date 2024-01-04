<?php

function sortPost($selection) {
    switch($selection) {
        case "data" {
            $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
            INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento ORDER BY DataPost DESC");                
        }
        case "like" {
            $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
            INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento INNER JOIN INTERAZIONE ON POST.NrPost =
            INTERAZIONE.NrPost WHERE INTERAZIONE.Tipo IS true GROUP BY NrPost ORDER BY COUNT(INTERAZIONE.Tipo) DESC");                
        }
        case "comm" {
            $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost 
            INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento GROUP BY NrPost ORDER BY COUNT(CONTENUTO.NrCommento) DESC");                
        }
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $templateParams["post"] = $result;
    toDecorate($result);
}

function addPost($post_img, $post_text) {
    $stmt = $this->db->prepare("INSERT INTO POST (NrPost, DataPost, TestoPost, ImmaginePost) VALUES (?, NOW(), ?, ?)");
    do {
        $pid = uniqid();
        $idcheck = $this->db->prepare("SELECT COUNT(*) FROM CONTENUTO WHERE NrPost = ? OR NrCommento = ?");
        $idcheck->bind_param('s', $pid, $pid);
        $idcheck->execute();
        $result = $idcheck->get_result();
    } while ($result > 0);
    $now = new Date();
    $stmt->bind_param('sss', $pid, $post_img, $post_text);
    $stmt->execute();
    $stmt = $this->db->prepare("INSERT INTO CREAZIONE (NomeUtente, NrPost) VALUES (?, ?)");
    $stmt->bind_param('ss', $_SESSION['uid'], $pid);
    $stmt->execute();
}

function removePost($pid) {
    $stmt = $this->db->prepare("SELECT NrCommento FROM CONTENUTO WHERE NrPost = ?");
    $stmt->bind_param('s', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    foreach($result as $comment) {
        removeComment($comment);
    }
    $stmt = $this->db->prepare("DELETE FROM POST, CREAZIONE, INTERAZIONE WHERE NrPost = ?");
    $stmt->bind_param('s', $pid);
    $stmt->execute();
}

function addComment($pid, $cimg, $ctext) {
    $stmt = $this->db->prepare("INSERT INTO COMMENTO
    (NrCommento, DataCommento, ImmagineCommento, TestoCommento) VALUES (?, NOW(), ?, ?)");
    do {
        $cid = uniqid();
        $idcheck = $this->db->prepare("SELECT COUNT(*) FROM CONTENUTO WHERE NrPost = ? OR NrCommento = ?");
        $idcheck->bind_param('s', $pid, $pid);
        $idcheck->execute();
        $result = $idcheck->get_result();
    } while ($result > 0);
    $now = new Date();
    $stmt->bind_param('sss', $cid, $cimg, $ctext);
    $stmt->execute();
    $stmt = $this->db->prepare("INSERT INTO CONTENUTO (NrPost, NrCommento) VALUES (?, ?)");
    $stmt->bind_param('ss', $pid, $cid);
    $stmt->execute();
    $stmt = $this->db->prepare("INSERT INTO SCRITTURA (NomeUtente, NrCommento) VALUES (?, ?)");
    $stmt->bind_param('ss', $_SESSION['uid'], $cid);
    $stmt->execute();
}

function removeComment($cid) {
    $stmt = $this->db->prepare("DELETE FROM COMMENTO, SCRITTURA, INTERAZIONE, CONTENUTO WHERE NrCommento = ?");
    $stmt->bind_param('s', $cid);
    $stmt->execute();
}

function addLikeOrDislike($element_id, $type) {
    $stmt = $this->db->prepare("INSERT INTO INTERAZIONE (NomeUtente, ElementId, Tipo) VALUES (?, ?, ?)");
    $stmt->bind_param('ssi', $_SESSION['uid'], $element_id, $type);
    $stmt->execute();
}

function removeLikeOrDislike($element_id) {
    $stmt = $this->db->prepare("DELETE FROM INTERAZIONE WHERE NomeUtente = ? AND ElementId = ?");
    $stmt->bind_param('ss', $_SESSION['uid'], $element_id);
    $stmt->execute();
}

function toDecorate($result) {
    $stmt = $this->db->prepare("SELECT ElementId FROM INTERAZIONE WHERE NomeUtente = ? AND Tipo = ?");
    $stmt->bind_param('si', $_SESSION['uid'], true);
    $stmt->execute();
    $element_id_like = $stmt->get_result();

    $stmt->bind_param('si', $_SESSION['uid'], false);
    $stmt->execute();
    $element_id_dislike = $stmt->get_result();
    decorate($element_id_like, $element_id_dislike);
}
?>