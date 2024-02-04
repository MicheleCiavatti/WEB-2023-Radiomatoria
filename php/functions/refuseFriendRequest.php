<?php
require_once "../classes/dbh.classes.php";
require_once "Notify.php";

$dbh = new Dbh;
if (isset($_GET['username']) && isset($_GET['other'])) {
    $receiver = $_GET['username'];
    $sender = $_GET['other'];
    removeNotification($sender, $receiver, 0, 1);
} else {
    error_log("Variabili non settate");
}