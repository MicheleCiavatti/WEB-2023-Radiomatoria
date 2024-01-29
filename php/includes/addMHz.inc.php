<?php
require_once "../classes/dbh.classes.php";
session_start();
if (isset($_POST['frequency'])) {
    $f = $_POST['frequency'];
    $uid = $_SESSION['NomeUtente'];

    $dbh = new Dbh;
    $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM BANDE WHERE MHz = ? AND NomeUtente = ?");
    if(!$stmt->execute(array($freq, $_COOKIE['NomeUtente']))) {
        $stmt = null;
        header('location: ../../login.html?error=stmtfailed');
        exit();
    }
    $result = $stmt->fetch(PDO::FETCH_NUM);
    if($result[0] != 0) {
        header('location: ../profile.php?id=' . $uid . '&error=duplicate');
        exit();
}

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