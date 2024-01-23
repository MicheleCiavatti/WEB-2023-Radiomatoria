<?php
require_once '../classes/dbh.classes.php';
session_start();
if (isset($_POST['new_clue'])) {
    $uid = $_SESSION['NomeUtente'];
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'UPDATE UTENTI
         SET Indizio = ?
         WHERE NomeUtente = ?'
    );
    if(!$s->execute(array($_POST['new_clue'], $uid))) {
        $s = null;
        header('location; ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    header('location: ../profile.php?id=' . $uid .'&error=none');
} else {
    error_log("Variabile non settata");
}
