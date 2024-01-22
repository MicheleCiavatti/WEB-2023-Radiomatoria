<?php
        require_once "../classes/dbh.classes.php";
        $dbh = new Dbh;
        if (isset($_POST['nid'])) {
            $stmt = $dbh->connect()->prepare("DELETE FROM NOTIFICHE WHERE IdNotifica = ?");
            if($stmt === false) {
                error_log("Errore nella preparazione della query DELETE.");
            }
            if(!$stmt->execute(array($_POST['nid']))) {
                error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
            }
        }