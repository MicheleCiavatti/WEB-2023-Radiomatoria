<?php
require_once "Notify.php";

if (isset($_GET['username']) && isset($_GET['other'])) {
    $sender = $_GET['username'];
    $receiver = $_GET['other'];
    removeNotification($sender, $receiver, 0, 1);
} else {
    error_log("Variabili non settate");
}