<?php
require_once "../classes/dbh.classes.php";
$dbh = new Dbh;
if (isset($_GET['username']) && isset($_GET['other'])) {
    $user = $_GET['username'];
    $other = $_GET['other'];
    $s = $dbh->connect()->prepare(
        'INSERT INTO FOLLOW (Followed, Follower)
         VALUES (?, ?);'
    );
    if(!$s->execute(array($other, $user))) {
        error_log("Errore nell'esecuzione della query INSERT: " . print_r($s->errorInfo(), true));
    }
}  else {
    error_log("Variabili non settate");
}
