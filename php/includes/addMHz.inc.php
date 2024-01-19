<?php
session_start();
if (isset($_POST['frequency'])) {
    $f = $_POST['frequency'];
    $uid = $_SESSION['NomeUtente'];

    require_once "../classes/dbh.classes.php";
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'INSERT INTO BANDE (NomeUtente, MHz)
         VALUES (?, ?);'
    );
    if (!$s->execute(array($uid, $f))) {
        $s = null;
        header('location: ../myProfile.php?error=stmtfailed');
        exit();
    }
    header('location: ../myProfile.php?error=none');
}