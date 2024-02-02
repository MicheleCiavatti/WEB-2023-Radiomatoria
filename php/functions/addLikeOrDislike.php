<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                        
if (isset($_GET['author']) && isset($_GET['post_id']) && isset($_GET['creator']) && isset($_GET['comment_id']) && isset($_GET['type'])) {
    $stmt = $dbh->connect()->prepare("INSERT INTO INTERAZIONI (Creatore, ElementIdPost, ElementCreator, ElementIdCommento, Tipo) VALUES (?, ?, ?, ?, ?)");
    if($stmt == false) {
        error_log("Errore nella preparazione della query INSERT.");
        exit();
    }
    if(!$stmt->execute(array($_GET['author'], $_GET['post_id'], $_GET['creator'], $_GET['comment_id'], $_GET['type']))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
    }
}
