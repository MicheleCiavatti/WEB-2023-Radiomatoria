<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                        
if (isset($_GET['author']) && isset($_GET['post_id']) && isset($_GET['creator']) && isset($_GET['type']) && isset($_GET['comment_id'])) {
    if($_GET['comment_id'] > 0) {
        $stmt = $dbh->connect()->prepare("INSERT INTO REAZIONI (Creatore, ElementIdPost, ElementIdCommento, Reagente, Tipo) VALUES (?, ?, ?, ?, ?)");
        if($stmt == false) {
            error_log("Errore nella preparazione della query INSERT.");
            exit();
        }
        if(!$stmt->execute(array($_GET['creator'], $_GET['post_id'], $_GET['comment_id'], $_GET['author'], $_GET['type']))) {
            error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
        }
    } else {
        $stmt = $dbh->connect()->prepare("INSERT INTO INTERAZIONI (Creatore, ElementId, Interagente, Tipo) VALUES (?, ?, ?, ?)");
        if($stmt == false) {
            error_log("Errore nella preparazione della query INSERT.");
            exit();
        }
        if(!$stmt->execute(array($_GET['creator'], $_GET['post_id'], $_GET['author'], $_GET['type']))) {
            error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
        }
    }
}
