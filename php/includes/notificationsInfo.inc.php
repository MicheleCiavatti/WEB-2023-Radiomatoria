<?php
    session_start();
    require_once __DIR__ . "/../classes/dbh.classes.php";

    function notificationAccess() {
        $dbh = new Dbh;
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM NOTIFICHE
             WHERE Ricevente = ? AND Lettura = ?;'
        );
        if (!$s->execute(array($_SESSION['NomeUtente'], false))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($s->errorInfo(), true));
            exit();
        }
        $r = $s->fetchAll(PDO::FETCH_ASSOC);
        $unread_notifications = [];
        foreach ($r as $row) {
            $unread_notifications[] = array('Ricevente' => $row['Ricevente'],
                                     'IdNotifica' => $row['IdNotifica'],
                                     'Mandante' => $row['Mandante'], 
                                     'TestoNotifica' => $row['TestoNotifica'], 
                                     'Richiesta' => $row['Richiesta'], 
                                     'Lettura' => $row['Lettura']);
        }
        if (!$s->execute(array($_SESSION['NomeUtente'], true))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($s->errorInfo(), true));
            exit();
        }
        $r = $s->fetchAll(PDO::FETCH_ASSOC);
        $read_notifications = [];
        foreach ($r as $row) {
            $read_notifications[] = array('Ricevente' => $row['Ricevente'],
                                     'IdNotifica' => $row['IdNotifica'],
                                     'Mandante' => $row['Mandante'], 
                                     'TestoNotifica' => $row['TestoNotifica'], 
                                     'Richiesta' => $row['Richiesta'], 
                                     'Lettura' => $row['Lettura']);
        }
        return array($unread_notifications, $read_notifications);
    }

    

    