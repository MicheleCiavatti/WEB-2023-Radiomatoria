<?php
require_once "../classes/dbh.classes.php";
session_start();
if (isset($_POST['frequency'])) {
    $f = $_POST['frequency'];
    $uid = $_SESSION['NomeUtente'];

    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'INSERT INTO BANDE (NomeUtente, MHz)
         VALUES (?, ?);'
    );
    $string = 'location: ../profile.php?id=' . $_SESSION['NomeUtente'];
    if (!$s->execute(array($uid, $f))) {
        $s = null;
        header($string);
        exit();
    }

    header($string);
}