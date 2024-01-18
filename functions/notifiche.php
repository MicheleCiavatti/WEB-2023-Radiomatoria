<?php
    function readNotification($nid) {
        $stmt = $this->db->prepare("UPDATE NOTIFICHE SET Lettura = ? WHERE IdNotifica = ?");
        $stmt->bind_param('is', true, $nid);
        $stmt->execute();
    }

    function removeNotification($nid) {
        $stmt = $this->db->prepare("DELETE FROM NOTIFICHE WHERE IdNotifica = ?");
        $stmt->bind_param('s', $nid);
        $stmt->execute();
    }

    function outcomeNotification($nid, $senderid, $outcome) {
        removeNotification($nid);
        $stmt = $this->db->prepare("INSERT INTO NOTIFICHE (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
        do {
            $nid = uniqid();
            $idcheck = $this->db->prepare("SELECT COUNT(*) FROM NOTIFICHE WHERE IdNotifica = ?");
            $idcheck->bind_param('s', $nid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result > 0);
        $stmt->bind_param('ssssii', $senderid, readCookie('NomeUtente'), $nid, $outcome, false, false);
        $stmt->execute();
    }

    function addFriend($nid, $senderid) {
        outcomeNotification($nid, $senderid, "ha accettato la tua richiesta di amicizia");
        $stmt = $this->db->prepare("INSERT INTO AMICIZIA (NomeUtente, NomeAmico) VALUES (? ?)");
        $stmt->bind_param('ss', readCookie('NomeUtente'), $senderid);
        $stmt->execute();
        $stmt->bind_param('ss', $senderid, readCookie('NomeUtente'));
        $stmt->execute();
    }

    function list_notifications() {
        $stmt = $this->db->prepare("SELECT * FROM NOTIFICHE WHERE NOTIFICHE.Ricevente = ? AND NOTIFICHE.Lettura = ?");
        $stmt->bind_param('si', readCookie('NomeUtente'), false);
        $stmt->execute();
        $notifiche_non_lette = $stmt->get_result();
        $stmt->bind_param('si', readCookie('NomeUtente'), true);
        $stmt->execute();
        $notifiche_lette = $stmt->get_result();
    }
?>