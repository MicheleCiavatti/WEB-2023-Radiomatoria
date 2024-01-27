<?php
require_once "../classes/dbh.classes.php";
require_once "../profile.php";

$dbh = new Dbh;
    
if (isset($_COOKIE['NomeUtente']) && isset($_POST['neoseguito'])) {
    $stmt = $dbh->connect()->prepare("INSERT INTO FOLLOW (Follower, Followed) VALUES (?, ?)");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query INSERT.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_POST['neoseguito']))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    notify("ti ha aggiunto alla sua lista di seguiti", $_POST['neoseguito'], false);
}