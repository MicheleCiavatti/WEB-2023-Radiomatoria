<?php
        require_once "../classes/dbh.classes.php";
        session_start();
        if (isset($_POST['senderid'])) {
            $dbh = new Dbh;
            $stmt = $dbh->connect()->prepare("INSERT INTO AMICIZIA (NomeUtente, NomeAmico) VALUES (? ?)");
            if($stmt === false) {
                error_log("Errore nella preparazione della query INSERT.");
            }
            if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_POST['senderid']))) {
                error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
            }
            if(!$stmt->execute(array($_POST['senderid'], $_COOKIE['NomeUtente']))) {
                error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
            }
        }
