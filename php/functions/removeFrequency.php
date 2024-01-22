<?php
require_once "../classes/dbh.classes.php";

error_log("Entrata nel file di rimozione frequenza: f_to_remove=" . $_POST['f_to_remove'] . " and username= " . $_POST['username']);

$dbh = new Dbh;

if (isset($_POST['username']) && isset($_POST['f_to_remove'])) {
    $s = $dbh->connect()->prepare(
        'DELETE
         FROM BANDE
         WHERE NomeUtente = ? AND MHz = ?;'
    );

    if ($s === false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit;
    }

    // Esegui la query
    if(!$s->execute(array($_POST['username'], $_POST['f_to_remove']))) {
        // Aggiungi un log degli errori
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    } else {
        // Log per il debugging
        error_log("Rimozione avvenuta con successo per NomeUtente={$_POST['username']}, MHz={$_POST['f_to_remove']}");
    }
}



