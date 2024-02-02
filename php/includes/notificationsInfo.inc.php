<?php
    session_start();
    require_once __DIR__ . "/../classes/dbh.classes.php";

    function notificationAccess($username) {
        $dbh = new Dbh;
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM NOTIFICHE
             WHERE Ricevente = ?;'
        );
        if (!$s->execute(array($username))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($s->errorInfo(), true));
            exit();
        }
        $r = $s->fetchAll(PDO::FETCH_ASSOC);
        $notifications = [];
        foreach ($r as $row) {
            $notifications[] = array('Ricevente' => $row['Ricevente'],
                                     'IdNotifica' => $row['IdNotifica'],
                                     'Mandante' => $row['Mandante'], 
                                     'TestoNotifica' => $row['TestoNotifica'], 
                                     'Richiesta' => $row['Richiesta'], 
                                     'Lettura' => $row['Lettura']);
        }
        return $notifications;
    }

    

    