<?php
    function removeNotification($nid) {
        $stmt = $this->db->prepare("SELECT MotivoNotifica FROM Notifiche WHERE NrNotifica = ?");
        $stmt->bind_param('s', $nid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result == "req_ami") {
            $stmt = $this->db->prepare("SELECT MittenteNotifica FROM Notifiche WHERE NrNotifica = ?");
            $stmt->bind_param('s', $nid);
            $stmt->execute();
            $result = $stmt->get_result();
            refuseFriend($result);
        }
        $stmt = $this->db->prepare("DELETE FROM Notifiche WHERE NrNotifica = ?");
        $stmt->bind_param('s', $nid);
        $stmt->execute();
    }

    function outcomeNotification($senderid, $outcome) {
        $stmt = $this->db->prepare("INSERT INTO Notifiche (IdNotifica, MittenteNotifica, MotivoNotifica, RiceventeNotifica) VALUES (?, ?, ?, ?)");
        do {
            $nid = uniqid();
            $idcheck = $this->db->prepare("SELECT IdNotifica FROM Notifiche WHERE IdNotifica = ?");
            $idcheck->bind_param('s', $nid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result == $nid);
        $stmt->bind_param('ssss', $nid, $_SESSION['uid'], $outcome, $senderid);
        $stmt->execute();
    }

    function outcomeFriend($nid, $senderid, $outcome) {
        outcomeNotification($senderid, $outcome);
        removeNotification($nid);
    }

    function addFriend($nid, $senderid) {
        outcomeFriend($nid, $senderid, "acc_ami");
        $stmt = $this->db->prepare("INSERT INTO AMICIZIA VALUES (? ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $senderid);
        $stmt->execute();
        $stmt->bind_param('ss', $senderid, $_SESSION['uid']);
        $stmt->execute();
    }
?>