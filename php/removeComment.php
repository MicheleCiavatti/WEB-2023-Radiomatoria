<?php
    function removeComment($cid) {
        $stmt = $this->db->prepare("DELETE FROM COMMENTO WHERE NrCommento = ?");
        $stmt->bind_param('i', $cid);
        $stmt->execute();
        $stmt = $this->db->prepare("DELETE FROM INTERAZIONI WHERE ElementId = ?");
        $stmt->bind_param('i', $cid);
        $stmt->execute();
    }
?>
