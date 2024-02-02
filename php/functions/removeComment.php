<?php
require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                    
if (isset($_GET['pid']) && isset($_GET['creator']) && isset($_GET['cid'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM COMMENTI WHERE NrPost = ? AND Creatore = ? AND NrCommento = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$stmt->execute(array($_GET['pid']), $_GET['creator'], $_GET['cid'])) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        exit();
    }
/*    $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE ElementId = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    $elementid = $_GET['pid'] . $_GET['creator'] . $_GET['cid'];
    if(!$stmt->execute(array($elementid))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
    }*/
} else {
    error_log("Variabili non settate");
}