<?php
    require_once "../classes/dbh.classes.php";

    function list_notifications() {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT * FROM NOTIFICHE WHERE NOTIFICHE.Ricevente = ? AND NOTIFICHE.Lettura = ?");
        if($stmt === false) {
            error_log("Errore nella preparazione della query SELECT.");
        }
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], false))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
        }
        $notifiche_non_lette = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], true))) {
            error_log("Errore nell'esecuzione della query SELECT: " . print_r($idcheck->errorInfo(), true));
        }
        $notifiche_lette = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array($notifiche_non_lette, $notifiche_lette);
    }
?>
