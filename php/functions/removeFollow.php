<?php
require_once "../classes/dbh.classes.php";
$dbh = new Dbh;

if (isset($_GET['username']) && isset($_GET['other'])) {
    $user = $_GET['username'];
    $other = $_GET['other'];
    $s = $dbh->connect()->prepare(
        'DELETE
         FROM FOLLOW
         WHERE Followed = ? AND Follower = ?;'
    );
    if ($s == false) {
        error_log("Errore nella preparazione della query DELETE.");
        exit();
    }
    if(!$s->execute(array($other, $user))) {
        error_log("Errore nell'esecuzione della query DELETE: " . print_r($s->errorInfo(), true));
    }
}  else {
    error_log("Variabili non settate");
}