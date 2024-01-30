<?php
require_once "../classes/dbh.classes.php";
session_start();

if (isset($_POST['comment_text'])) {
    $uid = $_SESSION['NomeUtente'];
    $comment_text = $_POST['comment_text'];
    $date = date("Y-m-d H:i:s");
    $post_author = $_POST['post_author'];
    $post_number = $_POST['post_number'];
    if (isset($_POST['comment_pic'])) {
        $comment_pic = $_POST['comment_pic'];
    } else {
        $comment_pic = NULL;
    }
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'SELECT *
         FROM COMMENTI
         WHERE Creatore = ? AND NrPost = ?;'
    );
    if (!$s->execute(array($post_author, $post_number))) {
        $s = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    $nrComment = $s->rowCount() + 1;
    $s = $dbh->connect()->prepare(
        'INSERT INTO COMMENTI (Creatore, DataCommento, TestoCommento, ImmagineCommento, NrCommento, NrPost, AutoreCommento)
         VALUES (?, ?, ?, ?, ?, ?, ?);'
    );
    if (!$s->execute(array($post_author, $date, $comment_text, $comment_pic, $nrComment, $post_number, $uid))) {
        $s = null;
        header('location: ../profile.php?id=' . $post_author . '&error=stmtfailed');
        exit();
    }
    header('location: ../profile.php?id=' . $post_author . '&error=none');
}