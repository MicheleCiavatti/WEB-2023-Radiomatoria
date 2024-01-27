<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $stmt = $dbh->connect()->prepare("SELECT NrCommento FROM COMMENTI WHERE NrPost = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query SELECT.");
        exit;
    }
    if(!$stmt->execute(array($pid))) {
        error_log("Errore nell'esecuzione della query SELECT: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $stmt = $dbh->connect()->prepare("DELETE FROM POST WHERE NrPost = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }
    if(!$stmt->execute(array($pid))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit;
    }
    $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE ElementId = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query SELECT.");
        exit;
    }
    if(!$stmt->execute(array($pid))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
    }
    return $result;
} else {
    error_log("Variabili non settate");
}