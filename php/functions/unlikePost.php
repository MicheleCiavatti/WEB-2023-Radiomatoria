<?php
require_once '../classes/dbh.classes.php';

if (isset($_GET['post_author']) && isset($_GET['post_number']) && isset($_GET['unliker'])) {
    $stringHeader = 'location: ../home.php';
    $dbh = new Dbh;
    $s = $dbh->connect()->prepare(
        'DELETE FROM INTERAZIONI
         WHERE Creatore = ? AND ElementId = ? AND Interagente = ? AND Tipo = 1;'
    );
    if (!$s->execute(array($_GET['post_author'], $_GET['post_number'], $_GET['unliker']))) {
        $s = null;
        header($stringHeader . '?error=stmtfailed');
        exit();
    }
    header($stringHeader);
    exit();
}
