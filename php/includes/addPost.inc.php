<?php
session_start();
require_once "../classes/dbh.classes.php";

if (isset($_POST['post_text'])) {
    $uid = $_SESSION['NomeUtente'];
    $text = $_POST['post_text'];
    $date = date("Y-m-d H:i:s");
    if (isset($_POST['post_image'])) {
        $image = $_POST['post_image'];
        unset($_POST['post_image']);
    } else {
        $image = null;
    }
    $dbh = new Dbh;
    $stmt = $dbh->connect()->prepare(
        'SELECT *
         FROM POST
         WHERE Creatore = ?;'
    );
    if (!$stmt->execute(array($uid))) {
        $stmt = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    $nrPost = $stmt->rowCount() + 1;
    $s = $dbh->connect()->prepare(
        'INSERT INTO POST (Creatore, DataPost, TestoPost, ImmaginePost, NrPost)
         VALUES (?, ?, ?, ?, ?);'
    );
    if (!$s->execute(array($uid, $date, $text, $image, $nrPost))) {
        $s = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    header('location: ../profile.php?id=' . $uid . '&error=none');
}
