<?php
require_once "../classes/dbh.classes.php";
    
$dbh = new Dbh;
    
if (isset($_COOKIE['NomeUtente']) && isset($_POST['f_to_remove'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM BANDE WHERE MHz = ? AND NomeUtente = ?");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($_POST['f_to_remove'], $_COOKIE['NomeUtente']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
    }
}