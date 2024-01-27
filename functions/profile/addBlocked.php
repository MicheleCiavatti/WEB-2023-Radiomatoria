<?php
require_once "../classes/dbh.classes.php";
require_once "../profile.php";

$dbh = new Dbh;
        
if (isset($_COOKIE['NomeUtente']) && isset($_POST['bloccato'])) {
    $stmt = $dbh->connect()->prepare("INSERT INTO BLOCCO (Bloccante, Bloccato) VALUES (?, ?)");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query INSERT.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_POST['bloccato']))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    notify("ti ha bloccato", $_POST['bloccato'], false);
}