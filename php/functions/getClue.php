<?php
require_once '../classes/dbh.classes.php';

if (isset($_GET['mail'])) {
    $mail = $_GET['mail'];
    $dbh = new Dbh();
    $stringHeader = 'location: ../../login.html';
    $s = $dbh->connect()->prepare(
        'SELECT Indizio
         FROM UTENTI
         WHERE IndirizzoMail = ?;'
    );
    if (!$s->execute([$mail])) {
        header($stringHeader . '?error=stmtfailed');
        exit();
    }
    $clue = $s->fetch();
    if ($clue) {
        header($stringHeader . '?clue=' . $clue['Indizio']);
        exit();
    }
}