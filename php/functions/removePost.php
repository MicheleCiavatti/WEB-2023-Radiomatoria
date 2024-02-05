<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;

if (isset($_GET['pid']) && isset($_GET['creator'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM POST WHERE NrPost = ? AND Creatore = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$stmt->execute(array($_GET['pid'], $_GET['creator']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit();
    }
    $stmt = $dbh->connect()->prepare("DELETE FROM COMMENTI WHERE NrPost = ? AND Creatore = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$stmt->execute(array($_GET['pid'], $_GET['creator']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit();
    }
    $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE ElementId = ? AND Creatore = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$stmt->execute(array($_GET['pid'], $_GET['creator']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit();
    }
    $stmt = $dbh->connect()->prepare("DELETE FROM REAZIONI WHERE ElementIdPost = ? AND Creatore = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$stmt->execute(array($_GET['pid'], $_GET['creator']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit();
    }
} else {
    error_log("Variabili non settate");
}