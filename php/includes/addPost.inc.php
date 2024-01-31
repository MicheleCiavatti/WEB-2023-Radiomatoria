<?php
session_start();
require_once "../classes/dbh.classes.php";

if (isset($_POST['post_text'])) {
    $uid = $_SESSION['NomeUtente'];
    $text = $_POST['post_text'];
    $date = date("Y-m-d H:i:s");
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
    if (isset($_FILES['post_image']) && !empty($_FILES['post_image']['name']) && $_FILES['post_image']['error'] == 0) {
        $imgDir = __DIR__ . "/../../img/";
        $imgName = "post_" . $uid . "_" . $nrPost . "." . pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['post_image']['tmp_name'], $imgDir . DIRECTORY_SEPARATOR . $imgName);
        $image = "../img/" . $imgName;
    } else {
        $image = null;
    }
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
