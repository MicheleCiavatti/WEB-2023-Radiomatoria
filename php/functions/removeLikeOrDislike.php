<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                            
if (isset($_GET['author']) && isset($_GET['post_id']) && isset($_GET['creator'])) {
    if(isset($_GET['comment_id']) && $_GET['comment_id'] > 0) {
        $stmt = $dbh->connect()->prepare("DELETE FROM REAZIONI WHERE Creatore = ? AND ElementIdPost = ? AND ElementIdCommento = ? AND Reagente = ?");
        if($stmt == false) {
            error_log("Errore nella preparazione della query DELETE.");
            exit();
        }
        if(!$stmt->execute(array($_GET['creator'], $_GET['post_id'], $_GET['comment_id'], $_GET['author']))) {
            error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        }
    } else {
        $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE Creatore = ? AND ElementId = ? AND Interagente = ?");
        if($stmt == false) {
            error_log("Errore nella preparazione della query DELETE.");
            exit();
        }
        if(!$stmt->execute(array($_GET['creator'], $_GET['post_id'], $_GET['author']))) {
            error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
        }
    }
}