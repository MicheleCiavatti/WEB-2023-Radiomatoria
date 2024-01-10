<?php
    function readNotification($nid) {
        $stmt = $this->db->prepare("UPDATE RICEZIONE SET Lettura = ? WHERE IdNotifica = ?");
        $stmt->bind_param('is', true, $nid);
        $stmt->execute();
    }

    function removeNotification($nid) {
        $stmt = $this->db->prepare("DELETE FROM NOTIFICA, CAUSA, RICEZIONE WHERE IdNotifica = ?");
        $stmt->bind_param('s', $nid);
        $stmt->execute();
    }

    function outcomeNotification($nid, $senderid, $outcome) {
        removeNotification($nid);
        $stmt = $this->db->prepare("INSERT INTO NOTIFICA (IdNotifica, TestoNotifica, MittenteNotifica, Richiesta) VALUES (?, ?, ?, ?)");
        do {
            $nid = uniqid();
            $idcheck = $this->db->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE IdNotifica = ?");
            $idcheck->bind_param('s', $nid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result > 0);
        $stmt->bind_param('sssi', $nid, $outcome, $_SESSION['uid'], false);
        $stmt->execute();

        $stmt = $this->db->prepare("INSERT INTO CAUSA (IdNotifica, NomeUtente) VALUES (?, ?)");
        $stmt->bind_param('ss', $nid, $_SESSION['uid']);
        $stmt->execute();

        $stmt = $this->db->prepare("INSERT INTO RICEZIONE (IdNotifica, NomeUtente, Lettura) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $nid, $senderid, false);
        $stmt->execute();
    }

    function addFriend($nid, $senderid) {
        outcomeNotification($nid, $senderid, "ha accettato la tua richiesta di amicizia");
        $stmt = $this->db->prepare("INSERT INTO AMICIZIA VALUES (? ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $senderid);
        $stmt->execute();
        $stmt->bind_param('ss', $senderid, $_SESSION['uid']);
        $stmt->execute();
    }
?>