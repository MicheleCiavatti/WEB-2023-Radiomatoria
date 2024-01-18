<?php

if(isset($_POST['submit'])) {
    $uid = $_POST['uid'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $mail = $_POST['address'];
    $birthdate = $_POST['birthdate'];
    $pw = $_POST['pw'];
    // The pwrepeat check should be done client side.
    $pwrepeat = $_POST['pwrepeat'];
    $clue = $_POST['clue'];

    require "../classes/dbh.classes.php";
    require "../classes/signup.classes.php";
    require "../classes/signup-contr.classes.php";
    $signup = new SignupContr($uid, $address, $city, $mail, $birthdate, $pw, $pwrepeat, $clue);
    $signup->signupUser();
    header('location: ../home.php?error=none');
}