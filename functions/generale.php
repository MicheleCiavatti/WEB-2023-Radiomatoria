<?php

    require_once "../classes/dbh.classes.php";
    function addPost($post_img, $post_text) {
        $dbh = new Dbh;
            
        do {
            $pid = hexdec(uniqid());
            $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM COMMENTI WHERE NrPost = ? OR NrCommento = ?");
            if($idcheck == false) {
                error_log("Errore nella preparazione della query SELECT.");
                exit;
            }
            if(!$idcheck->execute(array($_GET['username'], $lower, $upper))) {
                error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
                exit;
            } 
            $result = $idcheck->fetch(PDO::FETCH_NUM);
        } while ($result[0] > 0);
        $stmt = $dbh->connect()->prepare("INSERT INTO POST
        (Creatore, NrPost, DataPost, TestoPost, ImmaginePost) VALUES (?, ?, NOW(), ?, ?)");
        if($stmt == false) {
            error_log("Errore nella preparazione della query INSERT.");
            exit;
        }
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $pid, $post_text, $post_img))) {
            error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
        }
    }

    function addComment($pid, $cimg, $ctext) {
        $dbh = new Dbh;
        do {
            $cid = hexdec(uniqid());
            $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM COMMENTI WHERE NrPost = ? OR NrCommento = ?");
            if($idcheck == false) {
                error_log("Errore nella preparazione della query SELECT.");
                exit;
            }
            if(!$idcheck->execute(array($cid, $cid))) {
                error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
                exit;
            }
            $result = $idcheck->fetch(PDO::FETCH_NUM);
        } while ($result[0] > 0);
        $stmt = $dbh->connect()->prepare("INSERT INTO COMMENTI
        (Creatore, NrPost, NrCommento, DataCommento, TestoCommento, ImmagineCommento) VALUES (?, ?, ?, NOW(), ?, ?)");
        if($stmt == false) {
            error_log("Errore nella preparazione della query INSERT.");
            exit;
        }
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $pid, $cid, $ctext, $cimg))) {
            error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
        }
    }

?>