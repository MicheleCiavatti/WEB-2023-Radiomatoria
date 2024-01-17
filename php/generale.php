<?php

    public function accessProfile($username) {
        $stmt->db->prepare("SELECT UTENTE.*, BANDA.frequenza AS frequenze, (DISPONIBILITA.OraInizio, DISPONIBILITA.OraFine) AS orari, AMICIZIA.NomeAmico AS amici, FOLLOW.NomeSeguito AS seguiti, BLOCCO.NomeBloccato AS bloccati
        FROM UTENTE, AMICIZIA, FOLLOW, BLOCCO, BANDA, DISPONIBILITA WHERE UTENTE.NomeUtente = ? AND AMICIZIA.NomeUtente = ? AND FOLLOW.NomeUtente = ? AND BLOCCO.NomeUtente = ? AND BANDA.NomeUtente = ? AND DISPONIBILITA.NomeUtente = ?");
        $stmt->bind_param('ssssss', $username, $username, $username, $username, $username, $username);
        $stmt->execute();
        $utente = $stmt->get_result();
        switchToProfile();
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
        $stmt->bind_param('ss', readCookie('NomeUtente'), $pid);
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
        $stmt->bind_param('ss', readCookie('NomeUtente'), $cid);
        $stmt->execute();
    }

    function removeComment($cid) {
        $stmt = $this->db->prepare("DELETE FROM COMMENTO, SCRITTURA, INTERAZIONE, CONTENUTO WHERE NrCommento = ?");
        $stmt->bind_param('s', $cid);
        $stmt->execute();
    }

    function addLikeOrDislike($element_id, $type) {
        $stmt = $this->db->prepare("INSERT INTO INTERAZIONE (NomeUtente, ElementId, Tipo) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', readCookie('NomeUtente'), $element_id, $type);
        $stmt->execute();
    }

    function removeLikeOrDislike($element_id) {
        $stmt = $this->db->prepare("DELETE FROM INTERAZIONE WHERE NomeUtente = ? AND ElementId = ?");
        $stmt->bind_param('ss', readCookie('NomeUtente'), $element_id);
        $stmt->execute();
    }

?>
