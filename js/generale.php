<?php

    public function accessProfile($username) {
        $stmt->db->prepare("SELECT UTENTE.* FROM UTENTE WHERE UTENTE.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $utente = $stmt->get_result();

        $stmt->db->prepare("SELECT BANDA.frequenza FROM BANDA WHERE BANDA.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $frequenze = $stmt->get_result();

        $stmt->db->prepare("SELECT DISPONIBILITA.OraInizio, DISPONIBILITA.OraFine FROM DISPONIBILITA WHERE DISPONIBILITA.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $orari = $stmt->get_result();

        $stmt->db->prepare("SELECT AMICIZIA.NomeAmico FROM AMICIZIA WHERE AMICIZIA.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $amici = $stmt->get_result();

        $stmt->db->prepare("SELECT FOLLOW.NomeSeguito FROM FOLLOW WHERE FOLLOW.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $seguiti = $stmt->get_result();

        $stmt->db->prepare("SELECT BLOCCO.NomeBloccato FROM BLOCCO WHERE BLOCCO.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $bloccati = $stmt->get_result();
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