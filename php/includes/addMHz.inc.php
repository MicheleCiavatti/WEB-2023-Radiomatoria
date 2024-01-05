<?php

if (isset($_POST['frequency'])) {
    $f = $_POST['frequency'];

    require_once "../classes/dbh.classes.php";
    $dbh = new Dbh;
    
}