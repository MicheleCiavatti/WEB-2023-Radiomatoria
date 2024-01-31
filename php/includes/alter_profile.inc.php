<?php
require_once '../classes/dbh.classes.php';
session_start();

if (isset($_POST['new_name']) && isset($_POST['new_address']) && isset($_POST['new_city']) && isset($_POST['new_dob']) && isset($_POST['new_mail'])) {
    $uid = $_SESSION['NomeUtente'];
    $name = $_POST['new_name'];
    $address = $_POST['new_address'];
    $city = $_POST['new_city'];
    $dob = $_POST['new_dob'];
    $mail = $_POST['new_mail'];
    $dbh = new Dbh;

    $s = $dbh->connect()->prepare('SELECT COUNT(*) FROM UTENTI WHERE (NomeUtente = ? OR IndirizzoMail = ?) AND NOT (NomeUtente = ?);');
    if (!$s->execute(array($name, $mail, $uid))) {
        $s = null;
        header('location; ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    if($s->rowCount() > 0) {
        header('location; ../profile.php?id=' . $uid . '&error=duplicatenameormail');
        exit();
    }

    $s = $dbh->connect()->prepare(
        'UPDATE UTENTI
            SET NomeUtente = ?, Indirizzo = ?, Città = ?, DataNascita = ?, IndirizzoMail = ?
            WHERE NomeUtente = ?;'
    );
    if (!$s->execute(array($name, $_POST['new_address'], $_POST['new_city'], $_POST['new_dob'], $mail, $uid))) {
        $s = null;
        header('location; ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    $_SESSION['NomeUtente'] = $_POST['new_name'];
    header('location: ../profile.php?id=' . $_SESSION['NomeUtente'] .'&error=none');
} else {
    error_log("Variabile non settata");
}