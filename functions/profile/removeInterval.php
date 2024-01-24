<?php
require_once "../classes/dbh.classes.php";
    
$dbh = new Dbh;
    
if (isset($_COOKIE['NomeUtente']) && isset($_POST['orainizio']) && isset($_POST['orafine'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM DISPONIBILITA WHERE OraInizio = ? AND OraFine = ? AND NomeUtente = ?");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($_POST['orainizio'], $_POST['orafine'], $_COOKIE['NomeUtente']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
    }
}