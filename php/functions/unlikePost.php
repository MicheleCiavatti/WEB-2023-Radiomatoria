<?php
require_once '../classes/dbh.classes.php';
require_once 'Notify.php';

if (isset($_GET['post_author']) && isset($_GET['post_number']) && isset($_GET['unliker'])) {
    $post_author = $_GET['post_author'];
    $post_number = $_GET['post_number'];
    $unliker = $_GET['unliker'];
    $stringHeader = 'location: ../home.php';
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'DELETE FROM INTERAZIONI
         WHERE Creatore = ? AND ElementId = ? AND Interagente = ? AND Tipo = 1;'
    );
    if (!$s->execute(array($post_author, $post_number, $unliker))) {
        $s = null;
        header($stringHeader . '?error=stmtfailed');
        exit();
    }
    $s = $dbh->connect()->prepare(
        'SELECT IdNotifica
         FROM NOTIFICHE
         WHERE Mandante = ? AND Ricevente = ? AND Lettura = ?;'
    );
    if (!$s->execute(array($unliker, $post_author, 'Post_' . $post_author . '_' . $post_number))) {
        $s = null;
        header($stringHeader . '?error=stmtfailed');
        exit();
    }
    $nid = $s->fetch()['IdNotifica'];
    if ($nid != null) {
        removeNotification($unliker, $post_author, $nid);
    }
    header($stringHeader);
    exit();
}
