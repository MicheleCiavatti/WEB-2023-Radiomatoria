<?php

if(isset($_POST['address']) && isset($_POST['pw'])) {
    $address = $_POST['address'];
    $pw = $_POST['pw'];

    require "../classes/dbh.classes.php";
    require "../classes/login.classes.php";
    require "../classes/login-contr.classes.php";
    $login = new LoginContr($address, $pw);
    $login->loginUser();
    header('location: ../home.php?error=none');
}
else echo 'error';