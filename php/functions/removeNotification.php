<?php
require_once "Notify.php";

if (isset($_GET['username']) && isset($_GET['other']) && isset($_GET['nid'])) {
    $receiver = $_GET['username'];
    $sender = $_GET['other'];
    $nid = $_GET['nid'];
    removeNotification($sender, $receiver, $nid, 0);
} else {
    error_log("Variabili non settate");
}