<?php
require_once "../classes/dbh.classes.php";
require_once "../profile.php";

$dbh = new Dbh;
        
if (isset($_COOKIE['NomeUtente']) && isset($_POST['exseguito'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM FOLLOW WHERE Follower = ? AND Followed = ?");
    if ($stmt === false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($_COOKIE['NomeUtente'], $_POST['exseguito']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    notify("ti ha rimosso dalla sua lista di seguiti", $_POST['exseguito'], false);
}