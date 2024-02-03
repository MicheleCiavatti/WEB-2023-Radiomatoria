<?php
        require_once "../classes/dbh.classes.php";
        $dbh = new Dbh;
        if (isset($_GET['nid'])) {
            $stmt = $dbh->connect()->prepare("UPDATE NOTIFICHE SET Lettura = ? WHERE IdNotifica = ?");
            if($stmt === false) {
                error_log("Errore nella preparazione della query UPDATE.");
            }
            if(!$stmt->execute(array(true, $_GET['nid']))) {
                error_log("Errore nell'esecuzione della query UPDATE: " . print_r($stmt->errorInfo(), true));
            }
        }