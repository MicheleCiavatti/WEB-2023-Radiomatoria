<?php
require_once 'Notify.php';
require_once '../classes/dbh.classes.php';

if (isset($_GET['post_author']) && isset($_GET['post_number']) && isset($_GET['liker'])) {
    $stringHeader = 'location: ../home.php';
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'INSERT INTO INTERAZIONI (Creatore, ElementId, Interagente, Tipo)
         VALUES (?, ?, ?, 1);' // Tipo 1 = like
    );
    if (!$s->execute(array($_GET['post_author'], $_GET['post_number'], $_GET['liker']))) {
        $s = null;
        header($stringHeader . '?error=stmtfailed');
        exit();
    }
    $post_id = 'Post_' . $_GET['post_author'] . '_' . $_GET['post_number'];
    notify($_GET['liker'], $_GET['post_author'], $_GET['liker'] . " ha messo like al tuo post", 0, $post_id);
    header($stringHeader);
    exit();
}