<?php
require_once '../classes/dbh.classes.php';

if (isset($_GET['mail'])) {
    $mail = $_GET['mail'];
    error_log($mail);
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
    if ($s->rowCount() > 0) {
        $clue = $s->fetch();
        header($stringHeader . '?clue=' . $clue['Indizio']);
        exit();
    } else {
        header($stringHeader . '?MailInesistente');
        exit();
    }
}