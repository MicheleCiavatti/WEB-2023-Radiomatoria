<?php

    function addPost($post_img, $post_text) {
        $dbh = new Dbh;
        do {
            $pid = hexdec(uniqid());
            $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM COMMENTI WHERE NrPost = ? OR NrCommento = ?");
            if(!$idcheck->execute(array($pid, $pid))) {
                $idcheck = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $result = $idcheck->fetch(PDO::FETCH_NUM);
        } while ($result[0] > 0);
        $stmt = $dbh->connect()->prepare("INSERT INTO POST
        (Creatore, NrPost, DataPost, TestoPost, ImmaginePost) VALUES (?, ?, NOW(), ?, ?)");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $pid, $post_text, $post_img))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function removePost($pid) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT NrCommento FROM COMMENTI WHERE NrPost = ?");
        if(!$stmt->execute(array($pid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        foreach($result[0] as $comment) {
            removeComment($comment);
        }
        $stmt = $dbh->connect()->prepare("DELETE FROM POST WHERE NrPost = ?");
        if(!$stmt->execute(array($pid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE ElementId = ?");
        if(!$stmt->execute(array($pid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function addComment($pid, $cimg, $ctext) {
        $dbh = new Dbh;
        do {
            $cid = hexdec(uniqid());
            $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM COMMENTI WHERE NrPost = ? OR NrCommento = ?");
            if(!$idcheck->execute(array($cid, $cid))) {
                $idcheck = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $result = $idcheck->fetch(PDO::FETCH_NUM);
        } while ($result[0] > 0);
        $stmt = $dbh->connect()->prepare("INSERT INTO COMMENTI
        (Creatore, NrPost, NrCommento, DataCommento, TestoCommento, ImmagineCommento) VALUES (?, ?, ?, NOW(), ?, ?)");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $pid, $cid, $ctext, $cimg))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function removeComment($cid) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM COMMENTI WHERE NrCommento = ?");
        if(!$stmt->execute(array($cid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE ElementId = ?");
        if(!$stmt->execute(array($cid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function addLikeOrDislike($element_id, $type) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("INSERT INTO INTERAZIONI (Creatore, ElementId, Tipo) VALUES (?, ?, ?)");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $element_id, $type))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function removeLikeOrDislike($element_id) {
        $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE Creatore = ? AND ElementId = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $element_id))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

?>