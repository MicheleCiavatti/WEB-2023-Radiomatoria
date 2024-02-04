<?php
require_once '../classes/dbh.classes.php';
session_start();

if (isset($_POST['new_pw1']) && isset($_POST['new_pw2']) && isset($_POST['old_pw'])) {
    $uid = $_SESSION['NomeUtente'];
    if ($_POST['new_pw1'] == $_POST['new_pw2']) {
        $dbh = new Dbh;
        $s = $dbh->connect()->prepare(
            'SELECT UTENTI.Password
             FROM UTENTI
             WHERE NomeUtente = ?;'
        );
        if (!$s->execute(array($uid))) {
            $s = null;
            header('location; ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $result = $s->fetch(PDO::FETCH_NUM);
        if(password_verify($_POST['old_pw'], $result[0])) {
            $pw = password_hash($_POST['new_pw1'], PASSWORD_DEFAULT);
            $s = $dbh->connect()->prepare(
                'UPDATE UTENTI
                 SET UTENTI.Password = ?
                 WHERE NomeUtente = ?;'
            );
            if (!$s->execute(array($pw, $uid))) {
                $s = null;
                header('location; ../profile.php?id=' . $uid . '&error=stmtfailed');
                exit();
            }
            header('location: ../profile.php?id=' . $uid .'&error=none');
        } else {
            header('location; ../profile.php?id=' . $uid . '&error=wrongoldpw');
        }
    } else {
        header('location; ../profile.php?id=' . $uid . '&error=notamatch');
    }
} else {
    error_log("Variabile non settata");
}