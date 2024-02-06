<?php
require_once "../classes/dbh.classes.php";
require_once "../functions/Notify.php";
session_start();

if (isset($_POST['comment_text'])) {   
    $uid = $_SESSION['NomeUtente'];     
    $comment_text = $_POST['comment_text'];
    $date = date("Y-m-d H:i:s");
    $post_author = $_POST['post_author'];
    $post_number = $_POST['post_number'];
    if (isset($_POST['from_home'])) $stringHeader = 'location: ../home.php';
    else $stringHeader = 'location: ../profile.php?id=' . $post_author;

    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM COMMENTI
         WHERE Creatore = ? AND NrPost = ?;'
    );
    if (!$s->execute(array($post_author, $post_number))) {
        $s = null;
        header($stringHeader . '&error=stmtfailed');
        exit();
    }
    $nrComment = $s->rowCount() + 1;
    if (isset($_FILES['comment_image']) && !empty($_FILES['comment_image']['name']) && $_FILES['comment_image']['error'] == 0) {
        $imgDir = __DIR__ . "/../../img/";
        $imgName = "comment_" . $post_author . "_" . $post_number . "_" . $nrComment . "." . pathinfo($_FILES['comment_image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['comment_image']['tmp_name'], $imgDir . DIRECTORY_SEPARATOR . $imgName);
        $comment_pic = "../img/" . $imgName;
    } else {
        $comment_pic = null;
    }
    $s = $dbh->connect()->prepare(
        'INSERT INTO COMMENTI (Creatore, DataCommento, TestoCommento, ImmagineCommento, NrCommento, NrPost, AutoreCommento)
         VALUES (?, ?, ?, ?, ?, ?, ?);'
    );
    if (!$s->execute(array($post_author, $date, $comment_text, $comment_pic, $nrComment, $post_number, $uid))) {
        $s = null;
        header($stringHeader. '&error=stmtfailed');
        exit();
    }
    $post_id = 'Post_' . $post_author . '_' . $post_number;
    notify($uid, $post_author, "L'utente " . $uid . " ha commentato il tuo post", 0, $post_id);
    header($stringHeader);
}