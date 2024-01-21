<?php
    function readNotification($nid) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("UPDATE NOTIFICHE SET Lettura = ? WHERE IdNotifica = ?");
        if(!$stmt->execute(array(true, $nid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function removeNotification($nid) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM NOTIFICHE WHERE IdNotifica = ?");
        if(!$stmt->execute(array($nid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function outcomeNotification($nid, $senderid, $outcome) {
        removeNotification($nid);
        $dbh = new Dbh;
        $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICHE WHERE IdNotifica = ?");
        do {
            $result = null;
            $nid = hexdec(uniqid());
            if(!$idcheck->execute(array($nid))) {
                $idcheck = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $result = $idcheck->fetch(PDO::FETCH_NUM);
        } while ($result[0] > 0);
        $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICHE (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
        if(!$stmt->execute(array($senderid, readCookie('NomeUtente'), $nid, $outcome, false, false))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function addFriend($nid, $senderid) {
        outcomeNotification($nid, $senderid, "ha accettato la tua richiesta di amicizia");
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("INSERT INTO AMICIZIA (NomeUtente, NomeAmico) VALUES (? ?)");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $senderid))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        if(!$stmt->execute(array($senderid, $_COOKIE['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function list_notifications() {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT * FROM NOTIFICHE WHERE NOTIFICHE.Ricevente = ? AND NOTIFICHE.Lettura = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], false))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $notifiche_non_lette = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], true))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $notifiche_lette = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>