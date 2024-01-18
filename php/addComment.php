<?php
    function addComment($pid, $cimg, $ctext) {
        $stmt = $this->db->prepare("INSERT INTO COMMENTO
        (NrCommento, DataCommento, ImmagineCommento, TestoCommento) VALUES (?, NOW(), ?, ?)");
        do {
            $cid = hexdec(uniqid());
            $idcheck = $this->db->prepare("SELECT COUNT(*) FROM CONTENUTO WHERE NrPost = ? OR NrCommento = ?");
            $idcheck->bind_param('ii', $pid, $pid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result > 0);
        $now = new Date();
        $stmt->bind_param('iss', $cid, $cimg, $ctext);
        $stmt->execute();
        $stmt = $this->db->prepare("INSERT INTO CONTENUTO (NrPost, NrCommento) VALUES (?, ?)");
        $stmt->bind_param('ii', $pid, $cid);
        $stmt->execute();
        $stmt = $this->db->prepare("INSERT INTO SCRITTURA (NomeUtente, NrCommento) VALUES (?, ?)");
        $stmt->bind_param('si', readCookie('NomeUtente'), $cid);
        $stmt->execute();
    }
?>
