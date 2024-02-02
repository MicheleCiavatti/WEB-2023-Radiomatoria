<?php
session_start();
require_once "../classes/dbh.classes.php";

if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] == 0) {
    $uid = $_SESSION['NomeUtente'];
    $pathinfo = pathinfo($_FILES['profile_image']['name']);
    if (isset($pathinfo['extension']) //Check to block undesired formats
    && $pathinfo['extension'] != "jpg" 
    && $pathinfo['extension'] != "jpeg" 
    && $pathinfo['extension'] != "png") {
        header('location: ../profile.php?id=' . $uid . '&error=invalidfile');
        exit();
    }
    $imgDir = __DIR__ . "/../../img/";
    $fileToDelete = $uid . ".*";
    $oldPropic = glob($imgDir . $fileToDelete);
    foreach ($oldPropic as $file) {
        unlink($file);
    }
    $imgName = $uid . "." . pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $imgDir . DIRECTORY_SEPARATOR . $imgName);
    $image = "../img/" . $imgName;
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'UPDATE UTENTI
         SET FotoProfilo = ?
         WHERE NomeUtente = ?;'
    );
    if (!$s->execute(array($image, $uid))) {
        $s = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    header('location: ../profile.php?id=' . $uid . '&error=none');
}