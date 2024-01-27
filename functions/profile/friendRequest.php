<?php
require_once "../classes/dbh.classes.php";
require_once "../profile.php";
    
if(isset($_COOKIE['NomeUtente']) && isset($_POST['amico'])) {
    notify("ti ha aggiunto alla sua lista di seguiti", $_POST['amico'], true);
}