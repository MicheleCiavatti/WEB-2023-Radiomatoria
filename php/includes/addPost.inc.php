<?php
session_start();
require_once "../classes/dbh.classes.php";

if (isset($_POST['post_text'])) {
    $uid = $_SESSION['NomeUtente'];
    if (isset($_POST['from_home'])) {
        $stringHeader = 'location: ../home.php';
    } else {
        $stringHeader = 'location: ../profile.php?id=' . $uid;
    }
    $text = $_POST['post_text'];
    $date = date("Y-m-d H:i:s");
    $dbh = new Dbh;
    $stmt = $dbh->connect()->prepare(
        'SELECT MAX(NrPost) AS MaxPost
         FROM POST
         WHERE Creatore = ?;'
    );
    if (!$stmt->execute(array($uid))) {
        $stmt = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    if($stmt->rowCount() > 0) {
        $nrPost = $stmt->fetch()['MaxPost'] + 1;
    } else {
        $nrPost = 2;
    }
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
        header($stringHeader . '?error=stmtfailed');
        exit();
    }
    header($stringHeader);
}
