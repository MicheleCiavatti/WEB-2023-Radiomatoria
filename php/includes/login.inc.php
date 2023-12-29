<?php

if(isset($_POST['submit'])) {
    $uid = $_POST['uid'];
    $pw = $_POST['pw'];

    require "../classes/dbh.classes.php";
    require "../classes/login.classes.php";
    require "../classes/login-contr.classes.php";
    $login = new LoginContr($uid, $pw);
    $login->loginUser();
    header('location: ../home.php?error=none');
}