<?php
require_once 'Notify.php';

if (isset($_GET['username']) && isset($_GET['other']) && 
isset($_GET['text']) && isset($_GET['read']) && isset($_GET['request'])) {
    $sender = $_GET['username'];
    $receiver = $_GET['other'];
    $text = $_GET['text'];
    $read = $_GET['read'];
    $request = $_GET['request'];
    notify($sender, $receiver, $text, $request, $read);
} else {
    error_log("Variabili non settate");
}