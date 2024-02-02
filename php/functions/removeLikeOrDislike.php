<?php

require_once "../classes/dbh.classes.php";

$dbh = new Dbh;
                            
if (isset($_GET['author']) && isset($_GET['post_id']) && isset($_GET['creator']) && isset($_GET['comment_id'])) {
    $stmt = $dbh->connect()->prepare("DELETE FROM INTERAZIONI WHERE Creatore = ? AND ElementIdPost = ? AND ElementCreator = ? AND ElementIdCommento = ?");
    if($stmt == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$stmt->execute(array($_GET['author'], $_GET['post_id'], $_GET['creator'], $_GET['comment_id']))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($stmt->errorInfo(), true));
    }
}