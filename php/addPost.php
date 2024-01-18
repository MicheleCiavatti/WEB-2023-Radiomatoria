<?php
    function addPost($post_img, $post_text) {
        $stmt = $this->db->prepare("INSERT INTO POST (Creatore, NrPost, DataPost, TestoPost, ImmaginePost) VALUES (?, NOW(), ?, ?)");
        do {
            $pid = hexdec(uniqid());
            $idcheck = $this->db->prepare("SELECT COUNT(*) FROM COMMENTI WHERE NrPost = ? OR NrCommento = ?");
            $idcheck->bind_param('ii', $pid, $pid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result > 0);
        $now = new Date();
        $stmt->bind_param('issss', readCookie('NomeUtente'), $pid, $now, $post_text, $post_img);
        $stmt->execute();
    }
?>
