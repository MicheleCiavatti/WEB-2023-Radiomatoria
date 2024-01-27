<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                            
if (isset($_GET['element_id'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE Creatore = ? AND ElementId = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $element_id))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
    }
}