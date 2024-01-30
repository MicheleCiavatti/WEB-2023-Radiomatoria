<?php
require_once "../classes/dbh.classes.php";
session_start();

if (isset($_POST['comment_text'])) {
    $uid = $_SESSION['NomeUtente'];
    $comment_text = $_POST['comment_text'];
    $date = date("Y-m-d H:i:s");
    
}