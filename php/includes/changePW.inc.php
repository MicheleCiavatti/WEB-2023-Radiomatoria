<?php
require_once '../classes/dbh.classes.php';
session_start();

if (isset($_POST['new_pw1']) && isset($_POST['new_pw2'])) {
    if ($_POST['new_pw1'] == $_POST['new_pw2']) {
        $pw = password_hash($_POST['new_pw1'], PASSWORD_DEFAULT);
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
        header('location; ../profile.php?id=' . $uid . '&error=notamatch');
    }
} else {
    error_log("Variabile non settata");
}