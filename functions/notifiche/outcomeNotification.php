<?php
        require_once "../classes/dbh.classes.php";
        session_start();
        if (isset($_POST['nid']) && isset($_POST['senderid']) && isset($_POST['outcome'])) {
            $dbh = new Dbh;
            $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICHE WHERE IdNotifica = ?");
            if($idcheck === false) {
                error_log("Errore nella preparazione della query SELECT.");
            }
            do {
                $result = null;
                $nid = hexdec(uniqid());
                if(!$idcheck->execute(array($_POST['nid']))) {
                    error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
                }
                $result = $idcheck->fetch(PDO::FETCH_NUM);
            } while ($result[0] > 0);
            $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICHE (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
            if($stmt === false) {
                error_log("Errore nella preparazione della query INSERT.");
            }
            if(!$stmt->execute(array($_POST['senderid'], $_COOKIE['NomeUtente'], $_POST['nid'], $_POST['outcome'], false, false))) {
                error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
            }    
        }