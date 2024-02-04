<?php
require_once "../classes/dbh.classes.php";
require_once "Notify.php";

$dbh = new Dbh;
if (isset($_GET['username']) && isset($_GET['other'])) {
    $follower = $_GET['username'];
    $followed = $_GET['other'];
    $s = $dbh->connect()->prepare(
        'INSERT INTO FOLLOW (Followed, Follower)
         VALUES (?, ?);'
    );
    if(!$s->execute(array($followed, $follower))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
    notify($follower, $followed, $follower . ' ha iniziato a seguirti!', 0, 0);
}  else {
    error_log("Variabili non settate");
}
