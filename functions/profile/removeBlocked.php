<?php
require_once "../classes/dbh.classes.php";
require_once "../profile.php";

$dbh = new Dbh;
        
if (isset($_COOKIE['NomeUtente']) && isset($_POST['perdonato'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_POST['perdonato']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    notify("ha rimosso il tuo blocco", $_POST['perdonato'], false);
}