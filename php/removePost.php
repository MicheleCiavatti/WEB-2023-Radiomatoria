<?php
    function removePost($pid) {
        $stmt = $this->db->prepare("SELECT NrCommento FROM COMMENTI WHERE NrPost = ?");
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $comment) {
            removeComment($comment);
        }
        $stmt = $this->db->prepare("DELETE FROM POST WHERE NrPost = ?");
        $stmt->bind_param('i', $pid);
        $stmt->execute();
    }
?>
