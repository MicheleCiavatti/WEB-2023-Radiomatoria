<?php
        require_once "../classes/dbh.classes.php";
        session_start();
        if (isset($_GET['username']) && isset($_GET['senderid']) && isset($_GET['outcome'])) {
            $dbh = new Dbh;
            $blockcheck = $dbh->connect()->prepare("SELECT * FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
            if(!$blockcheck->execute(array($_GET['senderid'], $_GET['username']))) {
                $blockcheck = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            if($blockcheck->rowCount() > 0) {
                header('location: ../profile.php?id=' . $uid . '&error=blocked');
                exit();
            }
            $idcheck = $dbh->connect()->prepare("SELECT * FROM NOTIFICHE WHERE IdNotifica = ?");
            if($idcheck === false) {
                error_log("Errore nella preparazione della query SELECT.");
            }
            do {
                $nid = rand(1,999);
                if(!$idcheck->execute(array($nid))) {
                    error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
                }
            } while ($idcheck->rowCount() > 0);
            $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICHE (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
            if($stmt === false) {
                error_log("Errore nella preparazione della query INSERT.");
            }
            if(!$stmt->execute(array($_GET['senderid'], $_GET['username'], $nid, $_GET['outcome'], 0, 0))) {
                error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
            }    
        }
