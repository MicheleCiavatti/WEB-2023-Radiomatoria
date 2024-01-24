<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                        
if (isset($_GET['element_id']) && isset($_GET['type'])) {
    $stmt = $dbh->connect()->prepare("INSERT INTO INTERAZIONI (Creatore, ElementId, Tipo) VALUES (?, ?, ?)");
    if($stmt == false) {
        error_log("Errore nella preparazione della query INSERT.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_GET['element_id'], $_GET['type']))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
    }
}
