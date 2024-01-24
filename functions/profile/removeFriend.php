<?php
require_once "../classes/dbh.classes.php";
require_once "../profile.php";

$dbh = new Dbh;
        
if (isset($_COOKIE['NomeUtente']) && isset($_POST['examico'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM AMICIZIA WHERE Amico1 = ? AND Amico2 = ?");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_POST['examico']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    if(!$stmt->execute(array($_POST['examico'], $_COOKIE['NomeUtente']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    notify("ti ha rimosso dalla sua lista di amici", $_POST['examico'], false);
}