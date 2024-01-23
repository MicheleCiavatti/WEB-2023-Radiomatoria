<?php
require_once '../classes/dbh.classes.php';
session_start();

if (isset($_POST['new_pw'])) {
    $pw = password_hash($_POST['new_pw'], PASSWORD_DEFAULT);
    $uid = $_SESSION['NomeUtente'];
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'UPDATE UTENTI
         SET Password = ?
         WHERE NomeUtente = ?;'
    );
    if (!$s->execute(array($pw, $uid))) {
        $s = null;
        header('location; ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    header('location: ../profile.php?id=' . $uid .'&error=none');
} else {
    error_log("Variabile non settata");
}